<?php
/**
 * 申込完了ページ
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Attendee;

// 参加者ID取得
$attendeeId = (int)get('attendee_id');

if (!$attendeeId) {
    redirect('/public/index.php');
}

// 参加者情報取得
$attendee = Attendee::getById($attendeeId);

if (!$attendee) {
    redirect('/public/index.php');
}

// 欠席リンクURL
$cancelUrl = env('APP_URL') . '/public/cancel.php?token=' . $attendee['cancel_token'];

// QRコードチェックインURL
$checkinUrl = env('APP_URL') . '/public/checkin.php?token=' . $attendee['qr_code_token'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申込完了 - セミナー管理システム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.8;
            color: #333;
            background: #fafafa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .success-message {
            text-align: center;
            margin-bottom: 60px;
        }

        .success-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .success-title {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .success-subtitle {
            font-size: 13px;
            color: #999;
        }

        .info-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 48px 32px;
            margin-bottom: 32px;
        }

        .info-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row {
            display: flex;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 140px;
            font-size: 13px;
            color: #666;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            font-size: 15px;
            color: #333;
        }

        .next-steps {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 48px 32px;
            margin-bottom: 32px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: #333;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 500;
            flex-shrink: 0;
            margin-right: 16px;
        }

        .step-content {
            flex: 1;
            padding-top: 4px;
        }

        .step-title {
            font-size: 15px;
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }

        .step-desc {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 16px 48px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #333;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background: #000;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .link-section {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 32px;
            margin-bottom: 16px;
        }

        .link-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 12px;
        }

        .link-url {
            font-size: 12px;
            color: #999;
            word-break: break-all;
            padding: 12px;
            background: #fafafa;
            border: 1px solid #e0e0e0;
            font-family: monospace;
        }

        .text-muted {
            font-size: 12px;
            color: #999;
            margin-top: 8px;
        }

        .back-link {
            text-align: center;
            margin-top: 32px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }

        .back-link a:hover {
            color: #333;
        }

        @media (max-width: 640px) {
            .container {
                padding: 40px 16px;
            }

            .info-card,
            .next-steps,
            .link-section {
                padding: 32px 24px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 4px;
            }

            .btn {
                padding: 14px 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 成功メッセージ -->
        <div class="success-message">
            <div class="success-icon">✓</div>
            <h1 class="success-title">申込が完了しました</h1>
            <p class="success-subtitle">ご登録いただいたメールアドレスに確認メールを送信しました</p>
        </div>

        <!-- セミナー情報 -->
        <div class="info-card">
            <h2 class="info-title">セミナー情報</h2>

            <div class="info-row">
                <div class="info-label">セミナー名</div>
                <div class="info-value"><?php echo h($attendee['seminar_title']); ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">開催日時</div>
                <div class="info-value">
                    <?php echo formatDatetime($attendee['start_datetime'], 'Y年m月d日（' . getWeekday($attendee['start_datetime']) . '）H:i'); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">参加者名</div>
                <div class="info-value"><?php echo h($attendee['name']); ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">メールアドレス</div>
                <div class="info-value"><?php echo h($attendee['email']); ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">ステータス</div>
                <div class="info-value"><?php echo getStatusLabel($attendee['status']); ?></div>
            </div>
        </div>

        <!-- 次のステップ -->
        <div class="next-steps">
            <h2 class="info-title">次のステップ</h2>

            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <div class="step-title">メールを確認</div>
                    <div class="step-desc">
                        <?php echo h($attendee['email']); ?> に申込完了メールを送信しました。<br>
                        メールには欠席リンクとQRコードチェックイン用のURLが記載されています。
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <div class="step-title">参加費のお支払い</div>
                    <div class="step-desc">
                        下記のボタンから参加費（<?php echo formatPrice($attendee['price']); ?>）をお支払いください。
                    </div>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <div class="step-title">当日はQRコードでチェックイン</div>
                    <div class="step-desc">
                        セミナー当日は、メールに記載されたQRコードでチェックインしてください。
                    </div>
                </div>
            </div>
        </div>

        <!-- 支払いボタン -->
        <a href="/seminar-system/public/payment.php?email=<?php echo urlencode($attendee['email']); ?>" class="btn btn-block">
            参加費を支払う
        </a>

        <!-- 欠席リンク -->
        <div class="link-section">
            <div class="link-title">やむを得ず欠席される場合</div>
            <div class="link-url"><?php echo h($cancelUrl); ?></div>
            <p class="text-muted">上記URLから欠席の手続きができます。次回セミナーで使えるクレジットが付与されます。</p>
        </div>

        <!-- QRコードリンク -->
        <div class="link-section">
            <div class="link-title">QRコードチェックイン用URL</div>
            <div class="link-url"><?php echo h($checkinUrl); ?></div>
            <p class="text-muted">セミナー当日、会場でこのURLにアクセスしてチェックインしてください。</p>
        </div>

        <!-- トップへ戻る -->
        <div class="back-link">
            <a href="/seminar-system/public/index.php">← トップページへ戻る</a>
        </div>
    </div>
</body>
</html>
