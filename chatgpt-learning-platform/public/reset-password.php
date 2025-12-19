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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード再設定 | ChatGPT学習プラットフォーム</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body>
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
