#!/usr/bin/env php
<?php
/**
 * リマインダーメール送信（Cron実行用）
 * 明日開催のセミナー参加者にリマインダーメールを送信
 * 実行タイミング: 毎日 18:00
 */

require_once __DIR__ . '/../config/config.php';

use Seminar\Database;
use Seminar\EmailSender;

echo "━━━━━━━━━━━━━━━━━━━━━━\n";
echo "リマインダーメール送信処理開始\n";
echo "実行日時: " . date('Y-m-d H:i:s') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n\n";

$db = Database::getInstance();
$emailSender = new EmailSender();

// 明日開催のセミナーを取得（開始時刻が24時間後〜48時間後）
$tomorrow = date('Y-m-d');
$dayAfter = date('Y-m-d', strtotime('+2 days'));

$attendees = $db->query(
    "SELECT
        a.id,
        a.name,
        a.email,
        s.title as seminar_title,
        s.start_datetime
     FROM attendees a
     JOIN seminars s ON a.seminar_id = s.id
     WHERE a.status IN ('paid')
     AND DATE(s.start_datetime) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
     ORDER BY s.start_datetime ASC, a.id ASC"
);

if (empty($attendees)) {
    echo "送信対象の参加者はいません。\n\n";
    Logger::info('[Cron] リマインダーメール: 送信対象なし');
    exit(0);
}

echo "送信対象参加者: " . count($attendees) . "名\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($attendees as $attendee) {
    echo "処理中: ID={$attendee['id']}, {$attendee['name']} ({$attendee['email']})\n";
    echo "  セミナー: {$attendee['seminar_title']}\n";
    echo "  開催日時: {$attendee['start_datetime']}\n";

    // 既に送信済みかチェック
    if (EmailSender::hasSent($attendee['id'], 'reminder')) {
        echo "  → スキップ（送信済み）\n\n";
        continue;
    }

    // メール送信
    try {
        if ($emailSender->sendReminder($attendee['id'])) {
            echo "  → 送信成功 ✓\n\n";
            $successCount++;
        } else {
            echo "  → 送信失敗 ✗\n\n";
            $errorCount++;
        }
    } catch (\Exception $e) {
        echo "  → エラー: {$e->getMessage()} ✗\n\n";
        $errorCount++;
        Logger::error('[Cron] リマインダーメール送信エラー', [
            'attendee_id' => $attendee['id'],
            'error' => $e->getMessage()
        ]);
    }

    // 負荷軽減のため少し待機
    usleep(100000); // 0.1秒
}

echo "━━━━━━━━━━━━━━━━━━━━━━\n";
echo "処理完了\n";
echo "成功: {$successCount}件\n";
echo "失敗: {$errorCount}件\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n";

Logger::info('[Cron] リマインダーメール送信完了', [
    'success' => $successCount,
    'error' => $errorCount
]);

exit(0);
