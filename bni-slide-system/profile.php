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
$userRole = $currentUser['role'] ?? 'member'; // デフォルトはmember
$userLastName = htmlspecialchars($currentUser['last_name'] ?? '', ENT_QUOTES, 'UTF-8');
$userFirstName = htmlspecialchars($currentUser['first_name'] ?? '', ENT_QUOTES, 'UTF-8');
$userLastNameKana = htmlspecialchars($currentUser['last_name_kana'] ?? '', ENT_QUOTES, 'UTF-8');
$userFirstNameKana = htmlspecialchars($currentUser['first_name_kana'] ?? '', ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
$userPhone = htmlspecialchars($currentUser['phone'] ?? '', ENT_QUOTES, 'UTF-8');
$userCompany = htmlspecialchars($currentUser['company'] ?? '', ENT_QUOTES, 'UTF-8');
$userCategory = htmlspecialchars($currentUser['category'] ?? '', ENT_QUOTES, 'UTF-8');
$createdAt = htmlspecialchars($currentUser['created_at'] ?? '', ENT_QUOTES, 'UTF-8');
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
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="site-logo">BNI Slide System</div>
      <nav class="site-nav">
        <ul>
          <li><a href="index.php">アンケート</a></li>
          <li><a href="my-data.php">マイデータ</a></li>
          <li><a href="manual.php">マニュアル</a></li>
        </ul>
      </nav>
      <div class="user-menu">
        <button class="hamburger-btn" id="hamburgerBtn">
          <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <span>メニュー</span>
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
          <ul>
            <?php if ($userRole === 'admin'): ?>
            <li><a href="admin/slide.php" style="color: #FFD700;">📊 スライド</a></li>
            <li><div class="divider"></div></li>
            <?php endif; ?>
            <li><a href="profile.php">👤 プロフィール</a></li>
            <li><div class="divider"></div></li>
            <li><a href="logout.php" style="color: #CF2030;">🚪 ログアウト</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="form-container">
        <div class="card">
          <div class="form-header">
            <h1>プロフィール編集</h1>
            <p>ログイン中: <strong><?php echo $userName; ?></strong></p>
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

      // Form submission handler
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

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

  <script>
// Hamburger menu toggle - Modern Animation
(function() {
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const dropdownMenu = document.getElementById('dropdownMenu');

  if (hamburgerBtn && dropdownMenu) {
    hamburgerBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = dropdownMenu.classList.toggle('show');
      hamburgerBtn.classList.toggle('active', isOpen);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!dropdownMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      }
    });

    // Close dropdown when clicking a link
    dropdownMenu.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      });
    });

    // Close dropdown on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && dropdownMenu.classList.contains('show')) {
        dropdownMenu.classList.remove('show');
        hamburgerBtn.classList.remove('active');
      }
    });
  }
})();
  </script>
</body>
</html>
