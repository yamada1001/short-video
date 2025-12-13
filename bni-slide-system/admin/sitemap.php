<?php
/**
 * BNI Slide System - Admin Sitemap (Admin Only)
 * 管理者専用 - サイトマップ
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
  <title>サイトマップ（管理者用） | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <style>
    .sitemap-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .sitemap-header {
      text-align: center;
      margin-bottom: 50px;
      padding-bottom: 30px;
      border-bottom: 3px solid var(--bni-red);
    }

    .sitemap-header h1 {
      font-size: 32px;
      color: var(--bni-red);
      margin-bottom: 10px;
    }

    .sitemap-header p {
      color: #666;
      font-size: 16px;
    }

    .category-section {
      background: white;
      border-radius: 12px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .category-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }

    .category-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--bni-red), #e01020);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
    }

    .category-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--bni-red);
      margin: 0;
    }

    .page-list {
      display: grid;
      gap: 20px;
    }

    .page-item {
      display: flex;
      align-items: flex-start;
      gap: 20px;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 8px;
      border-left: 4px solid var(--bni-red);
      transition: all 0.3s ease;
    }

    .page-item:hover {
      background: #f0f0f0;
      transform: translateX(5px);
      box-shadow: 0 2px 8px rgba(207, 32, 48, 0.1);
    }

    .page-icon {
      width: 40px;
      height: 40px;
      background: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--bni-red);
      font-size: 20px;
      flex-shrink: 0;
    }

    .page-content {
      flex: 1;
    }

    .page-name {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    .page-name a {
      color: var(--bni-red);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .page-name a:hover {
      color: #e01020;
      text-decoration: underline;
    }

    .page-description {
      color: #666;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 8px;
    }

    .page-path {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      color: #999;
      background: white;
      padding: 4px 8px;
      border-radius: 4px;
      display: inline-block;
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 8px;
    }

    .badge-user {
      background: #D4EDDA;
      color: #155724;
    }

    .badge-admin {
      background: #FFF3CD;
      color: #856404;
    }

    .badge-api {
      background: #D1ECF1;
      color: #0C5460;
    }

    .badge-public {
      background: #F8D7DA;
      color: #721C24;
    }

    .badge-new {
      background: #FF6B00;
      color: white;
      animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
      font-size: 36px;
      font-weight: 700;
      color: var(--bni-red);
      margin-bottom: 5px;
    }

    .stat-label {
      color: #666;
      font-size: 14px;
    }

    .back-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: var(--bni-red);
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 30px;
    }

    .back-button:hover {
      background: #e01020;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(207, 32, 48, 0.3);
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
          <li><a href="bulk_input.php">一括入力</a></li>
          <li><a href="visitor_intro.php">ビジター紹介</a></li>
          <li><a href="networking_learning.php">学習コーナー</a></li>
          <li><a href="monthly_ranking.php">月間ランキング</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="sitemap.php" class="active">サイトマップ</a></li>
          <li><a href="../logout.php" style="color: #999;">ログアウト</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main-content">
    <div class="container">
      <div class="sitemap-container">

        <!-- Header -->
        <div class="sitemap-header">
          <h1><i class="fas fa-sitemap"></i> サイトマップ（管理者用）</h1>
          <p>BNI Slide Systemの全ページ・機能一覧です</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number">10</div>
            <div class="stat-label">ユーザーページ</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">14</div>
            <div class="stat-label">管理者ページ</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">13</div>
            <div class="stat-label">APIエンドポイント</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">37</div>
            <div class="stat-label">合計ページ数</div>
          </div>
        </div>

        <!-- Category: User Pages -->
        <div class="category-section">
          <div class="category-header">
            <div class="category-icon">
              <i class="fas fa-user"></i>
            </div>
            <h2 class="category-title">一般ユーザー向けページ</h2>
          </div>
          <div class="page-list">

            <!-- index.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../index.php" target="_blank">週次アンケートフォーム</a>
                  <span class="badge badge-user">ユーザー</span>
                </div>
                <div class="page-description">
                  BNI週次アンケートの入力フォーム。ビジター紹介情報とピッチプレゼンテーション資料（PDF/YouTube URL）を入力。名前・メールアドレスはログイン情報から自動入力。出席状況・リファーラル・サンクスリップなどは管理者が一括入力画面から入力します。
                </div>
                <div class="page-path">index.php</div>
              </div>
            </div>

            <!-- profile.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-user-edit"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../profile.php" target="_blank">プロフィール編集</a>
                  <span class="badge badge-user">ユーザー</span>
                </div>
                <div class="page-description">
                  ユーザー自身のプロフィール情報を編集。名前、メールアドレス、電話番号、会社名、カテゴリ、パスワードを変更可能。
                </div>
                <div class="page-path">profile.php</div>
              </div>
            </div>

            <!-- register.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../register.php" target="_blank">新規ユーザー登録</a>
                  <span class="badge badge-public">公開</span>
                </div>
                <div class="page-description">
                  新規ユーザーの登録フォーム。名前、メールアドレス、電話番号、会社名、カテゴリを入力。パスワードは自動生成され、メールで送信。
                </div>
                <div class="page-path">register.php</div>
              </div>
            </div>

            <!-- login.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-sign-in-alt"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../login.php" target="_blank">ログイン</a>
                  <span class="badge badge-public">公開</span>
                </div>
                <div class="page-description">
                  ユーザーログインページ。メールアドレスとパスワードで認証。2段階認証にも対応。
                </div>
                <div class="page-path">login.php</div>
              </div>
            </div>

            <!-- logout.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-sign-out-alt"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../logout.php" target="_blank">ログアウト</a>
                  <span class="badge badge-user">ユーザー</span>
                </div>
                <div class="page-description">
                  ログアウト処理。セッションを破棄してログインページにリダイレクト。
                </div>
                <div class="page-path">logout.php</div>
              </div>
            </div>

            <!-- my-data.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-database"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../my-data.php" target="_blank">マイデータ</a>
                  <span class="badge badge-user">ユーザー</span>
                </div>
                <div class="page-description">
                  自分が過去に提出したアンケートデータを一覧表示。週ごとの実績を確認可能。
                </div>
                <div class="page-path">my-data.php</div>
              </div>
            </div>

            <!-- edit-my-data.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-edit"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../edit-my-data.php" target="_blank">マイデータ編集</a>
                  <span class="badge badge-user">ユーザー</span>
                </div>
                <div class="page-description">
                  自分が提出したアンケートデータを編集。過去のデータを修正・更新可能。
                </div>
                <div class="page-path">edit-my-data.php</div>
              </div>
            </div>

            <!-- forgot-password.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-question-circle"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../forgot-password.php" target="_blank">パスワード忘れ</a>
                  <span class="badge badge-public">公開</span>
                </div>
                <div class="page-description">
                  パスワードリセット依頼フォーム。メールアドレスを入力してリセットリンクを送信。
                </div>
                <div class="page-path">forgot-password.php</div>
              </div>
            </div>

            <!-- reset-password.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-key"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="../reset-password.php" target="_blank">パスワードリセット</a>
                  <span class="badge badge-public">公開</span>
                </div>
                <div class="page-description">
                  新しいパスワード設定ページ。リセットトークンを検証して新パスワードを設定。CSRF対策実装済み。
                </div>
                <div class="page-path">reset-password.php</div>
              </div>
            </div>

          </div>
        </div>

        <!-- Category: Admin Pages -->
        <div class="category-section">
          <div class="category-header">
            <div class="category-icon">
              <i class="fas fa-user-shield"></i>
            </div>
            <h2 class="category-title">管理者向けページ</h2>
          </div>
          <div class="page-list">

            <!-- admin/slide.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-presentation"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="slide.php" target="_blank">週次レポート（スライド表示）</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  週次ミーティング用スライドをReveal.js形式で表示。週選択、PDF出力、タイトルスライド、サマリー、ビジター紹介、ピッチプレゼンテーション（PDF/YouTube埋め込み）、ビジターご紹介スライド、ネットワーキング学習コーナー、月間ランキングなど。コントロールパネル（歯車アイコン）から週選択とPDF出力が可能。
                </div>
                <div class="page-path">admin/slide.php</div>
              </div>
            </div>

            <!-- admin/migrate.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-database"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="migrate.php" target="_blank">データベースマイグレーション</a>
                  <span class="badge badge-admin">管理者</span>
                  <span class="badge badge-new">NEW</span>
                </div>
                <div class="page-description">
                  Phase 10実装で追加された新機能のためのデータベース更新をワンクリックで実行。VP統計情報テーブル更新、メンバー写真テーブル作成、テストデータ投入を自動実行。
                </div>
                <div class="page-path">admin/migrate.php</div>
              </div>
            </div>

            <!-- admin/edit.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-edit"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="edit.php" target="_blank">データ編集</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  週次アンケートデータを編集。CSVファイルの行単位での編集・削除が可能。入力日、紹介者名、メールアドレス、ビジター情報、リファーラル情報など11列表示。
                </div>
                <div class="page-path">admin/edit.php</div>
              </div>
            </div>

            <!-- admin/users.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-users-cog"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="users.php" target="_blank">ユーザー管理</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  登録ユーザーの一覧表示と管理。ユーザー名、名前、メールアドレス、会社名、カテゴリ、電話番号、登録日、最終更新日を表示。削除機能（開発中）。
                </div>
                <div class="page-path">admin/users.php</div>
              </div>
            </div>

            <!-- admin/bulk_input.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-list-check"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="bulk_input.php" target="_blank">一括入力</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  週を選択して全メンバーの出席状況、サンクスリップ、ワンツーワン（121）、アクティビティ、コメントを一括入力。座席表に登録されているメンバーのみ表示。各メンバーのフォームが縦並びで表示され、一括保存ボタンで保存。
                </div>
                <div class="page-path">admin/bulk_input.php</div>
              </div>
            </div>

            <!-- admin/referrals.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="referrals.php" target="_blank">リファーラル管理</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  週ごとのリファーラル総額を管理。金額はカンマ区切りで表示され、スライドに反映されます。
                </div>
                <div class="page-path">admin/referrals.php</div>
              </div>
            </div>

            <!-- admin/seating.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-chair"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="seating.php" target="_blank">座席表編集</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  チャプターミーティングの座席配置をドラッグ&ドロップで編集。8テーブル固定、最大7名/テーブル。
                </div>
                <div class="page-path">admin/seating.php</div>
              </div>
            </div>

            <!-- admin/maintenance_toggle.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-wrench"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="maintenance_toggle.php" target="_blank">メンテナンスモード設定</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  システムのメンテナンスモードをON/OFFできます。テスト中は有効にすることで、指定したメールアドレス以外のアクセスをブロックします。
                </div>
                <div class="page-path">admin/maintenance_toggle.php</div>
              </div>
            </div>

            <!-- admin/visitor_intro.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-image"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="visitor_intro.php" target="_blank">ビジターご紹介管理</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  スライドに表示するビジター紹介用の画像をアップロード・管理。週ごとに複数枚の画像を登録可能。画像はスライドの「ビジターご紹介」セクションに表示されます。推奨サイズ: 1920x1080px（フルHD）。
                </div>
                <div class="page-path">admin/visitor_intro.php</div>
              </div>
            </div>

            <!-- admin/networking_learning.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="networking_learning.php" target="_blank">ネットワーキング学習コーナー管理</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  スライドに表示する学習コンテンツ用の画像をアップロード・管理。週ごとに複数枚の画像を登録可能。画像はスライドの「ネットワーキング学習コーナー」セクションに表示されます。推奨サイズ: 1920x1080px（フルHD）。
                </div>
                <div class="page-path">admin/networking_learning.php</div>
              </div>
            </div>

            <!-- admin/monthly_ranking.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-trophy"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="monthly_ranking.php" target="_blank">月間ランキング管理</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  月初のスライドで表示される先月のトップ貢献者ランキングを管理。各メンバーのビジター紹介数、リファーラル金額、ワンツーワン数を入力。月初のスライドで自動的にランキング表示されます。上位3名がメダルで表彰。
                </div>
                <div class="page-path">admin/monthly_ranking.php</div>
              </div>
            </div>

            <!-- admin/sitemap.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-sitemap"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  <a href="sitemap.php" target="_blank">サイトマップ（管理者用）</a>
                  <span class="badge badge-admin">管理者</span>
                </div>
                <div class="page-description">
                  このページ。システム全体のページ・機能一覧を表示。
                </div>
                <div class="page-path">admin/sitemap.php</div>
              </div>
            </div>

          </div>
        </div>

        <!-- Category: API Endpoints -->
        <div class="category-section">
          <div class="category-header">
            <div class="category-icon">
              <i class="fas fa-plug"></i>
            </div>
            <h2 class="category-title">APIエンドポイント</h2>
          </div>
          <div class="page-list">

            <!-- api_save.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-save"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_save.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  週次アンケートデータの保存API。フォームデータを受け取り、CSVファイルに保存。管理者・ユーザーにメール通知。複数ビジター・リファーラル対応。
                </div>
                <div class="page-path">api_save.php</div>
              </div>
            </div>

            <!-- api_load.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-download"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_load.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  週次データの読み込みAPI。指定された週のCSVファイルを読み込み、統計計算を行い、JSON形式で返却。スライド生成で使用。
                </div>
                <div class="page-path">api_load.php</div>
              </div>
            </div>

            <!-- api_list_weeks.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_list_weeks.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  利用可能な週のリストを取得するAPI。CSVファイルから週情報を抽出し、金曜日の日付を計算して返却。未来の週は除外。
                </div>
                <div class="page-path">api_list_weeks.php</div>
              </div>
            </div>

            <!-- api_update.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-sync"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_update.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  データ編集ページからのCSV更新API。行単位での更新・削除を処理。16フィールド構造に対応。
                </div>
                <div class="page-path">api_update.php</div>
              </div>
            </div>

            <!-- api_register.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_register.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  新規ユーザー登録API（SQLite版）。ユーザー情報をSQLiteデータベースに保存。パスワードを自動生成し、ウェルカムメール送信。
                </div>
                <div class="page-path">api_register.php</div>
              </div>
            </div>

            <!-- api_update_profile.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-user-edit"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_update_profile.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  プロフィール更新API（SQLite版）。ユーザー情報（名前、メールアドレス、電話番号、会社名、カテゴリ）をSQLiteデータベースに保存。
                </div>
                <div class="page-path">api_update_profile.php</div>
              </div>
            </div>

            <!-- api_dashboard_stats.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_dashboard_stats.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  ダッシュボード統計データ取得API。今週・先週・今月の個人実績とチーム統計を返却。
                </div>
                <div class="page-path">api_dashboard_stats.php</div>
              </div>
            </div>

            <!-- api_load_my_data.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-database"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_load_my_data.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  自分のアンケートデータ読み込みAPI。過去に提出した全データを取得。
                </div>
                <div class="page-path">api_load_my_data.php</div>
              </div>
            </div>

            <!-- api_update_my_data.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-edit"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_update_my_data.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  自分のアンケートデータ更新API。過去データの編集・修正を処理。
                </div>
                <div class="page-path">api_update_my_data.php</div>
              </div>
            </div>

            <!-- api_send_reset_email.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_send_reset_email.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  パスワードリセットメール送信API。リセットトークンを生成してメール送信。
                </div>
                <div class="page-path">api_send_reset_email.php</div>
              </div>
            </div>

            <!-- api_reset_password.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-key"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_reset_password.php <span class="badge badge-api">POST API</span>
                </div>
                <div class="page-description">
                  パスワードリセット実行API。トークン検証後、新パスワードを設定。CSRF対策実装済み。
                </div>
                <div class="page-path">api_reset_password.php</div>
              </div>
            </div>

            <!-- api_members.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_members.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  メンバー一覧取得API。登録ユーザーの情報を取得。
                </div>
                <div class="page-path">api_members.php</div>
              </div>
            </div>

            <!-- api_export_csv.php -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-file-export"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  api_export_csv.php <span class="badge badge-api">GET API</span>
                </div>
                <div class="page-description">
                  CSVエクスポートAPI。SQLiteデータベースから指定週のデータをCSV形式で出力。
                </div>
                <div class="page-path">api_export_csv.php</div>
              </div>
            </div>

          </div>
        </div>

        <!-- Category: Data Files -->
        <div class="category-section">
          <div class="category-header">
            <div class="category-icon">
              <i class="fas fa-database"></i>
            </div>
            <h2 class="category-title">データファイル</h2>
          </div>
          <div class="page-list">

            <!-- members.json -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-file-code"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  members.json
                </div>
                <div class="page-description">
                  ユーザー情報のバックアップJSONファイル。SQLite移行後は参照用。データ移行時のソースとして使用。
                </div>
                <div class="page-path">data/members.json</div>
              </div>
            </div>

            <!-- CSV Files -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-file-csv"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  週次データ（CSV）
                </div>
                <div class="page-description">
                  週次アンケートデータを保存するCSVファイル。ファイル名形式: YYYY-MM-W.csv（例: 2025-12-1.csv）。16フィールド構造。
                </div>
                <div class="page-path">data/*.csv</div>
              </div>
            </div>

            <!-- SQLite Database -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-database"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  SQLiteデータベース
                </div>
                <div class="page-description">
                  メインデータベース（SQLite3）。ユーザー情報、週次アンケートデータ、ビジター、リファーラル、監査ログを管理。WALモード有効化。
                </div>
                <div class="page-path">data/bni_system.db</div>
              </div>
            </div>

            <!-- .htpasswd -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-key"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  .htpasswd（認証ファイル）
                </div>
                <div class="page-description">
                  ユーザー認証情報を保存。メールアドレスをユーザー名として使用。パスワードはAPR1-MD5形式でハッシュ化。
                </div>
                <div class="page-path">.htpasswd</div>
              </div>
            </div>

          </div>
        </div>

        <!-- Category: Documentation -->
        <div class="category-section">
          <div class="category-header">
            <div class="category-icon">
              <i class="fas fa-book"></i>
            </div>
            <h2 class="category-title">ドキュメント</h2>
          </div>
          <div class="page-list">

            <!-- README.md -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  README.md
                </div>
                <div class="page-description">
                  システム概要、機能説明、セットアップ手順などを記載したドキュメント。
                </div>
                <div class="page-path">README.md</div>
              </div>
            </div>

            <!-- IMPORTANT_NOTES.md -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  IMPORTANT_NOTES.md
                </div>
                <div class="page-description">
                  重要な注意事項。Basic認証を追加してはいけない理由など、システム運用上の重要情報。
                </div>
                <div class="page-path">IMPORTANT_NOTES.md</div>
              </div>
            </div>

          </div>
        </div>

        <!-- Back Button -->
        <div style="text-align: center;">
          <a href="users.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
            ユーザー管理に戻る
          </a>
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

</body>
</html>
