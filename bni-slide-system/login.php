<?php
/**
 * BNI Slide System - Login Page
 * ログインページ
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Load session auth helper
require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/redirect_helper.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirect = validateRedirectUrl($_GET['redirect'] ?? '');
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
            $redirect = validateRedirectUrl($_GET['redirect'] ?? '');
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
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
  <!-- End Google Tag Manager -->

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

    .login-form .form-input {
      padding-left: 45px;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 18px;
      pointer-events: none;
    }

    .login-form .form-group {
      position: relative;
    }

    .login-form .form-label {
      display: block;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

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

      <form method="POST" class="login-form">
        <div class="form-group">
          <label class="form-label">
            メールアドレス<span class="required">*</span>
          </label>
          <div style="position: relative;">
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
        </div>

        <div class="form-group">
          <label class="form-label">
            パスワード<span class="required">*</span>
          </label>
          <div style="position: relative;">
            <i class="fas fa-lock input-icon"></i>
            <input
              type="password"
              name="password"
              class="form-input"
              required
              placeholder="パスワードを入力"
            >
          </div>
        </div>

        <div class="form-submit">
          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-sign-in-alt"></i>
            ログイン
          </button>
        </div>
      </form>

      <div class="login-links">
        <p style="margin-bottom: 10px; color: #666;">パスワードを忘れた方</p>
        <a href="forgot-password.php" style="display: block; margin-bottom: 20px;">
          <i class="fas fa-key"></i>
          パスワードをリセット
        </a>

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
