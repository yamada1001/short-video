<?php
/**
 * BNI Slide System - VP統計情報管理画面 (Admin Only)
 * 管理者専用 - VP統計データ管理
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/csrf.php';

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
  <title>VP統計情報管理 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--text-dark);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      font-size: 14px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .btn-group {
      display: flex;
      gap: 10px;
      margin-top: 30px;
    }

    .message {
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
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
          <li><a href="attendance_admin.php">出欠確認</a></li>
          <li><a href="renewal_members_admin.php">更新メンバー</a></li>
          <li><a href="weekly_presenter_admin.php">ウィークリープレゼン</a></li>
          <li><a href="vp_stats_admin.php" class="active">VP統計</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="admin-container">
        <div class="card">
          <h1>VP統計情報管理</h1>
          <p class="mb-3">VP（Vice President）の統計情報を入力します。</p>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Form -->
          <form id="vpStatsForm">
            <div class="form-group">
              <label for="weekDate">週の日付（金曜日）*</label>
              <input type="date" id="weekDate" name="week_date" required>
              <small style="color: var(--text-light);">金曜日の日付を選択してください</small>
            </div>

            <div class="stats-grid">
              <div class="form-group">
                <label for="totalMembers">総メンバー数</label>
                <input type="number" id="totalMembers" name="total_members" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="attendanceCount">出席者数</label>
                <input type="number" id="attendanceCount" name="attendance_count" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="visitorCount">ビジター数</label>
                <input type="number" id="visitorCount" name="visitor_count" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="referralCount">リファーラル数</label>
                <input type="number" id="referralCount" name="referral_count" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="referralAmount">リファーラル総額（円）</label>
                <input type="number" id="referralAmount" name="referral_amount" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="thanksSlipsCount">サンクスリップ数</label>
                <input type="number" id="thanksSlipsCount" name="thanks_slips_count" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="oneToOneCount">1to1実施数</label>
                <input type="number" id="oneToOneCount" name="one_to_one_count" min="0" placeholder="0">
              </div>

              <div class="form-group">
                <label for="newMemberCount">新規メンバー数</label>
                <input type="number" id="newMemberCount" name="new_member_count" min="0" placeholder="0">
              </div>
            </div>

            <div class="form-group">
              <label for="notes">メモ（任意）</label>
              <textarea id="notes" name="notes" rows="3" placeholder="特記事項があれば入力してください"></textarea>
            </div>

            <div class="btn-group">
              <button type="submit" class="btn btn-primary">保存</button>
              <button type="button" onclick="loadData()" class="btn btn-secondary">読み込み</button>
              <a href="slide.php" class="btn btn-outline">スライド表示</a>
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
    const CSRF_TOKEN = '<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>';

    // 既存データを読み込む
    async function loadData() {
      const weekDate = document.getElementById('weekDate').value;
      if (!weekDate) {
        showMessage('週の日付を選択してください', 'error');
        return;
      }

      try {
        const response = await fetch('../api_load_vp_stats.php?week_date=' + weekDate);
        const data = await response.json();

        if (data.success && data.data) {
          const stats = JSON.parse(data.data.stats_data || '{}');

          document.getElementById('totalMembers').value = stats.total_members || '';
          document.getElementById('attendanceCount').value = stats.attendance_count || '';
          document.getElementById('visitorCount').value = stats.visitor_count || '';
          document.getElementById('referralCount').value = stats.referral_count || '';
          document.getElementById('referralAmount').value = stats.referral_amount || '';
          document.getElementById('thanksSlipsCount').value = stats.thanks_slips_count || '';
          document.getElementById('oneToOneCount').value = stats.one_to_one_count || '';
          document.getElementById('newMemberCount').value = stats.new_member_count || '';
          document.getElementById('notes').value = stats.notes || '';

          showMessage('データを読み込みました', 'success');
        }
      } catch (error) {
        console.error('Error loading data:', error);
      }
    }

    // フォーム送信
    document.getElementById('vpStatsForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const weekDate = document.getElementById('weekDate').value;

      const statsData = {
        total_members: parseInt(document.getElementById('totalMembers').value) || 0,
        attendance_count: parseInt(document.getElementById('attendanceCount').value) || 0,
        visitor_count: parseInt(document.getElementById('visitorCount').value) || 0,
        referral_count: parseInt(document.getElementById('referralCount').value) || 0,
        referral_amount: parseInt(document.getElementById('referralAmount').value) || 0,
        thanks_slips_count: parseInt(document.getElementById('thanksSlipsCount').value) || 0,
        one_to_one_count: parseInt(document.getElementById('oneToOneCount').value) || 0,
        new_member_count: parseInt(document.getElementById('newMemberCount').value) || 0,
        notes: document.getElementById('notes').value
      };

      const formData = new FormData();
      formData.append('csrf_token', CSRF_TOKEN);
      formData.append('week_date', weekDate);
      formData.append('stats_data', JSON.stringify(statsData));

      try {
        const response = await fetch('../api_save_vp_stats.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          showMessage(data.message || '保存しました', 'success');
        } else {
          showMessage(data.message || '保存に失敗しました', 'error');
        }
      } catch (error) {
        console.error('Error saving data:', error);
        showMessage('保存中にエラーが発生しました', 'error');
      }
    });

    // メッセージ表示
    function showMessage(text, type) {
      const message = document.getElementById('message');
      message.textContent = text;
      message.className = 'message ' + type;
      message.style.display = 'block';

      setTimeout(() => {
        message.style.display = 'none';
      }, 5000);
    }

    // 初期化
    document.addEventListener('DOMContentLoaded', () => {
      // 今週の金曜日を自動設定
      const today = new Date();
      const dayOfWeek = today.getDay();
      const daysUntilFriday = (5 - dayOfWeek + 7) % 7;
      const nextFriday = new Date(today);
      nextFriday.setDate(today.getDate() + daysUntilFriday);

      document.getElementById('weekDate').valueAsDate = nextFriday;
    });
  </script>
</body>
</html>
