#!/usr/bin/env php
<?php
/**
 * サンクスメール送信（Cron実行用）
 * セミナー終了後の出席者にサンクスメール（PDF添付）を送信
 * 実行タイミング: 毎日 22:00
 */

require_once __DIR__ . '/../config/config.php';

use Seminar\Database;
use Seminar\EmailSender;

echo "━━━━━━━━━━━━━━━━━━━━━━\n";
echo "サンクスメール送信処理開始\n";
echo "実行日時: " . date('Y-m-d H:i:s') . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n\n";

$db = Database::getInstance();
$emailSender = new EmailSender();

// 今日終了したセミナーの出席者を取得
$attendees = $db->query(
    "SELECT
        a.id,
        a.name,
        a.email,
        s.title as seminar_title,
        s.end_datetime,
        s.pdf_path
     FROM attendees a
     JOIN seminars s ON a.seminar_id = s.id
     WHERE a.status = 'attended'
     AND DATE(s.end_datetime) = CURDATE()
     AND s.end_datetime < NOW()
     ORDER BY s.end_datetime ASC, a.id ASC"
);

if (empty($attendees)) {
    echo "送信対象の参加者はいません。\n\n";
    Logger::info('[Cron] サンクスメール: 送信対象なし');
    exit(0);
}

echo "送信対象参加者: " . count($attendees) . "名\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($attendees as $attendee) {
    echo "処理中: ID={$attendee['id']}, {$attendee['name']} ({$attendee['email']})\n";
    echo "  セミナー: {$attendee['seminar_title']}\n";
    echo "  終了日時: {$attendee['end_datetime']}\n";

    if ($attendee['pdf_path']) {
        echo "  PDF: {$attendee['pdf_path']}\n";
    } else {
        echo "  PDF: なし\n";
    }

    // 既に送信済みかチェック
    if (EmailSender::hasSent($attendee['id'], 'thanks')) {
        echo "  → スキップ（送信済み）\n\n";
        continue;
    }

    // メール送信
    try {
        if ($emailSender->sendThanks($attendee['id'])) {
            echo "  → 送信成功 ✓\n\n";
            $successCount++;
        } else {
            echo "  → 送信失敗 ✗\n\n";
            $errorCount++;
        }
    } catch (\Exception $e) {
        echo "  → エラー: {$e->getMessage()} ✗\n\n";
        $errorCount++;
        Logger::error('[Cron] サンクスメール送信エラー', [
            'attendee_id' => $attendee['id'],
            'error' => $e->getMessage()
        ]);
    }

    // 負荷軽減のため少し待機
    usleep(200000); // 0.2秒（PDF添付があるため少し長めに）
}

echo "━━━━━━━━━━━━━━━━━━━━━━\n";
echo "処理完了\n";
echo "成功: {$successCount}件\n";
echo "失敗: {$errorCount}件\n";
echo "━━━━━━━━━━━━━━━━━━━━━━\n";

Logger::info('[Cron] サンクスメール送信完了', [
    'success' => $successCount,
    'error' => $errorCount
]);

exit(0);
