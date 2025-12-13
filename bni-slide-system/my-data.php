<?php
/**
 * BNI Slide System - My Data Page
 * ユーザー自身のアンケートデータ編集画面
 */

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
$userEmail = htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8');
$userRole = $currentUser['role'] ?? 'member'; // デフォルトはmember
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
  <title>マイデータ編集 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">

  <style>
    .data-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .page-header {
      margin-bottom: 30px;
    }

    .page-header h1 {
      font-size: 28px;
      color: #333;
      margin-bottom: 10px;
    }

    .page-header p {
      color: #666;
      font-size: 14px;
    }

    .data-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 20px;
      margin-bottom: 20px;
    }

    .data-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 2px solid #eee;
    }

    .data-card-title {
      font-size: 16px;
      font-weight: 600;
      color: #333;
    }

    .data-card-date {
      font-size: 14px;
      color: #666;
    }

    .data-row {
      display: flex;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .data-row:last-child {
      border-bottom: none;
    }

    .data-label {
      width: 200px;
      font-weight: 600;
      color: #666;
      flex-shrink: 0;
    }

    .data-value {
      flex: 1;
      color: #333;
    }

    .btn-edit {
      background: var(--bni-red);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s;
    }

    .btn-edit:hover {
      background: #a01828;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }

    .empty-state i {
      font-size: 64px;
      margin-bottom: 20px;
      opacity: 0.5;
    }

    .empty-state h3 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .empty-state p {
      font-size: 14px;
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #999;
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
          <li><a href="index.php">アンケート</a></li>
          <li><a href="my-data.php" class="active">マイデータ</a></li>
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
      <div class="data-container">
        <div class="page-header">
          <h1><i class="fas fa-database"></i> マイデータ編集</h1>
          <p>あなたが提出したアンケートデータの確認・編集ができます。ビジター情報やピッチプレゼンター情報の確認・修正が可能です。</p>
        </div>

        <div id="dataList" class="loading">
          <i class="fas fa-spinner fa-spin"></i> データを読み込んでいます...
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <p>&copy; 2025 BNI Slide System. All rights reserved.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    const userName = <?php echo json_encode($userName); ?>;
    const userEmail = <?php echo json_encode($userEmail); ?>;

    document.addEventListener('DOMContentLoaded', async function() {
      await loadMyData();
    });

    async function loadMyData() {
      try {
        const response = await fetch('api_load_my_data.php');
        const result = await response.json();

        const dataList = document.getElementById('dataList');

        if (!result.success) {
          throw new Error(result.message);
        }

        if (result.data.length === 0) {
          dataList.innerHTML = `
            <div class="empty-state">
              <i class="fas fa-inbox"></i>
              <h3>データがありません</h3>
              <p>まだアンケートを提出していません。<a href="index.php">アンケートフォーム</a>から入力してください。</p>
            </div>
          `;
          return;
        }

        // Group data by week
        const weeklyData = {};
        weeklyDataMap = {}; // Reset mapping
        result.data.forEach(row => {
          const week = row['週'];
          if (!weeklyData[week]) {
            weeklyData[week] = [];
            weeklyDataMap[week] = row['CSVファイル'];
          }
          weeklyData[week].push(row);
        });

        // Render data cards
        let html = '';
        Object.keys(weeklyData).sort().reverse().forEach(week => {
          const weekData = weeklyData[week];
          const firstRow = weekData[0];

          // Count visitors (only user-editable fields)
          const visitors = weekData.filter(row => row['ビジター名']).length;
          const isPitchPresenter = firstRow['ピッチ担当'] === 'はい';
          const pitchFile = firstRow['ピッチファイル'];
          const youtubeUrl = firstRow['YouTube URL'];

          html += `
            <div class="data-card">
              <div class="data-card-header">
                <div>
                  <div class="data-card-title">${week}</div>
                  <div class="data-card-date">入力日: ${firstRow['入力日']}</div>
                </div>
                <button class="btn-edit" onclick="editData('${week}')">
                  <i class="fas fa-edit"></i> 編集
                </button>
              </div>

              <div class="data-row">
                <div class="data-label">ビジター紹介</div>
                <div class="data-value">${visitors}名</div>
              </div>

              <div class="data-row">
                <div class="data-label">メインプレゼンテーション</div>
                <div class="data-value">${isPitchPresenter ? 'ピッチ担当' : '担当なし'}</div>
              </div>

              ${isPitchPresenter && pitchFile ? `
              <div class="data-row">
                <div class="data-label">ピッチ資料</div>
                <div class="data-value">${pitchFile}</div>
              </div>
              ` : ''}

              ${isPitchPresenter && youtubeUrl ? `
              <div class="data-row">
                <div class="data-label">YouTube動画</div>
                <div class="data-value"><a href="${youtubeUrl}" target="_blank">${youtubeUrl}</a></div>
              </div>
              ` : ''}
            </div>
          `;
        });

        dataList.innerHTML = html;

      } catch (error) {
        console.error('Error loading data:', error);
        document.getElementById('dataList').innerHTML = `
          <div class="empty-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>エラーが発生しました</h3>
            <p>${error.message}</p>
          </div>
        `;
      }
    }

    function editData(week) {
      // Redirect to edit page with week parameter
      const csvFile = weeklyDataMap[week];
      window.location.href = `edit-my-data.php?week=${encodeURIComponent(csvFile)}`;
    }

    // Store week to CSV file mapping
    let weeklyDataMap = {};
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
