<?php
/**
 * BNI Slide System - Admin Sitemap
 * 管理者専用サイトマップ（全ページ・機能一覧）
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
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
  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <div class="site-logo">BNI Slide System - Admin</div>
      <nav class="site-nav">
        <ul>
          <li><a href="slide.php">スライド表示</a></li>
          <li><a href="edit.php">編集</a></li>
          <li><a href="users.php">ユーザー管理</a></li>
          <li><a href="sitemap.php" class="active">サイトマップ</a></li>
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
            <div class="stat-number">6</div>
            <div class="stat-label">ユーザーページ</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">4</div>
            <div class="stat-label">管理者ページ</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">5</div>
            <div class="stat-label">APIエンドポイント</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">15</div>
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
                  BNI週次アンケートの入力フォーム。ビジター紹介、リファーラル情報、出席状況などを入力。名前・メールアドレスはログイン情報から自動入力。
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
                  週次アンケートデータをReveal.jsスライド形式で表示。週選択、タイトルスライド、サマリー、ビジター紹介、リファーラル詳細、メンバーピッチなど。
                </div>
                <div class="page-path">admin/slide.php</div>
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
                  新規ユーザー登録API。ユーザー情報をmembers.jsonと.htpasswdに保存。パスワードを自動生成し、ウェルカムメール送信。
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
                  プロフィール更新API。ユーザー情報（名前、メールアドレス、電話番号、会社名、カテゴリ、パスワード）をmembers.jsonと.htpasswdに保存。
                </div>
                <div class="page-path">api_update_profile.php</div>
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
                  ユーザー情報を保存するJSONファイル。名前、メールアドレス、電話番号、会社名、カテゴリ、認証情報、作成日時、更新日時を保存。
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

            <!-- WORK_LOG -->
            <div class="page-item">
              <div class="page-icon">
                <i class="fas fa-history"></i>
              </div>
              <div class="page-content">
                <div class="page-name">
                  作業ログ（WORK_LOG_*.md）
                </div>
                <div class="page-description">
                  日次の作業内容を記録したログファイル。実施した作業、変更内容、コミット履歴などを記載。
                </div>
                <div class="page-path">WORK_LOG_2025-12-05.md など</div>
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
