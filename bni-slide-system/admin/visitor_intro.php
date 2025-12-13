<?php
/**
 * BNI Slide System - Visitor Introduction Management (Admin Only)
 * 管理者専用 - ビジターご紹介管理画面
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
  <title>ビジターご紹介管理 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Common CSS -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .visitor-form {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-row.full {
      grid-template-columns: 1fr;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }

    .form-group label .required {
      color: #CF2030;
      margin-left: 4px;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      font-family: 'Noto Sans JP', sans-serif;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #CF2030;
    }

    .visitor-list {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-top: 30px;
    }

    .visitor-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .visitor-table th,
    .visitor-table td {
      padding: 12px 16px;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }

    .visitor-table th {
      background: #f5f5f5;
      font-weight: 600;
      color: #333;
    }

    .visitor-table tr:hover {
      background: #f9f9f9;
    }

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: #CF2030;
      color: #fff;
    }

    .btn-primary:hover {
      background: #a01828;
    }

    .btn-secondary {
      background: #6c757d;
      color: #fff;
    }

    .btn-secondary:hover {
      background: #5a6268;
    }

    .btn-danger {
      background: #dc3545;
      color: #fff;
      padding: 8px 16px;
      font-size: 14px;
    }

    .btn-danger:hover {
      background: #c82333;
    }

    .message {
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .add-visitor-btn {
      margin-bottom: 20px;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }

    .empty-state i {
      font-size: 64px;
      margin-bottom: 20px;
      color: #ddd;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      font-size: 12px;
      font-weight: 600;
      border-radius: 4px;
      text-transform: uppercase;
    }

    .badge-info {
      background: #17a2b8;
      color: #fff;
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <div class="container">
    <!-- Header -->
    <header class="site-header">
      <div class="container">
        <div class="site-logo">BNI Slide System - Admin</div>
        <nav class="site-nav">
          <ul>
            <li><a href="slide.php">スライド表示</a></li>
            <li><a href="edit.php">編集</a></li>
            <li><a href="bulk_input.php">一括入力</a></li>
            <li><a href="referrals.php">リファーラル管理</a></li>
            <li><a href="seating.php">座席表編集</a></li>
            <li><a href="monthly_ranking.php">月間ランキング</a></li>
            <li><a href="visitor_intro.php" class="active">ビジターご紹介</a></li>
            <li><a href="networking_learning.php">ネットワーキング学習</a></li>
            <li><a href="users.php">ユーザー管理</a></li>
            <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <h1>ビジターご紹介管理</h1>

      <!-- Week Selection -->
      <div class="visitor-form">
        <h2><i class="fas fa-calendar-week"></i> 対象週を選択</h2>
        <div class="form-row">
          <div class="form-group">
            <label for="weekSelector">表示する週 <span class="required">*</span></label>
            <select id="weekSelector">
              <option value="">読み込み中...</option>
            </select>
          </div>
          <div class="form-group" style="display: flex; align-items: flex-end;">
            <button id="loadDataBtn" class="btn btn-secondary">
              <i class="fas fa-download"></i> データ読み込み
            </button>
          </div>
        </div>
      </div>

      <!-- Add New Visitor Form -->
      <div class="visitor-form">
        <h2><i class="fas fa-user-plus"></i> ビジターを追加</h2>
        <form id="visitorForm">
          <div class="form-row">
            <div class="form-group">
              <label for="visitorName">お名前 <span class="required">*</span></label>
              <input type="text" id="visitorName" name="visitor_name" required placeholder="山田 太郎">
            </div>
            <div class="form-group">
              <label for="company">会社名（屋号）</label>
              <input type="text" id="company" name="company" placeholder="株式会社Example">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="specialty">専門分野</label>
              <input type="text" id="specialty" name="specialty" placeholder="経営コンサルティング">
            </div>
            <div class="form-group">
              <label for="sponsor">スポンサー（紹介者） <span class="required">*</span></label>
              <select id="sponsor" name="sponsor" required>
                <option value="">読み込み中...</option>
              </select>
            </div>
          </div>

          <div class="form-row full">
            <div class="form-group">
              <label for="attendant">アテンド（同行者） <span class="required">*</span></label>
              <select id="attendant" name="attendant" required>
                <option value="">読み込み中...</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> ビジターを追加
          </button>
        </form>
      </div>

      <!-- Visitor List -->
      <div class="visitor-list">
        <h2><i class="fas fa-list"></i> 登録済みビジター一覧</h2>
        <div id="visitorListContainer">
          <div class="empty-state">
            <i class="fas fa-user-friends"></i>
            <p>週を選択してデータを読み込んでください</p>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="../assets/js/visitor_intro.js"></script>
</body>
</html>
