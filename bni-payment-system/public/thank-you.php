<?php
/**
 * 支払い完了ページ
 * Square決済後のリダイレクト先
 */
require_once __DIR__ . '/../config/config.php';

$weekOf = getCurrentWeek();
$weekLabel = getWeekLabel($weekOf);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お支払いありがとうございました - BNI定例会費</title>
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
            max-width: 640px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .card {
            background: #fff;
            padding: 48px 32px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 32px;
            color: #DC143C;
        }

        .success-icon svg {
            width: 100%;
            height: 100%;
        }

        h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 24px;
            letter-spacing: 0.05em;
        }

        .thank-you-message {
            font-size: 15px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .payment-info {
            padding: 32px 0;
            margin-bottom: 32px;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
        }

        .info-label {
            color: #999;
        }

        .info-value {
            color: #333;
            font-weight: 500;
        }

        .note {
            font-size: 13px;
            color: #999;
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .note strong {
            color: #666;
            font-weight: 500;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 16px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #DC143C;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #A01225;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 640px) {
            .container {
                padding: 40px 16px;
            }

            .card {
                padding: 32px 24px;
            }

            .success-icon {
                width: 64px;
                height: 64px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1>お支払いありがとうございました</h1>

            <p class="thank-you-message">
                <?php echo h($weekLabel); ?>の定例会費のお支払いが完了しました。<br>
                決済完了メールがSquareから送信されます。
            </p>

            <div class="payment-info">
                <div class="info-item">
                    <span class="info-label">対象週</span>
                    <span class="info-value"><?php echo h($weekLabel); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">会費</span>
                    <span class="info-value">¥1,000</span>
                </div>
            </div>

            <p class="note">
                <strong>注意:</strong> 支払い情報はシステムに自動的に記録されます。<br>
                数分後に管理者画面で確認できるようになります。
            </p>

            <a href="index.php" class="btn">支払いページに戻る</a>
        </div>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> BNI Payment System</p>
        </footer>
    </div>
</body>
</html>
