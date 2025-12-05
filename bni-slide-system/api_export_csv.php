<?php
/**
 * BNI Slide System - Excel Export API (SQLite Version)
 * 指定した週のデータをExcel形式でエクスポート
 * Updated: 2025-12-06 - SQLite対応版
 * シンプルなCSV出力(UTF-8 BOM)でExcel互換性を確保
 */

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/date_helper.php';
require_once __DIR__ . '/includes/db.php';

// セッション開始（まだ開始されていない場合）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 管理者チェック
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    die('ログインが必要です');
}

$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';

if (!$isAdmin) {
    http_response_code(403);
    die('管理者権限が必要です');
}

// 週の指定を取得
$week = $_GET['week'] ?? '';

if (empty($week)) {
    http_response_code(400);
    die('週の指定が必要です');
}

// SQLiteからデータを取得してCSVエクスポート
exportFromSQLite($week);

/**
 * SQLiteデータベースからデータを取得してCSVエクスポート
 */
function exportFromSQLite($week) {
    $db = getDbConnection();

    // 指定週のデータを取得（JOINでビジター・リファーラルも取得）
    // CSVフォーマットに合わせて、1行 = 1リファーラル（またはビジター）
    $query = "
        SELECT
            sd.timestamp,
            sd.input_date,
            sd.user_name AS introducer_name,
            sd.user_email AS email,
            sd.attendance,
            v.visitor_name,
            v.visitor_company,
            v.visitor_industry,
            r.referral_name,
            r.referral_amount,
            r.referral_category,
            r.referral_provider,
            sd.thanks_slips,
            sd.one_to_one AS one_to_one_count,
            sd.activities,
            sd.comments
        FROM survey_data sd
        LEFT JOIN visitors v ON v.survey_data_id = sd.id
        LEFT JOIN referrals r ON r.survey_data_id = sd.id
        WHERE sd.week_date = ?
        ORDER BY sd.timestamp DESC, sd.id, v.id, r.id
    ";

    $stmt = $db->prepare($query);
    $stmt->bindValue(1, $week, SQLITE3_TEXT);
    $result = $stmt->execute();

    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
    }

    dbClose($db);

    // データが見つからない場合
    if (count($rows) === 0) {
        http_response_code(404);
        die('指定された週のデータが見つかりません');
    }

    // ファイル名を設定
    $filename = 'BNI_Weekly_Data_' . $week . '.csv';

    // ヘッダー設定（UTF-8 BOM付きでExcelで正しく開けるようにする）
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // UTF-8 BOMを出力（Excelで文字化けを防ぐ）
    echo "\xEF\xBB\xBF";

    // CSV出力
    $output = fopen('php://output', 'w');

    // ヘッダー行
    fputcsv($output, [
        'timestamp',
        '入力日',
        '紹介者名',
        'メールアドレス',
        '出席状況',
        'ビジター名',
        'ビジター会社名',
        'ビジター業種',
        '案件名',
        'リファーラル金額',
        'カテゴリ',
        'リファーラル提供者',
        'サンクスリップ数',
        'ワンツーワン数',
        'アクティビティ',
        'コメント'
    ]);

    // データ行
    foreach ($rows as $row) {
        fputcsv($output, [
            $row['timestamp'],
            $row['input_date'],
            $row['introducer_name'],
            $row['email'],
            $row['attendance'],
            $row['visitor_name'] ?? '',
            $row['visitor_company'] ?? '',
            $row['visitor_industry'] ?? '',
            $row['referral_name'] ?? '-',
            $row['referral_amount'] ?? 0,
            $row['referral_category'] ?? '',
            $row['referral_provider'] ?? '',
            $row['thanks_slips'],
            $row['one_to_one_count'],
            $row['activities'],
            $row['comments']
        ]);
    }

    fclose($output);
    exit;
}
