<?php
/**
 * BNI Slide System - User Profile Page
 * ユーザープロフィール編集
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info from Basic Auth
$currentUser = getCurrentUserInfo();

// If user not found, show error
if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。管理者にお問い合わせください。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
$userLastName = htmlspecialchars($currentUser['last_name'] ?? '', ENT_QUOTES, 'UTF-8');
$userFirstName = htmlspecialchars($currentUser['first_name'] ?? '', ENT_QUOTES, 'UTF-8');
$userLastNameKana = htmlspecialchars($currentUser['last_name_kana'] ?? '', ENT_QUOTES, 'UTF-8');
$userFirstNameKana = htmlspecialchars($currentUser['first_name_kana'] ?? '', ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
$userPhone = htmlspecialchars($currentUser['phone'] ?? '', ENT_QUOTES, 'UTF-8');
$userCompany = htmlspecialchars($currentUser['company'] ?? '', ENT_QUOTES, 'UTF-8');
$userCategory = htmlspecialchars($currentUser['category'] ?? '', ENT_QUOTES, 'UTF-8');
$username = htmlspecialchars($currentUser['htpasswd_user'], ENT_QUOTES, 'UTF-8');
$createdAt = htmlspecialchars($currentUser['created_at'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>プロフィール編集 | BNI Slide System</title>

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
          <li><a href="index.php">アンケート</a></li>
          <li><a href="profile.php" class="active">プロフィール</a></li>
          <li><a href="logout.php" style="color: #999;">ログアウト</a></li>
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
            <h1>プロフィール編集</h1>
            <p>ログイン中: <strong><?php echo $userName; ?></strong> (<?php echo $username; ?>)</p>
          </div>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Profile Form -->
          <form id="profileForm" method="POST">

            <!-- Section: 基本情報 -->
            <div class="form-section">
              <h2 class="form-section-title">基本情報</h2>

              <div class="form-group">
                <label class="form-label">
                  お名前<span class="required">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                  <div>
                    <input type="text" name="last_name" id="lastName" class="form-input" required placeholder="姓（例: 山田）" value="<?php echo $userLastName; ?>">
                    <span class="form-error">姓を入力してください</span>
                  </div>
                  <div>
                    <input type="text" name="first_name" id="firstName" class="form-input" required placeholder="名（例: 太郎）" value="<?php echo $userFirstName; ?>">
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
                    <input type="text" name="last_name_kana" id="lastNameKana" class="form-input" required placeholder="セイ（例: ヤマダ）" value="<?php echo $userLastNameKana; ?>">
                    <span class="form-error">セイを入力してください</span>
                  </div>
                  <div>
                    <input type="text" name="first_name_kana" id="firstNameKana" class="form-input" required placeholder="メイ（例: タロウ）" value="<?php echo $userFirstNameKana; ?>">
                    <span class="form-error">メイを入力してください</span>
                  </div>
                </div>
                <p class="form-hint">自動入力されますが、修正可能です</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  メールアドレス<span class="required">*</span>
                </label>
                <input type="email" name="email" class="form-input" required value="<?php echo $userEmail; ?>">
                <span class="form-error">メールアドレスを入力してください</span>
                <p class="form-hint">サンクスメールの送信先になります</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  電話番号
                </label>
                <input type="tel" name="phone" class="form-input" value="<?php echo $userPhone; ?>" placeholder="例: 090-1234-5678">
                <p class="form-hint">ハイフンありでもなしでも入力可能です（任意）</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  会社名（屋号）<span class="required">*</span>
                </label>
                <input type="text" name="company" class="form-input" required value="<?php echo $userCompany; ?>" placeholder="例: 株式会社〇〇">
                <span class="form-error">会社名を入力してください</span>
                <p class="form-hint">所属されている会社名または屋号を入力してください</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  カテゴリ名（業種・職種）<span class="required">*</span>
                </label>
                <input type="text" name="category" class="form-input" required value="<?php echo $userCategory; ?>" placeholder="例: 不動産仲介業">
                <span class="form-error">カテゴリ名を入力してください</span>
                <p class="form-hint">あなたの業種または職種を入力してください</p>
              </div>
            </div>

            <!-- Section: ログイン情報 -->
            <div class="form-section">
              <h2 class="form-section-title">ログイン情報</h2>

              <div class="form-group">
                <label class="form-label">
                  ユーザー名（ログインID）
                </label>
                <input type="text" class="form-input" value="<?php echo $username; ?>" readonly style="background-color: #F5F5F5; cursor: not-allowed;">
                <p class="form-hint">ユーザー名は変更できません</p>
              </div>

              <div class="form-group">
                <label class="form-label">
                  新しいパスワード
                </label>
                <input type="password" name="new_password" class="form-input" minlength="6" id="newPassword" placeholder="変更する場合のみ入力">
                <p class="form-hint">6文字以上で設定してください（変更しない場合は空欄のまま）</p>
              </div>

              <div class="form-group" id="confirmPasswordGroup" style="display: none;">
                <label class="form-label">
                  新しいパスワード（確認）
                </label>
                <input type="password" name="new_password_confirm" class="form-input" minlength="6" id="newPasswordConfirm" placeholder="確認のため再入力">
                <span class="form-error">パスワードが一致しません</span>
              </div>
            </div>

            <!-- Account Info -->
            <div class="form-section" style="background-color: #F9F9F9; padding: 20px; border-radius: 8px;">
              <h3 style="margin-top: 0; color: #666;">アカウント情報</h3>
              <p style="margin: 5px 0; color: #666;">登録日: <?php echo $createdAt; ?></p>
            </div>

            <!-- Submit Button -->
            <div class="form-submit">
              <button type="submit" class="btn btn-primary">変更を保存</button>
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
      const form = document.getElementById('profileForm');
      const messageDiv = document.getElementById('message');
      const newPasswordInput = document.getElementById('newPassword');
      const newPasswordConfirmInput = document.getElementById('newPasswordConfirm');
      const confirmPasswordGroup = document.getElementById('confirmPasswordGroup');
      const lastNameInput = document.getElementById('lastName');
      const firstNameInput = document.getElementById('firstName');
      const lastNameKanaInput = document.getElementById('lastNameKana');
      const firstNameKanaInput = document.getElementById('firstNameKana');

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
        if (e.data) {
          const isHiragana = /^[\u3041-\u3096]+$/.test(e.data);
          if (isHiragana) {
            lastNameReading = e.data;
          }
        }
      });

      lastNameInput.addEventListener('compositionend', function(e) {
        // Only auto-fill if furigana field is empty and we captured hiragana
        if (lastNameReading && !lastNameKanaInput.value) {
          const kana = hiraganaToKatakana(lastNameReading);
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
          }
        }
      });

      firstNameInput.addEventListener('compositionend', function(e) {
        if (firstNameReading && !firstNameKanaInput.value) {
          const kana = hiraganaToKatakana(firstNameReading);
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

      // Show/hide password confirmation field
      newPasswordInput.addEventListener('input', function() {
        if (this.value) {
          confirmPasswordGroup.style.display = 'block';
          newPasswordConfirmInput.required = true;
        } else {
          confirmPasswordGroup.style.display = 'none';
          newPasswordConfirmInput.required = false;
          newPasswordConfirmInput.value = '';
        }
      });

      // Form submission handler
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Password confirmation check (if changing password)
        if (newPasswordInput.value && newPasswordInput.value !== newPasswordConfirmInput.value) {
          showMessage('error', 'パスワードが一致しません');
          newPasswordConfirmInput.closest('.form-group').classList.add('error');
          return;
        }

        // Get form data
        const formData = new FormData(form);

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.textContent = '保存中...';

        try {
          // Submit form
          const response = await fetch('api_update_profile.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();

          if (result.success) {
            showMessage('success', result.message || 'プロフィールを更新しました！');

            // Clear password fields
            newPasswordInput.value = '';
            newPasswordConfirmInput.value = '';
            confirmPasswordGroup.style.display = 'none';

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
          } else {
            showMessage('error', result.message || '更新に失敗しました。もう一度お試しください。');
          }
        } catch (error) {
          console.error('Profile update error:', error);
          showMessage('error', 'エラーが発生しました。もう一度お試しください。');
        } finally {
          submitBtn.classList.remove('loading');
          submitBtn.disabled = false;
          submitBtn.textContent = '変更を保存';
        }
      });

      // Real-time password confirmation
      newPasswordConfirmInput.addEventListener('input', function() {
        const formGroup = this.closest('.form-group');
        if (this.value && this.value !== newPasswordInput.value) {
          formGroup.classList.add('error');
        } else {
          formGroup.classList.remove('error');
        }
      });

      /**
       * Show message
       */
      function showMessage(type, text) {
        messageDiv.className = `message message-${type} show`;
        messageDiv.textContent = text;

        // Auto-hide after 5 seconds
        setTimeout(() => {
          messageDiv.classList.remove('show');
        }, 5000);
      }
    });
  </script>
</body>
</html>
