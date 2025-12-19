<?php
/**
 * 会員登録ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 既にログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    redirect(APP_URL . '/dashboard.php');
}

$errors = [];
$success = false;

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // CSRF対策
    if (!verifyCsrfToken($csrfToken)) {
        $errors[] = '不正なリクエストです。';
    }

    // バリデーション
    if (empty($name)) {
        $errors[] = '名前を入力してください。';
    }

    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が正しくありません。';
    }

    if (empty($password)) {
        $errors[] = 'パスワードを入力してください。';
    } elseif (strlen($password) < 8) {
        $errors[] = 'パスワードは8文字以上で入力してください。';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'パスワードが一致しません。';
    }

    // メールアドレス重複チェック
    if (empty($errors)) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $existingUser = db()->fetchOne($sql, [$email]);

        if ($existingUser) {
            $errors[] = 'このメールアドレスは既に登録されています。';
        }
    }

    // ユーザー登録
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (email, password_hash, name, oauth_provider)
                VALUES (?, ?, ?, 'email')";

        if (db()->execute($sql, [$email, $passwordHash, $name])) {
            $userId = db()->lastInsertId();

            // 自動ログイン
            $_SESSION['user_id'] = $userId;

            $success = true;
            // ダッシュボードへリダイレクト
            sleep(1); // メッセージ表示のため1秒待機
            redirect(APP_URL . '/dashboard.php');
        } else {
            $errors[] = '登録に失敗しました。もう一度お試しください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">会員登録</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    登録が完了しました！ダッシュボードに移動します...
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" id="name" name="name" value="<?= h($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">パスワード（8文字以上）</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirm">パスワード（確認）</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">登録する</button>
            </form>

            <div class="auth-divider">
                <span>または</span>
            </div>

            <a href="<?= APP_URL ?>/google-login.php" class="btn btn-google btn-block">
                <img src="<?= APP_URL ?>/assets/images/google-icon.svg" alt="Google" class="btn-icon">
                Googleで登録
            </a>

            <p class="auth-footer">
                既にアカウントをお持ちですか？
                <a href="<?= APP_URL ?>/login.php">ログインはこちら</a>
            </p>
        </div>
    </div>
</body>
</html>
