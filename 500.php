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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .error-title {
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #333;
        }

        .error-message {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 40px;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .support-info {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .support-info h3 {
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
            color: #667eea;
            text-decoration: none;
        }

        .contact-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .error-container {
                padding: 40px 30px;
            }

            .error-code {
                font-size: 60px;
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
                <a href="tel:080-9245-5598" class="contact-link">📞 080-9245-5598</a>
                <a href="mailto:info@yojitu.com" class="contact-link">✉️ info@yojitu.com</a>
                <a href="/contact.php" class="contact-link">📝 お問い合わせフォーム</a>
            </div>
        </div>
    </div>
</body>
</html>
