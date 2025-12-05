<?php
/**
 * BNI Slide System - Dashboard
 * ダッシュボード - 週次・月次統計表示
 */

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUser();

// If user not found, show error
if (!$currentUser) {
    http_response_code(403);
    die('<h1>アクセスエラー</h1><p>ユーザー情報が見つかりません。管理者にお問い合わせください。</p>');
}

$userName = htmlspecialchars($currentUser['name'], ENT_QUOTES, 'UTF-8');
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
  <title>ダッシュボード | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">

  <style>
    .dashboard-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .dashboard-header {
      margin-bottom: 30px;
    }

    .dashboard-header h1 {
      font-size: 28px;
      color: #333;
      margin-bottom: 10px;
    }

    .dashboard-header p {
      color: #666;
      font-size: 16px;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
      margin-bottom: 30px;
    }

    .dashboard-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 24px;
      transition: all 0.3s ease;
    }

    .dashboard-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
      transform: translateY(-2px);
    }

    .dashboard-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #F0F0F0;
    }

    .dashboard-card-header h3 {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .dashboard-badge {
      font-size: 12px;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 12px;
    }

    .dashboard-badge.submitted {
      background: #D4EDDA;
      color: #155724;
    }

    .dashboard-badge.not-submitted {
      background: #F8D7DA;
      color: #721C24;
    }

    .dashboard-month-label {
      font-size: 14px;
      color: #666;
      font-weight: 500;
    }

    .dashboard-card-body {
      padding: 10px 0;
    }

    .dashboard-status {
      font-size: 16px;
      color: #555;
      margin: 10px 0;
      line-height: 1.6;
    }

    .dashboard-stat-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #F5F5F5;
    }

    .dashboard-stat-row:last-child {
      border-bottom: none;
    }

    .stat-label {
      font-size: 15px;
      color: #666;
      font-weight: 500;
    }

    .stat-value {
      font-size: 18px;
      font-weight: 700;
      color: #CF2030;
    }

    .stat-value.amount {
      font-size: 20px;
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
          <li><a href="dashboard.php" class="active">ダッシュボード</a></li>
          <li><a href="index.php">アンケート</a></li>
          <li><a href="my-data.php">マイデータ</a></li>
          <li><a href="manual.php">マニュアル</a></li>
          <li><a href="profile.php">プロフィール</a></li>
          <li><a href="logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
          <h1>ダッシュボード</h1>
          <p><?php echo $userName; ?> さんの週次・月次統計</p>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
          <!-- 今週の提出状況 -->
          <div class="dashboard-card">
            <div class="dashboard-card-header">
              <h3>今週の提出状況</h3>
              <span id="dashboardThisWeekBadge" class="dashboard-badge"></span>
            </div>
            <div class="dashboard-card-body">
              <p id="dashboardThisWeekStatus" class="dashboard-status">読み込み中...</p>
            </div>
          </div>

          <!-- 今週のチーム統計 -->
          <div class="dashboard-card">
            <div class="dashboard-card-header">
              <h3>今週のチーム統計</h3>
            </div>
            <div class="dashboard-card-body">
              <div class="dashboard-stat-row">
                <span class="stat-label">提出メンバー:</span>
                <span id="teamMembersCount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">総ビジター数:</span>
                <span id="teamVisitorCount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">総リファーラル金額:</span>
                <span id="teamReferralAmount" class="stat-value">-</span>
              </div>
            </div>
          </div>

          <!-- あなたの今週の統計 -->
          <div class="dashboard-card">
            <div class="dashboard-card-header">
              <h3>あなたの今週の統計</h3>
            </div>
            <div class="dashboard-card-body">
              <div class="dashboard-stat-row">
                <span class="stat-label">ビジター数:</span>
                <span id="userVisitorCount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">リファーラル金額:</span>
                <span id="userReferralAmount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">サンクスリップ:</span>
                <span id="userThanksSlips" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">ワンツーワン:</span>
                <span id="userOneToOne" class="stat-value">-</span>
              </div>
            </div>
          </div>

          <!-- あなたの今月の統計 -->
          <div class="dashboard-card">
            <div class="dashboard-card-header">
              <h3>あなたの今月の統計</h3>
              <span id="dashboardMonthLabel" class="dashboard-month-label"></span>
            </div>
            <div class="dashboard-card-body">
              <div class="dashboard-stat-row">
                <span class="stat-label">月間ビジター数:</span>
                <span id="monthlyVisitorCount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">月間リファーラル金額:</span>
                <span id="monthlyReferralAmount" class="stat-value">-</span>
              </div>
              <div class="dashboard-stat-row">
                <span class="stat-label">提出回数:</span>
                <span id="monthlyAttendanceCount" class="stat-value">-</span>
              </div>
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

  <!-- Dashboard Script -->
  <script>
    (function() {
      'use strict';

      // ダッシュボードデータを読み込む
      async function loadDashboardStats() {
        try {
          const response = await fetch('api_dashboard_stats.php');
          const data = await response.json();

          if (!data.success) {
            console.warn('ダッシュボードデータの取得に失敗:', data.message);
            return;
          }

          // 今週の提出状況
          const thisWeekUser = data.this_week.user;
          const badge = document.getElementById('dashboardThisWeekBadge');
          const status = document.getElementById('dashboardThisWeekStatus');

          if (thisWeekUser.submitted) {
            badge.textContent = '提出済み';
            badge.className = 'dashboard-badge submitted';
            status.innerHTML = `
              <strong>今週のアンケートは提出済みです</strong><br>
              出席状況: ${thisWeekUser.attendance || '-'}
            `;
          } else {
            badge.textContent = '未提出';
            badge.className = 'dashboard-badge not-submitted';
            status.innerHTML = '<strong>今週のアンケートをまだ提出していません</strong><br><a href="index.php" style="color: #CF2030; font-weight: 600;">アンケートを提出する →</a>';
          }

          // 今週のチーム統計
          const teamStats = data.this_week.team;
          document.getElementById('teamMembersCount').textContent = teamStats.total_members + '人';
          document.getElementById('teamVisitorCount').textContent = teamStats.visitor_count + '人';
          document.getElementById('teamReferralAmount').textContent = '¥' + teamStats.referral_amount.toLocaleString('ja-JP');

          // あなたの今週の統計
          document.getElementById('userVisitorCount').textContent = thisWeekUser.visitor_count + '人';
          document.getElementById('userReferralAmount').textContent = '¥' + thisWeekUser.referral_amount.toLocaleString('ja-JP');
          document.getElementById('userThanksSlips').textContent = thisWeekUser.thanks_slips + '枚';
          document.getElementById('userOneToOne').textContent = thisWeekUser.one_to_one + '回';

          // あなたの今月の統計
          const monthlyStats = data.this_month.user;
          document.getElementById('dashboardMonthLabel').textContent = data.week_dates.month;
          document.getElementById('monthlyVisitorCount').textContent = monthlyStats.visitor_count + '人';
          document.getElementById('monthlyReferralAmount').textContent = '¥' + monthlyStats.referral_amount.toLocaleString('ja-JP');
          document.getElementById('monthlyAttendanceCount').textContent = monthlyStats.attendance_count + '回';

          console.log('✅ ダッシュボードデータを読み込みました');

        } catch (error) {
          console.error('ダッシュボードデータの取得エラー:', error);
        }
      }

      // ページ読み込み時に実行
      document.addEventListener('DOMContentLoaded', function() {
        loadDashboardStats();
      });

    })();
  </script>

</body>
</html>
