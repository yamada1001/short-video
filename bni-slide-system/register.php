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

  <style>
    .confirm-row {
      display: flex;
      padding: 12px 0;
      border-bottom: 1px solid #e0e0e0;
    }

    .confirm-row:last-child {
      border-bottom: none;
    }

    .confirm-label {
      width: 180px;
      font-weight: 600;
      color: #333;
      flex-shrink: 0;
    }

    .confirm-value {
      flex: 1;
      color: #666;
      word-break: break-all;
    }

    .form-step {
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
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

          <!-- Registration Form (Input Step) -->
          <form id="registerForm" method="POST" class="form-step" data-step="input">

            <!-- Section: 基本情報 -->
            <div class="form-section">
              <h2 class="form-section-title">基本情報</h2>

              <div class="form-group">
                <label class="form-label">
                  お名前<span class="required">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                  <div>
                    <input type="text" name="last_name" id="lastName" class="form-input" required placeholder="姓（例: 山田）">
                    <span class="form-error">姓を入力してください</span>
                  </div>
                  <div>
                    <input type="text" name="first_name" id="firstName" class="form-input" required placeholder="名（例: 太郎）">
                    <span class="form-error">名を入力してください</span>
                  </div>
                </div>
                <p class="form-hint">スライドやアンケートに表示される名前です</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  フリガナ<span class="required">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                  <div>
                    <input type="text" name="last_name_kana" id="lastNameKana" class="form-input" required placeholder="セイ（例: ヤマダ）">
                    <span class="form-error">セイを入力してください</span>
                  </div>
                  <div>
                    <input type="text" name="first_name_kana" id="firstNameKana" class="form-input" required placeholder="メイ（例: タロウ）">
                    <span class="form-error">メイを入力してください</span>
                  </div>
                </div>
                <p class="form-hint">自動入力されますが、修正可能です</p>
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

              <div class="form-group">
                <label class="form-label">
                  会社名（屋号）<span class="required">*</span>
                </label>
                <input type="text" name="company" class="form-input" required placeholder="例: 株式会社〇〇">
                <span class="form-error">会社名を入力してください</span>
                <p class="form-hint">所属されている会社名または屋号を入力してください</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  カテゴリ名（業種・職種）<span class="required">*</span>
                </label>
                <input type="text" name="category" class="form-input" required placeholder="例: 不動産仲介業">
                <span class="form-error">カテゴリ名を入力してください</span>
                <p class="form-hint">あなたの業種または職種を入力してください</p>
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
              <button type="button" id="confirmBtn" class="btn btn-primary">確認画面へ</button>
              <a href="index.php" class="btn btn-outline" style="margin-left: 10px;">キャンセル</a>
            </div>
          </form>

          <!-- Confirmation Screen -->
          <div id="confirmationScreen" class="form-step" data-step="confirm" style="display: none;">
            <div class="form-section">
              <h2 class="form-section-title">入力内容の確認</h2>
              <p style="color: #666; margin-bottom: 20px;">以下の内容で登録します。よろしければ「登録する」ボタンを押してください。</p>
            </div>

            <div class="form-section" style="background-color: #f9f9f9; padding: 25px; border-radius: 8px;">
              <h3 style="color: var(--bni-red); margin-top: 0; margin-bottom: 20px; font-size: 18px;">基本情報</h3>

              <div class="confirm-row">
                <div class="confirm-label">お名前</div>
                <div class="confirm-value" id="confirmName"></div>
              </div>

              <div class="confirm-row">
                <div class="confirm-label">フリガナ</div>
                <div class="confirm-value" id="confirmNameKana"></div>
              </div>

              <div class="confirm-row">
                <div class="confirm-label">メールアドレス</div>
                <div class="confirm-value" id="confirmEmail"></div>
              </div>

              <div class="confirm-row">
                <div class="confirm-label">電話番号</div>
                <div class="confirm-value" id="confirmPhone"></div>
              </div>

              <div class="confirm-row">
                <div class="confirm-label">会社名（屋号）</div>
                <div class="confirm-value" id="confirmCompany"></div>
              </div>

              <div class="confirm-row">
                <div class="confirm-label">カテゴリ名</div>
                <div class="confirm-value" id="confirmCategory"></div>
              </div>
            </div>

            <div class="form-section" style="background-color: #F0F8FF; padding: 20px; border-radius: 8px; border-left: 4px solid #CF2030;">
              <h3 style="margin-top: 0; color: #CF2030; font-size: 16px;">📧 ログイン方法</h3>
              <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                <strong>ログインID:</strong> ご登録のメールアドレスをそのまま使用します
              </p>
              <p style="margin: 0; color: #666; font-size: 14px;">
                <strong>パスワード:</strong> 自動生成され、登録後にメールで送信されます
              </p>
            </div>

            <div class="form-submit">
              <button type="button" id="submitBtn" class="btn btn-primary">登録する</button>
              <button type="button" id="backBtn" class="btn btn-outline" style="margin-left: 10px;">戻る</button>
            </div>
          </div>
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
      const lastNameInput = document.getElementById('lastName');
      const firstNameInput = document.getElementById('firstName');
      const lastNameKanaInput = document.getElementById('lastNameKana');
      const firstNameKanaInput = document.getElementById('firstNameKana');
      const confirmBtn = document.getElementById('confirmBtn');
      const submitBtn = document.getElementById('submitBtn');
      const backBtn = document.getElementById('backBtn');
      const confirmationScreen = document.getElementById('confirmationScreen');

      // Auto-generate furigana using IME input
      let lastNameReading = '';
      let firstNameReading = '';

      // Convert hiragana to katakana
      function hiraganaToKatakana(str) {
        return str.replace(/[\u3041-\u3096]/g, function(match) {
          const chr = match.charCodeAt(0) + 0x60;
          return String.fromCharCode(chr);
        });
      }

      // Last name: Capture reading before kanji conversion
      lastNameInput.addEventListener('compositionstart', function(e) {
        lastNameReading = '';
      });

      lastNameInput.addEventListener('compositionupdate', function(e) {
        // compositionupdate fires for each character change during composition
        // We want to capture the hiragana before it converts to kanji
        if (e.data) {
          // Check if the data is hiragana (U+3041 to U+3096)
          const isHiragana = /^[\u3041-\u3096]+$/.test(e.data);
          if (isHiragana) {
            lastNameReading = e.data;
            console.log('姓 hiragana captured:', e.data);
          }
        }
      });

      lastNameInput.addEventListener('compositionend', function(e) {
        console.log('姓 compositionend. Reading:', lastNameReading);
        console.log('姓 Final input value:', lastNameInput.value);
        console.log('姓 Current furigana:', lastNameKanaInput.value);

        // Only auto-fill if furigana field is empty and we captured hiragana
        if (lastNameReading && !lastNameKanaInput.value) {
          const kana = hiraganaToKatakana(lastNameReading);
          console.log('姓 Converting to katakana:', kana);
          lastNameKanaInput.value = kana;
        }
        lastNameReading = '';
      });

      // First name: Capture reading before kanji conversion
      firstNameInput.addEventListener('compositionstart', function(e) {
        firstNameReading = '';
      });

      firstNameInput.addEventListener('compositionupdate', function(e) {
        if (e.data) {
          const isHiragana = /^[\u3041-\u3096]+$/.test(e.data);
          if (isHiragana) {
            firstNameReading = e.data;
            console.log('名 hiragana captured:', e.data);
          }
        }
      });

      firstNameInput.addEventListener('compositionend', function(e) {
        console.log('名 compositionend. Reading:', firstNameReading);
        console.log('名 Final input value:', firstNameInput.value);
        console.log('名 Current furigana:', firstNameKanaInput.value);

        if (firstNameReading && !firstNameKanaInput.value) {
          const kana = hiraganaToKatakana(firstNameReading);
          console.log('名 Converting to katakana:', kana);
          firstNameKanaInput.value = kana;
        }
        firstNameReading = '';
      });

      // Auto-convert hiragana to katakana in furigana fields
      lastNameKanaInput.addEventListener('input', function(e) {
        const value = this.value;
        const converted = hiraganaToKatakana(value);
        if (value !== converted) {
          this.value = converted;
        }
      });

      firstNameKanaInput.addEventListener('input', function(e) {
        const value = this.value;
        const converted = hiraganaToKatakana(value);
        if (value !== converted) {
          this.value = converted;
        }
      });

      // Confirm button handler - show confirmation screen
      confirmBtn.addEventListener('click', function() {
        // Validate form
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        // Get form values
        const lastName = lastNameInput.value;
        const firstName = firstNameInput.value;
        const lastNameKana = lastNameKanaInput.value;
        const firstNameKana = firstNameKanaInput.value;
        const email = form.querySelector('[name="email"]').value;
        const phone = form.querySelector('[name="phone"]').value || '（未入力）';
        const company = form.querySelector('[name="company"]').value;
        const category = form.querySelector('[name="category"]').value;

        // Populate confirmation screen
        document.getElementById('confirmName').textContent = lastName + ' ' + firstName;
        document.getElementById('confirmNameKana').textContent = lastNameKana + ' ' + firstNameKana;
        document.getElementById('confirmEmail').textContent = email;
        document.getElementById('confirmPhone').textContent = phone;
        document.getElementById('confirmCompany').textContent = company;
        document.getElementById('confirmCategory').textContent = category;

        // Hide form, show confirmation
        form.style.display = 'none';
        confirmationScreen.style.display = 'block';

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // Back button handler - return to form
      backBtn.addEventListener('click', function() {
        confirmationScreen.style.display = 'none';
        form.style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // Submit button handler - actually submit the form
      submitBtn.addEventListener('click', async function() {
        // Get form data
        const formData = new FormData(form);

        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.textContent = '登録中...';
        backBtn.disabled = true;

        try {
          // Submit form
          const response = await fetch('api_register.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            // Redirect to thanks page immediately
            window.location.href = 'register-thanks.php';
          } else {
            // Show error message and scroll to top
            showMessage('error', result.message || '登録に失敗しました。もう一度お試しください。');
            submitBtn.disabled = false;
            submitBtn.textContent = '登録する';
            backBtn.disabled = false;

            // Return to input form if on confirmation screen
            confirmationScreen.style.display = 'none';
            form.style.display = 'block';

            // Scroll to top to show error
            window.scrollTo({ top: 0, behavior: 'smooth' });
          }
        } catch (error) {
          console.error('Registration error:', error);
          showMessage('error', 'エラーが発生しました。もう一度お試しください。');
          submitBtn.disabled = false;
          submitBtn.textContent = '登録する';
          backBtn.disabled = false;

          // Return to input form if on confirmation screen
          confirmationScreen.style.display = 'none';
          form.style.display = 'block';

          // Scroll to top to show error
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
          submitBtn.classList.remove('loading');
        }
      });

      /**
       * Show message with emphasis
       */
      function showMessage(type, text) {
        messageDiv.className = `message message-${type} show`;
        messageDiv.textContent = text;

        // Ensure message div is visible at the top
        messageDiv.style.display = 'block';

        // Scroll to message (with offset for header)
        setTimeout(() => {
          const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
          const messageDivTop = messageDiv.getBoundingClientRect().top + window.pageYOffset;
          window.scrollTo({
            top: messageDivTop - headerHeight - 20,
            behavior: 'smooth'
          });
        }, 100);

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
