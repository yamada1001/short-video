<?php
/**
 * Generate Sample Data for BNI Slide System
 * 2025年の仮想データを生成
 */

// データディレクトリ
$dataDir = __DIR__ . '/data';

// メンバーリスト（仮想）
$members = [
    ['name' => '山田太郎', 'email' => 'yamada@example.com', 'company' => '山田商事', 'category' => 'コンサルティング'],
    ['name' => '佐藤花子', 'email' => 'sato@example.com', 'company' => '佐藤不動産', 'category' => '不動産'],
    ['name' => '鈴木一郎', 'email' => 'suzuki@example.com', 'company' => '鈴木IT', 'category' => 'IT・システム'],
    ['name' => '田中美咲', 'email' => 'tanaka@example.com', 'company' => '田中デザイン', 'category' => 'デザイン'],
    ['name' => '高橋健', 'email' => 'takahashi@example.com', 'company' => '高橋建設', 'category' => '建設'],
    ['name' => '伊藤真理', 'email' => 'ito@example.com', 'company' => '伊藤会計', 'category' => '会計・税務'],
    ['name' => '渡辺誠', 'email' => 'watanabe@example.com', 'company' => '渡辺法律事務所', 'category' => '法律'],
    ['name' => '中村由美', 'email' => 'nakamura@example.com', 'company' => '中村保険', 'category' => '保険'],
    ['name' => '小林大輔', 'email' => 'kobayashi@example.com', 'company' => '小林マーケティング', 'category' => 'マーケティング'],
    ['name' => '加藤美穂', 'email' => 'kato@example.com', 'company' => '加藤人材', 'category' => '人材紹介'],
];

// ビジター名（仮想）
$visitorNames = [
    '鈴木次郎', '山本三郎', '吉田四郎', '松本五郎', '井上六郎',
    '木村七郎', '林八郎', '斎藤九郎', '清水十郎', '山口十一郎',
    '森十二郎', '池田十三郎', '橋本十四郎', '石川十五郎', '前田十六郎'
];

// ビジター会社名
$visitorCompanies = [
    '株式会社ABC', '株式会社XYZ', '合同会社DEF', '有限会社GHI', '株式会社JKL',
    '株式会社MNO', '株式会社PQR', '合同会社STU', '有限会社VWX', '株式会社YZA'
];

// 業種
$industries = [
    'IT・システム開発', 'コンサルティング', '不動産', 'デザイン', '建設',
    '製造業', '小売業', '飲食業', 'サービス業', '教育'
];

// リファーラル案件名
$referralNames = [
    'ウェブサイト制作案件', '不動産売買仲介', 'ITシステム導入', 'ロゴデザイン制作',
    '建物リフォーム工事', '会計顧問契約', '法律相談案件', '保険契約',
    'マーケティング支援', '人材紹介案件', 'コンサルティング契約', '広告制作',
    'イベント企画', 'セミナー開催', '物販契約'
];

// カテゴリ
$categories = [
    'IT・システム', '不動産', 'デザイン', '建設', '会計・税務',
    '法律', '保険', 'マーケティング', '人材', 'コンサルティング'
];

// 2025年の金曜日リスト（過去4週分）
$fridays = [
    '2025-11-07', // 11月7日（金）
    '2025-11-14', // 11月14日（金）
    '2025-11-21', // 11月21日（金）
    '2025-11-28', // 11月28日（金）
    '2025-12-05', // 12月5日（金）
];

$totalRecords = 0;

foreach ($fridays as $friday) {
    echo "生成中: {$friday}.csv\n";

    $csvFile = $dataDir . '/' . $friday . '.csv';
    $fp = fopen($csvFile, 'w');

    // CSV ヘッダー
    $header = [
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
    ];
    fputcsv($fp, $header);

    // この週のデータ件数（6〜8件）
    $recordsThisWeek = rand(6, 8);

    for ($i = 0; $i < $recordsThisWeek; $i++) {
        $member = $members[array_rand($members)];

        // タイムスタンプ（金曜日の前の週の範囲内）
        $fridayTimestamp = strtotime($friday);
        $randomDaysAgo = rand(0, 6); // 0-6日前
        $randomHours = rand(9, 20); // 9時〜20時
        $randomMinutes = rand(0, 59);
        $timestamp = date('Y-m-d H:i:s', $fridayTimestamp - ($randomDaysAgo * 86400) + ($randomHours * 3600) + ($randomMinutes * 60));

        // 入力日
        $inputDate = date('Y-m-d', strtotime($timestamp));

        // 出席状況
        $attendanceOptions = ['出席', '出席', '出席', '代理出席', '欠席'];
        $attendance = $attendanceOptions[array_rand($attendanceOptions)];

        // ビジター情報（50%の確率で）
        $hasVisitor = rand(0, 1) === 1;
        $visitorName = $hasVisitor ? $visitorNames[array_rand($visitorNames)] : '';
        $visitorCompany = $hasVisitor ? $visitorCompanies[array_rand($visitorCompanies)] : '';
        $visitorIndustry = $hasVisitor ? $industries[array_rand($industries)] : '';

        // リファーラル情報（70%の確率で）
        $hasReferral = rand(0, 9) < 7;
        $referralName = $hasReferral ? $referralNames[array_rand($referralNames)] : '-';
        $referralAmount = $hasReferral ? rand(5, 100) * 10000 : 0; // 5万〜100万
        $referralCategory = $hasReferral ? $categories[array_rand($categories)] : '';
        $referralProvider = $hasReferral ? $members[array_rand($members)]['name'] : '';

        // サンクスリップ、ワンツーワン
        $thanksSlips = rand(0, 5);
        $oneToOne = rand(0, 3);

        // コメント（20%の確率で）
        $comments = rand(0, 4) === 0 ? '今週も良い成果がありました' : '';

        // 1行目を書き込み
        $row = [
            $timestamp,
            $inputDate,
            $member['name'],
            $member['email'],
            $attendance,
            $visitorName,
            $visitorCompany,
            $visitorIndustry,
            $referralName,
            $referralAmount,
            $referralCategory,
            $referralProvider,
            $thanksSlips,
            $oneToOne,
            '',
            $comments
        ];
        fputcsv($fp, $row);
        $totalRecords++;

        // 追加のビジターやリファーラルがある場合（30%の確率）
        if (rand(0, 9) < 3) {
            // 追加のビジターまたはリファーラル
            $hasAdditionalVisitor = rand(0, 1) === 1;
            $hasAdditionalReferral = rand(0, 1) === 1;

            $additionalVisitorName = $hasAdditionalVisitor ? $visitorNames[array_rand($visitorNames)] : '';
            $additionalVisitorCompany = $hasAdditionalVisitor ? $visitorCompanies[array_rand($visitorCompanies)] : '';
            $additionalVisitorIndustry = $hasAdditionalVisitor ? $industries[array_rand($industries)] : '';

            $additionalReferralName = $hasAdditionalReferral ? $referralNames[array_rand($referralNames)] : '-';
            $additionalReferralAmount = $hasAdditionalReferral ? rand(5, 100) * 10000 : 0;
            $additionalReferralCategory = $hasAdditionalReferral ? $categories[array_rand($categories)] : '';
            $additionalReferralProvider = $hasAdditionalReferral ? $members[array_rand($members)]['name'] : '';

            $additionalRow = [
                $timestamp,
                $inputDate,
                $member['name'],
                $member['email'],
                $attendance,
                $additionalVisitorName,
                $additionalVisitorCompany,
                $additionalVisitorIndustry,
                $additionalReferralName,
                $additionalReferralAmount,
                $additionalReferralCategory,
                $additionalReferralProvider,
                $thanksSlips,
                $oneToOne,
                '',
                $comments
            ];
            fputcsv($fp, $additionalRow);
            $totalRecords++;
        }
    }

    fclose($fp);
    echo "完了: {$friday}.csv（{$recordsThisWeek}メンバー分のデータ）\n";
}

echo "\n生成完了！合計 {$totalRecords} レコードを生成しました。\n";
echo "生成された週: " . implode(', ', $fridays) . "\n";
