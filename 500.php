<?php
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>500 サーバーエラー | 余日（Yojitsu）</title>

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

        .error-container {
            background: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(139, 115, 85, 0.15);
            max-width: 600px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
            border: 1px solid rgba(139, 115, 85, 0.1);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 24px;
            opacity: 0.8;
        }

        .error-code {
            font-family: 'Noto Serif JP', serif;
            font-size: 72px;
            font-weight: 300;
            color: #8B7355;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
        }

        .error-title {
            font-family: 'Noto Serif JP', serif;
            font-size: 28px;
            font-weight: 400;
            margin-bottom: 24px;
            color: #4A4A4A;
            letter-spacing: 0.08em;
        }

        .error-message {
            font-size: 15px;
            line-height: 1.9;
            color: #6B6B6B;
            margin-bottom: 40px;
            letter-spacing: 0.08em;
        }

        .error-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
            letter-spacing: 0.08em;
            font-size: 15px;
        }

        .btn-primary {
            background: #8B7355;
            color: #FFFFFF;
            border: 1px solid #8B7355;
        }

        .btn-primary:hover {
            background: #725f46;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 115, 85, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #8B7355;
            border: 1px solid #8B7355;
        }

        .btn-secondary:hover {
            background: #F5F3F0;
        }

        .support-info {
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(139, 115, 85, 0.15);
        }

        .support-info h3 {
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

        @media (max-width: 768px) {
            .error-container {
                padding: 40px 24px;
            }

            .error-code {
                font-size: 56px;
            }

            .error-title {
                font-size: 22px;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .contact-links {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <div class="error-code">500</div>
        <h1 class="error-title">サーバーエラーが発生しました</h1>
        <p class="error-message">
            申し訳ございません。サーバー側で一時的な問題が発生しています。<br>
            しばらく時間をおいてから再度お試しください。<br>
            問題が解決しない場合は、お手数ですがお問い合わせください。
        </p>

        <div class="error-actions">
            <a href="/" class="btn btn-primary">トップページへ戻る</a>
            <a href="javascript:history.back()" class="btn btn-secondary">前のページに戻る</a>
        </div>

        <div class="support-info">
            <h3>お困りの場合はお問い合わせください</h3>
            <div class="contact-links">
                <a href="tel:08046929681" class="contact-link">📞 080-4692-9681</a>
                <a href="mailto:yamada@yojitu.com" class="contact-link">✉️ yamada@yojitu.com</a>
                <a href="/contact.php" class="contact-link">📝 お問い合わせフォーム</a>
            </div>
        </div>
    </div>
</body>
</html>
