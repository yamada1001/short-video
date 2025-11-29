<?php
http_response_code(503);
header('Retry-After: 3600'); // 1時間後に再試行を推奨
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>メンテナンス中 | 余日（Yojitsu）</title>

    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Noto+Serif+JP:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #F5F3F0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #4A4A4A;
            line-height: 1.9;
            letter-spacing: 0.08em;
        }

        .maintenance-container {
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(139, 115, 85, 0.15);
            max-width: 700px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
            border: 1px solid rgba(139, 115, 85, 0.1);
        }

        .maintenance-icon {
            font-size: 100px;
            margin-bottom: 32px;
            opacity: 0.8;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .maintenance-code {
            font-family: 'Noto Serif JP', serif;
            font-size: 72px;
            font-weight: 300;
            color: #8B7355;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
        }

        .maintenance-title {
            font-family: 'Noto Serif JP', serif;
            font-size: 32px;
            font-weight: 400;
            margin-bottom: 24px;
            color: #4A4A4A;
            letter-spacing: 0.08em;
        }

        .maintenance-message {
            font-size: 15px;
            line-height: 1.9;
            color: #6B6B6B;
            margin-bottom: 32px;
            letter-spacing: 0.08em;
        }

        .maintenance-info {
            background: #F5F3F0;
            border-radius: 8px;
            padding: 28px;
            margin-bottom: 32px;
            border: 1px solid rgba(139, 115, 85, 0.1);
        }

        .maintenance-info h3 {
            font-size: 17px;
            font-weight: 500;
            margin-bottom: 16px;
            color: #4A4A4A;
            letter-spacing: 0.08em;
        }

        .maintenance-info p {
            font-size: 14px;
            color: #6B6B6B;
            line-height: 1.9;
            letter-spacing: 0.08em;
        }

        .estimated-time {
            display: inline-block;
            background: #8B7355;
            color: #FFFFFF;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 400;
            margin-top: 16px;
            letter-spacing: 0.08em;
            font-size: 14px;
        }

        .contact-section {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(139, 115, 85, 0.15);
        }

        .contact-section h3 {
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #6B6B6B;
            letter-spacing: 0.08em;
        }

        .contact-links {
            display: flex;
            gap: 24px;
            justify-content: center;
            flex-wrap: wrap;
            font-size: 14px;
        }

        .contact-link {
            color: #8B7355;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-link:hover {
            color: #725f46;
            text-decoration: underline;
        }

        .social-links {
            margin-top: 24px;
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #F5F3F0;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(139, 115, 85, 0.1);
        }

        .social-link:hover {
            background: #8B7355;
            color: #FFFFFF;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(139, 115, 85, 0.3);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 24px;
            }

            .maintenance-code {
                font-size: 56px;
            }

            .maintenance-title {
                font-size: 24px;
            }

            .maintenance-icon {
                font-size: 80px;
            }

            .contact-links {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <div class="maintenance-code">503</div>
        <h1 class="maintenance-title">メンテナンス中です</h1>
        <p class="maintenance-message">
            現在、サーバーメンテナンスを実施しております。<br>
            ご不便をおかけして大変申し訳ございません。
        </p>

        <div class="maintenance-info">
            <h3>メンテナンス情報</h3>
            <p>
                より良いサービスを提供するため、システムのアップデートを行っております。<br>
                メンテナンス完了まで今しばらくお待ちください。
            </p>
            <!-- メンテナンス時に以下のコメントを解除して時間を記載 -->
            <!-- <div class="estimated-time">完了予定: 2025年○月○日 ○時頃</div> -->
        </div>

        <div class="contact-section">
            <h3>緊急のお問い合わせはこちら</h3>
            <div class="contact-links">
                <a href="tel:08046929681" class="contact-link">📞 080-4692-9681</a>
                <a href="mailto:yamada@yojitu.com" class="contact-link">✉️ yamada@yojitu.com</a>
            </div>

            <div class="social-links">
                <a href="https://line.me/ti/p/CTOCx9YKjk" class="social-link" title="LINE">📱</a>
            </div>
        </div>
    </div>

    <!-- 自動リロード（5分ごと） -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 300000); // 5分 = 300,000ミリ秒
    </script>
</body>
</html>
