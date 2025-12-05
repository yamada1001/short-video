<?php
/**
 * BNI Slide System - Forgot Password
 * パスワードリセット - メールアドレス入力ページ
 */
header('Content-Type: text/html; charset=UTF-8');
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
  <title>パスワードを忘れた方 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">

  <style>
    .forgot-password-container {
      max-width: 500px;
      margin: 80px auto;
      padding: 20px;
    }

    .forgot-password-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 40px;
    }

    .forgot-password-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .forgot-password-header h1 {
      font-size: 24px;
      color: #333;
      margin-bottom: 10px;
    }

    .forgot-password-header p {
      color: #666;
      font-size: 14px;
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

  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="forgot-password-header">
        <h1>パスワードをお忘れの方</h1>
        <p>登録されているメールアドレスを入力してください。<br>パスワードリセット用のリンクをお送りします。</p>
      </div>

      <!-- Success/Error Messages -->
      <div id="message" class="message"></div>

      <!-- Forgot Password Form -->
      <form id="forgotPasswordForm" method="POST" action="api_send_reset_email.php">
        <div class="form-group">
          <label class="form-label">
            メールアドレス<span class="required">*</span>
          </label>
          <input type="email" name="email" id="email" class="form-input" required placeholder="例: taro@example.com">
          <span class="form-error">有効なメールアドレスを入力してください</span>
        </div>

        <div class="form-submit">
          <button type="submit" class="btn btn-primary">リセットメールを送信</button>
        </div>
      </form>

      <div class="back-to-login">
        <a href="login.php">← ログインページに戻る</a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const messageDiv = document.getElementById('message');

      submitBtn.disabled = true;
      submitBtn.textContent = '送信中...';

      try {
        const response = await fetch('api_send_reset_email.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          messageDiv.className = 'message message-success show';
          messageDiv.innerHTML = `
            <strong>メールを送信しました</strong><br>
            ${result.message}<br>
            メールが届かない場合は、迷惑メールフォルダをご確認ください。
          `;
          this.reset();
        } else {
          throw new Error(result.message || 'メール送信に失敗しました');
        }
      } catch (error) {
        messageDiv.className = 'message message-error show';
        messageDiv.textContent = error.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'リセットメールを送信';
      }

      setTimeout(() => {
        messageDiv.classList.remove('show');
      }, 10000);
    });
  </script>

</body>
</html>
