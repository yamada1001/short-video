<?php
/**
 * BNI Slide System - Weekly Reminder Email Sender (SQLite Version)
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

require_once __DIR__ . '/includes/db.php';

// 設定
define('MAIL_FROM', 'yamada@yojitu.com');
define('MAIL_FROM_NAME', 'BNI Slide System');
define('SURVEY_URL', 'https://yojitu.com/bni-slide-system/');

// リマインダータイプを取得
$reminderType = $argv[1] ?? 'friday';

// 今週の金曜日を取得
$thisFriday = getTargetFriday(time());
$thisFridayStr = $thisFriday->format('Y-m-d');

echo "[" . date('Y-m-d H:i:s') . "] リマインダー送信開始: {$reminderType}\n";
echo "対象週: " . $thisFridayStr . "\n";

try {
    $db = getDbConnection();

    // メンバーリストを取得
    $membersQuery = "SELECT id, name, email FROM users WHERE is_active = 1 ORDER BY name";
    $allMembers = dbQuery($db, $membersQuery);

    if (empty($allMembers)) {
        echo "エラー: メンバーが登録されていません\n";
        dbClose($db);
        exit(1);
    }

    echo "登録メンバー数: " . count($allMembers) . "\n";

    // 既に回答済みのメンバーを取得
    $submittedQuery = "SELECT DISTINCT user_email FROM survey_data WHERE week_date = :week_date";
    $submittedResults = dbQuery($db, $submittedQuery, [':week_date' => $thisFridayStr]);

    $submittedEmails = [];
    foreach ($submittedResults as $row) {
        $submittedEmails[] = strtolower(trim($row['user_email']));
    }

    echo "提出済みメンバー数: " . count($submittedEmails) . "\n";

    // リマインド対象のメンバーを抽出
    $targetMembers = [];

    if ($reminderType === 'thursday') {
        // 木曜日: 回答済みの方に更新情報確認
        foreach ($allMembers as $member) {
            $emailLower = strtolower(trim($member['email']));

            // 提出済みの場合のみリマインド対象
            if (in_array($emailLower, $submittedEmails)) {
                $targetMembers[] = [
                    'email' => $member['email'],
                    'name' => $member['name'] ?? 'メンバー'
                ];
            }
        }

        echo "回答済みメンバー数: " . count($targetMembers) . "\n";

        if (count($targetMembers) === 0) {
            echo "回答済みのメンバーがいません。リマインダーの送信は不要です。\n";
            dbClose($db);
            exit(0);
        }
    } else {
        // 金曜日・水曜日: 未回答の方にリマインド
        foreach ($allMembers as $member) {
            $emailLower = strtolower(trim($member['email']));

            // 提出済みでない場合のみリマインド対象
            if (!in_array($emailLower, $submittedEmails)) {
                $targetMembers[] = [
                    'email' => $member['email'],
                    'name' => $member['name'] ?? 'メンバー'
                ];
            }
        }

        echo "未提出メンバー数: " . count($targetMembers) . "\n";

        if (count($targetMembers) === 0) {
            echo "全員提出済みです。リマインダーの送信は不要です。\n";
            dbClose($db);
            exit(0);
        }
    }

    dbClose($db);

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

} catch (Exception $e) {
    if (isset($db)) {
        dbClose($db);
    }
    echo "エラー: " . $e->getMessage() . "\n";
    exit(1);
}

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

/**
 * Get target Friday date from timestamp
 */
function getTargetFriday($timestamp) {
    $dt = is_int($timestamp) ? (new DateTime())->setTimestamp($timestamp) : new DateTime($timestamp);
    $dayOfWeek = intval($dt->format('w'));
    $hour = intval($dt->format('H'));

    if ($dayOfWeek === 5 && $hour < 5) {
        return $dt;
    }

    if ($dayOfWeek === 5) {
        $dt->modify('+7 days');
    } else {
        $daysToAdd = (5 - $dayOfWeek + 7) % 7;
        if ($daysToAdd === 0) {
            $daysToAdd = 7;
        }
        $dt->modify("+$daysToAdd days");
    }

    return $dt;
}
