<?php
/**
 * BNI Slide System - 出欠確認管理画面 (Admin Only)
 * 管理者専用 - 出欠確認データ管理
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
  <title>出欠確認管理 | BNI Slide System</title>

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

    .member-checkbox-group {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 10px;
      padding: 15px;
      background-color: var(--bg-light);
      border-radius: 4px;
      max-height: 400px;
      overflow-y: auto;
    }

    .member-checkbox {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .member-checkbox input[type="checkbox"] {
      width: auto;
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
          <li><a href="attendance_admin.php" class="active">出欠確認</a></li>
          <li><a href="renewal_members_admin.php">更新メンバー</a></li>
          <li><a href="weekly_presenter_admin.php">ウィークリープレゼン</a></li>
          <li><a href="vp_stats_admin.php">VP統計</a></li>
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
          <h1>出欠確認管理</h1>
          <p class="mb-3">メンバーの出欠確認データを管理します。</p>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Form -->
          <form id="attendanceForm">
            <div class="form-group">
              <label for="weekDate">週の日付（金曜日）*</label>
              <input type="date" id="weekDate" name="week_date" required>
              <small style="color: var(--text-light);">金曜日の日付を選択してください</small>
            </div>

            <div class="form-group">
              <label>メンバー出欠確認</label>
              <div id="membersList" class="member-checkbox-group">
                <p style="color: var(--text-light);">データを読み込んでいます...</p>
              </div>
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
    let allMembers = [];

    // メンバーリストを読み込む
    async function loadMembers() {
      try {
        const response = await fetch('../api_members.php');
        const data = await response.json();

        if (data.success) {
          allMembers = data.members;
          renderMembersList();
        }
      } catch (error) {
        console.error('Error loading members:', error);
      }
    }

    // メンバーリストを表示
    function renderMembersList() {
      const container = document.getElementById('membersList');

      if (allMembers.length === 0) {
        container.innerHTML = '<p style="color: var(--text-light);">メンバーが見つかりません</p>';
        return;
      }

      container.innerHTML = allMembers.map(member => `
        <label class="member-checkbox">
          <input type="checkbox" name="member_${member.id}" value="${member.id}" data-name="${member.name}">
          <span>${member.name}</span>
        </label>
      `).join('');
    }

    // 既存データを読み込む
    async function loadData() {
      const weekDate = document.getElementById('weekDate').value;
      if (!weekDate) {
        showMessage('週の日付を選択してください', 'error');
        return;
      }

      try {
        const response = await fetch('../api_load_attendance.php?week_date=' + weekDate);
        const data = await response.json();

        if (data.success && data.data) {
          // チェックボックスをリセット
          document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

          // 既存データをチェック
          const membersData = JSON.parse(data.data.members_data || '[]');
          membersData.forEach(memberId => {
            const checkbox = document.querySelector(`input[value="${memberId}"]`);
            if (checkbox) checkbox.checked = true;
          });

          showMessage('データを読み込みました', 'success');
        }
      } catch (error) {
        console.error('Error loading data:', error);
      }
    }

    // フォーム送信
    document.getElementById('attendanceForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const weekDate = document.getElementById('weekDate').value;
      const checkedMembers = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
        .map(cb => ({
          id: cb.value,
          name: cb.dataset.name
        }));

      const formData = new FormData();
      formData.append('csrf_token', CSRF_TOKEN);
      formData.append('week_date', weekDate);
      formData.append('members_data', JSON.stringify(checkedMembers));

      try {
        const response = await fetch('../api_save_attendance.php', {
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
      loadMembers();

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
