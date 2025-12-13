<?php
/**
 * BNI Slide System - Networking Learning Corner Management (Admin Only)
 * 管理者専用 - ネットワーキング学習コーナー管理画面
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
  <title>ネットワーキング学習コーナー管理 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Common CSS -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .learning-container {
      max-width: 900px;
      margin: 0 auto;
    }

    .form-section {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .form-section h2 {
      color: #CF2030;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #CF2030;
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

    .form-group select,
    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      font-family: 'Noto Sans JP', sans-serif;
      transition: border-color 0.3s;
    }

    .form-group select:focus,
    .form-group input:focus {
      outline: none;
      border-color: #CF2030;
    }

    .member-selection {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      padding: 20px;
      max-height: 400px;
      overflow-y: auto;
      background: #fafafa;
    }

    .member-checkbox {
      display: flex;
      align-items: center;
      padding: 10px;
      margin-bottom: 8px;
      background: white;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .member-checkbox:hover {
      background: #f0f8ff;
    }

    .member-checkbox input[type="checkbox"] {
      width: 20px;
      height: 20px;
      margin-right: 12px;
      cursor: pointer;
    }

    .member-checkbox label {
      margin: 0;
      cursor: pointer;
      font-weight: normal;
      flex: 1;
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

    .message {
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      display: block;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      display: block;
    }

    .current-presenter {
      background: #e8f5e9;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 2px solid #4caf50;
    }

    .current-presenter h3 {
      color: #2e7d32;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .current-presenter .presenter-info {
      font-size: 18px;
      font-weight: 600;
      color: #1b5e20;
    }

    .button-group {
      display: flex;
      gap: 15px;
      margin-top: 20px;
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
            <li><a href="visitor_intro.php">ビジターご紹介</a></li>
            <li><a href="networking_learning.php" class="active">ネットワーキング学習</a></li>
            <li><a href="users.php">ユーザー管理</a></li>
            <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <div class="learning-container">
        <h1>ネットワーキング学習コーナー管理</h1>

        <div id="messageContainer"></div>

        <!-- Week Selection -->
        <div class="form-section">
          <h2><i class="fas fa-calendar-week"></i> 対象週を選択</h2>
          <div class="form-group">
            <label for="weekSelector">表示する週 <span class="required">*</span></label>
            <select id="weekSelector">
              <option value="">読み込み中...</option>
            </select>
          </div>
          <button id="loadDataBtn" class="btn btn-secondary">
            <i class="fas fa-download"></i> データ読み込み
          </button>
        </div>

        <!-- Current Presenter Display -->
        <div id="currentPresenterSection" style="display: none;">
          <div class="current-presenter">
            <h3><i class="fas fa-user-check"></i> 現在の担当者</h3>
            <div class="presenter-info" id="currentPresenterName"></div>
          </div>
        </div>

        <!-- Member Selection Form -->
        <div class="form-section">
          <h2><i class="fas fa-users"></i> 担当メンバーを選択</h2>
          <p style="color: #666; margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i> この週にネットワーキング学習コーナーを担当するメンバーを1名選択してください。
          </p>

          <form id="presenterForm">
            <div class="form-group">
              <label for="memberSelect">担当メンバー <span class="required">*</span></label>
              <select id="memberSelect" required>
                <option value="">-- メンバーを選択 --</option>
              </select>
            </div>

            <div class="button-group">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> 保存
              </button>
              <button type="button" id="clearBtn" class="btn btn-secondary">
                <i class="fas fa-times"></i> 選択解除
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script src="../assets/js/networking_learning.js"></script>
</body>
</html>
