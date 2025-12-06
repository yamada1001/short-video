<?php
/**
 * BNI Slide System - Reset Password
 * パスワードリセット - 新しいパスワード設定ページ
 */
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . '/includes/csrf.php';

// Generate CSRF token
$csrfToken = generateCSRFToken();

// トークンを取得
$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400);
    die('<h1>無効なリンクです</h1><p>パスワードリセットのリンクが正しくありません。</p>');
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
  <title>パスワードリセット | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">

  <style>
    .reset-password-container {
      max-width: 500px;
      margin: 80px auto;
      padding: 20px;
    }

    .reset-password-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 40px;
    }

    .reset-password-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .reset-password-header h1 {
      font-size: 24px;
      color: #333;
      margin-bottom: 10px;
    }

    .reset-password-header p {
      color: #666;
      font-size: 14px;
    }

    .password-requirements {
      background: #F0F8FF;
      border-left: 4px solid #2196F3;
      padding: 15px;
      margin-bottom: 20px;
      font-size: 13px;
      border-radius: 4px;
    }

    .password-requirements ul {
      margin: 10px 0 0 20px;
      padding: 0;
    }

    .password-requirements li {
      margin-bottom: 5px;
    }

    .back-to-login {
      text-align: center;
      margin-top: 20px;
    }

    .back-to-login a {
      color: #CF2030;
      text-decoration: none;
      font-size: 14px;
    }

    .back-to-login a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <div class="reset-password-container">
    <div class="reset-password-card">
      <div class="reset-password-header">
        <h1>新しいパスワードの設定</h1>
        <p>新しいパスワードを入力してください</p>
      </div>

      <!-- Success/Error Messages -->
      <div id="message" class="message"></div>

      <!-- Password Requirements -->
      <div class="password-requirements">
        <strong>パスワードの要件:</strong>
        <ul>
          <li>8文字以上</li>
          <li>大文字・小文字・数字を含むことを推奨</li>
        </ul>
      </div>

      <!-- Reset Password Form -->
      <form id="resetPasswordForm" method="POST" action="api_reset_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">

        <div class="form-group">
          <label class="form-label">
            新しいパスワード<span class="required">*</span>
          </label>
          <input type="password" name="password" id="password" class="form-input" required minlength="8" placeholder="8文字以上">
          <span class="form-error">パスワードは8文字以上で入力してください</span>
        </div>

        <div class="form-group">
          <label class="form-label">
            新しいパスワード（確認用）<span class="required">*</span>
          </label>
          <input type="password" name="password_confirm" id="password_confirm" class="form-input" required minlength="8" placeholder="確認のためもう一度入力">
          <span class="form-error">パスワードが一致しません</span>
        </div>

        <div class="form-submit">
          <button type="submit" class="btn btn-primary">パスワードを変更する</button>
        </div>
      </form>

      <div class="back-to-login">
        <a href="login.php">← ログインページに戻る</a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const password = document.getElementById('password').value;
      const passwordConfirm = document.getElementById('password_confirm').value;
      const messageDiv = document.getElementById('message');

      // パスワード確認チェック
      if (password !== passwordConfirm) {
        messageDiv.className = 'message message-error show';
        messageDiv.textContent = 'パスワードが一致しません';
        setTimeout(() => messageDiv.classList.remove('show'), 5000);
        return;
      }

      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');

      submitBtn.disabled = true;
      submitBtn.textContent = '変更中...';

      try {
        const response = await fetch('api_reset_password.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          messageDiv.className = 'message message-success show';
          messageDiv.innerHTML = `
            <strong>パスワードを変更しました</strong><br>
            ${result.message}<br>
            3秒後にログインページに移動します...
          `;
          this.reset();

          // 3秒後にログインページにリダイレクト
          setTimeout(() => {
            window.location.href = 'login.php';
          }, 3000);
        } else {
          throw new Error(result.message || 'パスワードの変更に失敗しました');
        }
      } catch (error) {
        messageDiv.className = 'message message-error show';
        messageDiv.textContent = error.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'パスワードを変更する';
      }

      setTimeout(() => {
        messageDiv.classList.remove('show');
      }, 10000);
    });
  </script>

</body>
</html>
