<?php
/**
 * サブスクリプション申し込み完了ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プレミアム会員登録完了 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="success-container">
        <div class="success-card">
            <div class="success-card__icon">🎉</div>
            <h1 class="success-card__title">プレミアム会員登録完了！</h1>
            <p class="success-card__message">
                ご登録ありがとうございます。<br>
                すべてのコースにアクセスできるようになりました。
            </p>

            <div class="alert alert-success">
                <h3>これからできること</h3>
                <ul>
                    <li>すべてのプレミアムコースを受講</li>
                    <li>Gemini AIを1日100回まで実行可能</li>
                    <li>課題の詳細フィードバックを受け取る</li>
                </ul>
            </div>

            <a href="<?= APP_URL ?>/dashboard.php" class="btn-primary btn-block">
                ダッシュボードへ
            </a>
        </div>

        <p class="success-footer">
            サブスクリプションの管理は<a href="<?= APP_URL ?>/dashboard.php" class="text-link">ダッシュボード</a>から行えます。
        </p>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
