<?php
/**
 * ログインページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 既にログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    redirect(APP_URL . '/dashboard.php');
}

$errors = [];

// セッションからのエラーメッセージを取得
if (isset($_SESSION['error_message'])) {
    $errors[] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // CSRF対策
    if (!verifyCsrfToken($csrfToken)) {
        $errors[] = '不正なリクエストです。';
    }

    // バリデーション
    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください。';
    }

    if (empty($password)) {
        $errors[] = 'パスワードを入力してください。';
    }

    // 認証
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE email = ? AND oauth_provider = 'email'";
        $user = db()->fetchOne($sql, [$email]);

        if ($user && password_verify($password, $user['password_hash'])) {
            // ログイン成功
            $_SESSION['user_id'] = $user['id'];
            redirect(APP_URL . '/dashboard.php');
        } else {
            $errors[] = 'メールアドレスまたはパスワードが間違っています。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-v2.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">ログイン</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group-inline">
                    <a href="<?= APP_URL ?>/forgot-password.php" class="link-sm">パスワードをお忘れですか？</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block">ログイン</button>
            </form>

            <div class="auth-divider">
                <span>または</span>
            </div>

            <a href="<?= APP_URL ?>/google-login.php" class="btn btn-google btn-block">
                <img src="<?= APP_URL ?>/assets/images/google-icon.svg" alt="Google" class="btn-icon">
                Googleでログイン
            </a>

            <p class="auth-footer">
                アカウントをお持ちでないですか？
                <a href="<?= APP_URL ?>/register.php">新規登録はこちら</a>
            </p>
        </div>
    </div>
</body>
</html>
