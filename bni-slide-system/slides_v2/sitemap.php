<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サイトマップ | BNI Slide System V2</title>

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
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 30px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        /* Container */
        .container {
            max-width: 1600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Stats Section */
        .stats-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .label {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Section */
        .section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #C8102E;
        }

        .section-header h2 {
            font-size: 24px;
            color: #C8102E;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header .icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Link Grid */
        .link-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .link-item {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            border: 2px solid transparent;
        }

        .link-item:hover {
            background: #fff;
            border-color: #C8102E;
            transform: translateX(5px);
        }

        .link-item-content {
            flex: 1;
        }

        .link-item-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .link-item-desc {
            font-size: 13px;
            color: #666;
        }

        .link-item-pages {
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        .link-item-status {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 12px;
        }

        .status-ok {
            background: #28a745;
            color: white;
        }

        .status-error {
            background: #dc3545;
            color: white;
        }

        .status-checking {
            background: #ffc107;
            color: #333;
        }

        /* Phase Badge */
        .phase-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .phase-1 { background: #007bff; color: white; }
        .phase-2 { background: #28a745; color: white; }
        .phase-3 { background: #17a2b8; color: white; }
        .phase-4 { background: #ffc107; color: #333; }
        .phase-5 { background: #6f42c1; color: white; }

        /* Footer */
        .footer {
            text-align: center;
            padding: 30px;
            color: #999;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .link-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #C8102E;
            border-radius: 50%;
            width: 14px;
            height: 14px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-sitemap"></i> サイトマップ</h1>
        <div class="subtitle">BNI Slide System V2 - 全ページ一覧とリンクステータスチェック</div>
    </div>

    <div class="container">
        <!-- 統計情報 -->
        <div class="stats-section">
            <h2 style="color: #C8102E; margin-bottom: 10px;">システム統計</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="number" id="admin-count">18</div>
                    <div class="label">管理画面</div>
                </div>
                <div class="stat-card">
                    <div class="number" id="slide-count">37</div>
                    <div class="label">スライド</div>
                </div>
                <div class="stat-card">
                    <div class="number" id="ok-count">-</div>
                    <div class="label">正常リンク</div>
                </div>
                <div class="stat-card">
                    <div class="number" id="error-count">-</div>
                    <div class="label">エラーリンク</div>
                </div>
            </div>
        </div>

        <!-- Phase 1: メンバー管理 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h2>Phase 1: メンバー管理<span class="phase-badge phase-1">完了</span></h2>
            </div>
            <div class="link-grid">
                <a href="admin/members.php" class="link-item" data-url="admin/members.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-user-circle"></i> メンバー管理</div>
                        <div class="link-item-desc">メンバー情報の追加・編集・削除（48名登録済み）</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- Phase 2: 基本管理画面 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-cogs"></i></div>
                <h2>Phase 2: 基本管理画面<span class="phase-badge phase-2">完了</span></h2>
            </div>
            <div class="link-grid">
                <a href="admin/seating.php" class="link-item" data-url="admin/seating.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-chair"></i> 座席管理</div>
                        <div class="link-item-desc">テーブル配置とメンバーの座席配置（ドラッグ&ドロップ）</div>
                        <div class="link-item-pages">p.7</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/main_presenter.php" class="link-item" data-url="admin/main_presenter.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-presentation"></i> メインプレゼン</div>
                        <div class="link-item-desc">メインプレゼンター設定、PDF・YouTube対応</div>
                        <div class="link-item-pages">p.8, p.204</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/speaker_rotation.php" class="link-item" data-url="admin/speaker_rotation.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-calendar-alt"></i> スピーカーローテーション</div>
                        <div class="link-item-desc">6週分のスピーカーローテーション管理</div>
                        <div class="link-item-pages">p.9-14, p.199-203, p.297-301</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/start_dash.php" class="link-item" data-url="admin/start_dash.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-rocket"></i> スタートダッシュプレゼン</div>
                        <div class="link-item-desc">2分間カウントダウンタイマー付きプレゼン</div>
                        <div class="link-item-pages">p.15, p.107</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- Phase 3: ビジター・メンバー関連 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-user-friends"></i></div>
                <h2>Phase 3: ビジター・メンバー関連<span class="phase-badge phase-3">完了</span></h2>
            </div>
            <div class="link-grid">
                <a href="admin/visitors.php" class="link-item" data-url="admin/visitors.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-user-plus"></i> ビジター管理</div>
                        <div class="link-item-desc">ビジター情報管理、4種類のスライド自動生成</div>
                        <div class="link-item-pages">p.19, p.169-180, p.213-224, p.235</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/substitutes.php" class="link-item" data-url="admin/substitutes.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-user-tie"></i> 代理出席</div>
                        <div class="link-item-desc">代理出席メンバーと代理出席者の管理（最大3名）</div>
                        <div class="link-item-pages">p.22-24</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/new_members.php" class="link-item" data-url="admin/new_members.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-user-check"></i> 新入会メンバー</div>
                        <div class="link-item-desc">新入会メンバーの登録と紹介スライド生成（最大3名）</div>
                        <div class="link-item-pages">p.25-27, p.100-102</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/weekly_no1.php" class="link-item" data-url="admin/weekly_no1.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-trophy"></i> 週間No.1</div>
                        <div class="link-item-desc">外部リファーラル・ビジター招待・1to1の1位</div>
                        <div class="link-item-pages">p.28</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/share_story.php" class="link-item" data-url="admin/share_story.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-comments"></i> シェアストーリー</div>
                        <div class="link-item-desc">シェアストーリー発表者の選択</div>
                        <div class="link-item-pages">p.72</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/happy_birthday.php" class="link-item" data-url="slides/happy_birthday.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-birthday-cake"></i> ハッピーバースデー</div>
                        <div class="link-item-desc">誕生日メンバーの自動表示（管理画面不要）</div>
                        <div class="link-item-pages">p.31</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/member_pitch.php" class="link-item" data-url="slides/member_pitch.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-microphone"></i> メンバーピッチ管理</div>
                        <div class="link-item-desc">33秒ピッチ、不参加メンバーのチェック</div>
                        <div class="link-item-pages">p.112-166</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- Phase 4: チャンピオン・統計関連 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h2>Phase 4: チャンピオン・統計関連<span class="phase-badge phase-4">完了</span></h2>
            </div>
            <div class="link-grid">
                <a href="admin/networking_pdf.php" class="link-item" data-url="admin/networking_pdf.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-file-pdf"></i> ネットワーキング学習</div>
                        <div class="link-item-desc">PDF添付とスライド自動生成</div>
                        <div class="link-item-pages">p.74-85</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/champions.php" class="link-item" data-url="admin/champions.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-medal"></i> チャンピオン管理</div>
                        <div class="link-item-desc">5種類のチャンピオン（リファーラル・バリュー・ビジター・1to1・CEU）</div>
                        <div class="link-item-pages">p.91-96</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/statistics.php" class="link-item" data-url="admin/statistics.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-chart-bar"></i> 統計情報</div>
                        <div class="link-item-desc">ビジター・リファーラル・売上・週次統計</div>
                        <div class="link-item-pages">p.188-190, p.302</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- Phase 5: その他の管理画面 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-ellipsis-h"></i></div>
                <h2>Phase 5: その他の管理画面<span class="phase-badge phase-5">完了</span></h2>
            </div>
            <div class="link-grid">
                <a href="admin/categories.php" class="link-item" data-url="admin/categories.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-tags"></i> 募集カテゴリ管理</div>
                        <div class="link-item-desc">激しく募集中のカテゴリとアンケート結果</div>
                        <div class="link-item-pages">p.185, p.194</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/referral_check.php" class="link-item" data-url="admin/referral_check.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-check-double"></i> リファーラル真正度確認</div>
                        <div class="link-item-desc">リファーラルの真正度チェック</div>
                        <div class="link-item-pages">p.227</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/qr_code.php" class="link-item" data-url="admin/qr_code.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-qrcode"></i> QRコード生成</div>
                        <div class="link-item-desc">アンケートURLからQRコード生成</div>
                        <div class="link-item-pages">p.242</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/slide_visibility.php" class="link-item" data-url="admin/slide_visibility.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-eye"></i> スライド表示管理</div>
                        <div class="link-item-desc">全スライドの表示/非表示切り替え</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- スライド一覧 -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-images"></i></div>
                <h2>スライド一覧<span class="phase-badge phase-2">37枚</span></h2>
            </div>
            <div class="link-grid">
                <a href="slides/main_presenter.php" class="link-item" data-url="slides/main_presenter.php">
                    <div class="link-item-content">
                        <div class="link-item-title">メインプレゼンスライド</div>
                        <div class="link-item-pages">p.8, p.204</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/speaker_rotation.php" class="link-item" data-url="slides/speaker_rotation.php">
                    <div class="link-item-content">
                        <div class="link-item-title">スピーカーローテーションスライド</div>
                        <div class="link-item-pages">p.9-14, p.199-203, p.297-301</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/start_dash.php" class="link-item" data-url="slides/start_dash.php">
                    <div class="link-item-content">
                        <div class="link-item-title">スタートダッシュスライド</div>
                        <div class="link-item-pages">p.15, p.107</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_intro.php" class="link-item" data-url="slides/visitor_intro.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジター紹介スライド</div>
                        <div class="link-item-pages">p.19</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/substitutes.php" class="link-item" data-url="slides/substitutes.php">
                    <div class="link-item-content">
                        <div class="link-item-title">代理出席スライド</div>
                        <div class="link-item-pages">p.22-24</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/new_members.php" class="link-item" data-url="slides/new_members.php">
                    <div class="link-item-content">
                        <div class="link-item-title">新入会メンバースライド</div>
                        <div class="link-item-pages">p.25-27, p.100-102</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/weekly_no1.php" class="link-item" data-url="slides/weekly_no1.php">
                    <div class="link-item-content">
                        <div class="link-item-title">週間No.1スライド</div>
                        <div class="link-item-pages">p.28</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/happy_birthday.php" class="link-item" data-url="slides/happy_birthday.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ハッピーバースデースライド</div>
                        <div class="link-item-pages">p.31</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/share_story.php" class="link-item" data-url="slides/share_story.php">
                    <div class="link-item-content">
                        <div class="link-item-title">シェアストーリースライド</div>
                        <div class="link-item-pages">p.72</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/networking_slides.php" class="link-item" data-url="slides/networking_slides.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ネットワーキング学習スライド</div>
                        <div class="link-item-pages">p.74-85</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/referral_champion.php" class="link-item" data-url="slides/referral_champion.php">
                    <div class="link-item-content">
                        <div class="link-item-title">リファーラルチャンピオンスライド</div>
                        <div class="link-item-pages">p.91</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/value_champion.php" class="link-item" data-url="slides/value_champion.php">
                    <div class="link-item-content">
                        <div class="link-item-title">バリューチャンピオンスライド</div>
                        <div class="link-item-pages">p.92</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_champion.php" class="link-item" data-url="slides/visitor_champion.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジターチャンピオンスライド</div>
                        <div class="link-item-pages">p.93</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/1to1_champion.php" class="link-item" data-url="slides/1to1_champion.php">
                    <div class="link-item-content">
                        <div class="link-item-title">1to1チャンピオンスライド</div>
                        <div class="link-item-pages">p.94</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/ceu_champion.php" class="link-item" data-url="slides/ceu_champion.php">
                    <div class="link-item-content">
                        <div class="link-item-title">CEUチャンピオンスライド</div>
                        <div class="link-item-pages">p.95</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/all_champions.php" class="link-item" data-url="slides/all_champions.php">
                    <div class="link-item-content">
                        <div class="link-item-title">各チャンピオン一覧スライド</div>
                        <div class="link-item-pages">p.96</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/renewal.php" class="link-item" data-url="slides/renewal.php">
                    <div class="link-item-content">
                        <div class="link-item-title">更新メンバースライド</div>
                        <div class="link-item-pages">p.98, p.229</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/member_pitch.php" class="link-item" data-url="slides/member_pitch.php">
                    <div class="link-item-content">
                        <div class="link-item-title">メンバーピッチスライド</div>
                        <div class="link-item-pages">p.112-166</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_self_intro.php" class="link-item" data-url="slides/visitor_self_intro.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジター自己紹介スライド</div>
                        <div class="link-item-pages">p.169-180</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/business_breakout.php" class="link-item" data-url="slides/business_breakout.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジネスブレイクアウトスライド</div>
                        <div class="link-item-pages">p.184</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/recruiting_categories.php" class="link-item" data-url="slides/recruiting_categories.php">
                    <div class="link-item-content">
                        <div class="link-item-title">募集カテゴリスライド</div>
                        <div class="link-item-pages">p.185</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_stats.php" class="link-item" data-url="slides/visitor_stats.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジター統計スライド</div>
                        <div class="link-item-pages">p.188</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/referral_stats.php" class="link-item" data-url="slides/referral_stats.php">
                    <div class="link-item-content">
                        <div class="link-item-title">リファーラル統計スライド</div>
                        <div class="link-item-pages">p.189</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/sales_stats.php" class="link-item" data-url="slides/sales_stats.php">
                    <div class="link-item-content">
                        <div class="link-item-title">売上統計スライド</div>
                        <div class="link-item-pages">p.190</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/category_survey.php" class="link-item" data-url="slides/category_survey.php">
                    <div class="link-item-content">
                        <div class="link-item-title">募集カテゴリアンケートスライド</div>
                        <div class="link-item-pages">p.194</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_feedback.php" class="link-item" data-url="slides/visitor_feedback.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジター感想スライド</div>
                        <div class="link-item-pages">p.213-224</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/referral_verification.php" class="link-item" data-url="slides/referral_verification.php">
                    <div class="link-item-content">
                        <div class="link-item-title">リファーラル真正度確認スライド</div>
                        <div class="link-item-pages">p.227</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/visitor_thanks.php" class="link-item" data-url="slides/visitor_thanks.php">
                    <div class="link-item-content">
                        <div class="link-item-title">ビジター感謝スライド</div>
                        <div class="link-item-pages">p.235</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/qr_code.php" class="link-item" data-url="slides/qr_code.php">
                    <div class="link-item-content">
                        <div class="link-item-title">QRコードスライド</div>
                        <div class="link-item-pages">p.242</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="slides/weekly_stats.php" class="link-item" data-url="slides/weekly_stats.php">
                    <div class="link-item-content">
                        <div class="link-item-title">週次統計スライド</div>
                        <div class="link-item-pages">p.302</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>

        <!-- その他のページ -->
        <div class="section">
            <div class="section-header">
                <div class="icon"><i class="fas fa-home"></i></div>
                <h2>その他のページ</h2>
            </div>
            <div class="link-grid">
                <a href="index.php" class="link-item" data-url="index.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-play-circle"></i> スライド表示画面</div>
                        <div class="link-item-desc">全スライドの統合表示画面</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="admin/index.php" class="link-item" data-url="admin/index.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-tachometer-alt"></i> 管理画面ダッシュボード</div>
                        <div class="link-item-desc">管理画面のトップページ</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="sitemap.php" class="link-item" data-url="sitemap.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-sitemap"></i> サイトマップ（このページ）</div>
                        <div class="link-item-desc">全ページ一覧とリンクチェック</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
                <a href="manual.php" class="link-item" data-url="manual.php">
                    <div class="link-item-content">
                        <div class="link-item-title"><i class="fas fa-book"></i> クライアント用マニュアル</div>
                        <div class="link-item-desc">システムの使い方マニュアル</div>
                    </div>
                    <div class="link-item-status status-checking"><div class="spinner"></div></div>
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        BNI Slide System V2 - Sitemap &copy; 2024 | Powered by PHP & SQLite3
    </div>

    <script>
        // ページ読み込み完了時にリンクチェック開始
        document.addEventListener('DOMContentLoaded', function() {
            const linkItems = document.querySelectorAll('.link-item[data-url]');
            let okCount = 0;
            let errorCount = 0;

            // 各リンクをチェック
            linkItems.forEach(function(item) {
                const url = item.getAttribute('data-url');
                const statusEl = item.querySelector('.link-item-status');

                // HEAD リクエストでファイル存在チェック
                fetch(url, { method: 'HEAD' })
                    .then(function(response) {
                        if (response.ok) {
                            statusEl.innerHTML = '<i class="fas fa-check"></i>';
                            statusEl.className = 'link-item-status status-ok';
                            okCount++;
                        } else {
                            statusEl.innerHTML = '<i class="fas fa-times"></i>';
                            statusEl.className = 'link-item-status status-error';
                            errorCount++;
                        }
                        updateStats();
                    })
                    .catch(function() {
                        statusEl.innerHTML = '<i class="fas fa-times"></i>';
                        statusEl.className = 'link-item-status status-error';
                        errorCount++;
                        updateStats();
                    });
            });

            function updateStats() {
                document.getElementById('ok-count').textContent = okCount;
                document.getElementById('error-count').textContent = errorCount;
            }
        });
    </script>
</body>
</html>
