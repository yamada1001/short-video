<?php
/**
 * BNI Slide System - CSV Data Migration Script
 * CSVファイルからSQLiteデータベースへデータを移行する
 *
 * 使い方:
 * php database/migrate_csv_to_sqlite.php
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/date_helper.php';

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

echo "==============================================\n";
echo "BNI Slide System - CSVデータ移行\n";
echo "==============================================\n\n";

// dataディレクトリのCSVファイルをスキャン
$dataDir = __DIR__ . '/../data';
$csvFiles = glob($dataDir . '/*.csv');

if (count($csvFiles) === 0) {
    echo "警告: CSVファイルが見つかりませんでした\n";
    exit(0);
}

echo "検出されたCSVファイル: " . count($csvFiles) . "件\n\n";

try {
    // データベース接続
    echo "データベースに接続中...\n";
    $db = getDbConnection();
    echo "✓ データベース接続成功\n\n";

    // トランザクション開始
    dbBeginTransaction($db);

    $totalSurveys = 0;
    $totalVisitors = 0;
    $totalReferrals = 0;
    $errorCount = 0;

    foreach ($csvFiles as $csvFile) {
        $filename = basename($csvFile);
        echo "----------------------------------------------\n";
        echo "処理中: {$filename}\n";

        // 週の日付を取得（ファイル名から）
        $weekDate = str_replace('.csv', '', $filename);

        // ファイルを開く
        $fp = fopen($csvFile, 'r');

        if (!$fp) {
            echo "  ✗ ファイルを開けませんでした\n";
            $errorCount++;
            continue;
        }

        // BOMをスキップ
        $bom = fread($fp, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($fp);
        }

        // ヘッダー行を読み込み
        $header = fgetcsv($fp);

        if (!$header) {
            echo "  ✗ ヘッダー行が読み込めませんでした\n";
            fclose($fp);
            $errorCount++;
            continue;
        }

        // データ行を読み込み
        $rows = [];
        while (($row = fgetcsv($fp)) !== false) {
            if (count($row) !== count($header)) {
                continue; // カラム数が一致しない行はスキップ
            }

            $data = array_combine($header, $row);

            if ($data === false) {
                continue;
            }

            $rows[] = $data;
        }

        fclose($fp);

        echo "  読み込み: " . count($rows) . "行\n";

        // 行をグループ化（timestamp + emailで）
        $groups = [];

        foreach ($rows as $row) {
            $timestamp = $row['timestamp'] ?? '';
            $email = $row['メールアドレス'] ?? '';

            if (empty($timestamp) || empty($email)) {
                continue;
            }

            $key = $timestamp . '|' . $email;

            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'base' => $row,
                    'visitors' => [],
                    'referrals' => []
                ];
            }

            // ビジター情報を追加
            $visitorName = trim($row['ビジター名'] ?? '');
            if (!empty($visitorName) && $visitorName !== '-') {
                $groups[$key]['visitors'][] = [
                    'name' => $visitorName,
                    'company' => $row['ビジター会社名'] ?? null,
                    'industry' => $row['ビジター業種'] ?? null
                ];
            }

            // リファーラル情報を追加
            $referralName = trim($row['案件名'] ?? '');
            $referralAmount = intval($row['リファーラル金額'] ?? 0);

            if (!empty($referralName) && $referralName !== '-' && $referralAmount > 0) {
                $groups[$key]['referrals'][] = [
                    'name' => $referralName,
                    'amount' => $referralAmount,
                    'category' => $row['カテゴリ'] ?? null,
                    'provider' => $row['リファーラル提供者'] ?? null
                ];
            }
        }

        echo "  グループ化: " . count($groups) . "件のアンケート\n";

        // 各グループをデータベースに挿入
        foreach ($groups as $key => $group) {
            try {
                $base = $group['base'];

                // ユーザーIDを取得（メールアドレスから）
                $userEmail = $base['メールアドレス'];
                $user = dbQueryOne($db,
                    "SELECT id, name FROM users WHERE email = :email",
                    [':email' => $userEmail]
                );

                $userId = $user ? $user['id'] : null;
                $userName = $user ? $user['name'] : ($base['紹介者名'] ?? '');

                // survey_dataを挿入
                $surveyQuery = "INSERT INTO survey_data (
                    week_date,
                    timestamp,
                    input_date,
                    user_id,
                    user_name,
                    user_email,
                    attendance,
                    thanks_slips,
                    one_to_one,
                    activities,
                    comments,
                    created_at
                ) VALUES (
                    :week_date,
                    :timestamp,
                    :input_date,
                    :user_id,
                    :user_name,
                    :user_email,
                    :attendance,
                    :thanks_slips,
                    :one_to_one,
                    :activities,
                    :comments,
                    :created_at
                )";

                $surveyParams = [
                    ':week_date' => $weekDate,
                    ':timestamp' => $base['timestamp'],
                    ':input_date' => $base['入力日'] ?? date('Y-m-d'),
                    ':user_id' => $userId,
                    ':user_name' => $userName,
                    ':user_email' => $userEmail,
                    ':attendance' => $base['出席状況'] ?? '出席',
                    ':thanks_slips' => intval($base['サンクスリップ数'] ?? 0),
                    ':one_to_one' => intval($base['ワンツーワン数'] ?? 0),
                    ':activities' => $base['アクティビティ'] ?? null,
                    ':comments' => $base['コメント'] ?? null,
                    ':created_at' => $base['timestamp']
                ];

                $surveyId = dbExecute($db, $surveyQuery, $surveyParams);

                // ビジターを挿入
                foreach ($group['visitors'] as $visitor) {
                    $visitorQuery = "INSERT INTO visitors (
                        survey_data_id,
                        visitor_name,
                        visitor_company,
                        visitor_industry,
                        created_at
                    ) VALUES (
                        :survey_data_id,
                        :visitor_name,
                        :visitor_company,
                        :visitor_industry,
                        :created_at
                    )";

                    $visitorParams = [
                        ':survey_data_id' => $surveyId,
                        ':visitor_name' => $visitor['name'],
                        ':visitor_company' => $visitor['company'],
                        ':visitor_industry' => $visitor['industry'],
                        ':created_at' => $base['timestamp']
                    ];

                    dbExecute($db, $visitorQuery, $visitorParams);
                    $totalVisitors++;
                }

                // リファーラルを挿入
                foreach ($group['referrals'] as $referral) {
                    $referralQuery = "INSERT INTO referrals (
                        survey_data_id,
                        referral_name,
                        referral_amount,
                        referral_category,
                        referral_provider,
                        created_at
                    ) VALUES (
                        :survey_data_id,
                        :referral_name,
                        :referral_amount,
                        :referral_category,
                        :referral_provider,
                        :created_at
                    )";

                    $referralParams = [
                        ':survey_data_id' => $surveyId,
                        ':referral_name' => $referral['name'],
                        ':referral_amount' => $referral['amount'],
                        ':referral_category' => $referral['category'],
                        ':referral_provider' => $referral['provider'],
                        ':created_at' => $base['timestamp']
                    ];

                    dbExecute($db, $referralQuery, $referralParams);
                    $totalReferrals++;
                }

                $totalSurveys++;

            } catch (Exception $e) {
                echo "  ✗ エラー: {$key} - " . $e->getMessage() . "\n";
                $errorCount++;
            }
        }

        echo "  ✓ 完了\n";
    }

    // トランザクションをコミット
    dbCommit($db);

    echo "\n";
    echo "==============================================\n";
    echo "CSVデータ移行が完了しました\n";
    echo "==============================================\n";
    echo "アンケート: {$totalSurveys}件\n";
    echo "ビジター: {$totalVisitors}件\n";
    echo "リファーラル: {$totalReferrals}件\n";
    echo "エラー: {$errorCount}件\n";
    echo "==============================================\n";

    // データベース接続を閉じる
    dbClose($db);

} catch (Exception $e) {
    // エラーが発生した場合はロールバック
    if (isset($db)) {
        dbRollback($db);
        dbClose($db);
    }

    echo "\n";
    echo "エラー: " . $e->getMessage() . "\n";
    echo "トランザクションをロールバックしました\n";
    echo "\n";
    exit(1);
}
