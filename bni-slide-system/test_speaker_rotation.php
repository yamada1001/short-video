<?php
/**
 * スピーカーローテーションテストスクリプト
 */

$dbPath = __DIR__ . '/database/bni_slide_v2.db';
$db = new SQLite3($dbPath);

// テスト用に6週分のダミーデータを作成
function calculateSixFridays() {
    $fridays = [];
    $today = new DateTime();
    $dayOfWeek = (int)$today->format('w');
    $daysUntilFriday = (5 - $dayOfWeek + 7) % 7;
    if ($daysUntilFriday > 0 || ($daysUntilFriday === 0 && $dayOfWeek !== 5)) {
        $thisFriday = clone $today;
        $thisFriday->modify("+{$daysUntilFriday} days");
    } else {
        $thisFriday = clone $today;
        if ($dayOfWeek !== 5) {
            $daysUntilNextFriday = 5 + (7 - $dayOfWeek);
            $thisFriday->modify("+{$daysUntilNextFriday} days");
        }
    }
    for ($i = 3; $i >= 1; $i--) {
        $pastFriday = clone $thisFriday;
        $pastFriday->modify("-{$i} weeks");
        $fridays[] = $pastFriday->format('Y-m-d');
    }
    $fridays[] = $thisFriday->format('Y-m-d');
    for ($i = 1; $i <= 2; $i++) {
        $futureFriday = clone $thisFriday;
        $futureFriday->modify("+{$i} weeks");
        $fridays[] = $futureFriday->format('Y-m-d');
    }
    return $fridays;
}

// メンバーIDを取得
$result = $db->query('SELECT id, name FROM members WHERE is_active = 1 LIMIT 6');
$members = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $members[] = $row;
}

if (empty($members)) {
    echo "エラー: アクティブなメンバーが見つかりません\n";
    exit(1);
}

$fridays = calculateSixFridays();

// データベースにテストデータを挿入
$db->exec('BEGIN TRANSACTION');
foreach ($fridays as $index => $friday) {
    $member = $members[$index % count($members)];
    $referralTarget = $member['name'] . ' さんの紹介先（テスト）';

    // 既存データを削除
    $stmt = $db->prepare('DELETE FROM speaker_rotation WHERE rotation_date = :date');
    $stmt->bindValue(':date', $friday, SQLITE3_TEXT);
    $stmt->execute();

    // 新しいデータを挿入
    $stmt = $db->prepare('
        INSERT INTO speaker_rotation (rotation_date, main_presenter_id, referral_target)
        VALUES (:date, :member_id, :referral_target)
    ');
    $stmt->bindValue(':date', $friday, SQLITE3_TEXT);
    $stmt->bindValue(':member_id', $member['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':referral_target', $referralTarget, SQLITE3_TEXT);
    $stmt->execute();
}
$db->exec('COMMIT');

echo "✅ テストデータを挿入しました\n\n";
echo "📅 6週分の金曜日:\n";
foreach ($fridays as $index => $friday) {
    $label = '';
    if ($index < 3) {
        $label = "過去" . (3 - $index) . "週";
    } elseif ($index === 3) {
        $label = "今週";
    } else {
        $label = "未来" . ($index - 3) . "週";
    }
    echo "  {$label}: {$friday}\n";
}

// 検証
$result = $db->query('
    SELECT
        sr.rotation_date,
        m.name,
        sr.referral_target
    FROM speaker_rotation sr
    LEFT JOIN members m ON sr.main_presenter_id = m.id
    ORDER BY sr.rotation_date
');

echo "\n📊 挿入されたデータ:\n";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "  {$row['rotation_date']} - {$row['name']} - {$row['referral_target']}\n";
}

echo "\n✅ テスト完了\n";
