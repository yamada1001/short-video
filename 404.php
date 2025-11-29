<?php
http_response_code(404);
$current_page = '404';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = '404 ページが見つかりません | 余日（Yojitsu）';
$page_description = 'お探しのページが見つかりませんでした。';
$robots_meta = 'noindex, nofollow';
$css_base_path = '/';

$inline_styles = <<<'EOD'
        .error-page {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }

        .error-content {
            text-align: center;
            max-width: 600px;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #ddd;
            line-height: 1;
            margin-bottom: 20px;
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

        .error-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .error-link {
            display: inline-block;
            padding: 12px 30px;
            background: #8B7355;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            letter-spacing: 0.08em;
        }

        .error-link:hover {
            background: #725f46;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 115, 85, 0.3);
        }

        .error-link.secondary {
            background: transparent;
            color: #8B7355;
            border: 1px solid #8B7355;
        }

        .error-link.secondary:hover {
            background: #F5F3F0;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 22px;
            }

            .error-links {
                flex-direction: column;
            }
        }
EOD;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="error-page">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">お探しのページが見つかりません</h1>
            <p class="error-message">
                申し訳ございません。お探しのページは削除されたか、URLが変更された可能性があります。<br>
                以下のリンクからお探しの情報を見つけてください。
            </p>
            <div class="error-links">
                <a href="/" class="error-link">トップページへ</a>
                <a href="/blog/" class="error-link secondary">ブログ一覧へ</a>
                <a href="/contact.php" class="error-link secondary">お問い合わせ</a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script defer src="/assets/js/app.js"></script>
</body>
</html>
