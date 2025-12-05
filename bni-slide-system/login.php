<?php
/**
 * BNI Slide System - Login Page
 * ログインページ
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Load session auth helper
require_once __DIR__ . '/includes/session_auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirect = $_GET['redirect'] ?? '/bni-slide-system/index.php';
    header('Location: ' . $redirect);
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'メールアドレスとパスワードを入力してください';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            $redirect = $_GET['redirect'] ?? '/bni-slide-system/index.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>ログイン | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">

  <style>
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--bni-red) 0%, #a01828 100%);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
    }

    .login-container::after {
      content: '';
      position: absolute;
      bottom: -50%;
      left: -50%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
    }

    .login-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 450px;
      width: 100%;
      padding: 50px 40px;
      position: relative;
      z-index: 1;
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-logo {
      width: 120px;
      height: auto;
      margin: 0 auto 30px;
      display: block;
    }

    .login-header h1 {
      font-size: 28px;
      color: var(--bni-red);
      margin-bottom: 8px;
    }

    .login-header p {
      color: #666;
      font-size: 14px;
    }

    .error-message {
      background: #F8D7DA;
      color: #721C24;
      padding: 12px 16px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .login-links {
      text-align: center;
      margin-top: 30px;
      padding-top: 30px;
      border-top: 1px solid #eee;
    }

    .login-links a {
      color: var(--bni-red);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .login-links a:hover {
      color: #e01020;
      text-decoration: underline;
    }

    .form-input {
      padding-left: 45px;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 18px;
    }

    .form-group {
      position: relative;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <img src="assets/images/bni-logo.svg" alt="BNI Logo" class="login-logo">
        <h1>ログイン</h1>
        <p>BNI Slide System</p>
      </div>

      <?php if ($error): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label class="form-label">
            メールアドレス<span class="required">*</span>
          </label>
          <i class="fas fa-envelope input-icon"></i>
          <input
            type="email"
            name="email"
            class="form-input"
            required
            autofocus
            placeholder="example@example.com"
            value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
          >
        </div>

        <div class="form-group">
          <label class="form-label">
            パスワード<span class="required">*</span>
          </label>
          <i class="fas fa-lock input-icon"></i>
          <input
            type="password"
            name="password"
            class="form-input"
            required
            placeholder="パスワードを入力"
          >
        </div>

        <div class="form-submit">
          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-sign-in-alt"></i>
            ログイン
          </button>
        </div>
      </form>

      <div class="login-links">
        <p style="margin-bottom: 10px; color: #666;">アカウントをお持ちでない方</p>
        <a href="register.php">
          <i class="fas fa-user-plus"></i>
          新規登録はこちら
        </a>
      </div>
    </div>
  </div>

</body>
</html>
