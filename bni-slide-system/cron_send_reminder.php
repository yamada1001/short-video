<?php
/**
 * BNI Slide System - Weekly Reminder Email Sender
 * 週次リマインダーメール送信スクリプト（cron実行用）
 *
 * 実行タイミング:
 * 1. 金曜日19時 - 未回答者に初回リマインド
 * 2. 水曜日20時 - まだ未回答の方に2回目リマインド
 * 3. 木曜日20時 - まだ未回答の方に最終リマインド
 *
 * 使い方:
 * php /path/to/cron_send_reminder.php [reminder_type]
 *
 * reminder_type: friday, wednesday, thursday
 */

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

require_once __DIR__ . '/includes/date_helper.php';

// 設定
define('MAIL_FROM', 'yamada@yojitu.com');
define('MAIL_FROM_NAME', 'BNI Slide System');
define('SURVEY_URL', 'https://yojitu.com/bni-slide-system/');

// リマインダータイプを取得
$reminderType = $argv[1] ?? 'friday';

// 今週の金曜日を取得
$thisFridayStr = getTargetFriday(date('Y-m-d H:i:s'));
$thisFriday = new DateTime($thisFridayStr);
$csvFile = __DIR__ . '/data/' . $thisFriday->format('Y-m-d') . '.csv';

echo "[" . date('Y-m-d H:i:s') . "] リマインダー送信開始: {$reminderType}\n";
echo "対象週: " . $thisFriday->format('Y-m-d') . "\n";

// メンバーリストを読み込み
$membersFile = __DIR__ . '/data/members.json';
if (!file_exists($membersFile)) {
    echo "エラー: メンバーファイルが見つかりません\n";
    exit(1);
}

$content = file_get_contents($membersFile);
$data = json_decode($content, true);

if (!$data || !isset($data['users'])) {
    echo "エラー: メンバーデータの読み込みに失敗しました\n";
    exit(1);
}

// 既に回答済みのメンバーを取得
$submittedMembers = [];
if (file_exists($csvFile)) {
    $fp = fopen($csvFile, 'r');

    // BOMスキップ
    $bom = fread($fp, 3);
    if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
        rewind($fp);
    }

    // ヘッダーをスキップ
    fgetcsv($fp);

    // データを読み込み
    while (($row = fgetcsv($fp)) !== false) {
        if (count($row) >= 3) {
            $email = $row[2]; // メールアドレス列
            if (!empty($email)) {
                $submittedMembers[] = strtolower(trim($email));
            }
        }
    }

    fclose($fp);

    // 重複削除
    $submittedMembers = array_unique($submittedMembers);
}

echo "提出済みメンバー数: " . count($submittedMembers) . "\n";

// リマインド対象のメンバーを抽出
$targetMembers = [];

if ($reminderType === 'thursday') {
    // 木曜日: 回答済みの方に更新情報確認
    foreach ($data['users'] as $email => $user) {
        $emailLower = strtolower(trim($email));

        // 提出済みの場合のみリマインド対象
        if (in_array($emailLower, $submittedMembers)) {
            $targetMembers[] = [
                'email' => $email,
                'name' => $user['name'] ?? 'メンバー'
            ];
        }
    }

    echo "回答済みメンバー数: " . count($targetMembers) . "\n";

    if (count($targetMembers) === 0) {
        echo "回答済みのメンバーがいません。リマインダーの送信は不要です。\n";
        exit(0);
    }
} else {
    // 金曜日・水曜日: 未回答の方にリマインド
    foreach ($data['users'] as $email => $user) {
        $emailLower = strtolower(trim($email));

        // 提出済みでない場合のみリマインド対象
        if (!in_array($emailLower, $submittedMembers)) {
            $targetMembers[] = [
                'email' => $email,
                'name' => $user['name'] ?? 'メンバー'
            ];
        }
    }

    echo "未提出メンバー数: " . count($targetMembers) . "\n";

    if (count($targetMembers) === 0) {
        echo "全員提出済みです。リマインダーの送信は不要です。\n";
        exit(0);
    }
}

// メール内容を生成
$mailData = getReminderMailContent($reminderType, $thisFriday);

echo "件名: " . $mailData['subject'] . "\n";
echo "送信開始...\n";

// リマインダーメールを送信
$sentCount = 0;
$failedCount = 0;

foreach ($targetMembers as $member) {
    $email = $member['email'];
    $name = $member['name'];

    // メール本文を個別化
    $message = str_replace('[NAME]', $name, $mailData['message']);

    // メールヘッダー
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // メール送信
    $result = mail($email, $mailData['subject'], $message, $headers);

    if ($result) {
        echo "  ✓ 送信成功: {$name} ({$email})\n";
        $sentCount++;
    } else {
        echo "  ✗ 送信失敗: {$name} ({$email})\n";
        $failedCount++;
    }

    // 送信間隔を空ける（サーバー負荷軽減）
    usleep(500000); // 0.5秒
}

echo "\n送信完了\n";
echo "成功: {$sentCount}件\n";
echo "失敗: {$failedCount}件\n";
echo "[" . date('Y-m-d H:i:s') . "] リマインダー送信終了\n";

/**
 * リマインダーメールの内容を取得
 *
 * @param string $type リマインダータイプ (friday, wednesday, thursday)
 * @param DateTime $targetFriday 対象の金曜日
 * @return array ['subject' => 件名, 'message' => 本文]
 */
function getReminderMailContent($type, $targetFriday) {
    $fridayDate = $targetFriday->format('Y年n月j日');

    switch ($type) {
        case 'friday':
            // 金曜日19時: 初回リマインド
            return [
                'subject' => '【BNI】今週のアンケート提出のお願い',
                'message' => "[NAME] 様

お疲れ様です。
BNI Slide System より、今週のアンケート提出のお願いです。

今週（{$fridayDate}週）のアンケートをまだご提出いただいておりません。
お手数ですが、下記URLよりご回答をお願いいたします。

▼アンケート回答はこちら
" . SURVEY_URL . "

ご多忙のところ恐れ入りますが、ご協力をお願いいたします。

---
BNI Slide System
" . MAIL_FROM
            ];

        case 'wednesday':
            // 水曜日20時: 2回目リマインド
            return [
                'subject' => '【BNI】アンケート提出のお願い（再送）',
                'message' => "[NAME] 様

お疲れ様です。
BNI Slide System より、今週のアンケート提出のお願いです。

今週（{$fridayDate}週）のアンケートをまだご提出いただいておりません。
明日のミーティングまでに、ご回答をお願いいたします。

▼アンケート回答はこちら
" . SURVEY_URL . "

お忙しいところ恐れ入りますが、ご協力をお願いいたします。

---
BNI Slide System
" . MAIL_FROM
            ];

        case 'thursday':
            // 木曜日20時: 回答済みの方への更新確認
            return [
                'subject' => '【BNI】更新情報はありませんか？',
                'message' => "[NAME] 様

お疲れ様です。
BNI Slide System より、更新情報のご確認です。

今週（{$fridayDate}週）のアンケートをご提出いただき、ありがとうございます。

その後、新たなリファーラルやビジターの追加など、
更新すべき情報はございませんでしょうか？

もし追加情報がございましたら、マイデータページより
いつでも更新が可能です。

▼マイデータページはこちら
" . SURVEY_URL . "my-data.php

※明日のミーティング当日の朝5時までに更新いただければ
　スライドに反映されます。

ご協力ありがとうございます。

---
BNI Slide System
" . MAIL_FROM
            ];

        default:
            return [
                'subject' => '【BNI】アンケート提出のお願い',
                'message' => "[NAME] 様

今週のアンケートをご提出ください。

" . SURVEY_URL . "

よろしくお願いいたします。
"
            ];
    }
}
