<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面 | BNI Slide System V2</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 30px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 8px;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
        }

        .welcome-section h2 {
            color: #C8102E;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .welcome-section .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 25px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #C8102E;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        /* Phase Section */
        .phase-section {
            margin-bottom: 40px;
        }

        .phase-header {
            font-size: 18px;
            color: #C8102E;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #C8102E;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .menu-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .menu-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .menu-card-icon i {
            font-size: 28px;
            color: white;
        }

        .menu-card h3 {
            font-size: 18px;
            color: #C8102E;
            margin-bottom: 10px;
        }

        .menu-card p {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .menu-card .pages {
            font-size: 12px;
            color: #999;
            font-weight: 500;
        }

        /* Quick Links */
        .quick-links {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 40px;
        }

        .quick-links h3 {
            color: #C8102E;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .quick-link-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-link-btn {
            display: block;
            padding: 15px 20px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s;
        }

        .quick-link-btn:hover {
            background: #C8102E;
            color: white;
            border-color: #C8102E;
        }

        .quick-link-btn i {
            margin-right: 8px;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }
            .welcome-section .stats {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-tachometer-alt"></i> BNI スライドシステム V2 管理画面</h1>
        <div class="subtitle">Slide Management Dashboard - 全18画面実装完了</div>
    </div>

    <div class="container">
        <div class="welcome-section">
            <h2>管理画面へようこそ</h2>
            <p>BNIスライドシステムV2の管理画面です。<br>各項目をクリックして、メンバー情報やスライド設定を管理してください。</p>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">18</div>
                    <div class="stat-label">管理画面</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">48</div>
                    <div class="stat-label">メンバー</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30+</div>
                    <div class="stat-label">スライド種類</div>
                </div>
            </div>
        </div>

        <!-- クイックリンク -->
        <div class="quick-links">
            <h3><i class="fas fa-rocket"></i> クイックアクセス</h3>
            <div class="quick-link-grid">
                <a href="../index.php" class="quick-link-btn" target="_blank">
                    <i class="fas fa-play-circle"></i> スライドショー起動
                </a>
                <a href="../sitemap.php" class="quick-link-btn" target="_blank">
                    <i class="fas fa-sitemap"></i> サイトマップ
                </a>
                <a href="../manual.php" class="quick-link-btn" target="_blank">
                    <i class="fas fa-book"></i> マニュアル
                </a>
                <a href="../test_integration.php" class="quick-link-btn" target="_blank">
                    <i class="fas fa-vial"></i> 統合テスト
                </a>
            </div>
        </div>

        <!-- Phase 1-2: 基本管理 -->
        <div class="phase-section">
            <div class="phase-header"><i class="fas fa-layer-group"></i> Phase 1-2: 基本管理（5画面）</div>
            <div class="menu-grid">
                <!-- 1. メンバー管理 -->
                <a href="members.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>メンバー管理</h3>
                    <p>メンバーの追加・編集・削除を行います。名前、会社名、カテゴリ、写真などを管理できます。</p>
                    <div class="pages">全48名登録済み</div>
                </a>

                <!-- 2. 座席管理 -->
                <a href="seating.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-chair"></i>
                    </div>
                    <h3>座席管理</h3>
                    <p>6つのテーブル（A-F）の座席配置をドラッグ&ドロップで管理します。座席表スライド（p.7）を自動生成。</p>
                    <div class="pages">p.7</div>
                </a>

                <!-- 3. メインプレゼン管理 -->
                <a href="main_presenter.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>メインプレゼン管理</h3>
                    <p>メインプレゼンターとテーマを設定します。p.8とp.204の2ページに対応。</p>
                    <div class="pages">p.8, p.204</div>
                </a>

                <!-- 4. スピーカーローテーション管理 -->
                <a href="speaker_rotation.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3>スピーカーローテーション管理</h3>
                    <p>5つの役割（司会、書記、タイマー、ビジターホスト、会計）のローテーションを管理。3箇所のスライドを自動生成。</p>
                    <div class="pages">p.9-14, p.199-203, p.297-301</div>
                </a>

                <!-- 5. スタートダッシュ管理 -->
                <a href="start_dash.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>スタートダッシュ管理</h3>
                    <p>p.15とp.107のスタートダッシュプレゼンターを設定します。2分間のカウントダウンタイマー付きです。</p>
                    <div class="pages">p.15, p.107</div>
                </a>
            </div>
        </div>

        <!-- Phase 3: ビジター・メンバー関連 -->
        <div class="phase-section">
            <div class="phase-header"><i class="fas fa-user-friends"></i> Phase 3: ビジター・メンバー関連（6画面）</div>
            <div class="menu-grid">
                <!-- 6. ビジター管理 -->
                <a href="visitors.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>ビジター管理</h3>
                    <p>ビジター情報を管理し、4種類のスライドを自動生成します。紹介、自己紹介（23秒タイマー）、感想、感謝のスライドに対応。</p>
                    <div class="pages">p.19, p.169-180, p.213-224, p.235</div>
                </a>

                <!-- 7. 代理出席管理 -->
                <a href="substitutes.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3>代理出席管理</h3>
                    <p>代理出席者の情報を管理します。本人と代理者の情報を設定し、p.22-24のスライドを自動生成。</p>
                    <div class="pages">p.22-24</div>
                </a>

                <!-- 8. 新入会メンバー管理 -->
                <a href="new_members.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>新入会メンバー管理</h3>
                    <p>新入会メンバーの紹介スライドを管理。2箇所のスライド（p.25-27, p.100-102）を自動生成します。</p>
                    <div class="pages">p.25-27, p.100-102</div>
                </a>

                <!-- 9. 週間No.1管理 -->
                <a href="weekly_no1.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>週間No.1管理</h3>
                    <p>週間No.1メンバーとその実績を設定します。p.28のスライドを自動生成。</p>
                    <div class="pages">p.28</div>
                </a>

                <!-- 10. シェアストーリー管理 -->
                <a href="share_story.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>シェアストーリー管理</h3>
                    <p>シェアストーリーの発表者とタイトルを設定します。5分間のカウントダウンタイマー付き（p.72）。</p>
                    <div class="pages">p.72</div>
                </a>

                <!-- 11. 更新メンバー管理 -->
                <a href="renewal.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-redo"></i>
                    </div>
                    <h3>更新メンバー管理</h3>
                    <p>更新メンバーの情報を管理。2箇所のスライド（p.98, p.229）を自動生成します。</p>
                    <div class="pages">p.98, p.229</div>
                </a>
            </div>
        </div>

        <!-- Phase 4: チャンピオン・統計 -->
        <div class="phase-section">
            <div class="phase-header"><i class="fas fa-award"></i> Phase 4: チャンピオン・統計（3画面）</div>
            <div class="menu-grid">
                <!-- 12. ネットワーキング学習管理 -->
                <a href="networking_pdf.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3>ネットワーキング学習管理</h3>
                    <p>PDFをアップロードし、自動的に画像に変換してp.74-85のスライドを生成します。Python3とPyMuPDFが必要です。</p>
                    <div class="pages">p.74-85（最大12ページ）</div>
                </a>

                <!-- 13. チャンピオン管理 -->
                <a href="champions.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>チャンピオン管理</h3>
                    <p>5種類のチャンピオン（リファーラル、バリュー、ビジター、1to1、CEU）の1位～3位を管理。p.91-96の6ページを自動生成。</p>
                    <div class="pages">p.91-96</div>
                </a>

                <!-- 14. 統計情報管理 -->
                <a href="statistics.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>統計情報管理</h3>
                    <p>ビジター、リファーラル、売上の統計情報を管理。4種類のスライド（p.188-190, p.302）を自動生成します。</p>
                    <div class="pages">p.188-190, p.302</div>
                </a>
            </div>
        </div>

        <!-- Phase 5: その他 -->
        <div class="phase-section">
            <div class="phase-header"><i class="fas fa-cog"></i> Phase 5: その他の機能（4画面）</div>
            <div class="menu-grid">
                <!-- 15. 募集カテゴリ管理 -->
                <a href="categories.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>募集カテゴリ管理</h3>
                    <p>激しく募集中のカテゴリとアンケート結果を管理。2ページのスライド（p.185, p.194）を自動生成します。</p>
                    <div class="pages">p.185, p.194</div>
                </a>

                <!-- 16. リファーラル真正度管理 -->
                <a href="referral_check.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h3>リファーラル真正度管理</h3>
                    <p>リファーラルの真正度確認情報を管理。p.227のスライドを自動生成します。</p>
                    <div class="pages">p.227</div>
                </a>

                <!-- 17. QRコード管理 -->
                <a href="qr_code.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3>QRコード管理</h3>
                    <p>アンケート用QRコードのURLとテキストを設定。Google Charts APIを使用してQRコードを生成（p.242）。</p>
                    <div class="pages">p.242</div>
                </a>

                <!-- 18. スライド表示/非表示管理 -->
                <a href="slide_visibility.php" class="menu-card">
                    <div class="menu-card-icon">
                        <i class="fas fa-eye-slash"></i>
                    </div>
                    <h3>スライド表示/非表示管理</h3>
                    <p>削除されたスライド（9ページ）の管理と、将来的なスライドの表示/非表示設定を管理します。</p>
                    <div class="pages">p.32, 37, 88, 106, 109-110, 192-193, 195</div>
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        BNI Slide System V2 &copy; 2024 | Powered by PHP & SQLite3 | 全18画面実装完了
    </div>
</body>
</html>
