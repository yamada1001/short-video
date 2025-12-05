<?php
/**
 * BNI Slide System - Data Edit (Admin Only)
 * 管理者専用 - データ編集画面
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
  <title>データ編集 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">
  <style>
    .edit-container {
      max-width: 1200px;
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

    .data-table {
      width: 100%;
      background-color: var(--bg-white);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .data-table table {
      width: 100%;
      border-collapse: collapse;
    }

    .data-table thead {
      background-color: var(--bni-red);
      color: var(--bni-white);
    }

    .data-table th,
    .data-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid var(--border-color);
    }

    .data-table tbody tr:hover {
      background-color: var(--bg-light);
    }

    .data-table input,
    .data-table select,
    .data-table textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border-color);
      border-radius: 4px;
      font-size: 14px;
    }

    .data-table textarea {
      min-height: 60px;
      resize: vertical;
    }

    .btn-group {
      display: flex;
      gap: 10px;
    }

    .btn-small {
      padding: 8px 16px;
      font-size: 14px;
    }

    .btn-danger {
      background-color: #E74C3C;
      color: white;
    }

    .btn-danger:hover {
      background-color: #C0392B;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-light);
    }

    .empty-state h3 {
      margin-bottom: 20px;
    }

    @media (max-width: 767px) {
      .action-bar {
        flex-direction: column;
        gap: 15px;
      }

      .data-table {
        overflow-x: auto;
      }

      .data-table table {
        min-width: 800px;
      }
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
          <li><a href="edit.php" class="active">編集</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="edit-container">
        <div class="card">
          <h1>データ編集</h1>
          <p class="mb-3">CSVデータを直接編集できます。変更後は「保存」ボタンを押してください。</p>

          <!-- Success/Error Messages -->
          <div id="message" class="message"></div>

          <!-- Action Bar -->
          <div class="action-bar">
            <div>
              <strong id="dataCount">データ読み込み中...</strong>
            </div>
            <div class="btn-group">
              <button onclick="saveChanges()" class="btn btn-primary btn-small">保存</button>
              <button onclick="location.reload()" class="btn btn-secondary btn-small">リロード</button>
              <button onclick="exportToExcel()" class="btn btn-success btn-small" style="background-color: #28A745;">Excelダウンロード</button>
              <a href="slide.php" class="btn btn-outline btn-small">スライド表示</a>
            </div>
          </div>

          <!-- Data Table -->
          <div class="data-table">
            <table id="dataTable">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th>入力日</th>
                  <th>紹介者名</th>
                  <th>メールアドレス</th>
                  <th>ビジター名</th>
                  <th>ビジター会社名</th>
                  <th>案件名</th>
                  <th>リファーラル金額</th>
                  <th>カテゴリ</th>
                  <th>出席状況</th>
                  <th style="width: 100px;">操作</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                <tr>
                  <td colspan="11" class="empty-state">
                    <div class="spinner"></div>
                    <p>データを読み込んでいます...</p>
                  </td>
                </tr>
              </tbody>
            </table>
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

  <!-- Scripts -->
  <script src="../assets/js/edit.js"></script>

</body>
</html>
