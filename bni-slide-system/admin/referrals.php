<?php
/**
 * BNI Slide System - Referral Management (Admin Only)
 * 管理者専用 - リファーラル金額管理画面
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/db.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRFトークン生成
$csrfToken = generateCSRFToken();

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    die('<h1>アクセス拒否</h1><p>このページは管理者のみアクセス可能です。</p><a href="../index.php">ホームに戻る</a>');
}

// 週リストを取得
$db = getDbConnection();
$weeks = [];
try {
    $result = dbQuery($db, "SELECT DISTINCT week_date FROM survey_data ORDER BY week_date DESC");
    foreach ($result as $row) {
        $weeks[] = $row['week_date'];
    }
} catch (Exception $e) {
    error_log("Failed to load weeks: " . $e->getMessage());
}
dbClose($db);
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
  <title>リファーラル管理 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">
  <style>
    .referrals-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .week-selector {
      margin-bottom: 30px;
      padding: 20px;
      background-color: var(--bg-white);
      border-radius: 8px;
      box-shadow: var(--shadow);
    }

    .week-selector label {
      display: block;
      font-weight: 600;
      margin-bottom: 10px;
      color: var(--text-primary);
    }

    .week-selector select {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: 2px solid var(--border-color);
      border-radius: 4px;
      background-color: var(--bg-white);
    }

    .referral-form {
      background-color: var(--bg-white);
      padding: 30px;
      border-radius: 8px;
      box-shadow: var(--shadow);
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--text-primary);
    }

    .form-input {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: 2px solid var(--border-color);
      border-radius: 4px;
      transition: border-color 0.3s;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--bni-red);
    }

    .btn {
      padding: 12px 30px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background-color: var(--bni-red);
      color: var(--bni-white);
    }

    .btn-primary:hover {
      background-color: var(--bni-red-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .message {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
      display: none;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .loading {
      display: none;
      text-align: center;
      padding: 20px;
    }
  </style>
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
          <li><a href="slide.php">スライド表示</a></li>
          <li><a href="edit.php">編集</a></li>
          <li><a href="bulk_input.php">一括入力</a></li>
          <li><a href="referrals.php" class="active">リファーラル管理</a></li>
          <li><a href="seating.php">座席表編集</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="audit_log.php">監査ログ</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="referrals-container">
        <h1 style="margin-bottom: 30px;">リファーラル金額管理</h1>

        <!-- Week Selector -->
        <div class="week-selector">
          <label for="weekSelect">週を選択</label>
          <select id="weekSelect" class="form-input">
            <option value="">週を選択してください</option>
            <?php foreach ($weeks as $week): ?>
              <?php
                $dt = new DateTime($week);
                $formatted = $dt->format('Y年n月j日（金）');
              ?>
              <option value="<?php echo htmlspecialchars($week); ?>">
                <?php echo htmlspecialchars($formatted); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div id="loading" class="loading">
          <i class="fas fa-spinner fa-spin fa-2x"></i>
          <p>読み込み中...</p>
        </div>

        <div id="message" class="message"></div>

        <!-- Referral Form -->
        <div id="referralForm" class="referral-form" style="display: none;">
          <form id="saveReferralForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <input type="hidden" name="week_date" id="weekDateInput">

            <div class="form-group">
              <label class="form-label">
                今週のリファーラル総額（円）<span style="color: #D00C24;">*</span>
              </label>
              <input type="text" name="total_amount_display" id="totalAmountDisplay" class="form-input" placeholder="例: 500,000" required>
              <input type="hidden" name="total_amount" id="totalAmount">
              <p style="font-size: 14px; color: #666; margin-top: 5px;">
                全メンバーのリファーラル金額の合計を入力してください（カンマは自動挿入されます）
              </p>
            </div>

            <div class="form-group">
              <label class="form-label">メモ（任意）</label>
              <textarea name="notes" id="notes" class="form-input" rows="3" placeholder="特記事項があれば入力してください"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> 保存
            </button>
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

  <script>
    const weekSelect = document.getElementById('weekSelect');
    const referralForm = document.getElementById('referralForm');
    const saveForm = document.getElementById('saveReferralForm');
    const weekDateInput = document.getElementById('weekDateInput');
    const totalAmount = document.getElementById('totalAmount');
    const totalAmountDisplay = document.getElementById('totalAmountDisplay');
    const notes = document.getElementById('notes');
    const loading = document.getElementById('loading');
    const message = document.getElementById('message');

    // カンマ区切り表示関数
    function formatNumber(num) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // カンマ除去関数
    function removeCommas(str) {
      return str.replace(/,/g, '');
    }

    // 金額入力フィールドのイベントリスナー
    totalAmountDisplay.addEventListener('input', function(e) {
      let value = removeCommas(e.target.value);
      // 数字のみ許可
      value = value.replace(/\D/g, '');
      if (value) {
        e.target.value = formatNumber(value);
        totalAmount.value = value;
      } else {
        e.target.value = '';
        totalAmount.value = '';
      }
    });

    // Week selector change handler
    weekSelect.addEventListener('change', async function() {
      const weekDate = this.value;
      if (!weekDate) {
        referralForm.style.display = 'none';
        return;
      }

      // Load existing referral data
      loading.style.display = 'block';
      referralForm.style.display = 'none';
      message.style.display = 'none';

      try {
        const response = await fetch(`../api_load_referrals.php?week=${encodeURIComponent(weekDate)}`);
        const result = await response.json();

        weekDateInput.value = weekDate;

        if (result.success && result.data) {
          // Load existing data
          const amount = result.data.total_amount || '';
          totalAmount.value = amount;
          totalAmountDisplay.value = amount ? formatNumber(amount) : '';
          notes.value = result.data.notes || '';
        } else {
          // New entry
          totalAmount.value = '';
          totalAmountDisplay.value = '';
          notes.value = '';
        }

        referralForm.style.display = 'block';
      } catch (error) {
        console.error('Error loading referral data:', error);
        showMessage('データの読み込みに失敗しました', 'error');
      } finally {
        loading.style.display = 'none';
      }
    });

    // Form submit handler
    saveForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);

      try {
        const response = await fetch('../api_save_referrals.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          showMessage('リファーラル金額を保存しました', 'success');
        } else {
          showMessage(result.message || '保存に失敗しました', 'error');
        }
      } catch (error) {
        console.error('Error saving referral data:', error);
        showMessage('保存に失敗しました', 'error');
      }
    });

    function showMessage(text, type) {
      message.textContent = text;
      message.className = 'message ' + type;
      message.style.display = 'block';

      if (type === 'success') {
        setTimeout(() => {
          message.style.display = 'none';
        }, 3000);
      }
    }
  </script>
</body>
</html>
