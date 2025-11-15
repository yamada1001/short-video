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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .maintenance-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }

        .maintenance-icon {
            font-size: 100px;
            margin-bottom: 30px;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .maintenance-code {
            font-size: 72px;
            font-weight: bold;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .maintenance-title {
            font-size: 32px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #333;
        }

        .maintenance-message {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }

        .maintenance-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .maintenance-info h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }

        .maintenance-info p {
            font-size: 14px;
            color: #666;
            line-height: 1.8;
        }

        .estimated-time {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            margin-top: 10px;
        }

        .contact-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .contact-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #666;
        }

        .contact-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            font-size: 14px;
        }

        .contact-link {
            color: #f5576c;
            text-decoration: none;
            transition: all 0.3s;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        .social-links {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-link {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            line-height: 40px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-link:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 30px;
            }

            .maintenance-code {
                font-size: 60px;
            }

            .maintenance-title {
                font-size: 24px;
            }

            .maintenance-icon {
                font-size: 80px;
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
                <a href="tel:080-9245-5598" class="contact-link">📞 080-9245-5598</a>
                <a href="mailto:info@yojitu.com" class="contact-link">✉️ info@yojitu.com</a>
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
