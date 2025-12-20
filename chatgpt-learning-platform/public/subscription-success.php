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
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-v2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="container" style="max-width: 600px; margin: 100px auto; text-align: center; padding: 0 20px;">
        <div style="background: white; padding: 60px 40px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.07);">
            <div style="font-size: 64px; margin-bottom: 20px;">🎉</div>
            <h1 style="font-size: 28px; margin-bottom: 16px;">プレミアム会員登録完了！</h1>
            <p style="color: var(--text-muted); margin-bottom: 30px;">
                ご登録ありがとうございます。<br>
                すべてのコースにアクセスできるようになりました。
            </p>

            <div class="alert alert-success" style="text-align: left; margin-bottom: 30px;">
                <h3 style="font-size: 16px; margin-bottom: 12px;">これからできること</h3>
                <ul style="padding-left: 20px;">
                    <li>すべてのプレミアムコースを受講</li>
                    <li>Gemini AIを1日100回まで実行可能</li>
                    <li>課題の詳細フィードバックを受け取る</li>
                </ul>
            </div>

            <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-primary btn-block" style="font-size: 18px; padding: 16px;">
                ダッシュボードへ
            </a>
        </div>

        <p style="margin-top: 30px; color: var(--text-muted); font-size: 14px;">
            サブスクリプションの管理は<a href="<?= APP_URL ?>/dashboard.php" class="text-link">ダッシュボード</a>から行えます。
        </p>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
