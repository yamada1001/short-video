<?php

namespace Seminar;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * メール送信クラス
 */
class EmailSender
{
    private PHPMailer $mailer;
    private Database $db;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->db = Database::getInstance();

        // SMTP設定
        $this->mailer->isSMTP();
        $this->mailer->Host = env('SMTP_HOST', 'smtp.gmail.com');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = env('SMTP_USERNAME');
        $this->mailer->Password = env('SMTP_PASSWORD');
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int)env('SMTP_PORT', 587);

        // デフォルト設定
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'セミナー運営事務局'));
    }

    /**
     * 基本的なメール送信
     *
     * @param string $to 送信先メールアドレス
     * @param string $subject 件名
     * @param string $body 本文
     * @param string|null $attachment 添付ファイルパス
     * @return bool 送信成功/失敗
     */
    public function sendMail(string $to, string $subject, string $body, ?string $attachment = null): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            if ($attachment && file_exists($attachment)) {
                $this->mailer->addAttachment($attachment);
            }

            $result = $this->mailer->send();

            // ログ記録
            Logger::info("メール送信成功: {$to} - {$subject}");

            return $result;
        } catch (Exception $e) {
            Logger::error("メール送信失敗: {$to} - {$subject} - {$e->getMessage()}");
            return false;
        }
    }

    /**
     * 申込確認メール送信
     *
     * @param int $attendeeId 参加者ID
     * @return bool
     */
    public function sendRegistrationConfirmation(int $attendeeId): bool
    {
        $attendee = Attendee::getById($attendeeId);
        if (!$attendee) {
            return false;
        }

        $seminar = Seminar::getById($attendee['seminar_id']);
        if (!$seminar) {
            return false;
        }

        $subject = "【申込受付完了】{$seminar['title']}";

        $body = "{$attendee['name']} 様\n\n";
        $body .= "セミナーへのお申し込みありがとうございます。\n";
        $body .= "以下の内容で申込を受け付けました。\n\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ セミナー情報\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "セミナー名: {$seminar['title']}\n";
        $body .= "開催日時: " . formatDatetime($seminar['start_datetime'], 'Y年m月d日（D） H:i') . "\n";
        $body .= "会場: " . ($seminar['venue'] ?: '未定') . "\n";
        $body .= "参加費: " . formatPrice($seminar['price']) . "\n\n";

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ お支払い手続き\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "下記URLよりお支払い手続きをお願いいたします。\n";
        $body .= getBaseUrl() . "/seminar-system/public/payment.php?email=" . urlencode($attendee['email']) . "\n\n";

        // クレジット保有の場合
        $totalCredit = Attendee::getTotalCredit($attendee['email']);
        if ($totalCredit > 0) {
            $body .= "※ 繰越クレジット {$totalCredit}円 をお持ちです。\n";
            $body .= "  お支払い時に「クレジットを使用する」にチェックを入れてください。\n\n";
        }

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 欠席される場合\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "やむを得ず欠席される場合は、下記URLより欠席申請をお願いします。\n";
        $body .= "セミナー料金と同額のクレジットが付与され、次回セミナーでご利用いただけます。\n\n";
        $body .= getBaseUrl() . "/seminar-system/public/cancel.php?token=" . urlencode($attendee['cancel_token']) . "\n\n";

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $body .= "何かご不明点がございましたら、お気軽にお問い合わせください。\n\n";
        $body .= "当日お会いできることを楽しみにしております。\n\n";
        $body .= "--\n";
        $body .= env('MAIL_FROM_NAME', 'セミナー運営事務局') . "\n";
        $body .= env('MAIL_FROM_ADDRESS') . "\n";

        $result = $this->sendMail($attendee['email'], $subject, $body);

        // メールログ記録
        if ($result) {
            $this->logEmail($attendeeId, 'registration_confirmation', $subject, $body);
        }

        return $result;
    }

    /**
     * 支払い完了メール送信
     *
     * @param int $attendeeId 参加者ID
     * @return bool
     */
    public function sendPaymentConfirmation(int $attendeeId): bool
    {
        $attendee = Attendee::getById($attendeeId);
        if (!$attendee) {
            return false;
        }

        $seminar = Seminar::getById($attendee['seminar_id']);
        if (!$seminar) {
            return false;
        }

        $subject = "【お支払い完了】{$seminar['title']}";

        $body = "{$attendee['name']} 様\n\n";
        $body .= "お支払いいただきありがとうございます。\n";
        $body .= "セミナー参加の準備が整いました。\n\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ セミナー情報\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "セミナー名: {$seminar['title']}\n";
        $body .= "開催日時: " . formatDatetime($seminar['start_datetime'], 'Y年m月d日（D） H:i') . "\n";
        $body .= "会場: " . ($seminar['venue'] ?: '未定') . "\n\n";

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 当日の受付について\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "当日は下記URLよりQRコードを表示し、受付スタッフにお見せください。\n\n";
        $body .= getBaseUrl() . "/seminar-system/public/checkin.php?token=" . urlencode($attendee['qr_token']) . "\n\n";

        $body .= "※ スマートフォンで上記URLにアクセスしてください。\n";
        $body .= "※ QRコードは当日受付で使用します。\n\n";

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $body .= "当日お会いできることを楽しみにしております。\n\n";
        $body .= "--\n";
        $body .= env('MAIL_FROM_NAME', 'セミナー運営事務局') . "\n";
        $body .= env('MAIL_FROM_ADDRESS') . "\n";

        $result = $this->sendMail($attendee['email'], $subject, $body);

        // メールログ記録
        if ($result) {
            $this->logEmail($attendeeId, 'payment_confirmation', $subject, $body);
        }

        return $result;
    }

    /**
     * リマインダーメール送信
     *
     * @param int $attendeeId 参加者ID
     * @return bool
     */
    public function sendReminder(int $attendeeId): bool
    {
        $attendee = Attendee::getById($attendeeId);
        if (!$attendee) {
            return false;
        }

        $seminar = Seminar::getById($attendee['seminar_id']);
        if (!$seminar) {
            return false;
        }

        $subject = "【明日開催】{$seminar['title']} のご案内";

        $body = "{$attendee['name']} 様\n\n";
        $body .= "明日のセミナー開催が近づいてまいりました。\n";
        $body .= "ご参加をお待ちしております。\n\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ セミナー情報\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "セミナー名: {$seminar['title']}\n";
        $body .= "開催日時: " . formatDatetime($seminar['start_datetime'], 'Y年m月d日（D） H:i') . "\n";
        $body .= "会場: " . ($seminar['venue'] ?: '未定') . "\n\n";

        if ($seminar['description']) {
            $body .= "内容:\n{$seminar['description']}\n\n";
        }

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "■ 当日の受付\n";
        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
        $body .= "QRコードをご用意ください:\n";
        $body .= getBaseUrl() . "/seminar-system/public/checkin.php?token=" . urlencode($attendee['qr_token']) . "\n\n";

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $body .= "お気をつけてお越しください。\n\n";
        $body .= "--\n";
        $body .= env('MAIL_FROM_NAME', 'セミナー運営事務局') . "\n";
        $body .= env('MAIL_FROM_ADDRESS') . "\n";

        $result = $this->sendMail($attendee['email'], $subject, $body);

        // メールログ記録
        if ($result) {
            $this->logEmail($attendeeId, 'reminder', $subject, $body);
        }

        return $result;
    }

    /**
     * サンクスメール送信（PDF添付）
     *
     * @param int $attendeeId 参加者ID
     * @return bool
     */
    public function sendThanks(int $attendeeId): bool
    {
        $attendee = Attendee::getById($attendeeId);
        if (!$attendee) {
            return false;
        }

        $seminar = Seminar::getById($attendee['seminar_id']);
        if (!$seminar) {
            return false;
        }

        // カスタマイズされたサンクスメール設定を使用（なければデフォルト）
        $subject = $seminar['thanks_mail_subject'] ?: "【ご参加ありがとうございました】{$seminar['title']}";

        $body = "{$attendee['name']} 様\n\n";

        // カスタム本文があればそれを使用
        if ($seminar['thanks_mail_body']) {
            $body .= $seminar['thanks_mail_body'] . "\n\n";
        } else {
            $body .= "本日はセミナーにご参加いただき、誠にありがとうございました。\n";
            $body .= "今後とも何卒よろしくお願いいたします。\n\n";
        }

        // PDF添付がある場合
        $pdfPath = null;
        if ($seminar['pdf_path']) {
            $fullPath = __DIR__ . '/../' . $seminar['pdf_path'];
            if (file_exists($fullPath)) {
                $pdfPath = $fullPath;
                $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                $body .= "■ 資料について\n";
                $body .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                $body .= "セミナーで使用した資料を添付しております。\n";
                $body .= "ぜひご活用ください。\n\n";
            }
        }

        $body .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $body .= "またのご参加を心よりお待ちしております。\n\n";
        $body .= "--\n";
        $body .= ($seminar['mail_sender_name'] ?: env('MAIL_FROM_NAME', 'セミナー運営事務局')) . "\n";
        $body .= env('MAIL_FROM_ADDRESS') . "\n";

        $result = $this->sendMail($attendee['email'], $subject, $body, $pdfPath);

        // メールログ記録
        if ($result) {
            $this->logEmail($attendeeId, 'thanks', $subject, $body);
        }

        return $result;
    }

    /**
     * メール送信ログを記録
     *
     * @param int $attendeeId 参加者ID
     * @param string $emailType メール種別
     * @param string $subject 件名
     * @param string $body 本文
     */
    private function logEmail(int $attendeeId, string $emailType, string $subject, string $body): void
    {
        $this->db->execute(
            "INSERT INTO email_logs (attendee_id, email_type, subject, body, sent_at)
             VALUES (?, ?, ?, ?, NOW())",
            [$attendeeId, $emailType, $subject, $body]
        );
    }

    /**
     * 特定の参加者にメールが送信済みかチェック
     *
     * @param int $attendeeId 参加者ID
     * @param string $emailType メール種別
     * @return bool
     */
    public static function hasSent(int $attendeeId, string $emailType): bool
    {
        $db = Database::getInstance();
        $result = $db->fetch(
            "SELECT COUNT(*) as count FROM email_logs
             WHERE attendee_id = ? AND email_type = ?",
            [$attendeeId, $emailType]
        );

        return (int)$result['count'] > 0;
    }
}
