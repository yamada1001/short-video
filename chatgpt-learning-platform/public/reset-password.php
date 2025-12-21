<?php
/**
 * パスワード再設定ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// すでにログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

$token = $_GET['token'] ?? '';
$errors = [];
$successMessage = '';
$tokenValid = false;
$userId = null;

// トークン検証
if ($token) {
    $userId = verifyPasswordResetToken($token);
    if ($userId) {
        $tokenValid = true;
    } else {
        $errors[] = 'このリンクは無効か、期限切れです。';
    }
} else {
    $errors[] = 'トークンが指定されていません。';
}

// パスワード更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValid) {
    verifyCsrfToken($_POST['csrf_token']);

    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // バリデーション
    if (empty($password)) {
        $errors[] = 'パスワードを入力してください';
    } elseif (strlen($password) < 8) {
        $errors[] = 'パスワードは8文字以上で入力してください';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'パスワードが一致しません';
    }

    if (empty($errors)) {
        // パスワードをハッシュ化
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // パスワードを更新
        $updateSql = "UPDATE users SET password_hash = ? WHERE id = ?";
        if (db()->execute($updateSql, [$passwordHash, $userId])) {
            // トークンを削除（使用済みにする）
            $deleteTokenSql = "DELETE FROM password_reset_tokens WHERE user_id = ?";
            db()->execute($deleteTokenSql, [$userId]);

            $successMessage = 'パスワードを変更しました。新しいパスワードでログインしてください。';
            $tokenValid = false; // フォームを非表示にする
        } else {
            $errors[] = 'パスワードの更新に失敗しました';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード再設定 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>パスワード再設定</h1>
                <p>新しいパスワードを入力してください。</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if (!$tokenValid): ?>
                    <div class="auth-footer">
                        <a href="<?= APP_URL ?>/forgot-password.php" class="text-link">パスワード再発行を再申請する</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <?= h($successMessage) ?>
                </div>
                <div class="auth-footer">
                    <a href="<?= APP_URL ?>/login.php" class="btn btn-primary btn-block">ログイン画面へ</a>
                </div>
            <?php elseif ($tokenValid): ?>
                <form method="POST" class="auth-form">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                    <div class="form-group">
                        <label for="password">新しいパスワード</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                        <small class="form-hint">8文字以上で入力してください</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">新しいパスワード（確認）</label>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        パスワードを変更する
                    </button>
                </form>

                <div class="auth-footer">
                    <a href="<?= APP_URL ?>/login.php" class="text-link">ログイン画面に戻る</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
