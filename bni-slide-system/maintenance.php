<?php
/**
 * BNI Slide System - Maintenance Page
 * メンテナンス中ページ
 */

require_once __DIR__ . '/config/maintenance.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンテナンス中 - BNI週次アンケートシステム</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .maintenance-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .message {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .end-time {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            color: #495057;
            margin-top: 20px;
        }

        .end-time strong {
            color: #667eea;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="icon">🔧</div>
        <h1>メンテナンス中</h1>
        <div class="message">
            <?php echo htmlspecialchars(MAINTENANCE_MESSAGE); ?>
        </div>

        <?php if (MAINTENANCE_END_TIME): ?>
        <div class="end-time">
            <strong>メンテナンス終了予定:</strong><br>
            <?php echo htmlspecialchars(MAINTENANCE_END_TIME); ?>
        </div>
        <?php endif; ?>

        <div class="footer">
            BNI週次アンケートシステム
        </div>
    </div>
</body>
</html>
