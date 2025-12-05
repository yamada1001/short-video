<?php
/**
 * BNI Slide System - Audit Log (Admin Only)
 * 管理者専用 - 監査ログ閲覧画面
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/audit_logger.php';

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

// ページネーション設定
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 50;
$offset = ($page - 1) * $perPage;

// ログを取得
$logs = getAuditLogs($perPage, $offset);
$totalCount = getAuditLogCount();
$totalPages = ceil($totalCount / $perPage);

// アクションラベル
$actionLabels = [
    'create' => '新規作成',
    'update' => '更新',
    'delete' => '削除'
];

// 対象ラベル
$targetLabels = [
    'survey_data' => 'アンケートデータ',
    'user' => 'ユーザー',
    'member' => 'メンバー'
];
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
  <title>監査ログ | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .log-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .log-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .log-header h1 {
      font-size: 28px;
      color: #333;
      margin: 0;
    }

    .log-stats {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      flex: 1;
    }

    .stat-card h3 {
      font-size: 14px;
      color: #666;
      margin: 0 0 10px 0;
    }

    .stat-card .value {
      font-size: 28px;
      font-weight: 700;
      color: #CF2030;
    }

    .log-table {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .log-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .log-table th {
      background: #F5F5F5;
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #333;
      border-bottom: 2px solid #E0E0E0;
    }

    .log-table td {
      padding: 15px;
      border-bottom: 1px solid #F0F0F0;
      color: #555;
    }

    .log-table tr:hover {
      background: #FAFAFA;
    }

    .action-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
    }

    .action-badge.create {
      background: #D4EDDA;
      color: #155724;
    }

    .action-badge.update {
      background: #FFF3CD;
      color: #856404;
    }

    .action-badge.delete {
      background: #F8D7DA;
      color: #721C24;
    }

    .log-data {
      font-size: 13px;
      color: #666;
      max-width: 400px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 30px;
      padding: 20px;
    }

    .pagination a,
    .pagination span {
      padding: 8px 16px;
      border: 1px solid #DDD;
      border-radius: 4px;
      text-decoration: none;
      color: #333;
      background: white;
    }

    .pagination a:hover {
      background: #F5F5F5;
    }

    .pagination .current {
      background: #CF2030;
      color: white;
      border-color: #CF2030;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }

    .empty-state i {
      font-size: 48px;
      margin-bottom: 20px;
      color: #DDD;
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
          <li><a href="../index.php">アンケート</a></li>
          <li><a href="slide.php">スライド</a></li>
          <li><a href="edit.php">データ編集</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="audit_log.php" class="active">監査ログ</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="log-container">
        <!-- Header -->
        <div class="log-header">
          <h1><i class="fas fa-history"></i> 監査ログ</h1>
          <a href="edit.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> データ編集に戻る
          </a>
        </div>

        <!-- Stats -->
        <div class="log-stats">
          <div class="stat-card">
            <h3>総ログ数</h3>
            <div class="value"><?php echo number_format($totalCount); ?></div>
          </div>
          <div class="stat-card">
            <h3>表示中</h3>
            <div class="value"><?php echo count($logs); ?> 件</div>
          </div>
          <div class="stat-card">
            <h3>ページ</h3>
            <div class="value"><?php echo $page; ?> / <?php echo $totalPages; ?></div>
          </div>
        </div>

        <!-- Log Table -->
        <div class="log-table">
          <?php if (count($logs) > 0): ?>
            <table>
              <thead>
                <tr>
                  <th style="width: 160px;">日時</th>
                  <th style="width: 100px;">アクション</th>
                  <th style="width: 120px;">対象</th>
                  <th style="width: 200px;">ユーザー</th>
                  <th>変更内容</th>
                  <th style="width: 120px;">IPアドレス</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($logs as $log): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($log['timestamp'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                      <span class="action-badge <?php echo $log['action']; ?>">
                        <?php echo $actionLabels[$log['action']] ?? $log['action']; ?>
                      </span>
                    </td>
                    <td><?php echo $targetLabels[$log['target']] ?? $log['target']; ?></td>
                    <td>
                      <strong><?php echo htmlspecialchars($log['user_name'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                      <small><?php echo htmlspecialchars($log['user_email'], ENT_QUOTES, 'UTF-8'); ?></small>
                    </td>
                    <td class="log-data">
                      <?php
                      if (isset($log['data']) && is_array($log['data'])) {
                        $dataStr = [];
                        foreach ($log['data'] as $key => $value) {
                          $dataStr[] = $key . ': ' . $value;
                        }
                        echo htmlspecialchars(implode(', ', $dataStr), ENT_QUOTES, 'UTF-8');
                      }
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($log['ip_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-file-alt"></i>
              <p>監査ログがまだありません</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="?page=1"><i class="fas fa-angle-double-left"></i></a>
              <a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-angle-left"></i></a>
            <?php endif; ?>

            <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);

            for ($i = $startPage; $i <= $endPage; $i++):
              if ($i == $page):
            ?>
              <span class="current"><?php echo $i; ?></span>
            <?php else: ?>
              <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php
              endif;
            endfor;
            ?>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?php echo $page + 1; ?>"><i class="fas fa-angle-right"></i></a>
              <a href="?page=<?php echo $totalPages; ?>"><i class="fas fa-angle-double-right"></i></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
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
