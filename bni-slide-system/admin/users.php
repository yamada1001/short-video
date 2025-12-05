<?php
/**
 * BNI Slide System - User Management (Admin Only)
 * 管理者専用 - ユーザー管理画面
 */

require_once __DIR__ . '/../includes/session_auth.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Load members data
$membersFile = __DIR__ . '/../data/members.json';
$membersData = [];

if (file_exists($membersFile)) {
    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);
    if ($data && isset($data['users'])) {
        $membersData = $data['users'];
    }
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
  <title>ユーザー管理 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .users-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding: 20px;
      background-color: var(--bg-white);
      border-radius: 8px;
      box-shadow: var(--shadow);
    }

    .users-table {
      width: 100%;
      background-color: var(--bg-white);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .users-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .users-table thead {
      background-color: var(--bni-red);
      color: var(--bni-white);
    }

    .users-table th,
    .users-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid var(--border-color);
    }

    .users-table th {
      font-weight: 600;
      font-size: 14px;
    }

    .users-table tbody tr:hover {
      background-color: var(--bg-light);
    }

    .user-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-active {
      background-color: #D4EDDA;
      color: #155724;
    }

    .btn-small {
      padding: 6px 12px;
      font-size: 13px;
      border-radius: 4px;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-delete {
      background-color: #DC3545;
      color: white;
    }

    .btn-delete:hover {
      background-color: #C82333;
    }

    .btn-reset {
      background-color: #FFC107;
      color: #333;
    }

    .btn-reset:hover {
      background-color: #E0A800;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      border-left: 4px solid var(--bni-red);
    }

    .stat-value {
      font-size: 32px;
      font-weight: 700;
      color: var(--bni-red);
      margin: 10px 0;
    }

    .stat-label {
      color: #666;
      font-size: 14px;
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
      <div class="site-logo">BNI Slide System - Admin</div>
      <nav class="site-nav">
        <ul>
          <li><a href="slide.php">スライド表示</a></li>
          <li><a href="edit.php">編集</a></li>
          <li><a href="users.php" class="active">ユーザー管理</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="users-container">
        <h1>ユーザー管理</h1>

        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-label">総ユーザー数</div>
            <div class="stat-value"><?php echo count($membersData); ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">登録ページ</div>
            <div class="stat-value" style="font-size: 16px;">
              <a href="../public/register.php" target="_blank" style="color: var(--bni-red);">登録ページを開く</a>
            </div>
          </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
          <div>
            <strong><?php echo count($membersData); ?> 人のユーザーが登録されています</strong>
          </div>
          <div>
            <button onclick="location.reload()" class="btn btn-secondary btn-small">更新</button>
          </div>
        </div>

        <!-- Users Table -->
        <div class="users-table">
          <table>
            <thead>
              <tr>
                <th>ユーザー名</th>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>会社名</th>
                <th>カテゴリ</th>
                <th>電話番号</th>
                <th>登録日</th>
                <th>最終更新</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($membersData)): ?>
                <tr>
                  <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                    登録されているユーザーはいません
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($membersData as $username => $user): ?>
                  <tr>
                    <td>
                      <strong><?php echo htmlspecialchars($username); ?></strong>
                      <span class="user-badge badge-active">アクティブ</span>
                    </td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['company'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['category'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($user['updated_at'] ?? '-'); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
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

</body>
</html>
