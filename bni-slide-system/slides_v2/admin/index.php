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

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
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
            font-size: 20px;
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-new {
            background: #28a745;
            color: white;
        }

        .badge-complete {
            background: #007bff;
            color: white;
        }

        .badge-pending {
            background: #ffc107;
            color: #333;
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
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-tachometer-alt"></i> BNI スライドシステム V2 管理画面</h1>
        <div class="subtitle">Slide Management Dashboard</div>
    </div>

    <div class="container">
        <div class="welcome-section">
            <h2>管理画面へようこそ</h2>
            <p>BNIスライドシステムV2の管理画面です。<br>各項目をクリックして、メンバー情報やスライド設定を管理してください。</p>
        </div>

        <div class="menu-grid">
            <!-- メンバー管理 -->
            <a href="members.php" class="menu-card">
                <div class="menu-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>
                    メンバー管理
                    <span class="badge badge-complete">Phase 1完了</span>
                </h3>
                <p>メンバーの追加・編集・削除を行います。名前、会社名、カテゴリ、写真などを管理できます。</p>
                <div class="pages">全48名登録済み</div>
            </a>

            <!-- スタートダッシュプレゼン -->
            <a href="start_dash.php" class="menu-card">
                <div class="menu-card-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>
                    スタートダッシュプレゼン
                    <span class="badge badge-complete">Phase 2完了</span>
                </h3>
                <p>p.15とp.107のスタートダッシュプレゼンターを設定します。2分間のカウントダウンタイマー付きです。</p>
                <div class="pages">p.15, p.107</div>
            </a>

            <!-- ビジター管理 -->
            <a href="visitors.php" class="menu-card">
                <div class="menu-card-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3>
                    ビジター管理
                    <span class="badge badge-new">NEW</span>
                </h3>
                <p>ビジター情報を管理し、4種類のスライドを自動生成します。紹介、自己紹介、感想、感謝のスライドに対応。</p>
                <div class="pages">p.19, p.169-180, p.213-224, p.235</div>
            </a>

            <!-- ウィークリープレゼン -->
            <a href="#" class="menu-card" style="opacity: 0.6; pointer-events: none;">
                <div class="menu-card-icon">
                    <i class="fas fa-presentation"></i>
                </div>
                <h3>
                    ウィークリープレゼン
                    <span class="badge badge-pending">準備中</span>
                </h3>
                <p>p.20-67のウィークリープレゼンを管理します。メンバーごとに1分間のプレゼンスライドを生成。</p>
                <div class="pages">p.20-67（48ページ）</div>
            </a>

            <!-- 60秒プレゼン -->
            <a href="#" class="menu-card" style="opacity: 0.6; pointer-events: none;">
                <div class="menu-card-icon">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <h3>
                    60秒プレゼン
                    <span class="badge badge-pending">準備中</span>
                </h3>
                <p>p.113-160の60秒プレゼンを管理します。メンバーごとに60秒のカウントダウンタイマー付き。</p>
                <div class="pages">p.113-160（48ページ）</div>
            </a>

            <!-- サンクスリファーラル -->
            <a href="#" class="menu-card" style="opacity: 0.6; pointer-events: none;">
                <div class="menu-card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>
                    サンクスリファーラル
                    <span class="badge badge-pending">準備中</span>
                </h3>
                <p>p.225-234のサンクスリファーラルを管理します。メンバー間のリファーラル実績を表示。</p>
                <div class="pages">p.225-234（10ページ）</div>
            </a>

            <!-- スライド一覧表示 -->
            <a href="../index.php" class="menu-card" target="_blank">
                <div class="menu-card-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <h3>スライド一覧表示</h3>
                <p>全309ページのスライドを表示します。フルスクリーンモードでプレゼンテーションを実行できます。</p>
                <div class="pages">全309ページ</div>
            </a>
        </div>
    </div>

    <div class="footer">
        BNI Slide System V2 &copy; 2024 | Powered by PHP & SQLite3
    </div>
</body>
</html>
