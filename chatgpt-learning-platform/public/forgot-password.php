<?php
/**
 * パスワード再発行申請ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// すでにログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token']);

    $email = trim($_POST['email'] ?? '');

    // バリデーション
    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '有効なメールアドレスを入力してください';
    }

    if (empty($errors)) {
        // ユーザーを検索
        $userSql = "SELECT * FROM users WHERE email = ? AND oauth_provider = 'email'";
        $user = db()->fetchOne($userSql, [$email]);

        // セキュリティのため、ユーザーが存在しない場合も同じメッセージを表示
        // （メールアドレスの存在確認を防ぐ）
        if ($user) {
            // トークン生成とメール送信
            $token = generatePasswordResetToken($user['id']);

            if ($token) {
                $resetUrl = APP_URL . '/reset-password.php?token=' . $token;

                $subject = 'パスワード再設定のご案内';
                $body = "
                    <h2>パスワード再設定</h2>
                    <p>パスワード再設定のリクエストを受け付けました。</p>
                    <p>以下のリンクをクリックして、新しいパスワードを設定してください。</p>
                    <p><a href=\"{$resetUrl}\">{$resetUrl}</a></p>
                    <p>このリンクは1時間有効です。</p>
                    <p>※このメールに心当たりがない場合は、無視してください。</p>
                ";

                sendEmail($email, $subject, $body);
            }
        }

        // 成功メッセージ（ユーザーの存在に関わらず表示）
        $successMessage = 'パスワード再設定用のメールを送信しました。メールをご確認ください。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード再発行 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <h1>パスワード再発行</h1>
                <p>登録されたメールアドレスを入力してください。<br>パスワード再設定用のリンクをお送りします。</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <?= h($successMessage) ?>
                </div>
                <div class="auth-footer">
                    <a href="<?= APP_URL ?>/login.php" class="text-link">ログイン画面に戻る</a>
                </div>
            <?php else: ?>
                <form method="POST" class="auth-form">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= h($_POST['email'] ?? '') ?>"
                            required
                            autocomplete="email"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        再設定メールを送信
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
