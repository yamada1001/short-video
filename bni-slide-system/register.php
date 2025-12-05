<?php
// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>新規ユーザー登録 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="site-logo">BNI Slide System</div>
      <nav class="site-nav">
        <ul>
          <li><a href="register.php" class="active">新規登録</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="form-container">
        <div class="card">
          <div class="form-header">
            <h1>新規ユーザー登録</h1>
            <p>BNI週次アンケートシステムへようこそ！以下のフォームからご登録ください。</p>
          </div>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Registration Form -->
          <form id="registerForm" method="POST">

            <!-- Section: 基本情報 -->
            <div class="form-section">
              <h2 class="form-section-title">基本情報</h2>

              <div class="form-group">
                <label class="form-label">
                  お名前（フルネーム）<span class="required">*</span>
                </label>
                <input type="text" name="name" class="form-input" required placeholder="例: 山田太郎">
                <span class="form-error">お名前を入力してください</span>
                <p class="form-hint">スライドやアンケートに表示される名前です</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  メールアドレス<span class="required">*</span>
                </label>
                <input type="email" name="email" class="form-input" required placeholder="例: example@example.com">
                <span class="form-error">メールアドレスを入力してください</span>
                <p class="form-hint">サンクスメールの送信先になります</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  電話番号
                </label>
                <input type="tel" name="phone" class="form-input" placeholder="例: 090-1234-5678">
                <p class="form-hint">ハイフンありでもなしでも入力可能です（任意）</p>
              </div>
            </div>

            <!-- Section: ログイン情報 -->
            <div class="form-section">
              <h2 class="form-section-title">ログイン情報</h2>

              <div style="background-color: #F0F8FF; padding: 20px; border-radius: 8px; border-left: 4px solid #CF2030;">
                <h3 style="margin-top: 0; color: #CF2030; font-size: 16px;">📧 ログイン方法</h3>
                <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                  <strong>ログインID:</strong> ご登録のメールアドレスをそのまま使用します
                </p>
                <p style="margin: 0; color: #666; font-size: 14px;">
                  <strong>パスワード:</strong> 自動生成され、登録後にメールで送信されます
                </p>
                <p style="margin: 10px 0 0 0; color: #999; font-size: 13px;">
                  ※ ログイン後、プロフィール画面からパスワードを変更できます
                </p>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="form-submit">
              <button type="submit" class="btn btn-primary">登録する</button>
              <a href="index.php" class="btn btn-outline" style="margin-left: 10px;">キャンセル</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2024 BNI Slide System. All rights reserved.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('registerForm');
      const messageDiv = document.getElementById('message');

      // Form submission handler
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(form);

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.textContent = '登録中...';

        try {
          // Submit form
          const response = await fetch('api_register.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            showMessage('success', result.message || '登録が完了しました！5秒後にログイン画面に移動します...');
            form.reset();

            // Redirect to index after 5 seconds
            setTimeout(() => {
              window.location.href = 'index.php';
            }, 5000);
          } else {
            showMessage('error', result.message || '登録に失敗しました。もう一度お試しください。');
            submitBtn.disabled = false;
            submitBtn.textContent = '登録する';
          }
        } catch (error) {
          console.error('Registration error:', error);
          showMessage('error', 'エラーが発生しました。もう一度お試しください。');
          submitBtn.disabled = false;
          submitBtn.textContent = '登録する';
        } finally {
          submitBtn.classList.remove('loading');
        }
      });

      /**
       * Show message
       */
      function showMessage(type, text) {
        messageDiv.className = `message message-${type} show`;
        messageDiv.textContent = text;

        // Auto-hide after 10 seconds (except for success message)
        if (type !== 'success') {
          setTimeout(() => {
            messageDiv.classList.remove('show');
          }, 10000);
        }
      }
    });
  </script>
</body>
</html>
