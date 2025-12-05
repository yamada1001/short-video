<?php
/**
 * BNI Slide System - Generate Test Data
 * 大量のテストデータを生成
 */

// Set UTF-8 encoding
mb_internal_encoding('UTF-8');

echo "<h1>BNI Slide System - テストデータ生成</h1>";
echo "<pre>";

// ==========================================
// 1. メンバーデータ生成
// ==========================================

$lastNames = [
    '山田', '佐藤', '田中', '鈴木', '高橋', '渡辺', '伊藤', '中村', '小林', '加藤',
    '吉田', '山本', '斉藤', '松本', '井上', '木村', '林', '清水', '山崎', '森',
    '阿部', '池田', '橋本', '山下', '石川', '中島', '前田', '藤田', '小川', '後藤',
    '岡田', '長谷川', '村上', '近藤', '石井', '遠藤', '青木', '坂本', '福田', '西村',
    '太田', '三浦', '藤井', '岡本', '松田', '中野', '原田', '竹内', '小野', '田口'
];

$firstNames = [
    '太郎', '次郎', '三郎', '健', '誠', '勇', '隆', '学', '修', '明',
    '博', '哲也', '大輔', '健太', '翔', '拓也', '智也', '達也', '裕太', '雄一',
    '花子', '美咲', '愛', '舞', '優', '彩', '美穂', '陽子', '真理', '恵',
    '浩二', '賢一', '孝', '悟', '剛', '勝', '栄一', '進', '幸雄', '正人',
    '優子', '美香', '由美', '直美', '智子', '麻衣', '沙織', '春菜', '香織', '奈々'
];

$companies = [
    '株式会社アクティブワークス', '有限会社ビジネスパートナーズ', '株式会社クリエイティブソリューションズ',
    '株式会社デザインファクトリー', '合同会社イノベーションラボ', '株式会社フューチャービジョン',
    '株式会社グローバルトレード', '有限会社ハーモニー企画', '株式会社インテリジェンスネット',
    '株式会社ジャパンクオリティ', '株式会社キャピタルワークス', '有限会社ライフサポート',
    '株式会社マーケティングプロ', '株式会社ネクストステージ', '有限会社オフィスソリューション',
    '株式会社プライムコンサルティング', '株式会社クオリティライフ', '有限会社リアルエステート',
    '株式会社セーフティネット', '株式会社テクノロジーパートナーズ', '有限会社ユニバーサルサービス',
    '株式会社ベストプラクティス', '株式会社ワールドワイドトレーディング', '有限会社エクセレントケア',
    '株式会社ヤマト建設', '株式会社サクセスストーリー', '有限会社ファーストクラス',
    '株式会社グリーンテクノロジー', '株式会社ホスピタリティサービス', '有限会社イーストウエスト',
    '株式会社ダイナミックソリューション', '株式会社カスタマーファースト', '有限会社ローカルビジネス',
    '株式会社マスターピース', '株式会社ナショナルサービス', '有限会社オーシャンビュー',
    '株式会社パワフルワークス', '株式会社クイックレスポンス', '有限会社ロイヤルサポート',
    '株式会社ストロングパートナー', '株式会社トータルソリューション', '有限会社ユアサポート',
    '株式会社ベンチャースピリット', '株式会社ワンストップサービス', '有限会社エコフレンドリー',
    '株式会社ゼロワンテクノロジー', '株式会社アライアンスグループ', '有限会社ブライトフューチャー',
    '株式会社クラウドシステムズ', '株式会社デジタルマーケティング', '有限会社エンタープライズ'
];

$categories = [
    'IT・システム開発', 'Web制作・デザイン', '建設・リフォーム', '不動産', '保険',
    '税理士・会計士', '社労士', '弁護士', '行政書士', 'コンサルティング',
    '人材派遣', '広告・マーケティング', '印刷', '飲食店', 'カフェ',
    '美容・エステ', '整体・マッサージ', '歯科医院', 'クリニック', '介護',
    '運送・物流', '清掃サービス', 'セキュリティ', '教育・研修', '翻訳・通訳',
    '写真・映像制作', 'イベント企画', '旅行代理店', 'ホテル', '自動車販売',
    '自動車整備', '金融', '投資顧問', '葬儀', '花屋',
    '製造業', '卸売', '小売', 'EC', 'アプリ開発'
];

$members = [];
$usedEmails = [];

for ($i = 0; $i < 50; $i++) {
    $lastName = $lastNames[array_rand($lastNames)];
    $firstName = $firstNames[array_rand($firstNames)];
    $name = $lastName . ' ' . $firstName;
    $company = $companies[$i] ?? $companies[array_rand($companies)];
    $category = $categories[array_rand($categories)];

    // Generate unique email
    $emailBase = strtolower(romanize($lastName) . romanize($firstName));
    $email = $emailBase . '@example.com';
    $counter = 1;
    while (in_array($email, $usedEmails)) {
        $email = $emailBase . $counter . '@example.com';
        $counter++;
    }
    $usedEmails[] = $email;

    $username = $email;

    $members[$username] = [
        'name' => $name,
        'email' => $email,
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'company' => $company,
        'category' => $category,
        'phone' => sprintf('090-%04d-%04d', rand(1000, 9999), rand(1000, 9999)),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(30, 180) . ' days')),
        'updated_at' => date('Y-m-d H:i:s', strtotime('-' . rand(0, 30) . ' days'))
    ];
}

// Save members data
$membersData = ['users' => $members];
$membersJson = json_encode($membersData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/data/members.json', $membersJson);

echo "✓ メンバーデータ生成完了: 50名\n";

// ==========================================
// 2. 週次アンケートデータ生成（8週分）
// ==========================================

$visitorCompanies = [
    'ABC商事', 'XYZ株式会社', 'テクノソフト', 'グローバルトレーディング', 'ユニバーサル',
    'プライムコーポレーション', 'エクセレント企画', 'ベストソリューション', 'フューチャーワークス',
    'イノベーション', 'クリエイティブ', 'ダイナミック', 'パワフル', 'ストロング',
    'ブライト', 'サクセス', 'マスター', 'ビクトリー', 'チャンピオン', 'エリート'
];

$visitorFirstNames = [
    '太郎', '次郎', '健', '誠', '拓也', '大輔', '翔', '裕太', '健太', '智也',
    '花子', '美咲', '愛', '舞', '優', '彩', '美穂', '陽子', '恵', '由美'
];

$referralNames = [
    'ホームページ制作', 'Webシステム開発', 'リフォーム工事', 'オフィス移転', '保険見直し',
    '税務顧問契約', '人材紹介', '広告出稿', '印刷物制作', '店舗デザイン',
    'ネットワーク構築', 'セキュリティ対策', '清掃業務委託', '研修プログラム', '翻訳業務',
    '動画制作', 'イベント企画', '社員旅行手配', '車両リース', '法律相談',
    '不動産購入', '融資相談', 'マーケティング支援', 'SEO対策', 'SNS運用',
    'アプリ開発', 'EC構築', 'ロゴデザイン', '名刺制作', 'パンフレット制作',
    '物流委託', '配送業務', '在庫管理システム', '会計ソフト導入', '給与計算委託',
    '社会保険手続き', '就業規則作成', 'ISO取得支援', '展示会出展', '新規事業コンサル'
];

$referralCategories = [
    'IT・システム', 'Web制作', '建設', '不動産', '保険',
    '税務会計', '人材', '広告', '印刷', 'デザイン',
    'セキュリティ', '清掃', '教育', '翻訳', '映像制作',
    'イベント', '旅行', '自動車', '法律', '金融',
    'マーケティング', 'EC', 'ロゴ', '物流', '会計ソフト',
    '社労士', 'コンサル', '展示会', 'その他'
];

// Generate data for past 8 weeks (Fridays)
$fridays = [];
$currentDate = new DateTime();

// Find most recent Friday
while ($currentDate->format('w') != 5) { // 5 = Friday
    $currentDate->modify('-1 day');
}

// Generate 8 Fridays
for ($i = 0; $i < 8; $i++) {
    $fridays[] = clone $currentDate;
    $currentDate->modify('-7 days');
}

$fridays = array_reverse($fridays); // Oldest to newest

$totalRecords = 0;

foreach ($fridays as $friday) {
    $csvFilename = $friday->format('Y-m-d') . '.csv';
    $csvPath = __DIR__ . '/data/' . $csvFilename;

    // CSV header
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

    $csvData = [$header];

    // Randomly select 35-45 members to submit (70-90% attendance)
    $attendingMembers = array_rand($members, rand(35, 45));
    if (!is_array($attendingMembers)) {
        $attendingMembers = [$attendingMembers];
    }

    foreach ($attendingMembers as $username) {
        $member = $members[$username];

        $inputDate = $friday->format('Y-m-d');
        $timestamp = $friday->format('Y-m-d') . ' ' . sprintf('%02d:%02d:%02d', rand(0, 23), rand(0, 59), rand(0, 59));

        // Random attendance status (90% 出席, 5% 欠席, 5% 代理出席)
        $rand = rand(1, 100);
        if ($rand <= 90) {
            $attendance = '出席';
        } elseif ($rand <= 95) {
            $attendance = '欠席';
        } else {
            $attendance = '代理出席';
        }

        // Random visitors (30% chance, 1-2 visitors)
        $hasVisitor = rand(1, 100) <= 30;
        $visitorCount = $hasVisitor ? rand(1, 2) : 0;

        // Random referrals (40% chance, 1-3 referrals)
        $hasReferral = rand(1, 100) <= 40;
        $referralCount = $hasReferral ? rand(1, 3) : 0;

        // Random thanks slips (0-5)
        $thanksSlips = rand(0, 5);

        // Random one-to-one (0-3)
        $oneToOne = rand(0, 3);

        $maxRows = max($visitorCount, $referralCount, 1);

        for ($rowIndex = 0; $rowIndex < $maxRows; $rowIndex++) {
            // Visitor data
            if ($rowIndex < $visitorCount) {
                $visitorLastName = $lastNames[array_rand($lastNames)];
                $visitorFirstName = $visitorFirstNames[array_rand($visitorFirstNames)];
                $visitorName = $visitorLastName . ' ' . $visitorFirstName . '様';
                $visitorCompany = $visitorCompanies[array_rand($visitorCompanies)];
                $visitorIndustry = $categories[array_rand($categories)];
            } else {
                $visitorName = '';
                $visitorCompany = '';
                $visitorIndustry = '';
            }

            // Referral data
            if ($rowIndex < $referralCount) {
                $referralName = $referralNames[array_rand($referralNames)];
                $referralAmount = rand(1, 50) * 100000; // 10万〜500万
                $referralCategory = $referralCategories[array_rand($referralCategories)];

                // Random provider (could be same member or another member)
                $providerUsername = array_rand($members);
                $referralProvider = $members[$providerUsername]['name'];
            } else {
                $referralName = '-';
                $referralAmount = 0;
                $referralCategory = '';
                $referralProvider = '';
            }

            $csvData[] = [
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
                ''
            ];

            $totalRecords++;
        }
    }

    // Write CSV
    $fp = fopen($csvPath, 'w');
    foreach ($csvData as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);

    echo "✓ " . $csvFilename . " 生成完了: " . (count($csvData) - 1) . " レコード\n";
}

echo "\n";
echo "==========================================\n";
echo "テストデータ生成完了！\n";
echo "==========================================\n";
echo "メンバー数: 50名\n";
echo "週次データ: 8週分\n";
echo "総レコード数: " . $totalRecords . " 件\n";
echo "\n";
echo "生成されたファイル:\n";
echo "- data/members.json\n";
foreach ($fridays as $friday) {
    echo "- data/" . $friday->format('Y-m-d') . ".csv\n";
}
echo "\n";
echo "これでスライド表示が充実した内容になります！\n";
echo "</pre>";

// Helper function to romanize Japanese names (simple version)
function romanize($japanese) {
    $map = [
        '山田' => 'yamada', '佐藤' => 'sato', '田中' => 'tanaka', '鈴木' => 'suzuki',
        '高橋' => 'takahashi', '渡辺' => 'watanabe', '伊藤' => 'ito', '中村' => 'nakamura',
        '小林' => 'kobayashi', '加藤' => 'kato', '吉田' => 'yoshida', '山本' => 'yamamoto',
        '斉藤' => 'saito', '松本' => 'matsumoto', '井上' => 'inoue', '木村' => 'kimura',
        '林' => 'hayashi', '清水' => 'shimizu', '山崎' => 'yamazaki', '森' => 'mori',
        '太郎' => 'taro', '次郎' => 'jiro', '三郎' => 'saburo', '健' => 'ken',
        '誠' => 'makoto', '勇' => 'isamu', '隆' => 'takashi', '学' => 'manabu',
        '花子' => 'hanako', '美咲' => 'misaki', '愛' => 'ai', '舞' => 'mai',
        '優' => 'yu', '彩' => 'aya', '美穂' => 'miho', '陽子' => 'yoko',
        '阿部' => 'abe', '池田' => 'ikeda', '橋本' => 'hashimoto', '山下' => 'yamashita',
        '石川' => 'ishikawa', '中島' => 'nakajima', '前田' => 'maeda', '藤田' => 'fujita',
        '小川' => 'ogawa', '後藤' => 'goto', '岡田' => 'okada', '長谷川' => 'hasegawa',
        '村上' => 'murakami', '近藤' => 'kondo', '石井' => 'ishii', '遠藤' => 'endo',
        '青木' => 'aoki', '坂本' => 'sakamoto', '福田' => 'fukuda', '西村' => 'nishimura',
        '太田' => 'ota', '三浦' => 'miura', '藤井' => 'fujii', '岡本' => 'okamoto',
        '松田' => 'matsuda', '中野' => 'nakano', '原田' => 'harada', '竹内' => 'takeuchi',
        '小野' => 'ono', '田口' => 'taguchi',
        '修' => 'osamu', '明' => 'akira', '博' => 'hiroshi', '哲也' => 'tetsuya',
        '大輔' => 'daisuke', '健太' => 'kenta', '翔' => 'sho', '拓也' => 'takuya',
        '智也' => 'tomoya', '達也' => 'tatsuya', '裕太' => 'yuta', '雄一' => 'yuichi',
        '真理' => 'mari', '恵' => 'megumi', '浩二' => 'koji', '賢一' => 'kenichi',
        '孝' => 'takashi', '悟' => 'satoru', '剛' => 'tsuyoshi', '勝' => 'masaru',
        '栄一' => 'eiichi', '進' => 'susumu', '幸雄' => 'yukio', '正人' => 'masato',
        '優子' => 'yuko', '美香' => 'mika', '由美' => 'yumi', '直美' => 'naomi',
        '智子' => 'tomoko', '麻衣' => 'mai', '沙織' => 'saori', '春菜' => 'haruna',
        '香織' => 'kaori', '奈々' => 'nana'
    ];

    return $map[$japanese] ?? strtolower(str_replace(' ', '', $japanese));
}
