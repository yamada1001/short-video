<?php
/**
 * QRコードチェックイン画面
 * 参加者がQRコードを表示し、管理者がスキャンして出席確認
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Attendee;
use Seminar\Seminar;

$pageTitle = 'チェックイン';

// トークン取得
$token = get('token', '');
$error = '';
$attendee = null;
$seminar = null;

if (!$token) {
    $error = '無効なURLです。メールに記載されたURLからアクセスしてください。';
} else {
    // トークンで参加者情報取得
    $attendee = Attendee::getByQrToken($token);

    if (!$attendee) {
        $error = '無効なトークンです。URLが正しいか確認してください。';
    } else {
        // セミナー情報取得
        $seminar = Seminar::getById($attendee['seminar_id']);

        if (!$seminar) {
            $error = 'セミナー情報が見つかりません。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($pageTitle); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.8;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px 24px;
            text-align: center;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 32px 24px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            text-align: center;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            margin: 16px 0;
        }

        .status-applied {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-paid {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .status-attended {
            background: #e8f5e9;
            color: #388e3c;
        }

        .status-absent {
            background: #fbe9e7;
            color: #d84315;
        }

        .info-section {
            background: #fafafa;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e8e8e8;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 110px;
            font-weight: 500;
            color: #666;
            flex-shrink: 0;
            font-size: 14px;
        }

        .info-value {
            flex: 1;
            color: #333;
            font-size: 14px;
        }

        .qr-section {
            text-align: center;
            padding: 32px 0;
        }

        .qr-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 16px;
            color: #333;
        }

        #qrcode {
            display: inline-block;
            padding: 24px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .qr-instruction {
            margin-top: 24px;
            padding: 16px;
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            font-size: 14px;
            color: #856404;
            text-align: left;
        }

        .qr-instruction strong {
            display: block;
            margin-bottom: 8px;
        }

        .qr-instruction ol {
            margin-left: 20px;
            margin-top: 8px;
        }

        .qr-instruction li {
            margin-bottom: 4px;
        }

        .text-muted {
            color: #888;
            font-size: 13px;
            text-align: center;
            margin-top: 16px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 24px 16px;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: auto;
            }

            .qr-section {
                padding: 24px 0;
            }

            #qrcode {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 チェックイン</h1>
            <p>下のQRコードを受付スタッフにお見せください</p>
        </div>

        <div class="content">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo h($error); ?>
                </div>
            <?php elseif ($attendee && $seminar): ?>
                <div style="text-align: center;">
                    <span class="status-badge status-<?php echo h($attendee['status']); ?>">
                        <?php echo getStatusLabel($attendee['status']); ?>
                    </span>
                </div>

                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">お名前</div>
                        <div class="info-value"><strong><?php echo h($attendee['name']); ?></strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">セミナー</div>
                        <div class="info-value"><?php echo h($seminar['title']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">開催日時</div>
                        <div class="info-value"><?php echo formatDatetime($seminar['start_datetime'], 'Y年m月d日 H:i'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">会場</div>
                        <div class="info-value"><?php echo h($seminar['venue']) ?: '-'; ?></div>
                    </div>
                </div>

                <?php if ($attendee['status'] === 'attended'): ?>
                    <div class="alert" style="background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7;">
                        ✓ チェックイン完了しています
                    </div>
                <?php else: ?>
                    <div class="qr-section">
                        <div class="qr-title">受付用QRコード</div>
                        <div id="qrcode"></div>
                    </div>

                    <div class="qr-instruction">
                        <strong>📱 ご利用方法</strong>
                        <ol>
                            <li>受付スタッフに画面をお見せください</li>
                            <li>スタッフがQRコードをスキャンします</li>
                            <li>チェックイン完了です！</li>
                        </ol>
                    </div>
                <?php endif; ?>

                <p class="text-muted">
                    ID: <?php echo h($attendee['id']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($attendee && $seminar && $attendee['status'] !== 'attended'): ?>
    <!-- QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs2@0.0.2/qrcode.min.js"></script>
    <script>
        // QRコード生成
        new QRCode(document.getElementById('qrcode'), {
            text: '<?php echo h($token); ?>',
            width: 200,
            height: 200,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
    <?php endif; ?>
</body>
</html>
