<?php
/**
 * BNI Slide System - ウィークリープレゼン管理画面 (Admin Only)
 * 管理者専用 - ウィークリープレゼン担当者管理
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
  <title>ウィークリープレゼン管理 | BNI Slide System</title>

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
          <li><a href="weekly_presenter_admin.php" class="active">ウィークリープレゼン</a></li>
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
          <h1>ウィークリープレゼン管理</h1>
          <p class="mb-3">ウィークリープレゼン担当者を選択します。</p>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Form -->
          <form id="presenterForm">
            <div class="form-group">
              <label for="weekDate">週の日付（金曜日）*</label>
              <input type="date" id="weekDate" name="week_date" required>
              <small style="color: var(--text-light);">金曜日の日付を選択してください</small>
            </div>

            <div class="form-group">
              <label for="memberId">プレゼン担当メンバー*</label>
              <select id="memberId" name="member_id" required>
                <option value="">選択してください</option>
              </select>
            </div>

            <div class="form-group">
              <label for="topic">プレゼントピック</label>
              <input type="text" id="topic" name="topic" placeholder="プレゼンのトピックを入力してください">
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

    // メンバーリストを読み込む
    async function loadMembers() {
      try {
        const response = await fetch('../api_members.php');
        const data = await response.json();

        if (data.success) {
          const select = document.getElementById('memberId');
          select.innerHTML = '<option value="">選択してください</option>' +
            data.members.map(member =>
              `<option value="${member.id}" data-name="${member.name}">${member.name}</option>`
            ).join('');
        }
      } catch (error) {
        console.error('Error loading members:', error);
      }
    }

    // 既存データを読み込む
    async function loadData() {
      const weekDate = document.getElementById('weekDate').value;
      if (!weekDate) {
        showMessage('週の日付を選択してください', 'error');
        return;
      }

      try {
        const response = await fetch('../api_load_weekly_presenter.php?week_date=' + weekDate);
        const data = await response.json();

        if (data.success && data.data) {
          document.getElementById('memberId').value = data.data.member_id || '';
          document.getElementById('topic').value = data.data.topic || '';
          document.getElementById('notes').value = data.data.notes || '';

          showMessage('データを読み込みました', 'success');
        }
      } catch (error) {
        console.error('Error loading data:', error);
      }
    }

    // フォーム送信
    document.getElementById('presenterForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const weekDate = document.getElementById('weekDate').value;
      const memberId = document.getElementById('memberId').value;
      const memberName = document.getElementById('memberId').selectedOptions[0]?.dataset.name || '';
      const topic = document.getElementById('topic').value;
      const notes = document.getElementById('notes').value;

      const formData = new FormData();
      formData.append('csrf_token', CSRF_TOKEN);
      formData.append('week_date', weekDate);
      formData.append('member_id', memberId);
      formData.append('member_name', memberName);
      formData.append('topic', topic);
      formData.append('notes', notes);

      try {
        const response = await fetch('../api_save_weekly_presenter.php', {
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
