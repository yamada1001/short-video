<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Instagram運用代行サービス - ご提案資料</title>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->

    <!-- LINE Seed Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* LINEseedフォントの代替として最適なフォント設定 */
        @font-face {
            font-family: 'LINESeed';
            src: local('M PLUS 1p'), local('Hiragino Sans'), local('Hiragino Kaku Gothic ProN');
            font-weight: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'M PLUS 1p', 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', 'Yu Gothic', 'Meiryo', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            background: #ffffff;
            min-height: 100vh;
        }

        /* ヘッダーは非表示 */
        .header {
            display: none;
        }

        /* モバイルメニューボタン */
        .mobile-menu-btn {
            display: none;
            background: #333;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 300;
        }

        /* フローティング目次ボタン（右下固定・モバイル専用） */
        .floating-menu-btn {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #333;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5em;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 150;
            transition: all 0.3s;
        }

        .floating-menu-btn:hover {
            background: #555;
            transform: scale(1.1);
        }

        .floating-menu-btn:active {
            transform: scale(0.95);
        }

        /* レイアウト */
        .main-layout {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 40px;
            padding: 40px 20px;
        }

        /* サイドバー目次（右側に配置） */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            position: sticky;
            top: 20px;
            height: fit-content;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            order: 2;
        }

        /* メインコンテンツ（左側に配置） */
        .content {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 50px;
            order: 1;
        }

        .sidebar h2 {
            font-size: 1.1em;
            font-weight: 300;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar h2 i {
            color: #666;
            margin-right: 8px;
        }

        .toc {
            list-style: none;
        }

        .toc li {
            margin-bottom: 12px;
        }

        .toc a {
            display: block;
            color: #666;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 0.9em;
            font-weight: 300;
        }

        .toc a:hover, .toc a.active {
            background: #f5f5f5;
            color: #333;
        }

        .toc a i {
            margin-right: 8px;
            width: 20px;
        }

        .section {
            margin-bottom: 80px;
            scroll-margin-top: 20px;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.75em;
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 300;
        }

        .section-title i {
            color: #666;
            margin-right: 12px;
        }

        .section-content {
            font-size: 0.95em;
            line-height: 2;
            color: #555;
            font-weight: 300;
        }

        /* ハイライトボックス */
        .highlight-box {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 3px solid #333;
            padding: 30px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .highlight-box h3 {
            color: #333;
            font-size: 1.25em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .highlight-box h3 i {
            margin-right: 10px;
        }

        .highlight-box p {
            font-size: 0.95em;
            line-height: 1.9;
            color: #555;
            font-weight: 300;
        }

        /* 料金例 */
        .price-example {
            background: #ffffff;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
        }

        .price-example .budget {
            font-size: 2em;
            color: #333;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .price-example .budget i {
            margin-right: 12px;
        }

        .price-example .details {
            font-size: 1.05em;
            color: #666;
            line-height: 1.8;
            font-weight: 300;
        }

        /* メリットリスト */
        .benefits-list {
            list-style: none;
            margin: 30px 0;
        }

        .benefits-list li {
            padding: 18px 25px;
            margin: 15px 0;
            background: #fafafa;
            border-radius: 4px;
            border-left: 2px solid #333;
            font-size: 0.95em;
            font-weight: 300;
            transition: all 0.3s;
        }

        .benefits-list li:hover {
            background: #f5f5f5;
        }

        .benefits-list li i {
            color: #666;
            margin-right: 12px;
            width: 20px;
        }

        /* 警告ボックス */
        .warning-box {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 3px solid #999;
            padding: 30px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .warning-box h3 {
            color: #333;
            font-size: 1.15em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .warning-box h3 i {
            margin-right: 10px;
        }

        .warning-box p {
            color: #666;
            line-height: 1.9;
            font-weight: 300;
        }

        /* 情報ボックス */
        .info-box {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 3px solid #666;
            padding: 30px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .info-box h3 {
            color: #333;
            font-size: 1.15em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .info-box h3 i {
            margin-right: 10px;
        }

        .info-box p {
            color: #555;
            line-height: 1.9;
            font-weight: 300;
        }

        /* サービスグリッド */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin: 35px 0;
        }

        .service-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            background: #fafafa;
            border-color: #333;
        }

        .service-card i {
            font-size: 3em;
            color: #666;
            margin-bottom: 20px;
        }

        .service-card h3 {
            color: #333;
            font-size: 1.15em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .service-card p {
            color: #666;
            font-size: 0.9em;
            font-weight: 300;
            line-height: 1.7;
        }

        /* CTAセクション */
        .cta-section {
            background: #fafafa;
            border: 2px solid #333;
            color: #333;
            padding: 50px;
            border-radius: 8px;
            text-align: center;
            margin: 50px 0;
        }

        .cta-section h3 {
            font-size: 1.75em;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .cta-section h3 i {
            margin-right: 12px;
        }

        .cta-section p {
            font-size: 1.05em;
            font-weight: 300;
            line-height: 2;
            color: #555;
        }

        /* フッター */
        .footer {
            background: #ffffff;
            padding: 40px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e0e0e0;
            margin-top: 60px;
        }

        .footer p {
            margin: 10px 0;
            font-weight: 300;
        }

        .footer i {
            color: #666;
            margin: 0 5px;
        }

        /* フローティング目次（モバイル用） */
        .floating-toc {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 200;
        }

        .floating-toc.active {
            display: block;
        }

        .floating-toc-content {
            position: absolute;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100%;
            background: white;
            box-shadow: 2px 0 20px rgba(0,0,0,0.3);
            transition: left 0.3s;
            overflow-y: auto;
        }

        .floating-toc.active .floating-toc-content {
            left: 0;
        }

        .floating-toc-header {
            padding: 25px;
            background: #333;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .floating-toc-header h2 {
            font-size: 1.2em;
            font-weight: 300;
        }

        .close-toc {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
        }

        .floating-toc .toc {
            padding: 25px;
        }

        /* レスポンシブ */
        @media (max-width: 1024px) {
            .sidebar {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            /* モバイル時にフローティングボタン表示 */
            .floating-menu-btn {
                display: block;
            }

            .main-layout {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 25px;
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.2em;
            }

            .header p {
                font-size: 0.85em;
            }

            .section-title {
                font-size: 1.5em;
            }

            .content {
                padding: 25px 20px;
            }

            .service-grid {
                grid-template-columns: 1fr;
            }

            .price-example .budget {
                font-size: 1.75em;
            }

            .cta-section {
                padding: 35px 25px;
            }

            .cta-section h3 {
                font-size: 1.4em;
            }
        }

        /* スムーススクロール */
        html {
            scroll-behavior: smooth;
        }

        /* 選択テキストの色 */
        ::selection {
            background: #0066cc;
            color: white;
        }

        /* タブナビゲーション */
        .tab-navigation {
            background: #ffffff;
            border-bottom: 2px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .tab-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 0;
            padding: 0 20px;
        }

        .tab-btn {
            flex: 1;
            max-width: 300px;
            background: transparent;
            border: none;
            padding: 20px 30px;
            font-size: 1em;
            font-weight: 300;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            font-family: 'M PLUS 1p', sans-serif;
        }

        .tab-btn:hover {
            background: #fafafa;
            color: #333;
        }

        .tab-btn.active {
            color: #333;
            border-bottom-color: #333;
            font-weight: 400;
        }

        .tab-btn i {
            margin-right: 8px;
        }

        /* タブコンテンツ */
        .tab-content-wrapper {
            min-height: calc(100vh - 200px);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 競合調査レポート用スタイル */
        .research-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 30px;
        }

        .research-title {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: 300;
            text-align: center;
        }

        .research-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 60px;
            flex-wrap: wrap;
            font-size: 0.9em;
            color: #666;
        }

        .research-meta span {
            padding: 10px 20px;
            background: #fafafa;
            border-radius: 20px;
        }

        .research-meta i {
            margin-right: 8px;
            color: #999;
        }

        .research-section {
            margin-bottom: 80px;
        }

        .research-section-title {
            font-size: 2em;
            color: #333;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 300;
        }

        .research-section-title i {
            margin-right: 15px;
            color: #666;
        }

        .research-subsection-title {
            font-size: 1.5em;
            color: #333;
            margin: 40px 0 25px;
            font-weight: 300;
        }

        /* アカウントカード */
        .account-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .account-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .account-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s;
            position: relative;
            cursor: pointer;
            height: 100%;
        }

        .account-card-link:hover .account-card {
            border-color: #333;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }

        .account-card.highlight {
            border: 2px solid #333;
            background: #fafafa;
        }

        .badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 400;
        }

        .badge.success {
            background: #28a745;
            color: white;
        }

        .badge.featured {
            background: #6f42c1;
            color: white;
        }

        .account-header h4 {
            font-size: 1.15em;
            color: #333;
            margin-bottom: 8px;
            font-weight: 400;
        }

        .account-handle {
            font-size: 0.85em;
            color: #666;
            font-family: monospace;
            display: inline-block;
            padding: 5px 10px;
            background: #f5f5f5;
            border-radius: 6px;
            margin-top: 8px;
        }

        .account-card-link:hover .account-handle {
            background: #333;
            color: #fff;
        }

        .account-handle i {
            margin-right: 5px;
        }

        .account-stats {
            margin: 15px 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .account-stats .stat {
            font-size: 0.9em;
            color: #666;
            padding: 5px 10px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .account-stats .stat i {
            margin-right: 5px;
            color: #999;
        }

        .account-desc {
            font-size: 0.9em;
            color: #666;
            line-height: 1.7;
            margin-top: 12px;
        }

        /* 戦略ボックス */
        .strategy-box {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #333;
            padding: 30px;
            margin-bottom: 40px;
            border-radius: 8px;
        }

        .strategy-box h3 {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .strategy-list {
            list-style: none;
        }

        .strategy-list li {
            padding: 10px 0;
            font-size: 1em;
            color: #666;
            font-weight: 300;
        }

        .strategy-list li i {
            color: #28a745;
            margin-right: 10px;
        }

        /* プラットフォームグリッド */
        .platform-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .platform-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px;
        }

        .platform-card h4 {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .platform-card ul {
            list-style: none;
        }

        .platform-card ul li {
            padding: 8px 0;
            color: #666;
            font-size: 0.95em;
            line-height: 1.8;
            padding-left: 20px;
            position: relative;
        }

        .platform-card ul li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #333;
        }

        /* エンゲージメントボックス */
        .engagement-box {
            background: #e3f2fd;
            border: 2px solid #2196F3;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 40px;
            text-align: center;
        }

        .engagement-box h3 {
            font-size: 1.3em;
            color: #1976D2;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .formula {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            font-size: 1.2em;
            color: #333;
            font-weight: 300;
            font-family: monospace;
        }

        /* エンゲージメントグリッド */
        .engagement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .engagement-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
        }

        .engagement-card:hover {
            border-color: #333;
            transform: translateY(-3px);
        }

        .engagement-card i {
            font-size: 2.5em;
            color: #666;
            margin-bottom: 15px;
            display: block;
        }

        .engagement-card h4 {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 12px;
            font-weight: 300;
        }

        .engagement-card p {
            font-size: 0.9em;
            color: #666;
            line-height: 1.7;
        }

        /* ケーススタディ */
        .case-study {
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 35px;
            margin-bottom: 30px;
            position: relative;
        }

        .case-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 400;
        }

        .case-badge.gold {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #333;
        }

        .case-badge.silver {
            background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%);
            color: #fff;
        }

        .case-badge.bronze {
            background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%);
            color: #fff;
        }

        .case-study h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .case-result {
            background: #fafafa;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 1.05em;
            color: #333;
        }

        .case-study h4 {
            font-size: 1.1em;
            color: #666;
            margin: 20px 0 12px;
            font-weight: 300;
        }

        .case-list {
            list-style: none;
        }

        .case-list li {
            padding: 8px 0 8px 25px;
            color: #666;
            font-size: 0.95em;
            line-height: 1.8;
            position: relative;
        }

        .case-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }

        /* ベストプラクティスグリッド */
        .best-practice-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .best-practice-card {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 35px;
        }

        .best-practice-card h3 {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 25px;
            font-weight: 300;
        }

        .best-practice-card ul {
            list-style: none;
        }

        .best-practice-card ul li {
            padding: 15px 0;
            color: #666;
            font-size: 0.95em;
            line-height: 1.8;
            border-bottom: 1px solid #e0e0e0;
        }

        .best-practice-card ul li:last-child {
            border-bottom: none;
        }

        /* 結論セクション */
        .research-section.conclusion {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 50px;
            border-radius: 20px;
        }

        .conclusion-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .conclusion-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .conclusion-card h4 {
            font-size: 1.15em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .conclusion-card p {
            font-size: 0.95em;
            color: #666;
            line-height: 1.8;
        }

        .final-message {
            background: #ffffff;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 40px;
            margin-top: 40px;
            text-align: center;
        }

        .final-message i {
            font-size: 2.5em;
            color: #FFD700;
            display: block;
            margin-bottom: 20px;
        }

        .final-message p {
            font-size: 1.15em;
            color: #333;
            line-height: 2;
            font-weight: 300;
        }

        /* レスポンシブ対応 */
        @media (max-width: 768px) {
            .tab-btn {
                padding: 15px 20px;
                font-size: 0.9em;
            }

            .research-title {
                font-size: 1.8em;
            }

            .research-meta {
                flex-direction: column;
                gap: 10px;
            }

            .account-grid {
                grid-template-columns: 1fr;
            }

            .platform-grid {
                grid-template-columns: 1fr;
            }

            .best-practice-grid {
                grid-template-columns: 1fr;
            }

            .conclusion-grid {
                grid-template-columns: 1fr;
            }

            .research-section.conclusion {
                padding: 30px 20px;
            }

            .research-container {
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- ヘッダー -->
    <header class="header">
        <div class="header-content">
            <div>
                <h1><i class="fab fa-instagram"></i>Instagram運用代行サービス</h1>
                <p>撮影・編集・投稿まで、まるっとお任せください</p>
            </div>
            <button class="mobile-menu-btn" onclick="toggleToc()">
                <i class="fas fa-bars"></i> 目次
            </button>
        </div>
    </header>

    <!-- モバイル用目次ボタン（右下固定） -->
    <button class="floating-menu-btn" onclick="toggleToc()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- タブナビゲーション -->
    <div class="tab-navigation">
        <div class="tab-nav-container">
            <button class="tab-btn active" onclick="switchTab('proposal')">
                <i class="fas fa-file-alt"></i> 提案資料
            </button>
            <button class="tab-btn" onclick="switchTab('research')">
                <i class="fas fa-chart-bar"></i> 競合調査レポート
            </button>
        </div>
    </div>

    <!-- フローティング目次（モバイル用） -->
    <div class="floating-toc" id="floatingToc" onclick="closeTocIfBackdrop(event)">
        <div class="floating-toc-content">
            <div class="floating-toc-header">
                <h2><i class="fas fa-list"></i> 目次</h2>
                <button class="close-toc" onclick="toggleToc()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ul class="toc">
                <li><a href="#proposal" onclick="closeToc()"><i class="fas fa-bullseye"></i>ご提案</a></li>
                <li><a href="#challenges"><i class="fas fa-exclamation-triangle"></i>現状の課題</a></li>
                <li><a href="#services"><i class="fas fa-cogs"></i>サービス内容</a></li>
                <li><a href="#pricing"><i class="fas fa-yen-sign"></i>料金プラン</a></li>
                <li><a href="#marketing"><i class="fas fa-chart-line"></i>マーケティング戦略</a></li>
                <li><a href="#consulting"><i class="fas fa-user-tie"></i>コンサルティング</a></li>
                <li><a href="#training"><i class="fas fa-graduation-cap"></i>社員育成サポート</a></li>
                <li><a href="#scope"><i class="fas fa-info-circle"></i>サービス範囲</a></li>
                <li><a href="#summary"><i class="fas fa-check-circle"></i>まとめ</a></li>
            </ul>
        </div>
    </div>

    <!-- タブコンテンツエリア -->
    <div class="tab-content-wrapper">
        <!-- 提案資料タブ -->
        <div id="proposal-tab" class="tab-content active">
            <!-- メインレイアウト -->
            <div class="main-layout">
        <!-- サイドバー目次（PC用） -->
        <aside class="sidebar">
            <h2><i class="fas fa-list"></i> 目次</h2>
            <ul class="toc">
                <li><a href="#proposal"><i class="fas fa-bullseye"></i>ご提案</a></li>
                <li><a href="#challenges"><i class="fas fa-exclamation-triangle"></i>現状の課題</a></li>
                <li><a href="#services"><i class="fas fa-cogs"></i>サービス内容</a></li>
                <li><a href="#pricing"><i class="fas fa-yen-sign"></i>料金プラン</a></li>
                <li><a href="#marketing"><i class="fas fa-chart-line"></i>マーケティング戦略</a></li>
                <li><a href="#consulting"><i class="fas fa-user-tie"></i>コンサルティング</a></li>
                <li><a href="#training"><i class="fas fa-graduation-cap"></i>社員育成サポート</a></li>
                <li><a href="#scope"><i class="fas fa-info-circle"></i>サービス範囲</a></li>
                <li><a href="#summary"><i class="fas fa-check-circle"></i>まとめ</a></li>
            </ul>
        </aside>

        <!-- メインコンテンツ -->
        <main class="content">
            <!-- 結論・提案 -->
            <section class="section" id="proposal">
                <h2 class="section-title"><i class="fas fa-bullseye"></i>ご提案</h2>
                <div class="highlight-box">
                    <h3><i class="fas fa-handshake"></i>Instagram運用を弊社で代行させてください</h3>
                    <p>
                        撮影から編集、投稿まで、Instagram運用に関わる全ての業務を弊社が代行いたします。<br>
                        コンテンツ制作のプロフェッショナルとして、貴社のブランド価値を最大限に引き出す投稿を継続的にお届けします。
                    </p>
                </div>
            </section>

            <!-- 背景・課題 -->
            <section class="section" id="challenges">
                <h2 class="section-title"><i class="fas fa-exclamation-triangle"></i>現状の課題</h2>
                <div class="section-content">
                    <p>
                        代表の仲道様より当初は社員育成のご要望をいただいておりましたが、
                        担当予定の2名の方が急遽業務対応が難しい状況となりました（育休・体調不良）。
                    </p>

                    <div class="warning-box">
                        <h3><i class="fas fa-exclamation-circle"></i>Instagram運用における課題</h3>
                        <p>
                            社内での運用体制では、人員の急な不在により<strong>更新が止まってしまうリスク</strong>があります。<br>
                            Instagramのアルゴリズムでは、投稿の継続性が非常に重要な要素となっており、
                            更新が止まることでこれまで積み上げてきたエンゲージメントが大きく低下してしまいます。<br><br>

                            <strong>弊社のような業務委託の組織は、「やめない社員」として捉えていただけるとわかりやすいかと思います。</strong><br>
                            安定した運用体制と継続性が、弊社の大きな強みです。
                        </p>
                    </div>

                    <p style="margin-top: 30px;">
                        そこで、<strong>弊社での運用代行</strong>をご提案させていただきます。<br>
                        専門チームによる安定した運用体制で、継続的な情報発信を実現いたします。
                    </p>
                </div>
            </section>

            <!-- サービス内容 -->
            <section class="section" id="services">
                <h2 class="section-title"><i class="fas fa-cogs"></i>サービス内容</h2>
                <div class="service-grid">
                    <div class="service-card">
                        <i class="fas fa-camera"></i>
                        <h3>撮影</h3>
                        <p>プロカメラマンによる高品質な撮影</p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-video"></i>
                        <h3>編集</h3>
                        <p>トレンドを押さえた魅力的な動画編集</p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-mobile-alt"></i>
                        <h3>投稿管理</h3>
                        <p>最適なタイミングでの投稿・運用</p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-lightbulb"></i>
                        <h3>コンサルティング</h3>
                        <p>戦略立案・改善提案を継続的に実施</p>
                    </div>
                </div>

                <ul class="benefits-list">
                    <li><i class="fas fa-check-circle"></i>撮影から編集、投稿まで全てお任せいただけます</li>
                    <li><i class="fas fa-users"></i>専門チームによる安定した運用体制</li>
                    <li><i class="fas fa-fire"></i>トレンドを押さえた質の高いコンテンツ制作</li>
                    <li><i class="fas fa-heart"></i>継続的な投稿による安定したエンゲージメント</li>
                    <li><i class="fas fa-shield-alt"></i>リソース不足の心配なし</li>
                </ul>
            </section>

            <!-- 料金プラン -->
            <section class="section" id="pricing">
                <h2 class="section-title"><i class="fas fa-yen-sign"></i>料金プラン</h2>
                <div class="section-content">
                    <p>
                        <strong>ご予算に応じて柔軟にプランを設定いたします。</strong><br>
                        投稿本数やサービス内容を調整することで、最適なプランをご提案させていただきます。
                    </p>

                    <div class="price-example">
                        <div class="budget"><i class="fas fa-yen-sign"></i>例：月額 150,000円</div>
                        <div class="details">
                            <strong>月10本のリール投稿</strong><br>
                            撮影・編集・投稿・分析レポート込み
                        </div>
                    </div>

                    <p style="margin-top: 30px; text-align: center; color: #666;">
                        <i class="fas fa-info-circle"></i> ご予算・目標に応じて投稿本数や撮影頻度を調整いたします<br>
                        <i class="fas fa-file-invoice"></i> 詳細なお見積りは別途ご提案させていただきます
                    </p>
                </div>
            </section>

            <!-- マーケティング戦略 NEW! -->
            <section class="section" id="marketing">
                <h2 class="section-title"><i class="fas fa-chart-line"></i>マーケティング戦略</h2>

                <div class="info-box">
                    <h3><i class="fas fa-users"></i>貴社の強み：人脈構築活動</h3>
                    <p>
                        代表の仲道様が<strong>倫理法人会などを通じて築いてこられた人脈</strong>が、
                        貴社の大きな強みであると認識しております。<br><br>

                        不動産という商材の特性上、<strong>信頼関係に基づくご紹介</strong>は
                        最も効果的な集客手法であり、これまでの実績がそれを証明しています。<br><br>

                        <strong>ぜひこの人脈構築活動を止めることなく、継続していただきたい</strong>と考えております。
                    </p>
                </div>

                <div class="section-content" style="margin-top: 35px;">
                    <h3 style="color: #333; font-size: 1.25em; margin-bottom: 20px; font-weight: 300;">
                        <i class="fas fa-bullseye"></i>弊社が目指すゴール
                    </h3>

                    <p style="margin-bottom: 30px;">
                        仲道様の<strong>人脈から集客した見込み顧客</strong>に対して、
                        <strong>コンバージョン率（成約率）を改善すること</strong>が、弊社の主な役割となります。
                    </p>

                    <div class="service-grid">
                        <div class="service-card">
                            <i class="fas fa-handshake"></i>
                            <h3>集客</h3>
                            <p>人脈構築活動<br>（仲道様の強み）</p>
                        </div>
                        <div class="service-card" style="background: #fafafa; border-color: #333;">
                            <i class="fas fa-arrow-right" style="color: #666;"></i>
                            <h3>↓</h3>
                            <p>見込み顧客</p>
                        </div>
                        <div class="service-card">
                            <i class="fas fa-chart-line"></i>
                            <h3>コンバージョン改善</h3>
                            <p>Instagramコンテンツ<br>（弊社の役割）</p>
                        </div>
                        <div class="service-card" style="background: #fafafa; border-color: #333;">
                            <i class="fas fa-check-circle" style="color: #666;"></i>
                            <h3>↓</h3>
                            <p>成約</p>
                        </div>
                    </div>

                    <div class="highlight-box" style="margin-top: 35px;">
                        <h3><i class="fas fa-magic"></i>マーケターが携わる制作</h3>
                        <p>
                            ただの制作会社ではなく、<strong>マーケターが携わる制作</strong>であることが弊社の強みです。<br><br>

                            • <strong>戦略的なコンテンツ企画</strong>：ターゲット顧客の心を動かす企画立案<br>
                            • <strong>エンゲージメント最大化</strong>：最大限のリーチを獲得できるよう全力を尽くします<br>
                            • <strong>データ分析に基づく改善</strong>：投稿パフォーマンスを分析し、継続的に最適化<br>
                            • <strong>コンバージョンを意識した制作</strong>：見込み顧客を顧客へと導くストーリー設計
                        </p>
                    </div>

                    <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; margin-top: 35px;">
                        <h3 style="color: #333; margin-bottom: 20px;">
                            <i class="fas fa-lightbulb"></i> 不動産ビジネスにおけるInstagramの役割
                        </h3>
                        <ul class="benefits-list">
                            <li><i class="fas fa-building"></i>物件の魅力を視覚的に訴求</li>
                            <li><i class="fas fa-heart"></i>ブランドへの信頼感・親近感を醸成</li>
                            <li><i class="fas fa-comments"></i>顧客との継続的なコミュニケーション</li>
                            <li><i class="fas fa-star"></i>ご紹介で来た見込み顧客の背中を押す</li>
                            <li><i class="fas fa-chart-line"></i>検討期間中のエンゲージメント維持</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- コンサルティングについて -->
            <section class="section" id="consulting">
                <h2 class="section-title"><i class="fas fa-user-tie"></i>コンサルティング業務について</h2>
                <div class="info-box">
                    <h3><i class="fas fa-sync-alt"></i>運用代行 + コンサルティング</h3>
                    <p>
                        当初ご提案していたコンサルワークとしての働き方から、
                        <strong>Instagram運用を弊社で巻き取る形</strong>に変更させていただければと考えております。
                    </p>
                </div>

                <div class="section-content" style="margin-top: 30px;">
                    <p>
                        形式は異なりますが、<strong>私も本案件に引き続き参加</strong>いたしますので、
                        コンサルタントとしての戦略提案やアドバイスは<strong>インクルード（追加費用なし）</strong>で対応させていただきます。
                    </p>

                    <ul class="benefits-list" style="margin-top: 30px;">
                        <li><i class="fas fa-chess"></i>Instagram戦略の立案・改善提案</li>
                        <li><i class="fas fa-pencil-ruler"></i>コンテンツ企画のディレクション</li>
                        <li><i class="fas fa-chart-pie"></i>トレンド分析と最適化提案</li>
                        <li><i class="fas fa-file-chart-line"></i>定期的な成果レポートと改善ディスカッション</li>
                    </ul>
                </div>
            </section>

            <!-- 育成サポート -->
            <section class="section" id="training">
                <h2 class="section-title"><i class="fas fa-graduation-cap"></i>社員育成サポート</h2>
                <div class="section-content">
                    <p>
                        運用代行がメインとなりますが、<strong>社員の方からご質問いただいた際には、できる範囲でアドバイスやサポート</strong>をさせていただきます。<br><br>

                        講習会や定期的な1on1といった形式的な育成プログラムではなく、
                        <strong>日々の業務の中で気になることがあれば、いつでもご質問いただける関係性</strong>を大切にしたいと考えております。
                    </p>

                    <div style="background: #fafafa; border: 1px solid #e0e0e0; padding: 25px; border-radius: 8px; margin: 30px 0;">
                        <h3 style="color: #333; margin-bottom: 15px; font-weight: 300;">
                            <i class="fas fa-comment-dots"></i> ご質問例
                        </h3>
                        <p style="font-weight: 300; color: #666; line-height: 1.9;">
                            • 撮影時のポイントについて<br>
                            • 編集ツールの使い方<br>
                            • Instagram運用の基本的な考え方<br>
                            • 投稿のタイミングや頻度について
                        </p>
                    </div>

                    <p style="margin-top: 25px;">
                        あくまで運用は弊社が責任を持って行いますが、
                        <strong>ノウハウの共有を通じて、貴社内でもInstagram運用の理解を深めていただけます。</strong>
                    </p>
                </div>
            </section>

            <!-- 重要な認識合わせ -->
            <section class="section" id="scope">
                <h2 class="section-title"><i class="fas fa-info-circle"></i>サービス範囲について</h2>
                <div class="warning-box">
                    <h3><i class="fas fa-tools"></i>弊社が担当する範囲：「制作」</h3>
                    <p>
                        <strong>弊社が担当するのは「制作」部分</strong>となります。<br><br>

                        撮影・編集・投稿というコンテンツ制作に特化したサービスをご提供いたします。<br><br>

                        <strong>マーケティング施策（広告運用等）まで対応する場合</strong>は、
                        別途広告予算を確保いただく必要がございます。<br><br>

                        まずは質の高いコンテンツを継続的に発信し、オーガニック（自然流入）での
                        エンゲージメント向上を目指してまいります。
                    </p>
                </div>

                <div style="background: #e6f9f9; border: 2px solid #00acc1; border-radius: 12px; padding: 30px; margin-top: 30px;">
                    <h3 style="color: #00838f; margin-bottom: 20px;">
                        <i class="fas fa-trophy"></i> 弊社の強み
                    </h3>
                    <ul class="benefits-list">
                        <li><i class="fas fa-award"></i><strong>制作のプロフェッショナル</strong>として、高品質なコンテンツを提供</li>
                        <li><i class="fas fa-fire"></i>トレンドを押さえた<strong>魅力的なリール動画</strong>の制作</li>
                        <li><i class="fas fa-calendar-check"></i>継続的な投稿による<strong>安定したブランディング</strong></li>
                        <li><i class="fas fa-rocket"></i>エンゲージメントを高める<strong>戦略的なコンテンツ企画</strong></li>
                        <li><i class="fas fa-chart-line"></i><strong>マーケター視点</strong>での制作・運用</li>
                    </ul>
                </div>
            </section>

            <!-- まとめ -->
            <section class="section" id="summary">
                <h2 class="section-title"><i class="fas fa-check-circle"></i>ご提案のまとめ</h2>
                <div class="section-content">
                    <div style="background: #fafafa; padding: 40px; border-radius: 8px; border: 2px solid #333;">
                        <ol style="font-size: 1em; line-height: 2.5; padding-left: 25px; color: #333;">
                            <li><strong>Instagram運用を弊社で代行</strong>（撮影・編集・投稿）</li>
                            <li><strong>ご予算に応じた柔軟なプラン設定</strong></li>
                            <li><strong>コンサルティング業務もインクルード</strong></li>
                            <li><strong>社員育成サポートも可能</strong></li>
                            <li><strong>人脈からの集客後のコンバージョン率改善</strong>が主な役割</li>
                            <li><strong>マーケター視点での制作</strong>で最大限のリーチを実現</li>
                        </ol>
                    </div>

                    <p style="margin-top: 40px; font-size: 1.15em; text-align: center; color: #333; font-weight: 300; line-height: 2;">
                        <i class="fas fa-star"></i><br>
                        継続的で質の高いコンテンツ発信により、<br>
                        貴社のInstagramアカウントを成長させます。
                    </p>
                </div>
            </section>
        </main>
            </div><!-- /main-layout -->
        </div><!-- /proposal-tab -->

        <!-- 競合調査レポートタブ -->
        <div id="research-tab" class="tab-content">
            <div class="research-container">
                <h1 class="research-title"><i class="fas fa-chart-bar"></i> 不動産業界 SNS競合調査レポート</h1>
                <div class="research-meta">
                    <span><i class="fas fa-calendar"></i> 調査日: 2025年12月8日</span>
                    <span><i class="fas fa-bullseye"></i> 目的: 採用・売買に強いアカウントの分析</span>
                    <span><i class="fas fa-hashtag"></i> 対象SNS: Instagram / TikTok / YouTube</span>
                </div>

                <!-- プラットフォーム別主要アカウント -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fab fa-instagram"></i> Instagram 主要アカウント</h2>

                    <h3 class="research-subsection-title">採用特化アカウント</h3>
                    <div class="account-grid">
                        <a href="https://www.instagram.com/mfrealty.recruit/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>三井不動産リアルティグループ</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @mfrealty.recruit
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 5,654人</span>
                                </div>
                                <p class="account-desc">採用情報、日常業務風景、会社の雰囲気、インターン情報、社員の本音インタビュー</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/nomura_solutions.recruit/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>野村不動産ソリューションズ</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @nomura_solutions.recruit
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 1,049人</span>
                                </div>
                                <p class="account-desc">就職活動に役立つ情報、先輩社員の日常、会社のリアルな雰囲気</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/kintechu_recruit/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>近鉄不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @kintechu_recruit
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 826人</span>
                                </div>
                                <p class="account-desc">リアルな雰囲気、業務内容、社内イベント</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/kosugi_recruit/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>コスギ不動産HD</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @kosugi_recruit
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 751人</span>
                                </div>
                                <p class="account-desc">採用・不動産に関する有益情報</p>
                            </div>
                        </a>
                    </div>

                    <h3 class="research-subsection-title">売買・物件紹介特化アカウント</h3>
                    <div class="account-grid">
                        <a href="https://www.instagram.com/is_room_/" target="_blank" class="account-card-link">
                            <div class="account-card highlight">
                                <div class="badge success">最優秀事例</div>
                                <div class="account-header">
                                    <h4>アイズルーム (I's Room)</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @is_room_
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 10,000人+</span>
                                </div>
                                <p class="account-desc"><strong>3ヶ月で1万フォロワー達成</strong>、女性向け賃貸物件、リール活用</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/goodroom_jp/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>グッドルーム (goodroom)</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @goodroom_jp
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 156,000人</span>
                                </div>
                                <p class="account-desc">リノベ・デザイナーズ物件、ルームツアーリール活用</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/tokyo__r/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>東京R不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @tokyo__r
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 65,000人</span>
                                    <span class="stat"><i class="fas fa-image"></i> 1,193投稿</span>
                                </div>
                                <p class="account-desc">「グッとくる」物件を独自の視点でセレクト</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/simplenaiken/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>Simple NAIKEN</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @simplenaiken
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 57,000人</span>
                                    <span class="stat"><i class="fas fa-image"></i> 1,260投稿</span>
                                </div>
                                <p class="account-desc">一人暮らし・カップル向け、トレンドキーワード活用、高品質動画</p>
                            </div>
                        </a>

                        <a href="https://www.instagram.com/asunofudosan/" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>明日の不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-instagram"></i> @asunofudosan
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 14,000人</span>
                                    <span class="stat"><i class="fas fa-image"></i> 2,544投稿</span>
                                </div>
                                <p class="account-desc">大阪エリア、一人暮らし・同棲検討者向け</p>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- TikTok -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fab fa-tiktok"></i> TikTok 主要アカウント</h2>
                    <div class="account-grid">
                        <a href="https://www.tiktok.com/@naikenboys" target="_blank" class="account-card-link">
                            <div class="account-card highlight">
                                <div class="badge featured">注目事例</div>
                                <div class="account-header">
                                    <h4>ないけんぼーいず (Naiken Boys)</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-tiktok"></i> @naikenboys
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 170,000人（全SNS36万人）</span>
                                </div>
                                <p class="account-desc"><strong>複数の100万再生動画、SNS経由の問い合わせが80%以上</strong></p>
                            </div>
                        </a>

                        <a href="https://www.tiktok.com/@only_you_home" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>ONLY YOU HOME</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-tiktok"></i> @onlyyouhome
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 287,000人</span>
                                </div>
                                <p class="account-desc">注文住宅ビルダー</p>
                            </div>
                        </a>

                        <a href="https://www.tiktok.com/@lakia.umeda" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>LAKIA不動産 大阪梅田店</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-tiktok"></i> @lakia_umeda
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 106,000人</span>
                                </div>
                                <p class="account-desc">ルームツアー動画、多数の動画が10万いいね超え</p>
                            </div>
                        </a>

                        <a href="https://www.tiktok.com/@kenchobi_house" target="_blank" class="account-card-link">
                            <div class="account-card highlight">
                                <div class="badge success">成約実績</div>
                                <div class="account-header">
                                    <h4>Kenchobi</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-tiktok"></i> @kenchobi
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 7,000人+</span>
                                </div>
                                <p class="account-desc"><strong>TikTok経由で20件以上の契約・100件以上の問い合わせ獲得</strong></p>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- YouTube -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fab fa-youtube"></i> YouTube 主要チャンネル</h2>
                    <div class="account-grid">
                        <a href="https://www.youtube.com/@YukkuriFudosan" target="_blank" class="account-card-link">
                            <div class="account-card highlight">
                                <div class="badge featured">最大規模</div>
                                <div class="account-header">
                                    <h4>ゆっくり不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-youtube"></i> ゆっくり不動産
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 75万人</span>
                                    <span class="stat"><i class="fas fa-play"></i> 1.8億回以上</span>
                                </div>
                                <p class="account-desc">ユニークな物件紹介、音声合成ソフト活用</p>
                            </div>
                        </a>

                        <a href="https://www.youtube.com/@gmentakishima" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>不動産Gメン滝島</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-youtube"></i> 不動産Gメン滝島
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 45万人</span>
                                </div>
                                <p class="account-desc">業界問題の告発、投資失敗回避ノウハウ</p>
                            </div>
                        </a>

                        <a href="https://www.youtube.com/@rakumachi" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>不動産投資の楽待</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-youtube"></i> 不動産投資の楽待
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 42.7万人</span>
                                    <span class="stat"><i class="fas fa-play"></i> 2億回以上</span>
                                </div>
                                <p class="account-desc">投資情報、投資家インタビュー</p>
                            </div>
                        </a>

                        <a href="https://www.youtube.com/channel/UCsWTZ4nYODCwlE8rdv7DZzA" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>もふもふ不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-youtube"></i> もふもふ不動産
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 26.4万人</span>
                                </div>
                                <p class="account-desc">不動産投資・税金・株式投資</p>
                            </div>
                        </a>

                        <a href="https://www.youtube.com/@urakenfudosan" target="_blank" class="account-card-link">
                            <div class="account-card">
                                <div class="account-header">
                                    <h4>ウラケン不動産</h4>
                                    <span class="account-handle">
                                        <i class="fab fa-youtube"></i> ウラケン不動産
                                    </span>
                                </div>
                                <div class="account-stats">
                                    <span class="stat"><i class="fas fa-users"></i> 17万人以上</span>
                                </div>
                                <p class="account-desc">投資戦略の専門知識</p>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- コンテンツ戦略分析 -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fas fa-lightbulb"></i> コンテンツ戦略分析</h2>

                    <div class="strategy-box">
                        <h3><i class="fas fa-calendar-check"></i> 投稿頻度のベストプラクティス</h3>
                        <ul class="strategy-list">
                            <li><i class="fas fa-check"></i> <strong>推奨頻度: 1日1回以上の投稿</strong></li>
                            <li><i class="fas fa-check"></i> Instagram: 毎日ストーリー投稿が目安</li>
                            <li><i class="fas fa-check"></i> TikTok: 高頻度投稿でアルゴリズムに乗りやすくなる</li>
                            <li><i class="fas fa-check"></i> YouTube: 週1〜2回の定期投稿が基本</li>
                        </ul>
                    </div>

                    <h3 class="research-subsection-title">プラットフォーム別コンテンツ特徴</h3>
                    <div class="platform-grid">
                        <div class="platform-card">
                            <h4><i class="fab fa-instagram"></i> Instagram（リール活用）</h4>
                            <ul>
                                <li>ルームツアー動画: ナレーション・効果音付き、テンポの良い編集</li>
                                <li>物件情報の隠し方: 最後まで詳細を見せず、リピート視聴を促進</li>
                                <li>ビジュアル重視: 高品質な写真・動画で物件の魅力を視覚的に訴求</li>
                                <li>ハッシュタグ戦略: トレンドキーワードを活用して検索性向上</li>
                            </ul>
                        </div>

                        <div class="platform-card">
                            <h4><i class="fab fa-tiktok"></i> TikTok（バズ狙い）</h4>
                            <ul>
                                <li>短尺動画: 15〜60秒で物件の魅力を凝縮</li>
                                <li>トレンド音源活用: TikTokのトレンド音楽を使った編集</li>
                                <li>キャッチーな導入: 最初の3秒で視聴者を惹きつける</li>
                                <li>エンタメ性: 営業マンのキャラクター性を前面に出す</li>
                            </ul>
                        </div>

                        <div class="platform-card">
                            <h4><i class="fab fa-youtube"></i> YouTube（詳細解説）</h4>
                            <ul>
                                <li>長尺コンテンツ: 10〜20分で物件を詳しく紹介</li>
                                <li>SEO対策: タイトル・説明文・タグの最適化</li>
                                <li>シリーズ化: 定期的なコンテンツで視聴者を定着化</li>
                                <li>専門性の訴求: 業界知識・投資ノウハウの提供</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- エンゲージメント分析 -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fas fa-chart-line"></i> エンゲージメント分析</h2>

                    <div class="engagement-box">
                        <h3><i class="fas fa-calculator"></i> エンゲージメント率の計算方法</h3>
                        <div class="formula">
                            エンゲージメント率 = エンゲージメント数 ÷ リーチ数 × 100
                        </div>
                    </div>

                    <h3 class="research-subsection-title">高エンゲージメントを実現するポイント</h3>
                    <div class="engagement-grid">
                        <div class="engagement-card">
                            <i class="fas fa-crosshairs"></i>
                            <h4>ターゲット明確化</h4>
                            <p>年齢層・興味関心・地域を分析してコンテンツを作成</p>
                        </div>
                        <div class="engagement-card">
                            <i class="fas fa-star"></i>
                            <h4>コンテンツの質</h4>
                            <p>物件の魅力を強調した高品質な画像・動画、有益情報の発信</p>
                        </div>
                        <div class="engagement-card">
                            <i class="fas fa-sync-alt"></i>
                            <h4>分析と改善</h4>
                            <p>エンゲージメント率を定期的にチェック、反応が良い投稿を継続・改善</p>
                        </div>
                        <div class="engagement-card">
                            <i class="fas fa-hashtag"></i>
                            <h4>ハッシュタグ活用</h4>
                            <p>SNS内での検索性を高め、発見されやすくする</p>
                        </div>
                    </div>
                </section>

                <!-- 成功事例 -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fas fa-trophy"></i> 成功事例の詳細分析</h2>

                    <div class="case-study">
                        <div class="case-badge gold">最優秀事例</div>
                        <h3><i class="fab fa-instagram"></i> アイズルーム（Instagram）</h3>
                        <div class="case-result">
                            <strong>成果: 3ヶ月で1万フォロワー達成</strong>
                        </div>
                        <h4>成功要因:</h4>
                        <ul class="case-list">
                            <li>リール動画を積極活用（フォロワー外へのリーチ拡大）</li>
                            <li>物件詳細情報を最後まで隠し、リピート視聴を促進</li>
                            <li>女性向け賃貸物件に特化したターゲティング</li>
                            <li>毎日投稿による継続性</li>
                        </ul>
                    </div>

                    <div class="case-study">
                        <div class="case-badge silver">注目事例</div>
                        <h3><i class="fab fa-tiktok"></i> ないけんぼーいず（TikTok）</h3>
                        <div class="case-result">
                            <strong>成果: フォロワー17万人（全SNS合計36万人）、複数の100万再生動画、SNS経由の問い合わせが80%以上</strong>
                        </div>
                        <h4>成功要因:</h4>
                        <ul class="case-list">
                            <li>TikTokのエンタメ性を活かした動画制作</li>
                            <li>営業マンのキャラクター性を前面に出す</li>
                            <li>短尺でインパクトのあるルームツアー</li>
                            <li>トレンド音源の積極活用</li>
                        </ul>
                    </div>

                    <div class="case-study">
                        <div class="case-badge bronze">長期成功事例</div>
                        <h3><i class="fab fa-youtube"></i> ゆっくり不動産（YouTube）</h3>
                        <div class="case-result">
                            <strong>成果: 登録者75万人、総再生回数1.8億回以上</strong>
                        </div>
                        <h4>成功要因:</h4>
                        <ul class="case-list">
                            <li>ユニークな物件に特化（競合との差別化）</li>
                            <li>音声合成ソフトで親しみやすさを演出</li>
                            <li>継続的な投稿で信頼性を構築</li>
                            <li>エンタメ性と情報性のバランス</li>
                        </ul>
                    </div>
                </section>

                <!-- ベストプラクティス -->
                <section class="research-section">
                    <h2 class="research-section-title"><i class="fas fa-check-circle"></i> ベストプラクティスまとめ</h2>

                    <div class="best-practice-grid">
                        <div class="best-practice-card">
                            <h3><i class="fas fa-user-tie"></i> 採用目的</h3>
                            <ul>
                                <li><strong>リアルな社員の声を発信</strong><br>先輩社員インタビュー、日常業務風景、社内イベント</li>
                                <li><strong>就活生に役立つ情報提供</strong><br>業界研究、インターン情報、選考ポイント</li>
                                <li><strong>企業ブランディング</strong><br>働きやすさ、社員の成長ストーリー、企業理念</li>
                            </ul>
                        </div>

                        <div class="best-practice-card">
                            <h3><i class="fas fa-home"></i> 売買・物件紹介目的</h3>
                            <ul>
                                <li><strong>視覚的魅力の最大化</strong><br>高品質撮影、ルームツアー、ストーリーテリング</li>
                                <li><strong>ターゲット特化型コンテンツ</strong><br>セグメント化、地域特化、ライフスタイル提案</li>
                                <li><strong>継続的な運用体制</strong><br>毎日投稿、ストーリーズ活用、トレンド意識</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- 提案への活用ポイント -->
                <section class="research-section conclusion">
                    <h2 class="research-section-title"><i class="fas fa-lightbulb"></i> 提案への活用ポイント</h2>

                    <div class="conclusion-grid">
                        <div class="conclusion-card">
                            <h4><i class="fas fa-rocket"></i> Instagram運用代行の価値</h4>
                            <p>成功事例「アイズルーム」のような<strong>3ヶ月で1万フォロワー</strong>の実績を目指せる。競合他社もInstagramに注力している現状があり、リール動画の重要性が明確。</p>
                        </div>

                        <div class="conclusion-card">
                            <h4><i class="fas fa-sync"></i> 継続的運用の重要性</h4>
                            <p>毎日投稿が基本（人員不足では困難）。業務委託なら「やめない社員」として安定運用可能。</p>
                        </div>

                        <div class="conclusion-card">
                            <h4><i class="fas fa-chart-line"></i> コンバージョン率改善</h4>
                            <p>人脈からの見込み顧客に対して、Instagramで信頼感・親近感を醸成。物件の魅力を視覚的に訴求し、背中を押す役割を果たす。</p>
                        </div>

                        <div class="conclusion-card">
                            <h4><i class="fas fa-brain"></i> マーケター視点の制作</h4>
                            <p>ただの制作会社ではなく、戦略的なコンテンツ企画が強み。エンゲージメント最大化・データ分析による継続的改善。</p>
                        </div>
                    </div>

                    <div class="final-message">
                        <i class="fas fa-star"></i>
                        <p>この調査結果から、不動産業界におけるSNS活用は<strong>継続性・質の高いコンテンツ・ターゲット特化</strong>が成功の鍵であることが明確になりました。</p>
                    </div>
                </section>
            </div>
        </div><!-- /research-tab -->
    </div><!-- /tab-content-wrapper -->

    <!-- フッター -->
    <footer class="footer">
        <p><strong><i class="fab fa-instagram"></i> Instagram運用代行サービス ご提案資料</strong></p>
        <p style="margin-top: 20px; font-size: 0.9em;">
            ご不明な点やご質問がございましたら、お気軽にお問い合わせください。<br>
            貴社に最適なプランをご提案させていただきます。
        </p>
    </footer>

    <!-- JavaScript -->
    <script>
        // タブ切り替え機能
        function switchTab(tabName) {
            // すべてのタブボタンとコンテンツから active クラスを削除
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // 選択されたタブに active クラスを追加
            event.target.closest('.tab-btn').classList.add('active');
            document.getElementById(tabName + '-tab').classList.add('active');

            // ページトップにスムーススクロール
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 目次の開閉
        function toggleToc() {
            const floatingToc = document.getElementById('floatingToc');
            floatingToc.classList.toggle('active');
        }

        // 目次を閉じる
        function closeToc() {
            const floatingToc = document.getElementById('floatingToc');
            floatingToc.classList.remove('active');
        }

        // 背景クリックで目次を閉じる
        function closeTocIfBackdrop(event) {
            if (event.target.classList.contains('floating-toc')) {
                closeToc();
            }
        }

        // アクティブなセクションをハイライト
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.section');
            const tocLinks = document.querySelectorAll('.toc a');

            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (window.pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            tocLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // ページ読み込み時のアニメーション
        document.addEventListener('DOMContentLoaded', function() {
            // スムーススクロール
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
        });
    </script>
</body>
</html>
