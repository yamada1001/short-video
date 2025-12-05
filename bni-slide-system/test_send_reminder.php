<?php
/**
 * BNI Slide System - Test Reminder Email Sender
 * リマインダーメールのテスト送信スクリプト
 */

require_once __DIR__ . '/includes/date_helper.php';

// 設定
define('MAIL_FROM', 'yamada@yojitu.com');
define('MAIL_FROM_NAME', 'BNI Slide System');
define('SURVEY_URL', 'https://yojitu.com/bni-slide-system/');

// テスト送信先
$testEmail = 'yamada1881r@gmail.com';
$testName = 'テストユーザー';

// 今週の金曜日を取得
$thisFriday = getTargetFriday(time());

echo "==============================================\n";
echo "リマインダーメール テスト送信\n";
echo "==============================================\n";
echo "送信先: {$testEmail}\n";
echo "対象週: " . $thisFriday->format('Y-m-d') . "\n";
echo "\n";

// 3種類のリマインダーを送信
$reminderTypes = ['friday', 'wednesday', 'thursday'];
$sentCount = 0;

foreach ($reminderTypes as $type) {
    echo "----------------------------------------------\n";
    echo "送信中: {$type}\n";

    // メール内容を取得
    $mailData = getReminderMailContent($type, $thisFriday);
    $message = str_replace('[NAME]', $testName, $mailData['message']);

    echo "件名: " . $mailData['subject'] . "\n";

    // メールヘッダー
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // メール送信
    $result = mail($testEmail, $mailData['subject'], $message, $headers);

    if ($result) {
        echo "✓ 送信成功\n";
        $sentCount++;
    } else {
        echo "✗ 送信失敗\n";
    }

    echo "\n";

    // 送信間隔を空ける
    sleep(2);
}

echo "==============================================\n";
echo "テスト送信完了: {$sentCount}/3 件送信\n";
echo "==============================================\n";

/**
 * リマインダーメールの内容を取得
 */
function getReminderMailContent($type, $targetFriday) {
    $fridayDate = $targetFriday->format('Y年n月j日');

    switch ($type) {
        case 'friday':
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
                'message' => 'テストメッセージ'
            ];
    }
}
