<?php
/**
 * BNI Slide System - User Manual
 * システム利用マニュアル
 */

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUserInfo();
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
  <title>利用マニュアル | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">

  <style>
    body {
      font-family: 'Noto Sans JP', sans-serif;
      line-height: 1.8;
      color: #333;
      background: #F5F5F5;
      margin: 0;
      padding: 0;
    }

    .manual-wrapper {
      display: flex;
      max-width: 1400px;
      margin: 0 auto;
      position: relative;
    }

    .manual-container {
      flex: 1;
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 20px;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Floating Sidebar Table of Contents */
    .floating-toc {
      position: fixed;
      top: 100px;
      right: 20px;
      width: 280px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      padding: 20px;
      max-height: calc(100vh - 140px);
      overflow-y: auto;
      z-index: 100;
      transition: all 0.3s ease;
    }

    .floating-toc.hidden {
      transform: translateX(320px);
      opacity: 0;
    }

    .floating-toc h3 {
      font-size: 16px;
      font-weight: 700;
      color: #CF2030;
      margin: 0 0 15px 0;
      padding-bottom: 10px;
      border-bottom: 2px solid #CF2030;
    }

    .floating-toc ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .floating-toc li {
      margin: 0;
      padding: 0;
    }

    .floating-toc a {
      display: block;
      padding: 8px 12px;
      color: #666;
      text-decoration: none;
      font-size: 14px;
      border-left: 3px solid transparent;
      transition: all 0.2s ease;
      margin-bottom: 4px;
    }

    .floating-toc a:hover {
      background: #F9F9F9;
      color: #CF2030;
      border-left-color: #CF2030;
    }

    .floating-toc a.active {
      background: #FFF5F5;
      color: #CF2030;
      border-left-color: #CF2030;
      font-weight: 600;
    }

    .floating-toc-toggle {
      position: fixed;
      top: 100px;
      right: 20px;
      width: 50px;
      height: 50px;
      background: #CF2030;
      color: white;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      font-size: 20px;
      display: none;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(207, 32, 48, 0.3);
      z-index: 101;
      transition: all 0.3s ease;
    }

    .floating-toc-toggle:hover {
      background: #A01828;
      transform: scale(1.1);
    }

    .floating-toc-toggle.show {
      display: flex;
    }

    /* Responsive */
    @media (max-width: 1400px) {
      .floating-toc {
        display: none;
      }
      .floating-toc-toggle.show {
        display: flex;
      }
    }

    @media (max-width: 768px) {
      .manual-wrapper {
        flex-direction: column;
      }
      .floating-toc {
        position: fixed;
        top: 60px;
        right: 10px;
        width: 240px;
      }
    }

    .manual-header {
      text-align: center;
      padding-bottom: 40px;
      border-bottom: 3px solid #CF2030;
      margin-bottom: 40px;
    }

    .manual-header h1 {
      font-size: 36px;
      font-weight: 700;
      color: #CF2030;
      margin: 0 0 10px 0;
    }

    .manual-header .subtitle {
      font-size: 16px;
      color: #666;
      margin: 0;
    }

    .manual-header .version {
      display: inline-block;
      background: #CF2030;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 14px;
      margin-top: 10px;
    }

    .manual-nav {
      background: #F9F9F9;
      border-left: 4px solid #CF2030;
      padding: 20px;
      margin-bottom: 40px;
      border-radius: 4px;
    }

    .manual-nav h2 {
      font-size: 18px;
      font-weight: 600;
      color: #CF2030;
      margin: 0 0 15px 0;
    }

    .manual-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .manual-nav ul li {
      margin-bottom: 8px;
    }

    .manual-nav ul li a {
      color: #333;
      text-decoration: none;
      transition: color 0.2s;
    }

    .manual-nav ul li a:hover {
      color: #CF2030;
    }

    .manual-section {
      margin-bottom: 60px;
    }

    .manual-section h2 {
      font-size: 28px;
      font-weight: 700;
      color: #CF2030;
      margin: 0 0 20px 0;
      padding-bottom: 10px;
      border-bottom: 2px solid #CF2030;
    }

    .manual-section h3 {
      font-size: 22px;
      font-weight: 600;
      color: #333;
      margin: 30px 0 15px 0;
    }

    .manual-section h4 {
      font-size: 18px;
      font-weight: 600;
      color: #555;
      margin: 20px 0 10px 0;
    }

    .manual-section p {
      margin: 0 0 15px 0;
      line-height: 1.8;
    }

    .manual-section ul, .manual-section ol {
      margin: 0 0 15px 20px;
      line-height: 1.8;
    }

    .manual-section ul li, .manual-section ol li {
      margin-bottom: 8px;
    }

    .info-box {
      background: #E8F4FD;
      border-left: 4px solid #2196F3;
      padding: 15px 20px;
      margin: 20px 0;
      border-radius: 4px;
    }

    .info-box h4 {
      color: #2196F3;
      margin: 0 0 10px 0;
      font-size: 16px;
    }

    .warning-box {
      background: #FFF3CD;
      border-left: 4px solid #FFC107;
      padding: 15px 20px;
      margin: 20px 0;
      border-radius: 4px;
    }

    .warning-box h4 {
      color: #856404;
      margin: 0 0 10px 0;
      font-size: 16px;
    }

    .important-box {
      background: #FFE8E8;
      border-left: 4px solid #CF2030;
      padding: 15px 20px;
      margin: 20px 0;
      border-radius: 4px;
    }

    .important-box h4 {
      color: #CF2030;
      margin: 0 0 10px 0;
      font-size: 16px;
    }

    .code-box {
      background: #F5F5F5;
      border: 1px solid #DDD;
      padding: 15px;
      border-radius: 4px;
      font-family: monospace;
      margin: 15px 0;
      overflow-x: auto;
    }

    .step-list {
      counter-reset: step-counter;
      list-style: none;
      padding: 0;
    }

    .step-list li {
      counter-increment: step-counter;
      margin-bottom: 20px;
      padding-left: 40px;
      position: relative;
    }

    .step-list li::before {
      content: counter(step-counter);
      position: absolute;
      left: 0;
      top: 0;
      background: #CF2030;
      color: white;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
    }

    .screenshot {
      border: 2px solid #DDD;
      border-radius: 4px;
      margin: 15px 0;
      max-width: 100%;
      height: auto;
    }

    .table-container {
      overflow-x: auto;
      margin: 20px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    table th {
      background: #CF2030;
      color: white;
      padding: 12px;
      text-align: left;
      font-weight: 600;
    }

    table td {
      border: 1px solid #DDD;
      padding: 12px;
    }

    table tr:nth-child(even) {
      background: #F9F9F9;
    }

    .faq-item {
      margin-bottom: 25px;
      padding: 20px;
      background: #F9F9F9;
      border-radius: 8px;
      border-left: 4px solid #CF2030;
    }

    .faq-question {
      font-size: 18px;
      font-weight: 600;
      color: #CF2030;
      margin: 0 0 10px 0;
    }

    .faq-answer {
      color: #333;
      line-height: 1.8;
      margin: 0;
    }

    .nav-menu {
      background: white;
      padding: 15px 20px;
      margin-bottom: 20px;
      border-bottom: 2px solid #CF2030;
      text-align: center;
    }

    .nav-menu a {
      color: #CF2030;
      text-decoration: none;
      margin: 0 15px;
      font-weight: 600;
      transition: opacity 0.2s;
    }

    .nav-menu a:hover {
      opacity: 0.7;
    }

    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #CF2030;
      color: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      transition: opacity 0.3s;
      opacity: 0;
      pointer-events: none;
    }

    .back-to-top.visible {
      opacity: 1;
      pointer-events: auto;
    }

    .back-to-top:hover {
      background: #A51828;
    }

    @media print {
      .nav-menu, .back-to-top {
        display: none;
      }
      .manual-container {
        box-shadow: none;
      }
    }

    @media (max-width: 768px) {
      .manual-container {
        padding: 20px 15px;
      }
      .manual-header h1 {
        font-size: 28px;
      }
      .manual-section h2 {
        font-size: 24px;
      }
      .manual-section h3 {
        font-size: 20px;
      }
      .nav-menu a {
        display: block;
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <?php if ($currentUser): ?>
  <div class="nav-menu">
    <a href="index.php"><i class="fas fa-home"></i> ホーム</a>
    <a href="my-data.php"><i class="fas fa-database"></i> マイデータ</a>
    <a href="profile.php"><i class="fas fa-user"></i> プロフィール</a>
    <?php if (isset($currentUser['role']) && $currentUser['role'] === 'admin'): ?>
    <a href="admin/slide.php"><i class="fas fa-presentation"></i> スライド</a>
    <a href="admin/users.php"><i class="fas fa-users"></i> ユーザー管理</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Floating Table of Contents -->
  <div class="floating-toc" id="floatingToc">
    <h3><i class="fas fa-list"></i> 目次</h3>
    <ul>
      <li><a href="#overview" class="toc-link">1. システム概要</a></li>
      <li><a href="#login" class="toc-link">2. ログイン方法</a></li>
      <li><a href="#member" class="toc-link">3. 一般メンバーの使い方</a></li>
      <li><a href="#admin" class="toc-link">4. 管理者の使い方</a></li>
      <li><a href="#faq" class="toc-link">5. よくある質問</a></li>
      <li><a href="#troubleshooting" class="toc-link">6. トラブルシューティング</a></li>
      <li><a href="#contact" class="toc-link">7. お問い合わせ</a></li>
    </ul>
  </div>

  <!-- Toggle Button for Mobile -->
  <button class="floating-toc-toggle show" id="tocToggle">
    <i class="fas fa-list"></i>
  </button>

  <div class="manual-wrapper">
    <div class="manual-container">
    <!-- Header -->
    <div class="manual-header">
      <h1><i class="fas fa-book"></i> BNI Slide System 利用マニュアル</h1>
      <p class="subtitle">BNI宗麟チャプター メンバー様・管理者様</p>
      <span class="version">Version 3.0</span>
      <p style="margin-top: 15px; font-size: 14px; color: #666;">最終更新: 2025年12月13日</p>
    </div>

    <!-- Table of Contents -->
    <div class="manual-nav">
      <h2><i class="fas fa-list"></i> 目次</h2>
      <ul>
        <li><a href="#overview">1. システム概要</a></li>
        <li><a href="#login">2. ログイン方法</a></li>
        <li><a href="#member">3. 一般メンバーの使い方</a></li>
        <li><a href="#admin">4. 管理者の使い方</a></li>
        <li><a href="#faq">5. よくある質問</a></li>
        <li><a href="#troubleshooting">6. トラブルシューティング</a></li>
        <li><a href="#contact">7. お問い合わせ</a></li>
      </ul>
    </div>

    <!-- Section 1: システム概要 -->
    <div id="overview" class="manual-section">
      <h2>1. システム概要</h2>

      <p>BNI Slide Systemは、宗麟チャプターの週次ミーティングで使用するスライド資料を自動生成するシステムです。</p>

      <h3>主な機能</h3>
      <ul>
        <li><strong>週次アンケート機能</strong>: メンバーがビジター紹介とピッチプレゼンテーション資料を入力</li>
        <li><strong>自動スライド生成</strong>: 入力されたデータから美しいスライド資料を自動作成</li>
        <li><strong>マイデータ管理</strong>: 自分が入力したデータの閲覧・編集が可能</li>
        <li><strong>管理機能</strong>: 管理者によるデータ編集・一括入力・ユーザー管理</li>
      </ul>

      <div class="important-box">
        <h4><i class="fas fa-exclamation-circle"></i> 重要: ユーザー入力項目の変更について</h4>
        <p><strong>ユーザーアンケート(index.php)で入力する項目:</strong></p>
        <ul style="margin: 10px 0 0 20px;">
          <li>ビジター情報（名前、会社名、業種）</li>
          <li>ピッチプレゼンテーション資料（PDF、YouTube URL）</li>
        </ul>
        <p style="margin-top: 10px;"><strong>管理者専用の入力項目（一括入力画面から）:</strong></p>
        <ul style="margin: 10px 0 0 20px;">
          <li>出席状況</li>
          <li>サンクスリップ</li>
          <li>ワンツーワン（121）</li>
          <li>アクティビティ</li>
          <li>コメント</li>
          <li>リファーラル情報</li>
        </ul>
      </div>

      <div class="info-box">
        <h4><i class="fas fa-link"></i> アクセスURL</h4>
        <p><strong>本番環境</strong>: <a href="https://yojitu.com/bni-slide-system/" target="_blank">https://yojitu.com/bni-slide-system/</a></p>
      </div>

      <div class="important-box">
        <h4><i class="fas fa-calendar-alt"></i> 週の定義</h4>
        <p>このシステムでは、週の境界を以下のように定義しています。</p>
        <ul style="margin: 10px 0 0 20px;">
          <li><strong>週の開始</strong>: 金曜日 午前5:00</li>
          <li><strong>週の終了</strong>: 次の金曜日 午前4:59</li>
        </ul>
        <p style="margin-top: 10px;"><strong>例</strong>: 12月5日（金）のスライドには、11月28日（金）5:00 〜 12月5日（金）4:59 に入力されたデータが反映されます。</p>
      </div>
    </div>

    <!-- Section 2: ログイン方法 -->
    <div id="login" class="manual-section">
      <h2>2. ログイン方法</h2>

      <h3>初回ログイン（新規登録）</h3>
      <ol class="step-list">
        <li>ブラウザで <a href="https://yojitu.com/bni-slide-system/" target="_blank">https://yojitu.com/bni-slide-system/</a> にアクセス</li>
        <li>「ログイン」ボタンをクリック</li>
        <li>初めての方は「新規登録はこちら」をクリック</li>
        <li>以下の情報を入力:
          <ul>
            <li><strong>氏名</strong>: フルネーム（例: 山田太郎）</li>
            <li><strong>メールアドレス</strong>: BNIで使用しているメールアドレス</li>
            <li><strong>パスワード</strong>: 8文字以上の安全なパスワード</li>
            <li><strong>パスワード（確認用）</strong>: 同じパスワードをもう一度入力</li>
          </ul>
        </li>
        <li>「登録」ボタンをクリック</li>
        <li>登録完了画面が表示されたら「ログインページへ」をクリック</li>
      </ol>

      <h3>2回目以降のログイン</h3>
      <ol class="step-list">
        <li><a href="https://yojitu.com/bni-slide-system/login.php" target="_blank">https://yojitu.com/bni-slide-system/login.php</a> にアクセス</li>
        <li>登録したメールアドレスとパスワードを入力</li>
        <li>「ログイン」ボタンをクリック</li>
      </ol>

      <div class="warning-box">
        <h4><i class="fas fa-exclamation-triangle"></i> パスワードを忘れた場合</h4>
        <p>現在、パスワードリセット機能は実装されていません。管理者にお問い合わせください。</p>
      </div>
    </div>

    <!-- Section 3: 一般メンバーの使い方 -->
    <div id="member" class="manual-section">
      <h2>3. 一般メンバーの使い方</h2>

      <h3>週次アンケート回答</h3>
      <p>毎週のミーティング前に、先週の活動内容を入力します。</p>

      <h4>1. アンケートページにアクセス</h4>
      <p>ログイン後、トップページ（<a href="https://yojitu.com/bni-slide-system/index.php" target="_blank">index.php</a>）に自動的にアンケートフォームが表示されます。</p>

      <h4>2. ビジター情報の入力（複数可）</h4>
      <p>ビジターを紹介した場合、以下の情報を入力します。</p>
      <ul>
        <li><strong>ビジター名</strong>: ゲストの氏名（例: 佐藤花子様）</li>
        <li><strong>会社名</strong>: ゲストの会社名または屋号</li>
        <li><strong>業種</strong>: ゲストの業種（例: 不動産業、IT業）</li>
      </ul>

      <div class="info-box">
        <h4><i class="fas fa-plus-circle"></i> 複数のビジターを追加する場合</h4>
        <ul>
          <li>「+ ビジターを追加」ボタンをクリック</li>
          <li>新しい入力欄が表示されます</li>
          <li>最大10名まで追加可能</li>
        </ul>
      </div>

      <h4>3. ピッチプレゼンテーション資料</h4>
      <p>ピッチプレゼンテーション（30秒プレゼン）の資料を登録します。</p>
      <ul>
        <li><strong>PDFファイル</strong>: 最大30MBまで（スライド埋め込み表示）</li>
        <li><strong>YouTube URL</strong>: YouTube動画URLを入力（動画埋め込み再生）</li>
        <li>PDF、YouTubeどちらか一方、または両方を登録可能</li>
        <li>何も登録しない場合はテキストのみのスライドが表示されます</li>
      </ul>

      <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> ピッチプレゼンテーションについて</h4>
        <p>毎週30秒で自社サービスや商品を紹介するプレゼンテーションです。視覚的な資料を事前に登録することで、スライド表示時に自動的に表示されます。</p>
      </div>

      <h4>4. 送信</h4>
      <ol class="step-list">
        <li>すべて入力したら「送信」ボタンをクリック</li>
        <li>送信完了画面が表示されます</li>
        <li>管理者にメール通知が送信されます</li>
      </ol>

      <div class="important-box">
        <h4><i class="fas fa-exclamation-circle"></i> 重要な注意事項</h4>
        <ul>
          <li><strong>週1回制限</strong>: 同じ週に2回以上送信することはできません</li>
          <li><strong>編集可能</strong>: 送信後も「マイデータ」から編集可能です</li>
          <li><strong>締切</strong>: 金曜日の午前5時までに入力してください</li>
          <li><strong>出席状況・リファーラルなど</strong>: これらは管理者が一括入力します（メンバーは入力不要）</li>
        </ul>
      </div>

      <h3>マイデータ閲覧</h3>
      <p>自分が過去に入力したデータを確認できます。</p>

      <ol class="step-list">
        <li>ナビゲーションメニューから「マイデータ」をクリック</li>
        <li>週ごとにカード形式で表示されます</li>
        <li>各カードには以下の情報が表示されます:
          <ul>
            <li>週の日付（例: 2025年12月5日（金））</li>
            <li>入力日</li>
            <li>ビジター紹介数</li>
            <li>出席状況</li>
            <li>ピッチプレゼンテーション担当</li>
            <li>シェアストーリー担当</li>
            <li>エデュケーション担当</li>
          </ul>
        </li>
      </ol>

      <h3>マイデータ編集</h3>
      <p>過去に入力したデータを修正できます。</p>

      <ol class="step-list">
        <li>「マイデータ」ページから編集したい週の「編集」ボタンをクリック</li>
        <li>すべての項目を自由に編集できます</li>
        <li>ビジターの追加: 「+ ビジターを追加」ボタンをクリック</li>
        <li>ビジターの削除: 各項目の「削除」ボタンをクリック</li>
        <li>「保存」ボタンをクリック</li>
        <li>成功メッセージが表示されたら完了</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> 編集可能な週</h4>
        <p><strong>金曜日のデータのみ</strong>編集可能です。これはスライドが金曜日にのみ使用されるためです。</p>
      </div>
    </div>

    <!-- Section 4: 管理者の使い方 -->
    <div id="admin" class="manual-section">
      <h2>4. 管理者の使い方</h2>

      <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> 管理者権限について</h4>
        <p>管理者ログイン情報は、チャプター管理者の方から別途ご案内いたします。</p>
      </div>

      <h3>スライド管理画面（admin/slide.php）</h3>
      <p>週次ミーティングで使用するスライドを表示・管理する最も重要な画面です。</p>

      <h4>1. スライドページにアクセス</h4>
      <ol class="step-list">
        <li>管理者でログイン後、ヘッダーメニューから「スライド表示」をクリック</li>
        <li>または直接 <a href="admin/slide.php" target="_blank">admin/slide.php</a> にアクセス</li>
        <li>画面が読み込まれ、最新週のスライドが自動生成されます</li>
      </ol>

      <h4>2. 週の選択方法</h4>
      <ol class="step-list">
        <li>画面左下の「歯車アイコン」をクリック（コントロールパネルが開きます）</li>
        <li>「表示する週」ドロップダウンから金曜日の日付を選択</li>
        <li>選択すると自動的にスライドが再生成されます</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-calendar-alt"></i> 週の選択について</h4>
        <ul>
          <li>金曜日のデータのみがリストに表示されます</li>
          <li>デフォルトは最新の週が選択されています</li>
          <li>過去のスライドも選択して閲覧可能です</li>
        </ul>
      </div>

      <h4>3. スライド操作方法</h4>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>操作</th>
              <th>方法</th>
              <th>説明</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>次のスライド</td>
              <td>右矢印キー / 画面右側クリック / スペースキー</td>
              <td>次のスライドに進みます</td>
            </tr>
            <tr>
              <td>前のスライド</td>
              <td>左矢印キー / 画面左側クリック</td>
              <td>前のスライドに戻ります</td>
            </tr>
            <tr>
              <td>スライド一覧</td>
              <td>ESCキー</td>
              <td>全スライドのサムネイル一覧を表示（クリックでジャンプ可能）</td>
            </tr>
            <tr>
              <td>フルスクリーン</td>
              <td>Fキー</td>
              <td>フルスクリーン表示/解除を切り替え</td>
            </tr>
            <tr>
              <td>目次表示</td>
              <td>画面左の目次パネル</td>
              <td>スライド名をクリックで直接ジャンプ可能</td>
            </tr>
            <tr>
              <td>コントロールパネル</td>
              <td>画面左下の歯車アイコン</td>
              <td>週の選択、PDF出力などの設定</td>
            </tr>
          </tbody>
        </table>
      </div>

      <h4>4. PDF出力方法</h4>
      <ol class="step-list">
        <li>コントロールパネル（歯車アイコン）を開く</li>
        <li>「PDFで出力」ボタンをクリック</li>
        <li>ブラウザの印刷プレビューが開きます</li>
        <li>「送信先」を「PDFに保存」に設定</li>
        <li>「保存」をクリックしてPDFファイルをダウンロード</li>
      </ol>

      <div class="warning-box">
        <h4><i class="fas fa-exclamation-triangle"></i> PDF出力時の注意点</h4>
        <ul>
          <li>推奨ブラウザ: Google Chrome（最も安定したPDF出力が可能）</li>
          <li>用紙サイズ: A4横向き推奨</li>
          <li>余白: なし（または最小）</li>
          <li>背景のグラフィック: 有効にする</li>
        </ul>
      </div>

      <h4>5. スライドの構成</h4>
      <p>スライドは以下の順序で自動生成されます:</p>
      <ol>
        <li><strong>タイトルスライド</strong>: BNI宗麟チャプター ロゴとタイトル</li>
        <li><strong>今週のサマリー</strong>: 出席人数、ビジター数、リファーラル総額、サンクスリップ数</li>
        <li><strong>ビジター紹介一覧</strong>: 全ビジターの一覧表示</li>
        <li><strong>ビジター自己紹介スライド</strong>: 各ビジターごとに1枚（名前、会社名、業種）</li>
        <li><strong>ピッチプレゼンテーション</strong>: 各メンバーの30秒ピッチスライド（PDF/動画埋め込み）</li>
        <li><strong>ビジターご紹介スライド</strong>: 管理者が登録したビジター紹介画像表示</li>
        <li><strong>ネットワーキング学習コーナー</strong>: 管理者が登録した学習コンテンツ画像表示</li>
        <li><strong>月間ランキング</strong>: 月初の場合、先月のトップ貢献者ランキング</li>
        <li><strong>ありがとうございました</strong>: エンディングスライド</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-video"></i> ピッチプレゼンテーションスライドについて</h4>
        <ul>
          <li>メンバーが登録したPDFファイルは自動的にスライドに埋め込まれます</li>
          <li>YouTube URLが登録されている場合は、動画プレーヤーが表示されます</li>
          <li>PDF、YouTubeの両方が登録されている場合は、両方が表示されます</li>
          <li>何も登録されていない場合は、メンバー名のみのシンプルなスライドが表示されます</li>
        </ul>
      </div>

      <h3>一括入力画面（admin/bulk_input.php）</h3>
      <p>週を選択して、全メンバーの出席状況、サンクスリップ、ワンツーワン、アクティビティ、コメントなどを一括で入力できる画面です。</p>

      <h4>1. アクセス方法</h4>
      <ol class="step-list">
        <li>管理者メニューから「一括入力」をクリック</li>
        <li>または <a href="admin/bulk_input.php" target="_blank">admin/bulk_input.php</a> に直接アクセス</li>
      </ol>

      <h4>2. 週の選択</h4>
      <ol class="step-list">
        <li>ページ上部の「対象週」ドロップダウンから入力したい週を選択</li>
        <li>金曜日の日付のみが表示されます</li>
        <li>選択すると、その週のデータが自動的に読み込まれます</li>
      </ol>

      <h4>3. データ入力</h4>
      <p>各メンバーのフォームが縦並びで表示されます。以下の項目を入力できます:</p>
      <ul>
        <li><strong>出席状況</strong>: 出席 / 欠席 / 代理出席</li>
        <li><strong>サンクスリップ</strong>: 送信数を数値で入力</li>
        <li><strong>ワンツーワン（121）</strong>: 実施数を数値で入力</li>
        <li><strong>アクティビティ</strong>: 件数を数値で入力</li>
        <li><strong>コメント</strong>: 自由記述（スライドに表示されます）</li>
      </ul>

      <h4>4. 保存</h4>
      <ol class="step-list">
        <li>全メンバーの入力が完了したら、ページ下部の「一括保存」ボタンをクリック</li>
        <li>保存成功のメッセージが表示されます</li>
        <li>スライド画面で反映を確認できます</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-info-circle"></i> 一括入力について</h4>
        <ul>
          <li>座席表に登録されているメンバーのみが表示されます</li>
          <li>メンバーは座席表の順序で並んでいます</li>
          <li>各メンバーのフォームは独立しており、個別に入力できます</li>
          <li>既存データがある場合は自動的に読み込まれます</li>
        </ul>
      </div>

      <h3>データ編集画面（admin/edit.php）</h3>
      <p>週次アンケートデータの確認と編集ができる画面です。</p>

      <ol class="step-list">
        <li>管理者メニューから「データ編集」をクリック</li>
        <li>編集する週をドロップダウンから選択</li>
        <li>各セルを直接クリックして編集</li>
        <li>Enter キーで確定（自動保存）</li>
        <li>「CSVダウンロード」ボタンでバックアップ可能</li>
      </ol>

      <h3>ビジターご紹介管理（admin/visitor_intro.php）</h3>
      <p>スライドに表示するビジター紹介用の画像をアップロードして管理する画面です。</p>

      <h4>使い方</h4>
      <ol class="step-list">
        <li>管理者メニューから「ビジターご紹介管理」にアクセス</li>
        <li>対象週を選択（金曜日の日付）</li>
        <li>「画像を選択」ボタンで画像ファイルを選択（JPG, PNG, GIF対応）</li>
        <li>「アップロード」ボタンをクリック</li>
        <li>アップロードした画像がスライドの「ビジターご紹介」セクションに表示されます</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-image"></i> 画像について</h4>
        <ul>
          <li>推奨サイズ: 1920x1080px（フルHD）</li>
          <li>最大ファイルサイズ: 10MB</li>
          <li>複数枚アップロード可能（スライドで順番に表示）</li>
          <li>不要な画像は削除可能</li>
        </ul>
      </div>

      <h3>ネットワーキング学習コーナー管理（admin/networking_learning.php）</h3>
      <p>スライドに表示する学習コンテンツ用の画像をアップロードして管理する画面です。</p>

      <h4>使い方</h4>
      <ol class="step-list">
        <li>管理者メニューから「ネットワーキング学習コーナー管理」にアクセス</li>
        <li>対象週を選択（金曜日の日付）</li>
        <li>「画像を選択」ボタンで画像ファイルを選択（JPG, PNG, GIF対応）</li>
        <li>「アップロード」ボタンをクリック</li>
        <li>アップロードした画像がスライドの「ネットワーキング学習コーナー」セクションに表示されます</li>
      </ol>

      <h3>月間ランキング管理（admin/monthly_ranking.php）</h3>
      <p>月初のスライドで表示される先月のトップ貢献者ランキングを管理する画面です。</p>

      <h4>使い方</h4>
      <ol class="step-list">
        <li>管理者メニューから「月間ランキング」にアクセス</li>
        <li>対象年月を選択（例: 2025年12月）</li>
        <li>各メンバーのビジター紹介数、リファーラル金額、ワンツーワン数を入力</li>
        <li>「保存」ボタンをクリック</li>
        <li>月初のスライドで自動的にランキング表示されます</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-trophy"></i> ランキング表示について</h4>
        <ul>
          <li>月初のスライドで自動的に表示されます</li>
          <li>上位3名がゴールド・シルバー・ブロンズメダルで表彰されます</li>
          <li>ビジター紹介数とリファーラル金額でソートされます</li>
        </ul>
      </div>

      <h3>ユーザー管理（admin/users.php）</h3>
      <p>メンバーアカウントを管理します。</p>

      <h4>ユーザー一覧の確認</h4>
      <ul>
        <li>ユーザーID</li>
        <li>氏名</li>
        <li>メールアドレス</li>
        <li>権限（一般/管理者）</li>
        <li>登録日</li>
      </ul>

      <h4>新規ユーザーの追加</h4>
      <ol class="step-list">
        <li>「新規ユーザー追加」ボタンをクリック</li>
        <li>氏名、メールアドレス、パスワード、権限を入力</li>
        <li>「追加」ボタンをクリック</li>
      </ol>

      <h4>ユーザー情報の編集</h4>
      <ul>
        <li>各ユーザーの「編集」ボタンをクリック</li>
        <li>氏名、メールアドレス、権限を変更可能</li>
        <li>パスワード変更も可能</li>
      </ul>

      <div class="warning-box">
        <h4><i class="fas fa-trash-alt"></i> ユーザーの削除</h4>
        <p><strong>注意</strong>: 削除したユーザーは復元できません。慎重に操作してください。</p>
      </div>

      <h3>座席表編集（admin/seating.php）</h3>
      <p>チャプターミーティングの座席配置を編集できます。</p>

      <ol class="step-list">
        <li>管理者メニューから「座席表編集」をクリック</li>
        <li>メンバープール（未配置エリア）から各テーブルにメンバーをドラッグ&ドロップ</li>
        <li>テーブル内での順序も自由に変更可能</li>
        <li>「保存」ボタンをクリック</li>
      </ol>

      <div class="info-box">
        <h4><i class="fas fa-table"></i> 座席表の仕様</h4>
        <ul>
          <li>8つのテーブル（A, B, C, D, E, F, G, H）固定</li>
          <li>各テーブル最大7名まで</li>
          <li>ドラッグ&ドロップで直感的に配置可能</li>
          <li>特別エリア（ポーチタイム、スクリーン）は固定位置</li>
        </ul>
      </div>
    </div>

    <!-- Section 5: よくある質問 -->
    <div id="faq" class="manual-section">
      <h2>5. よくある質問</h2>

      <div class="faq-item">
        <div class="faq-question">Q1. アンケートを送信したのに「今週は既に回答済みです」と表示される</div>
        <div class="faq-answer"><strong>A</strong>: このシステムでは、同じ週に2回以上送信することはできません。データを修正したい場合は、「マイデータ」から編集してください。</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q2. ビジターを複数入力したい</div>
        <div class="faq-answer"><strong>A</strong>: 「+ ビジターを追加」ボタンをクリックすると、入力欄が追加されます。最大10名まで追加可能です。</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q3. 送信後にデータを修正したい</div>
        <div class="faq-answer"><strong>A</strong>: 「マイデータ」ページから該当週のデータを編集できます。編集ボタンをクリックして修正してください。</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q4. スライドに自分のデータが反映されていない</div>
        <div class="faq-answer">
          <strong>A</strong>: 以下を確認してください:
          <ul style="margin: 10px 0 0 20px;">
            <li>送信したタイミングが週の範囲内か（金曜5時〜次の金曜5時未満）</li>
            <li>管理者がスライドで正しい週を選択しているか</li>
            <li>ブラウザをリロード（F5キー）してみてください</li>
          </ul>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q5. パスワードを忘れた</div>
        <div class="faq-answer"><strong>A</strong>: 現在、パスワードリセット機能は実装されていません。管理者にお問い合わせください。管理者がユーザー管理画面からパスワードを再設定できます。</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q6. スライドが真っ白で表示されない</div>
        <div class="faq-answer">
          <strong>A</strong>: 以下を試してください:
          <ol style="margin: 10px 0 0 20px;">
            <li>ブラウザをリロード（F5キー）</li>
            <li>ブラウザのキャッシュをクリア</li>
            <li>別のブラウザで試す（Chrome、Firefox、Safariなど）</li>
            <li>それでも解決しない場合は管理者に連絡</li>
          </ol>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q7. メンバー別貢献度に自分の名前が表示されない</div>
        <div class="faq-answer"><strong>A</strong>: ビジター紹介数とリファーラル金額が両方とも0の場合、名前は表示されません。これは意図的な仕様です。</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">Q8. データをバックアップしたい</div>
        <div class="faq-answer"><strong>A</strong>: 管理者は「データ編集」ページからCSVダウンロード機能を使用してバックアップできます。</div>
      </div>
    </div>

    <!-- Section 6: トラブルシューティング -->
    <div id="troubleshooting" class="manual-section">
      <h2>6. トラブルシューティング</h2>

      <h3>ログインできない</h3>
      <p><strong>症状</strong>: メールアドレスとパスワードを入力してもログインできない</p>
      <p><strong>対処法</strong>:</p>
      <ol>
        <li>メールアドレスとパスワードが正しいか確認</li>
        <li>半角/全角、大文字/小文字を確認</li>
        <li>パスワードを忘れた場合は管理者に連絡</li>
        <li>新規登録がまだの場合は「新規登録はこちら」から登録</li>
      </ol>

      <h3>送信ボタンが押せない</h3>
      <p><strong>症状</strong>: アンケートフォームで送信ボタンが押せない、またはグレーアウトしている</p>
      <p><strong>対処法</strong>:</p>
      <ol>
        <li>必須項目（出席状況）が選択されているか確認</li>
        <li>リファーラル金額が数字で入力されているか確認</li>
        <li>ブラウザをリロードして再度入力</li>
      </ol>

      <h3>スライドの表示がおかしい</h3>
      <p><strong>症状</strong>: スライドのレイアウトが崩れている、文字が重なっている</p>
      <p><strong>対処法</strong>:</p>
      <ol>
        <li>ブラウザをリロード（F5キー）</li>
        <li>ズームレベルを100%に設定（Ctrl + 0）</li>
        <li>フルスクリーンモード（Fキー）で表示</li>
        <li>最新のブラウザを使用（Chrome、Firefox、Safari推奨）</li>
      </ol>

      <h3>データが保存されない</h3>
      <p><strong>症状</strong>: マイデータ編集で保存ボタンを押しても保存されない</p>
      <p><strong>対処法</strong>:</p>
      <ol>
        <li>インターネット接続を確認</li>
        <li>ブラウザのJavaScriptが有効になっているか確認</li>
        <li>ブラウザのコンソールにエラーが表示されていないか確認（F12キーで開く）</li>
        <li>管理者に連絡</li>
      </ol>

      <div class="important-box">
        <h4><i class="fas fa-shield-alt"></i> セキュリティに関する注意事項</h4>
        <h5>パスワード管理</h5>
        <ul>
          <li>強力なパスワードを設定してください（8文字以上、英数字記号混在）</li>
          <li>パスワードを他人に教えないでください</li>
          <li>定期的にパスワードを変更してください</li>
        </ul>
        <h5>個人情報の取り扱い</h5>
        <ul>
          <li>このシステムには個人情報（氏名、メールアドレス）が含まれます</li>
          <li>ログイン情報を第三者と共有しないでください</li>
          <li>公共のパソコンを使用した場合は必ずログアウトしてください</li>
        </ul>
      </div>
    </div>

    <!-- Section 7: お問い合わせ -->
    <div id="contact" class="manual-section">
      <h2>7. お問い合わせ</h2>

      <div class="info-box">
        <h4><i class="fas fa-headset"></i> 技術サポート</h4>
        <p><strong>システム開発者</strong>: 山田蓮</p>
        <p><strong>メール</strong>: （管理者から提供されます）</p>
      </div>

      <h3>対応時間</h3>
      <ul>
        <li><strong>平日</strong>: 9:00 - 18:00</li>
        <li><strong>土日祝日</strong>: 対応不可（緊急時のみ）</li>
      </ul>

      <h3>お問い合わせ前の確認事項</h3>
      <ol>
        <li>このマニュアルの「よくある質問」を確認</li>
        <li>「トラブルシューティング」を試す</li>
        <li>エラーメッセージのスクリーンショットを撮影</li>
        <li>発生日時、操作内容を記録</li>
      </ol>

      <h3>システム要件</h3>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>項目</th>
              <th>要件</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>推奨ブラウザ</td>
              <td>Google Chrome（最新版）、Mozilla Firefox（最新版）、Safari（最新版）、Microsoft Edge（最新版）</td>
            </tr>
            <tr>
              <td>非推奨ブラウザ</td>
              <td>Internet Explorer（サポート終了）</td>
            </tr>
            <tr>
              <td>インターネット接続</td>
              <td>常時インターネット接続が必要（Wi-Fiまたは有線LAN推奨）</td>
            </tr>
            <tr>
              <td>画面解像度</td>
              <td>最小: 1024×768 / 推奨: 1920×1080（スライド表示時）</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 60px; padding-top: 30px; border-top: 2px solid #DDD; text-align: center; color: #666;">
      <h3 style="color: #CF2030; margin-bottom: 15px;">付録: 用語集</h3>
      <div style="text-align: left; max-width: 700px; margin: 0 auto;">
        <p><strong>BNI</strong>: Business Network International - 世界最大のビジネスネットワーキング組織</p>
        <p><strong>チャプター</strong>: BNIの地域グループ（宗麟チャプター）</p>
        <p><strong>ビジター</strong>: ミーティングに招待されたゲスト</p>
        <p><strong>リファーラル</strong>: ビジネス紹介、見込み客の紹介</p>
        <p><strong>サンクスリップ</strong>: メンバーへの感謝を伝える用紙</p>
        <p><strong>ワンツーワン (121)</strong>: メンバー同士の個別ミーティング</p>
        <p><strong>Givers Gain®</strong>: BNIの理念「与える者は与えられる」</p>
      </div>
      <div style="margin-top: 30px;">
        <table style="margin: 0 auto; width: auto;">
          <thead>
            <tr>
              <th>バージョン</th>
              <th>日付</th>
              <th>内容</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1.0</td>
              <td>2025-12-05</td>
              <td>初版リリース</td>
            </tr>
            <tr>
              <td>2.0</td>
              <td>2025-12-11</td>
              <td>スライド機能の改善とマイデータ機能追加</td>
            </tr>
            <tr>
              <td>3.0</td>
              <td>2025-12-13</td>
              <td>ユーザー入力項目の変更、管理者機能の大幅拡張（ビジターご紹介管理、ネットワーキング学習コーナー、月間ランキングなど）</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p style="margin-top: 30px; font-size: 14px;">このマニュアルに関するご意見・ご要望は管理者までお寄せください。</p>
      <p style="margin-top: 10px; font-size: 14px; color: #CF2030; font-weight: 600;">Givers Gain® | BNI Slide System</p>
    </div>
    </div><!-- /.manual-container -->
  </div><!-- /.manual-wrapper -->

  <!-- Back to Top Button -->
  <div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
  </div>

  <script>
    // Back to top button functionality
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    });

    backToTop.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Floating TOC Scroll Highlighting
    const sections = document.querySelectorAll('.manual-section');
    const tocLinks = document.querySelectorAll('.toc-link');
    const floatingToc = document.getElementById('floatingToc');
    const tocToggle = document.getElementById('tocToggle');

    // Toggle floating TOC on mobile
    if (tocToggle) {
      tocToggle.addEventListener('click', () => {
        floatingToc.classList.toggle('hidden');
      });
    }

    // Highlight active section in TOC based on scroll position
    function highlightActiveTocItem() {
      let currentSection = '';
      const scrollPosition = window.scrollY + 150;

      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;

        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
          currentSection = section.getAttribute('id');
        }
      });

      tocLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href').substring(1);

        if (href === currentSection) {
          link.classList.add('active');

          // Auto-scroll TOC to show active item
          if (floatingToc && !floatingToc.classList.contains('hidden')) {
            const linkTop = link.offsetTop;
            const tocScrollTop = floatingToc.scrollTop;
            const tocHeight = floatingToc.offsetHeight;

            if (linkTop < tocScrollTop || linkTop > tocScrollTop + tocHeight - 100) {
              floatingToc.scrollTo({
                top: linkTop - 100,
                behavior: 'smooth'
              });
            }
          }
        }
      });
    }

    // Run on scroll with throttle
    let scrollTimeout;
    window.addEventListener('scroll', () => {
      if (scrollTimeout) {
        clearTimeout(scrollTimeout);
      }
      scrollTimeout = setTimeout(highlightActiveTocItem, 50);
    });

    // Initial highlight
    highlightActiveTocItem();
  </script>
</body>
</html>
