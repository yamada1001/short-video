<?php
/**
 * BNI Slide System - Slide Presentation (Admin Only)
 * 管理者専用 - スライド表示画面
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
  <title>BNI週次レポート | BNI Slide System</title>

  <!-- Google Fonts: Noto Sans JP -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Common CSS -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <!-- Reveal.js CSS -->
  <link rel="stylesheet" href="../assets/lib/reveal.js/dist/reveal.css">
  <link rel="stylesheet" href="../assets/lib/reveal.js/dist/theme/white.css" id="theme">

  <!-- Custom Slide CSS -->
  <link rel="stylesheet" href="../assets/css/slide.css?v=20251213">

  <!-- Google Fonts: Noto Sans JP -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="spinner"></div>
    <div class="loading-text">データを読み込んでいます...</div>
  </div>

  <!-- Control Icon Button -->
  <button id="controlButton" class="control-icon-button" title="コントロール">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="3"></circle>
      <path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m-2.83 2.83l-4.24 4.24m13.66-4.24l-4.24-4.24m-2.83-2.83l-4.24-4.24"></path>
    </svg>
  </button>

  <!-- BNI Logo - Top Right (for normal slides) -->
  <img id="logoTopRight" class="bni-logo-top-right hidden" src="../assets/images/bni-logo.svg" alt="BNI Logo">

  <!-- BNI Logo - Bottom Right (for title slides) -->
  <img id="logoBottomRight" class="bni-logo-bottom-right hidden" src="../assets/images/bni-logo.svg" alt="BNI Logo">

  <!-- Control Panel Modal -->
  <div id="controlPanel" class="control-panel hidden">
    <div class="control-panel-content">
      <div class="control-panel-header">
        <h3>コントロールパネル</h3>
        <button id="closeControlPanel" class="close-button">×</button>
      </div>
      <div class="control-panel-body">
        <div class="control-group">
          <label>表示する週:</label>
          <select id="weekSelector">
            <option value="">読み込み中...</option>
          </select>
        </div>
        <div class="control-group">
          <label>スライドパターン:</label>
          <select id="slidePattern">
            <option value="normal">通常スライド</option>
            <option value="monthly_ranking">月初ランキング</option>
          </select>
          <p style="font-size: 12px; color: #666; margin-top: 5px;">※ 月初ランキングは先月のデータを表示</p>
        </div>
        <div class="control-group">
          <button id="exportPdfBtn" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
            <i class="fas fa-file-pdf"></i> PDFで出力
          </button>
        </div>
        <div class="control-group">
          <a href="edit.php" class="edit-link">📝 編集モード</a>
          <a href="monthly_ranking.php" class="edit-link" style="margin-left: 10px;">📊 ランキング入力</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Sidebar Table of Contents -->
  <div id="sidebarToc" class="sidebar-toc">
    <div class="sidebar-toc-header">
      <h4>目次</h4>
      <button id="toggleSidebar" class="toggle-sidebar-btn">
        <i class="fas fa-chevron-left"></i>
      </button>
    </div>
    <div class="sidebar-toc-content" id="tocContent">
      <!-- Table of contents will be generated dynamically -->
    </div>
  </div>

  <!-- Toggle Sidebar Button (Show when collapsed) -->
  <button id="showSidebarBtn" class="sidebar-toggle-show" style="display: none;">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <rect x="3" y="3" width="7" height="7"></rect>
      <rect x="3" y="14" width="7" height="7"></rect>
      <line x1="14" y1="4" x2="21" y2="4"></line>
      <line x1="14" y1="8" x2="21" y2="8"></line>
      <line x1="14" y1="15" x2="21" y2="15"></line>
      <line x1="14" y1="19" x2="21" y2="19"></line>
    </svg>
  </button>

  <!-- Reveal.js Presentation -->
  <div class="reveal">
    <div class="slides" id="slideContainer">
      <!-- Slides will be generated dynamically by JavaScript -->
    </div>
  </div>

  <!-- Reveal.js Scripts -->
  <script src="../assets/lib/reveal.js/dist/reveal.js"></script>

  <!-- Slide Generator -->
  <script src="../assets/js/svg-slide-generator.js?v=20251213"></script>

  <!-- Custom Slide Script -->
  <script src="../assets/js/slide.js"></script>

</body>
</html>
