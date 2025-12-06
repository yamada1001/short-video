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
    <title>TUNE STAY KYOTO - Instagram撮影ガイド</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @font-face {
            font-family: 'LINE Seed JP';
            src: url('./fonts/LINESeedJP_OTF_Rg.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'LINE Seed JP';
            src: url('./fonts/LINESeedJP_OTF_Bd.otf') format('opentype');
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: 'LINE Seed JP';
            src: url('./fonts/LINESeedJP_OTF_Th.otf') format('opentype');
            font-weight: 300;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'LINE Seed JP', sans-serif;
            font-weight: 100;
            line-height: 2.0;
            color: #2c3e50;
            background: #ffffff;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-direction: row;
            gap: 30px;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            order: 1;
        }

        /* サイドバー目次 - 右側配置 */
        .sidebar-toc {
            position: sticky;
            top: 20px;
            width: 280px;
            max-height: calc(100vh - 40px);
            background: #fafafa;
            border-radius: 8px;
            padding: 25px;
            border: 1px solid #e0e0e0;
            display: none;
            order: 2;
            overflow-y: auto;
        }

        /* スクロールバーのスタイル */
        .sidebar-toc::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-toc::-webkit-scrollbar-track {
            background: #f5f5f5;
            border-radius: 3px;
        }

        .sidebar-toc::-webkit-scrollbar-thumb {
            background: #2c3e50;
            border-radius: 3px;
        }

        .sidebar-toc::-webkit-scrollbar-thumb:hover {
            background: #1a252f;
        }

        @media (min-width: 1024px) {
            .sidebar-toc {
                display: block;
            }
        }

        /* タブレット・モバイル対応 */
        @media (max-width: 1023px) {
            .container {
                flex-direction: column;
                padding: 15px;
            }

            .main-content {
                order: 1;
            }

            .sidebar-toc {
                display: none;
            }

            header {
                padding: 60px 30px;
                border-radius: 15px;
            }

            h1 {
                font-size: 2.5em;
            }

            .section {
                padding: 30px 20px;
                margin-bottom: 25px;
            }

            h2 {
                font-size: 1.8em;
            }

            h3 {
                font-size: 1.4em;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 12px;
            }

            body {
                font-size: 16px;
                line-height: 1.75;
            }

            /* 高解像度ディスプレイ（Pixel等）向け */
            @media (-webkit-min-device-pixel-ratio: 2.5) and (min-width: 360px),
                   (min-resolution: 2.5dppx) and (min-width: 360px) {
                body {
                    font-size: 17px;
                }
            }

            /* 超高解像度ディスプレイ（Pixel 8等）向け */
            @media (-webkit-min-device-pixel-ratio: 3) and (min-width: 400px),
                   (min-resolution: 3dppx) and (min-width: 400px) {
                body {
                    font-size: 18px;
                }
            }

            header {
                padding: 35px 20px;
                border-radius: 10px;
            }

            h1 {
                font-size: 1.75em;
                letter-spacing: 0.5px;
            }

            .subtitle {
                font-size: 0.95em;
                margin-top: 12px;
            }

            .section {
                padding: 25px 18px;
                margin-bottom: 20px;
                border-radius: 10px;
            }

            h2 {
                font-size: 1.5em;
                margin-bottom: 20px;
                padding-bottom: 12px;
            }

            h3 {
                font-size: 1.25em;
                margin-top: 30px;
                margin-bottom: 15px;
            }

            h4 {
                font-size: 1.1em;
                margin-top: 20px;
                margin-bottom: 12px;
            }

            p {
                font-size: 1em;
                line-height: 1.75;
                margin-bottom: 16px;
            }

            li {
                font-size: 1em;
                line-height: 1.7;
                margin-bottom: 12px;
            }

            ul {
                margin-bottom: 20px;
            }

            table {
                font-size: 0.95em;
            }

            th, td {
                padding: 12px 10px;
                font-size: 1em;
            }

            .tag {
                padding: 6px 14px;
                font-size: 0.9em;
            }

            .checklist li {
                font-size: 1em;
                padding: 16px 0 16px 45px;
            }

            .checklist li:before {
                font-size: 1.6em;
                left: 5px;
            }

            .info-box, .highlight, .photo-tip, .warning-box {
                padding: 20px;
                margin: 20px 0;
            }

            .reference {
                font-size: 0.95em;
                margin-top: 15px;
            }
        }

        /* モバイル用フローティング目次ボタン */
        .mobile-toc-button {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background: #2c3e50;
            color: white;
            border-radius: 50%;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            cursor: pointer;
            z-index: 1000;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .mobile-toc-button:active {
            transform: scale(0.95);
        }

        @media (max-width: 1023px) {
            .mobile-toc-button {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* モバイル目次オーバーレイ */
        .mobile-toc-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1100;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-toc-overlay.active {
            display: block;
            opacity: 1;
        }

        /* モバイル目次パネル */
        .mobile-toc-panel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 320px;
            height: 100%;
            background: #ffffff;
            z-index: 1200;
            overflow-y: auto;
            transition: right 0.3s ease;
            box-shadow: -4px 0 12px rgba(0,0,0,0.2);
        }

        .mobile-toc-panel.active {
            right: 0;
        }

        .mobile-toc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            background: #fafafa;
        }

        .mobile-toc-header h3 {
            margin: 0;
            font-size: 1.2em;
            color: #2c3e50;
            border: none;
            padding: 0;
        }

        .mobile-toc-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #666;
            cursor: pointer;
            padding: 5px;
            line-height: 1;
        }

        .mobile-toc-content {
            padding: 20px;
        }

        .mobile-toc-content ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mobile-toc-content li {
            margin-bottom: 8px;
        }

        .mobile-toc-content a {
            color: #666;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.95em;
            line-height: 1.4;
        }

        .mobile-toc-content a i {
            width: 18px;
            text-align: center;
            margin-right: 10px;
            color: #999;
        }

        .mobile-toc-content a:active {
            background: #f5f5f5;
            color: #2c3e50;
        }

        .mobile-toc-content a.active {
            background: #2c3e50;
            color: white;
        }

        .mobile-toc-content a.active i {
            color: white;
        }

        .sidebar-toc h3 {
            font-size: 1.1em;
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 12px;
            font-weight: 400;
        }

        .sidebar-toc ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-toc li {
            margin-bottom: 8px;
        }

        .sidebar-toc a {
            color: #666;
            text-decoration: none;
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.9em;
        }

        .sidebar-toc a i {
            width: 18px;
            text-align: center;
            margin-right: 10px;
            color: #999;
        }

        .sidebar-toc a:hover {
            background: #f5f5f5;
            color: #2c3e50;
        }

        .sidebar-toc a:hover i {
            color: #2c3e50;
        }

        .sidebar-toc a.active {
            background: #2c3e50;
            color: white;
            font-weight: 400;
        }

        .sidebar-toc a.active i {
            color: white;
        }

        header {
            background: #ffffff;
            color: #2c3e50;
            padding: 80px 40px;
            text-align: center;
            margin-bottom: 60px;
            border-bottom: 1px solid #e0e0e0;
        }

        h1 {
            font-size: 3.5em;
            margin-bottom: 15px;
            font-weight: 300;
            letter-spacing: 2px;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 1.2em;
            font-weight: 100;
            color: #666;
            letter-spacing: 1px;
        }

        .section {
            background: white;
            margin-bottom: 50px;
            padding: 50px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        h2 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 400;
        }

        h2 i {
            color: #2c3e50;
            margin-right: 12px;
            font-size: 0.9em;
        }

        h3 {
            color: #2c3e50;
            font-size: 1.5em;
            margin-top: 40px;
            margin-bottom: 20px;
            padding-left: 15px;
            border-left: 3px solid #2c3e50;
            font-weight: 400;
        }

        h4 {
            color: #2c3e50;
            font-size: 1.2em;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: 400;
        }

        h4 i {
            color: #666;
            margin-right: 8px;
            font-size: 0.9em;
        }

        p {
            margin-bottom: 20px;
            font-size: 1.05em;
            line-height: 2.2;
        }

        li {
            margin-bottom: 14px;
            font-size: 1.05em;
            line-height: 2.0;
        }

        ul {
            margin-left: 30px;
            margin-bottom: 25px;
        }

        .highlight {
            background: #fafafa;
            padding: 25px;
            border-left: 3px solid #2c3e50;
            margin: 25px 0;
            border-radius: 4px;
        }

        .photo-tip {
            background: #fafafa;
            padding: 25px;
            border-left: 3px solid #2c3e50;
            margin: 25px 0;
            border-radius: 4px;
        }

        .info-box {
            background: #fafafa;
            padding: 25px;
            border-radius: 4px;
            margin: 25px 0;
            border: 1px solid #e0e0e0;
        }

        .warning-box {
            background: #fafafa;
            padding: 25px;
            border-radius: 4px;
            margin: 25px 0;
            border-left: 3px solid #2c3e50;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 25px 0;
        }

        .grid-item {
            padding: 25px;
            background: #ffffff;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }

        .reference {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
            font-style: italic;
        }

        .reference a {
            color: #2c3e50;
            text-decoration: none;
            word-break: break-all;
            border-bottom: 1px solid #e0e0e0;
        }

        .reference a:hover {
            border-bottom-color: #2c3e50;
        }

        .sns-section {
            background: #fafafa;
            color: #2c3e50;
            padding: 30px;
            border-radius: 4px;
            margin: 20px 0;
            border: 1px solid #e0e0e0;
        }

        .sns-section h3 {
            color: #2c3e50;
            border-left-color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #fafafa;
            color: #2c3e50;
            font-weight: 400;
        }

        tr:hover {
            background-color: #fafafa;
        }

        .tag {
            display: inline-block;
            background: #2c3e50;
            color: white;
            padding: 6px 14px;
            border-radius: 3px;
            font-size: 0.85em;
            margin: 5px 5px 5px 0;
            font-weight: 400;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #666;
            margin-top: 40px;
        }

        .shot-list {
            background: #ffffff;
            color: #2c3e50;
            padding: 50px 0;
            margin: 30px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .shot-list h2 {
            color: #2c3e50;
            border-bottom-color: #e0e0e0;
        }

        .shot-category {
            background: #fafafa;
            color: #2c3e50;
            padding: 25px;
            margin: 20px 0;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }

        .shot-category h3 {
            color: #2c3e50;
            border-left-color: #2c3e50;
            margin-top: 0;
        }

        .checklist {
            list-style: none;
            margin: 15px 0;
            padding: 0;
        }

        .checklist li {
            padding: 12px 0 12px 35px;
            position: relative;
            border-bottom: 1px solid #eee;
            font-size: 1.05em;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .checklist li:hover {
            background-color: #fafafa;
        }

        .checklist li:last-child {
            border-bottom: none;
        }

        .checklist li:before {
            content: "☐";
            position: absolute;
            left: 0;
            font-size: 1.4em;
            color: #2c3e50;
            font-weight: normal;
            cursor: pointer;
        }

        .checklist li.checked:before {
            content: "☑";
            color: #2c3e50;
        }

        .checklist li.checked {
            opacity: 0.6;
            text-decoration: line-through;
        }

        .checklist li.priority-high:after {
            content: "★必須";
            margin-left: 10px;
            background: #2c3e50;
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: 400;
        }

        .checklist li.priority-medium:after {
            content: "●重要";
            margin-left: 10px;
            background: #666;
            color: white;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: 400;
        }

        .shot-note {
            background: #fafafa;
            padding: 10px 15px;
            margin: 10px 0;
            border-left: 3px solid #2c3e50;
            border-radius: 4px;
            font-size: 0.95em;
        }

        .time-badge {
            display: inline-block;
            background: #2c3e50;
            color: white;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 0.85em;
            margin-right: 10px;
            font-weight: 400;
        }

        @media print {
            body {
                background: white;
            }
            .section {
                box-shadow: none;
                page-break-inside: avoid;
            }
            .checklist li:before {
                content: "□";
            }
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="container">
        <!-- メインコンテンツ -->
        <div class="main-content">
            <header>
                <h1>TUNE STAY KYOTO</h1>
                <p class="subtitle">Instagram撮影ガイド & ホテル情報レポート</p>
                <p style="margin-top: 20px; font-size: 0.9em; position: relative; z-index: 1;">作成日: 2025年12月6日</p>
            </header>

            <!-- 基本情報 -->
            <div class="section" id="basic-info">
            <h2><i class="fas fa-map-marker-alt"></i> 基本情報</h2>

            <div class="info-box">
                <h4>ホテル名</h4>
                <p>TUNE STAY KYOTO(チューン ステイ キョウト)</p>

                <h4>所在地</h4>
                <p>〒600-8310 京都市下京区七条通新町西入夷之町708</p>

                <h4>アクセス</h4>
                <p>JR京都駅中央口から徒歩5分</p>

                <h4>フロント営業時間</h4>
                <p>7:00〜24:00</p>

                <h4>連絡先</h4>
                <p>電話: 075-644-6660<br>
                メール: kyoto@tune-stay.com</p>
            </div>

            <h3>運営会社</h3>
            <div class="info-box">
                <p><strong>運営:</strong> <a href="https://www.tat-group.co.jp/" target="_blank" style="color: #2c3e50; text-decoration: underline;">株式会社ティーエーティー(TAT Co., Ltd.)</a></p>
                <p><strong>代表取締役:</strong> 田畑 伸幸（たばた のぶゆき）</p>
                <p><strong>設立:</strong> 2006年1月27日</p>
                <p><strong>資本金:</strong> 1,000万円</p>
                <p><strong>土地・建物所有:</strong> <a href="https://www.n-up.co.jp/" target="_blank" style="color: #2c3e50; text-decoration: underline;">新都市企画株式会社</a></p>
                <p><strong>グループ:</strong> <a href="https://piecehotel.com/" target="_blank" style="color: #2c3e50; text-decoration: underline;">PIECE GROUP</a>（4号店）</p>
                <p style="margin-top: 10px; font-size: 0.95em;">株式会社ティーエーティーは京都でPIECEブランドのホステル・ホテルを運営しており、TUNE STAY KYOTOはそのフラッグシップホテルとして位置づけられています。</p>
            </div>

            <h3>コンセプト</h3>
            <div class="highlight">
                <p><strong>「人生に心躍る寄り道を」</strong>をコンセプトに、3つのコンテンツ体験を提供する体験型ホテル</p>
                <div style="margin-top: 15px;">
                    <span class="tag">GIN(ジン)</span>
                    <span class="tag">BOOKS(本)</span>
                    <span class="tag">SHORT FILM(ショートフィルム)</span>
                </div>
            </div>

            <h4 style="margin-top: 25px;"><i class="fas fa-cocktail"></i> GIN（ジン）の意味</h4>
            <div class="photo-tip">
                <p>「GIN」は単なるお酒の提供ではなく、<strong>宿泊者が自分で組み合わせを考え、創造する体験</strong>を通じて、新しい発見や出会いを楽しむというコンセプトです。</p>
                <ul style="margin-top: 15px;">
                    <li><strong>カスタマイズ体験:</strong> 24種類のクラフトジン × 6種類のトニックウォーターから自分だけのオリジナルジントニックを創造</li>
                    <li><strong>探求心:</strong> 味わいを想像しながら組み合わせを考えることで、新しい発見を楽しむ</li>
                    <li><strong>調和(TUNE):</strong> 作り手の波長と使い手の波長が調和された時に生まれる「共鳴のハーモニー」</li>
                    <li><strong>寄り道:</strong> 旅の途中での予期せぬ出会いや発見を象徴</li>
                </ul>
                <p style="margin-top: 15px;"><strong>料金:</strong> 宿泊者限定 ¥1,000で3杯分のテイスティング体験</p>
            </div>

            <h3>開業情報</h3>
            <p>2019年11月開業。PIECEグループの第4号ホテルで、「Harmonic Hotel(調和するホテル)」というコンセプトで設計されました。</p>

            <div class="reference">
                参照元: <a href="https://www.tune-stay.com/" target="_blank">https://www.tune-stay.com/</a><br>
                参照元: <a href="https://www.tune-stay.com/gin" target="_blank">https://www.tune-stay.com/gin</a><br>
                参照元: <a href="https://www.booking.com/hotel/jp/piece-nanajo.html" target="_blank">https://www.booking.com/hotel/jp/piece-nanajo.html</a><br>
                参照元: <a href="https://www.n-up.co.jp/projects/535/" target="_blank">https://www.n-up.co.jp/projects/535/</a>
            </div>
        </div>

        <!-- Instagram・SNS情報 -->
        <div class="section" id="sns-info">
            <h2><i class="fas fa-hashtag"></i> SNS情報</h2>

            <div class="sns-section">
                <h3>Instagram</h3>
                <p><strong>アカウント:</strong> @tune_stay_kyoto</p>
                <p><strong>フォロワー:</strong> 約10,000人</p>
                <p><strong>投稿数:</strong> 128投稿</p>
                <p style="margin-top: 15px;">ライフスタイルホテルとしての雰囲気、本のコレクション、ジンバーの写真などが投稿されています。</p>
            </div>

            <h3>TikTok</h3>
            <p>複数のクリエイターがTUNE STAY KYOTOを特集しており、以下のような特徴が紹介されています:</p>
            <ul>
                <li>9階ラウンジでのアルコール・スナック・カップ麺食べ放題</li>
                <li>京都駅から徒歩5分の好立地</li>
                <li>英語対応可能なスタッフ</li>
                <li>コージーな雰囲気</li>
                <li>朝食が500円で提供(ロックスベーグル、卵など)</li>
            </ul>

            <div class="reference">
                参照元: <a href="https://www.tiktok.com/@hotel_diary01/video/7149158356727385345" target="_blank">TikTok - @hotel_diary01</a><br>
                参照元: <a href="https://www.tiktok.com/@kanakoworld16/video/7130091478168194306" target="_blank">TikTok - @kanakoworld16</a><br>
                参照元: <a href="https://www.tiktok.com/@life0ftayl0r/video/7320295017992441119" target="_blank">TikTok - @life0ftayl0r</a>
            </div>
        </div>

        <!-- 施設構成 -->
        <div class="section" id="facility">
            <h2><i class="fas fa-building"></i> 施設構成</h2>

            <h3>本館(メインビルディング)</h3>
            <p>コンパクトで機能的な客室設計。ブックホテルとして知られ、約2,000〜2,500冊の京都関連書籍を揃えています。</p>

            <h3>別館「HIDEOUT(ハイドアウト)」</h3>
            <p><strong>2022年3月オープン</strong></p>
            <p>「隠れ家」をコンセプトにしたラグジュアリースイートルーム専用の別館。全16室(8タイプ)で、広さは59〜93㎡。キッチン付きの上質な空間で、専用カードキーでアクセスします。</p>

            <div class="highlight">
                <h4>HIDEOUT SUITE の特徴</h4>
                <ul>
                    <li>最大4名宿泊可能(ゲスト2名まで招待可)</li>
                    <li>全面窓ガラスで自然光がたっぷり入る明るいリビング</li>
                    <li>対面式アイランドキッチン完備</li>
                    <li>大理石造りのラグジュアリーバスルーム</li>
                    <li>専用クラブラウンジ(8階・9階)へのアクセス</li>
                </ul>
            </div>

            <div class="reference">
                参照元: <a href="https://www.tune-stay.com/suite" target="_blank">https://www.tune-stay.com/suite</a><br>
                参照元: <a href="https://kiwakohori.com/travel-tunestaykyoto/" target="_blank">https://kiwakohori.com/travel-tunestaykyoto/</a><br>
                参照元: <a href="https://note.com/tripx/n/nbde492414d75" target="_blank">https://note.com/tripx/n/nbde492414d75</a>
            </div>
        </div>

        <!-- 客室タイプ -->
        <div class="section" id="rooms">
            <h2><i class="fas fa-bed"></i> 客室タイプ</h2>

            <h3>本館</h3>
            <table>
                <tr>
                    <th>客室タイプ</th>
                    <th>広さ</th>
                    <th>特徴</th>
                </tr>
                <tr>
                    <td>シングルルーム</td>
                    <td>7.5㎡</td>
                    <td>カプセルホテル風のコンパクト設計</td>
                </tr>
                <tr>
                    <td>ダブルルーム</td>
                    <td>12㎡</td>
                    <td>デスク・ワークスペース付き</td>
                </tr>
                <tr>
                    <td>ツインルーム</td>
                    <td>11㎡</td>
                    <td>二段ベッドタイプ</td>
                </tr>
                <tr>
                    <td>ツインスペリアルルーム</td>
                    <td>16㎡</td>
                    <td>本館で最も広い客室</td>
                </tr>
            </table>

            <p><strong>デザイン特徴:</strong> シンプルで洗練された北欧系インテリア、暗めの照明で落ち着いた雰囲気</p>

            <h3>HIDEOUT別館</h3>
            <table>
                <tr>
                    <th>客室タイプ</th>
                    <th>広さ</th>
                    <th>宿泊定員</th>
                    <th>料金目安(2名)</th>
                </tr>
                <tr>
                    <td>ジュニアスイート</td>
                    <td>59㎡〜</td>
                    <td>2〜4名</td>
                    <td>¥30,000〜¥35,000</td>
                </tr>
                <tr>
                    <td>プレミアム2ベッドルームスイート</td>
                    <td>93㎡</td>
                    <td>最大4名</td>
                    <td>¥60,000〜</td>
                </tr>
            </table>

            <div class="reference">
                参照元: <a href="https://gigazine.net/gsc_news/en/20191018-tune-stay-kyoto-hotel/" target="_blank">https://gigazine.net/gsc_news/en/20191018-tune-stay-kyoto-hotel/</a><br>
                参照元: <a href="https://travel.rakuten.com/hotel/Japan-Kyoto_Prefecture-Kyoto-Tune_Stay_Kyoto/177977/" target="_blank">楽天トラベル - TUNE STAY KYOTO</a>
            </div>
        </div>

        <!-- 撮影スポット -->
        <div class="section" id="photo-spots">
            <h2><i class="fas fa-camera"></i> Instagram撮影スポット</h2>

            <div class="photo-tip">
                <h4><i class="fas fa-bullseye"></i> 最重要撮影ポイント</h4>
                <p><strong>ブックギャラリー(大階段エリア)</strong></p>
                <p>壁一面に約2,000〜2,500冊の京都関連書籍が並ぶ圧巻の空間。階段に沿って設置された本棚は、木製部分が腰掛けとして機能し、「泊まれる本屋」としての象徴的なスペースです。</p>
            </div>

            <div class="grid">
                <div class="grid-item">
                    <h4>1. ブックギャラリー</h4>
                    <p><strong>特徴:</strong></p>
                    <ul>
                        <li>2,000〜2,500冊の京都関連書籍</li>
                        <li>ハシゴで上の本にもアクセス可能</li>
                        <li>階段に腰掛けて撮影可能</li>
                        <li>間接照明で柔らかい雰囲気</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> 階段の上部から見下ろすアングル、本棚を背景にしたポートレート</p>
                </div>

                <div class="grid-item">
                    <h4>2. カフェ＆バー</h4>
                    <p><strong>営業時間:</strong> 7:00〜24:00</p>
                    <ul>
                        <li>落ち着いた照明</li>
                        <li>大画面でのショートフィルム上映</li>
                        <li>京都ビールラボとのコラボドリンク</li>
                        <li>おしゃれなカウンター席</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> ドリンクと本を組み合わせた構図、夜の雰囲気撮影</p>
                </div>

                <div class="grid-item">
                    <h4>3. GINバー</h4>
                    <p><strong>営業時間:</strong> 17:00〜22:30(宿泊者限定)</p>
                    <ul>
                        <li>世界中のクラフトジンコレクション</li>
                        <li>カスタムG&T作成体験</li>
                        <li>人気メニュー: Blue Hour(¥800)</li>
                        <li>青いジンがトニックでピンクに変化</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> カラーチェンジの瞬間、ボトルコレクション</p>
                </div>

                <div class="grid-item">
                    <h4>4. 共用キッチン</h4>
                    <p><strong>利用時間:</strong> 24時間</p>
                    <ul>
                        <li>広々としたキッチンスペース</li>
                        <li>調理器具一式完備</li>
                        <li>白を基調とした清潔感</li>
                        <li>テーブル席多数</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> 朝の自然光を活かした撮影</p>
                </div>

                <div class="grid-item">
                    <h4>5. 個室風呂(大小2カ所)</h4>
                    <ul>
                        <li>大浴場: 二人で利用できる広さ</li>
                        <li>小浴場: 一人用サイズ</li>
                        <li>ダイソンドライヤー完備</li>
                        <li>当日予約制</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> アメニティの美しい配置</p>
                </div>

                <div class="grid-item">
                    <h4>6. HIDEOUTラウンジ</h4>
                    <p><strong>8階:</strong> 本・レコード・ボードゲーム<br>
                    <strong>9階:</strong> フリーフロー(アルコール・スナック)</p>
                    <ul>
                        <li>ワインディスペンサー</li>
                        <li>ビール・ソフトドリンク飲み放題</li>
                        <li>ナッツ・菓子類フリー</li>
                        <li>レコードプレーヤー</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> ワイングラスと本の組み合わせ</p>
                </div>

                <div class="grid-item">
                    <h4>7. HIDEOUTスイート客室</h4>
                    <ul>
                        <li>全面窓ガラスのリビング</li>
                        <li>大理石のバスルーム</li>
                        <li>モダンなアイランドキッチン</li>
                        <li>コーナーローソファー</li>
                    </ul>
                    <p><strong>撮影のコツ:</strong> 自然光を活かした昼間の撮影、ラグジュアリー感の演出</p>
                </div>

                <div class="grid-item">
                    <h4>8. ショートフィルム上映</h4>
                    <p>毎晩、大階段エリアでショートフィルムを上映。階段に座って映画鑑賞できる独特の体験。</p>
                    <p><strong>撮影のコツ:</strong> 上映時の雰囲気、観客の様子</p>
                </div>
            </div>

            <div class="photo-tip">
                <h4><i class="fas fa-lightbulb"></i> 撮影アドバイス</h4>
                <ul>
                    <li><strong>照明:</strong> 全体的に間接照明で落ち着いた雰囲気。モノトーンを基調とした洗練された空間</li>
                    <li><strong>時間帯:</strong> 朝の自然光(キッチン・HIDEOUT)、夕方〜夜の雰囲気(バー・ラウンジ)</li>
                    <li><strong>構図:</strong> 本を活かした構図が効果的。ライフスタイル感を演出</li>
                    <li><strong>統一感:</strong> シンプルで洗練された北欧系デザインが全館で統一されている</li>
                </ul>
            </div>

            <div class="reference">
                参照元: <a href="https://leoleophoto.com/tune-stay-kyoto/" target="_blank">https://leoleophoto.com/tune-stay-kyoto/</a><br>
                参照元: <a href="https://tabino-blog.hatenablog.com/entry/2023/02/08/080000" target="_blank">https://tabino-blog.hatenablog.com/entry/2023/02/08/080000</a><br>
                参照元: <a href="https://naochen.blog.jp/kyoto_tunestaykyoto2207.html" target="_blank">https://naochen.blog.jp/kyoto_tunestaykyoto2207.html</a>
            </div>
        </div>

        <!-- アメニティ・設備 -->
        <div class="section" id="amenities">
            <h2><i class="fas fa-spray-can"></i> アメニティ・設備</h2>

            <h3>基本アメニティ</h3>
            <div class="grid">
                <div class="grid-item">
                    <h4>バスルーム</h4>
                    <ul>
                        <li>POLAのシャンプー・コンディショナー・ボディソープ</li>
                        <li>Panasonicドライヤー</li>
                        <li>使い捨てスリッパ</li>
                        <li>タオル(エレベーターホールで追加取得可)</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4>貸出品(フロント)</h4>
                    <ul>
                        <li>カミソリ</li>
                        <li>ヘアゴム</li>
                        <li>アイロン</li>
                        <li>デスクライト(客室用)</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4>HIDEOUT専用アメニティ</h4>
                    <ul>
                        <li>OSAJIブランドのスキンケア</li>
                        <li>ダイソンドライヤー(大浴場)</li>
                        <li>高級バスアメニティ</li>
                    </ul>
                </div>
            </div>

            <h3>客室設備</h3>
            <ul>
                <li>Wi-Fi完備</li>
                <li>エアコン</li>
                <li>インターネット対応TV</li>
                <li>デスク(ダブルルーム以上)</li>
            </ul>

            <h3>HIDEOUT客室設備</h3>
            <ul>
                <li>フルキッチン(IHコンロ、冷蔵庫、トースター、電子レンジ、ケトル)</li>
                <li>調理器具・食器一式</li>
                <li>レコードプレーヤー</li>
                <li>サウンドスピーカー</li>
                <li>加湿空気清浄機</li>
                <li>ダブルボウル洗面台</li>
            </ul>

            <h3>共用施設</h3>
            <ul>
                <li>共用キッチン(24時間利用可)</li>
                <li>大浴場・小浴場(当日予約制)</li>
                <li>ランドリー</li>
                <li>荷物預かりサービス</li>
                <li>ラウンジ</li>
            </ul>

            <div class="reference">
                参照元: <a href="https://tabino-blog.hatenablog.com/entry/2023/02/08/080000" target="_blank">https://tabino-blog.hatenablog.com/entry/2023/02/08/080000</a>
            </div>
        </div>

        <!-- 食事・ドリンク -->
        <div class="section" id="food">
            <h2><i class="fas fa-utensils"></i> 食事・ドリンク</h2>

            <h3>朝食</h3>
            <p><strong>提供時間:</strong> 7:00〜11:00<br>
            <strong>料金:</strong> ¥500〜¥600</p>

            <div class="info-box">
                <h4>メニュー内容</h4>
                <ul>
                    <li>焼きたてベーグルサンド(3種類から選択)</li>
                    <li>スモークサーモン</li>
                    <li>ソーセージ</li>
                    <li>ベジタリアンオプション</li>
                    <li>ドリンク: コーヒー、紅茶、オレンジジュース、グアバジュース</li>
                </ul>
                <p><strong>特徴:</strong> 西洋風の朝食で、日本の物価を考えると非常にお得な価格設定</p>
            </div>

            <h3>カフェ＆バー</h3>
            <p><strong>営業時間:</strong> 7:00〜24:00</p>
            <ul>
                <li>スペシャルティコーヒー</li>
                <li>京都で醸造されたクラフトビール</li>
                <li>京都ビールラボとのコラボドリンク(ワサビ塩ゴーズなど)</li>
            </ul>

            <h3>GINバー</h3>
            <p><strong>営業時間:</strong> 17:00〜22:30(宿泊者限定)</p>
            <div class="highlight">
                <h4>体験型ジンバー</h4>
                <p>世界中のクラフトジンから自分好みのジン、トニックウォーター、ガーニッシュを選んでカスタムジントニックを作成できます。</p>
                <p><strong>人気メニュー:</strong></p>
                <ul>
                    <li>Blue Hour(¥800) - 青いジンがトニックでピンクに変化</li>
                    <li>各種クラフトジン</li>
                </ul>
            </div>

            <h3>HIDEOUTクラブラウンジ</h3>
            <p><strong>利用:</strong> HIDEOUT宿泊者専用、オールインクルーシブ</p>

            <h4>9階ラウンジ</h4>
            <ul>
                <li>ビール(飲み放題)</li>
                <li>ワイン(コイン制: 1人2枚で2杯)</li>
                <li>ソフトドリンク(飲み放題)</li>
                <li>ナッツ・菓子類(フリーフロー)</li>
                <li>カップラーメン(持ち出し可)</li>
            </ul>

            <h4>8階ラウンジ</h4>
            <ul>
                <li>レコード鑑賞</li>
                <li>ボードゲーム</li>
                <li>書籍(大人向け・絵本)</li>
            </ul>

            <div class="reference">
                参照元: <a href="https://www.tune-stay.com/gin" target="_blank">https://www.tune-stay.com/gin</a><br>
                参照元: <a href="https://naochen.blog.jp/kyoto_tunestaykyoto2207.html" target="_blank">https://naochen.blog.jp/kyoto_tunestaykyoto2207.html</a>
            </div>
        </div>

        <!-- 3つのコンテンツ体験 -->
        <div class="section" id="contents">
            <h2><i class="fas fa-film"></i> 3つのコンテンツ体験</h2>

            <div class="grid">
                <div class="grid-item">
                    <h4><i class="fas fa-book"></i> BOOKS</h4>
                    <p>人気書店「B&B」プロデュースによる京都テーマの書店</p>
                    <ul>
                        <li>約2,500冊の京都関連書籍</li>
                        <li>ガイドブック、小説、写真集、漫画</li>
                        <li>24時間閲覧可能</li>
                        <li>壁一面の本棚が圧巻</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4><i class="fas fa-video"></i> SHORT FILM</h4>
                    <p>毎晩開催されるショートフィルム上映</p>
                    <ul>
                        <li>大階段エリアで上映</li>
                        <li>階段に座って鑑賞</li>
                        <li>大画面での映画体験</li>
                        <li>独特の空間演出</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4><i class="fas fa-cocktail"></i> GIN</h4>
                    <p>クラフトジンに特化したバー体験</p>
                    <ul>
                        <li>世界中のクラフトジン</li>
                        <li>カスタムG&T作成</li>
                        <li>宿泊者限定</li>
                        <li>17:00〜22:30営業</li>
                    </ul>
                </div>
            </div>

            <div class="highlight">
                <h4>体験型ホテルとしての魅力</h4>
                <p>単なる宿泊施設ではなく、「本を読み、映画を観て、ジンを楽しむ」という3つの文化体験を通じて、京都での滞在をより豊かにする設計がされています。</p>
            </div>

            <div class="reference">
                参照元: <a href="https://prtimes.jp/main/html/rd/p/000000020.000049053.html" target="_blank">https://prtimes.jp/main/html/rd/p/000000020.000049053.html</a>
            </div>
        </div>

        <!-- 周辺撮影スポット -->
        <div class="section" id="nearby">
            <h2><i class="fas fa-map-marked-alt"></i> 周辺の撮影スポット(徒歩圏内)</h2>

            <div class="info-box">
                <h4>京都駅ビル内</h4>
                <ul>
                    <li><strong>大階段:</strong> 171段の階段、夜は色とりどりのイルミネーション</li>
                    <li><strong>空中経路:</strong> 夕日と京都タワーの絶景ビュー</li>
                    <li><strong>カフェエリア:</strong> モダンな建築とカフェの組み合わせ</li>
                </ul>
            </div>

            <div class="grid">
                <div class="grid-item">
                    <h4>東本願寺</h4>
                    <p>2016年に大規模改修完了。印象的な建築が撮影スポット。徒歩数分の距離。</p>
                </div>

                <div class="grid-item">
                    <h4>TKP Garden City Kyoto</h4>
                    <p>ホテルから徒歩6分</p>
                </div>

                <div class="grid-item">
                    <h4>三十三間堂</h4>
                    <p>約1.2マイル(約1.9km)</p>
                </div>

                <div class="grid-item">
                    <h4>清水寺</h4>
                    <p>約1.9マイル(約3km)</p>
                </div>
            </div>

            <div class="warning-box">
                <h4>アクセスの良さ</h4>
                <p>京都駅から徒歩5分という立地により、京都市内の主要な撮影スポットへのアクセスが非常に便利です。駅ビル内の商業施設も充実しており、撮影の合間の休憩や買い物にも便利です。</p>
            </div>

            <div class="reference">
                参照元: <a href="https://rurubu.jp/andmore/article/5407" target="_blank">るるぶ - 京都駅ビルフォトジェニックスポット</a><br>
                参照元: <a href="https://kyotokimonorental.com/blog/article/id/kyoto-15-photo-spot" target="_blank">京都のインスタ映えスポット15選</a>
            </div>
        </div>

        <!-- 料金・予約情報 -->
        <div class="section" id="pricing">
            <h2><i class="fas fa-yen-sign"></i> 料金目安</h2>

            <h3>本館</h3>
            <div class="info-box">
                <p><strong>スタンダードルーム:</strong> 1泊2名で約¥8,000〜(1名あたり¥4,000〜)</p>
                <p><strong>朝食付きプラン:</strong> 平日1泊朝食付きで¥4,500〜</p>
                <p><strong>特徴:</strong> リーズナブルな価格設定で、京都駅至近という立地を考えると非常にコストパフォーマンスが高い</p>
            </div>

            <h3>HIDEOUT別館</h3>
            <div class="info-box">
                <p><strong>ジュニアスイート:</strong> 2名利用で¥30,000〜¥35,000</p>
                <p><strong>プレミアム2ベッドルームスイート:</strong> 2名利用で¥60,000〜</p>
                <p><strong>特典:</strong> クラブラウンジのフリーフロー(アルコール・スナック)込み</p>
            </div>

            <div class="reference">
                参照元: <a href="https://tabino-blog.hatenablog.com/entry/2023/02/08/080000" target="_blank">https://tabino-blog.hatenablog.com/entry/2023/02/08/080000</a><br>
                参照元: <a href="https://travel.rakuten.com/hotel/Japan-Kyoto_Prefecture-Kyoto-Tune_Stay_Kyoto/177977/" target="_blank">楽天トラベル</a>
            </div>
        </div>

        <!-- レビュー・口コミ -->
        <div class="section" id="reviews">
            <h2><i class="fas fa-star"></i> レビュー・評価</h2>

            <div class="highlight">
                <h4>総合評価</h4>
                <p><strong>カップルの評価:</strong> 9.3/10(2人旅行での評価)</p>
                <p><strong>ロケーション評価:</strong> 非常に高評価(京都駅から徒歩5分)</p>
            </div>

            <h3>ポジティブなレビュー</h3>
            <ul>
                <li>「リーズナブルで共有スペースがおしゃれで充実」</li>
                <li>「スタッフが親切で英語対応も可能」</li>
                <li>「コージーな雰囲気で居心地が良い」</li>
                <li>「本のコレクションが素晴らしい」</li>
                <li>「クラブラウンジが楽しすぎる」</li>
                <li>「アメリカ人旅行者: 3つ星ホテルだが、アメリカの5つ星に匹敵」</li>
                <li>「周辺に多くの居酒屋があり、夜遅くまで食事を楽しめる」</li>
                <li>「設備とサービスが優れており、コストパフォーマンス抜群」</li>
            </ul>

            <h3>注意点</h3>
            <ul>
                <li>本館の客室は非常にコンパクト(7.5〜16㎡)</li>
                <li>客室の照明が暗め(デスクライトはフロントで貸出可能)</li>
                <li>個室風呂は当日予約制(先着順)</li>
            </ul>

            <div class="reference">
                参照元: <a href="https://www.booking.com/hotel/jp/piece-nanajo.html" target="_blank">Booking.com レビュー</a><br>
                参照元: <a href="https://tabino-blog.hatenablog.com/entry/2023/02/08/080000" target="_blank">宿泊ブログレビュー</a><br>
                参照元: <a href="https://www.tiktok.com/@life0ftayl0r/video/7320295017992441119" target="_blank">TikTok レビュー</a>
            </div>
        </div>

        <!-- 撮影時の注意事項 -->
        <div class="section" id="shooting-tips">
            <h2><i class="fas fa-exclamation-triangle"></i> Instagram撮影時の注意事項とアドバイス</h2>

            <div class="warning-box">
                <h4>撮影許可について</h4>
                <p>商業目的での撮影の場合、事前にホテルへ許可を取ることをお勧めします。フロント営業時間は7:00〜24:00です。</p>
                <p><strong>連絡先:</strong> 075-644-6660 / kyoto@tune-stay.com</p>
            </div>

            <h3>撮影のベストタイミング</h3>
            <div class="grid">
                <div class="grid-item">
                    <h4>朝(7:00〜11:00)</h4>
                    <ul>
                        <li>共用キッチンの自然光</li>
                        <li>朝食シーン</li>
                        <li>HIDEOUTの明るいリビング</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4>昼(11:00〜17:00)</h4>
                    <ul>
                        <li>ブックギャラリー</li>
                        <li>カフェエリア</li>
                        <li>客室の自然光撮影</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4>夕方〜夜(17:00〜24:00)</h4>
                    <ul>
                        <li>GINバー</li>
                        <li>ショートフィルム上映</li>
                        <li>ラウンジの雰囲気</li>
                        <li>間接照明を活かした撮影</li>
                    </ul>
                </div>
            </div>

            <h3>撮影機材アドバイス</h3>
            <ul>
                <li><strong>照明:</strong> 全体的に暗めなので、明るいレンズ(F1.8〜F2.8)が推奨</li>
                <li><strong>ISO:</strong> 高ISO対応のカメラが有利(ISO 3200〜6400程度)</li>
                <li><strong>三脚:</strong> 夜間撮影には三脚があると便利(事前確認推奨)</li>
                <li><strong>スマートフォン:</strong> ナイトモード搭載機種が有効</li>
            </ul>

            <h3>Instagram投稿のハッシュタグ提案</h3>
            <div class="info-box">
                <p>
                    <span class="tag">#TUNESTAYKYOTO</span>
                    <span class="tag">#チューンステイキョウト</span>
                    <span class="tag">#京都ホテル</span>
                    <span class="tag">#京都旅行</span>
                    <span class="tag">#ブックホテル</span>
                    <span class="tag">#泊まれる本屋</span>
                    <span class="tag">#京都カフェ</span>
                    <span class="tag">#京都駅</span>
                    <span class="tag">#クラフトジン</span>
                    <span class="tag">#京都インスタ映え</span>
                    <span class="tag">#京都フォトスポット</span>
                    <span class="tag">#ライフスタイルホテル</span>
                    <span class="tag">#HIDEOUT</span>
                </p>
            </div>
        </div>

        <!-- ブログ記事情報 -->
        <div class="section" id="references">
            <h2><i class="fas fa-newspaper"></i> 参考ブログ記事・メディア</h2>

            <h3>詳細な宿泊記</h3>
            <ul>
                <li><a href="https://tabino-blog.hatenablog.com/entry/2023/02/08/080000" target="_blank">たびブログ - 京都駅近ホテル「TUNE STAY KYOTO」宿泊記</a></li>
                <li><a href="https://naochen.blog.jp/kyoto_tunestaykyoto2207.html" target="_blank">自己中心食日記 - クラブラウンジが楽しすぎる京都のホテル</a></li>
                <li><a href="https://kiwakohori.com/travel-tunestaykyoto/" target="_blank">旅ブログ - TUNE STAY KYOTO 隠れ家スイート宿泊記</a></li>
                <li><a href="https://as-book-hotel.com/tunetasykyoto-2733" target="_blank">京都のブックホテル - 5つの魅力！子連れ宿泊記</a></li>
                <li><a href="https://leoleophoto.com/tune-stay-kyoto/" target="_blank">Leo Leo Photo - 京都駅徒歩5分の安くてオシャレなホテル</a></li>
            </ul>

            <h3>メディア掲載</h3>
            <ul>
                <li><a href="https://gigazine.net/gsc_news/en/20191018-tune-stay-kyoto-hotel/" target="_blank">GIGAZINE - 泊まれる図書館のようなホテル詳細レポート</a></li>
                <li><a href="https://www.kyoto.coop/musubi/cat341/tune_stay_kyoto/" target="_blank">むすび。- 不思議な癒し空間で夜ふかししたい夏の夜</a></li>
                <li><a href="https://classy-online.jp/lifestyle/223327/" target="_blank">CLASSY. - 専用クラブラウンジも！豪華キッチン付きホテルHIDEOUT宿泊レポート</a></li>
                <li><a href="https://note.com/tripx/n/nbde492414d75" target="_blank">TRIPX - 本館と別館HIDEOUT徹底比較</a></li>
                <li><a href="https://readytoland.com/ja/kyoto-hideout-suite-hotel/" target="_blank">Ready to Land - 大人の隠れ家「HIDEOUT SUITE」</a></li>
            </ul>

            <h3>SNS</h3>
            <ul>
                <li><strong>Instagram:</strong> <a href="https://www.instagram.com/tune_stay_kyoto/" target="_blank">@tune_stay_kyoto</a> (10K フォロワー)</li>
                <li><strong>TikTok:</strong> 複数のクリエイターが投稿(上記参照元参照)</li>
            </ul>
        </div>

        <!-- 撮影カットリスト -->
        <div class="shot-list" id="shot-list">
            <h2><i class="fas fa-video"></i> 撮影カットリスト(マスト撮影項目)</h2>
            <p style="margin-bottom: 30px; font-size: 1.1em;">各項目をチェックしながら撮影を進めてください。★必須は絶対に撮るべきカット、●重要は優先度が高いカットです。</p>

            <!-- 1. ブックギャラリー -->
            <div class="shot-category">
                <h3>1. ブックギャラリー(大階段エリア)</h3>
                <div class="shot-note">
                    <span class="time-badge">11:00-17:00推奨</span>
                    <strong>撮影のポイント:</strong> 自然光と間接照明のバランスが良い時間帯。ホテルの最重要撮影スポット
                </div>
                <ul class="checklist">
                    <li class="priority-high">壁一面の本棚全体を収めた引きのショット(縦・横両方)</li>
                    <li class="priority-high">階段の下から見上げるアングル(本棚の壮大さを強調)</li>
                    <li class="priority-high">階段の上部から見下ろすアングル</li>
                    <li class="priority-medium">階段に座って本を読むポートレート</li>
                    <li class="priority-medium">本棚にかかったハシゴのディテール</li>
                    <li class="priority-medium">本の背表紙のクローズアップ(京都関連書籍を強調)</li>
                    <li>階段の木製部分に腰掛けたシーン</li>
                    <li>本を手に取っている手元のショット</li>
                    <li>複数人が階段に座って談笑しているシーン</li>
                </ul>
            </div>

            <!-- 2. GINバー -->
            <div class="shot-category">
                <h3>2. GINバー</h3>
                <div class="shot-note">
                    <span class="time-badge">17:00-22:30</span>
                    <strong>撮影のポイント:</strong> 夕方から夜の雰囲気を活かす。カラーチェンジドリンクは必須
                </div>
                <ul class="checklist">
                    <li class="priority-high">Blue Hour(青→ピンクに変化)のカラーチェンジの瞬間を連写</li>
                    <li class="priority-high">ジンボトルコレクション全体のショット</li>
                    <li class="priority-medium">カウンター席の雰囲気(バーテンダー目線)</li>
                    <li class="priority-medium">カスタムG&T作成プロセス(ジン選び→トニック→ガーニッシュ)</li>
                    <li>グラスのクローズアップ(氷、ガーニッシュのディテール)</li>
                    <li>複数のジンカクテルを並べたフラットレイ</li>
                    <li>バーカウンターの照明と雰囲気</li>
                    <li>ジンボトルの個別ショット(特徴的なボトルデザイン)</li>
                </ul>
            </div>

            <!-- 3. カフェ＆バーエリア -->
            <div class="shot-category">
                <h3>3. カフェ＆バーエリア</h3>
                <div class="shot-note">
                    <span class="time-badge">7:00-24:00</span>
                    <strong>撮影のポイント:</strong> 朝と夜で異なる雰囲気を撮り分ける
                </div>
                <ul class="checklist">
                    <li class="priority-high">カフェカウンター全体のショット</li>
                    <li class="priority-medium">コーヒーと本を組み合わせた構図</li>
                    <li class="priority-medium">カウンター席から見たバーエリア</li>
                    <li>大画面でのショートフィルム上映シーン</li>
                    <li>京都ビールラボのドリンク</li>
                    <li>カフェでの読書シーン</li>
                    <li>照明の雰囲気(夜の間接照明)</li>
                </ul>
            </div>

            <!-- 4. 本館客室 -->
            <div class="shot-category">
                <h3>4. 本館客室</h3>
                <div class="shot-note">
                    <span class="time-badge">9:00-11:00推奨</span>
                    <strong>撮影のポイント:</strong> 自然光を活かす。コンパクトな空間を広く見せる工夫
                </div>
                <ul class="checklist">
                    <li class="priority-high">ダブルルーム全体(広角で広さを強調)</li>
                    <li class="priority-medium">ベッド周りのディテール(リネン、枕)</li>
                    <li class="priority-medium">デスク・ワークスペース</li>
                    <li>洗面台エリア</li>
                    <li>北欧系インテリアの特徴的な家具</li>
                    <li>窓からの光が差し込むシーン</li>
                    <li>客室の間接照明(夜バージョン)</li>
                </ul>
            </div>

            <!-- 5. HIDEOUTスイート客室 -->
            <div class="shot-category">
                <h3>5. HIDEOUTスイート客室</h3>
                <div class="shot-note">
                    <span class="time-badge">10:00-16:00推奨</span>
                    <strong>撮影のポイント:</strong> 全面窓ガラスからの自然光を最大限活用。ラグジュアリー感を演出
                </div>
                <ul class="checklist">
                    <li class="priority-high">リビング全体(全面窓ガラスと自然光)</li>
                    <li class="priority-high">大理石のバスルーム(複数アングル)</li>
                    <li class="priority-high">アイランドキッチン全体とディテール</li>
                    <li class="priority-medium">コーナーローソファーでのくつろぎシーン</li>
                    <li class="priority-medium">ダブルボウル洗面台</li>
                    <li class="priority-medium">寝室(2部屋それぞれ)</li>
                    <li>レコードプレーヤーとレコード</li>
                    <li>キッチンでの調理シーン(出張シェフサービスがあれば)</li>
                    <li>リビングから見た窓の景色</li>
                    <li>バスルームのバスチェアとアメニティ</li>
                </ul>
            </div>

            <!-- 6. HIDEOUTラウンジ -->
            <div class="shot-category">
                <h3>6. HIDEOUTラウンジ(8階・9階)</h3>
                <div class="shot-note">
                    <span class="time-badge">終日OK</span>
                    <strong>撮影のポイント:</strong> フリーフローの雰囲気とアメニティを強調
                </div>
                <ul class="checklist">
                    <li class="priority-high">9階ラウンジ全体(ワインディスペンサー、スナック類)</li>
                    <li class="priority-high">フリーフローのドリンク・スナック類のフラットレイ</li>
                    <li class="priority-medium">8階ラウンジ(本、レコード、ボードゲーム)</li>
                    <li class="priority-medium">レコードプレーヤーとビニールレコード</li>
                    <li>ワイングラスとナッツのスタイリング</li>
                    <li>ボードゲームで遊ぶシーン</li>
                    <li>絵本コーナー(子連れ向けアピール)</li>
                    <li>ラウンジでのくつろぎシーン</li>
                </ul>
            </div>

            <!-- 7. 共用エリア -->
            <div class="shot-category">
                <h3>7. 共用エリア</h3>
                <div class="shot-note">
                    <span class="time-badge">朝7:00-10:00推奨</span>
                    <strong>撮影のポイント:</strong> 清潔感と機能性を強調
                </div>
                <ul class="checklist">
                    <li class="priority-high">共用キッチン全体(白を基調とした清潔感)</li>
                    <li class="priority-medium">大浴場のバスルーム(ダイソンドライヤー含む)</li>
                    <li class="priority-medium">キッチンの調理器具・食器</li>
                    <li>テーブル席エリア</li>
                    <li>小浴場</li>
                    <li>ランドリー設備</li>
                    <li>エレベーターホール(タオル追加取得場所)</li>
                </ul>
            </div>

            <!-- 8. アメニティ・設備 -->
            <div class="shot-category">
                <h3>8. アメニティ・設備</h3>
                <div class="shot-note">
                    <strong>撮影のポイント:</strong> ブランド名が見えるように配置。美しいスタイリング
                </div>
                <ul class="checklist">
                    <li class="priority-medium">POLAのバスアメニティ(シャンプー、コンディショナー、ボディソープ)</li>
                    <li class="priority-medium">OSAJIスキンケア(HIDEOUT専用)</li>
                    <li>Panasonicドライヤー / ダイソンドライヤー</li>
                    <li>タオルとスリッパの配置</li>
                    <li>アメニティ全体のフラットレイ</li>
                    <li>貸出品(フロント)の紹介</li>
                </ul>
            </div>

            <!-- 9. 朝食 -->
            <div class="shot-category">
                <h3>9. 朝食</h3>
                <div class="shot-note">
                    <span class="time-badge">7:00-9:00推奨</span>
                    <strong>撮影のポイント:</strong> 焼きたての温かさと朝の光を活かす
                </div>
                <ul class="checklist">
                    <li class="priority-high">ベーグルサンド3種類すべて</li>
                    <li class="priority-high">朝食セット全体(ドリンク含む)</li>
                    <li class="priority-medium">スモークサーモンベーグルのクローズアップ</li>
                    <li>コーヒー・紅茶と朝食の組み合わせ</li>
                    <li>朝食を食べている手元のショット</li>
                    <li>カフェエリアでの朝食シーン</li>
                </ul>
            </div>

            <!-- 10. ショートフィルム上映 -->
            <div class="shot-category">
                <h3>10. ショートフィルム上映</h3>
                <div class="shot-note">
                    <span class="time-badge">夜間</span>
                    <strong>撮影のポイント:</strong> 雰囲気重視。スクリーンと観客の様子
                </div>
                <ul class="checklist">
                    <li class="priority-medium">大画面での映画上映シーン</li>
                    <li class="priority-medium">階段に座って鑑賞する人々</li>
                    <li>スクリーンの光が階段を照らす雰囲気</li>
                    <li>鑑賞中の表情(シルエット)</li>
                </ul>
            </div>

            <!-- 11. エントランス・外観・その他 -->
            <div class="shot-category">
                <h3>11. エントランス・外観・その他</h3>
                <div class="shot-note">
                    <span class="time-badge">昼間 / 夜間</span>
                    <strong>撮影のポイント:</strong> ホテルの第一印象。昼と夜の両方撮影
                </div>
                <ul class="checklist">
                    <li class="priority-high">ホテル外観(黒い近代的な建物)</li>
                    <li class="priority-medium">エントランス(モダンで日本的な雰囲気)</li>
                    <li class="priority-medium">フロントエリア</li>
                    <li>ホテルのサイン・ロゴ</li>
                    <li>夜のライトアップされたエントランス</li>
                    <li>京都駅からホテルまでの道のり(徒歩5分をアピール)</li>
                    <li>HIDEOUT別館への専用カードキーエントランス</li>
                    <li>周辺の居酒屋エリア(夜の雰囲気)</li>
                </ul>
            </div>

            <!-- 12. ライフスタイル・体験シーン -->
            <div class="shot-category">
                <h3>12. ライフスタイル・体験シーン</h3>
                <div class="shot-note">
                    <strong>撮影のポイント:</strong> Instagram向けのストーリー性のあるシーン
                </div>
                <ul class="checklist">
                    <li class="priority-high">本を読みながらジンを楽しむシーン</li>
                    <li class="priority-medium">友人とラウンジでくつろぐシーン</li>
                    <li class="priority-medium">レコードを聴きながら本を読むシーン</li>
                    <li>キッチンで料理を楽しむシーン</li>
                    <li>ボードゲームで遊ぶシーン</li>
                    <li>朝食を楽しむシーン</li>
                    <li>バスルームでリラックスするシーン</li>
                    <li>ベッドで読書するシーン</li>
                    <li>カスタムG&Tを作成している手元</li>
                </ul>
            </div>

            <div class="warning-box" style="margin-top: 30px;">
                <h4><i class="fas fa-clipboard-list"></i> 撮影チェックリスト使用のコツ</h4>
                <ul>
                    <li><strong>優先順位:</strong> ★必須 → ●重要 → その他 の順で撮影</li>
                    <li><strong>時間配分:</strong> 各カテゴリーの推奨時間帯を参考に効率的に</li>
                    <li><strong>バックアップ:</strong> 重要なカットは複数アングルで撮影</li>
                    <li><strong>照明確認:</strong> 暗めの照明なので、ISO・絞りを事前チェック</li>
                    <li><strong>進捗管理:</strong> 印刷して現場でチェックを入れながら進める</li>
                </ul>
            </div>
        </div>

        <!-- まとめ -->
        <div class="section" id="summary">
            <h2><i class="fas fa-clipboard-check"></i> 撮影のまとめ</h2>

            <div class="highlight">
                <h4>TUNE STAY KYOTOの撮影価値</h4>
                <p>TUNE STAY KYOTOは、「ブックホテル」という独自のコンセプトと、GIN・BOOKS・SHORT FILMという3つの文化体験を提供する体験型ホテルです。Instagram撮影においては以下の点が特に魅力的です:</p>
            </div>

            <div class="grid">
                <div class="grid-item">
                    <h4><i class="fas fa-sparkles"></i> 視覚的魅力</h4>
                    <ul>
                        <li>2,500冊の本が並ぶ壁面</li>
                        <li>洗練された北欧デザイン</li>
                        <li>間接照明の雰囲気</li>
                        <li>大理石のバスルーム(HIDEOUT)</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4><i class="fas fa-camera-retro"></i> 多様な撮影シーン</h4>
                    <ul>
                        <li>読書シーン</li>
                        <li>カフェ・バー</li>
                        <li>料理(キッチン)</li>
                        <li>ラウンジでのリラックス</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4><i class="fas fa-location-arrow"></i> 立地の良さ</h4>
                    <ul>
                        <li>京都駅から徒歩5分</li>
                        <li>周辺に多数の撮影スポット</li>
                        <li>アクセス抜群</li>
                        <li>移動が効率的</li>
                    </ul>
                </div>

                <div class="grid-item">
                    <h4><i class="fas fa-magic"></i> ユニークな体験</h4>
                    <ul>
                        <li>カスタムG&T作成</li>
                        <li>ショートフィルム鑑賞</li>
                        <li>クラブラウンジ</li>
                        <li>レコード鑑賞</li>
                    </ul>
                </div>
            </div>

            <div class="photo-tip">
                <h4>撮影日のスケジュール例</h4>
                <p><strong>午前:</strong></p>
                <ul>
                    <li>7:00〜9:00: 朝食撮影(ベーグル、ドリンク)</li>
                    <li>9:00〜11:00: 自然光を活かした客室・キッチン撮影</li>
                </ul>

                <p><strong>午後:</strong></p>
                <ul>
                    <li>11:00〜14:00: ブックギャラリー・大階段エリア撮影</li>
                    <li>14:00〜17:00: HIDEOUTラウンジ、スイート客室撮影</li>
                </ul>

                <p><strong>夕方〜夜:</strong></p>
                <ul>
                    <li>17:00〜19:00: GINバー撮影(カラーチェンジドリンク)</li>
                    <li>19:00〜21:00: ラウンジでのリラックスシーン、ショートフィルム鑑賞</li>
                    <li>21:00〜: カフェバーの夜の雰囲気撮影</li>
                </ul>
            </div>
        </div>

        <!-- フッター -->
        <footer>
            <p>このレポートは、TUNE STAY KYOTOのInstagram撮影のために作成されました。</p>
            <p>最新情報は公式サイトでご確認ください: <a href="https://www.tune-stay.com/" target="_blank">https://www.tune-stay.com/</a></p>
            <p style="margin-top: 20px; font-size: 0.9em;">作成日: 2025年12月6日</p>
        </footer>
        </div><!-- /main-content -->

        <!-- フローティングサイドバー目次(右側) -->
        <aside class="sidebar-toc">
            <h3><i class="fas fa-list"></i> 目次</h3>
            <ul>
                <li><a href="#basic-info"><i class="fas fa-map-marker-alt"></i> 基本情報</a></li>
                <li><a href="#sns-info"><i class="fas fa-hashtag"></i> SNS情報</a></li>
                <li><a href="#facility"><i class="fas fa-building"></i> 施設構成</a></li>
                <li><a href="#rooms"><i class="fas fa-bed"></i> 客室タイプ</a></li>
                <li><a href="#photo-spots"><i class="fas fa-camera"></i> 撮影スポット</a></li>
                <li><a href="#shot-list"><i class="fas fa-video"></i> 撮影カットリスト</a></li>
                <li><a href="#amenities"><i class="fas fa-spray-can"></i> アメニティ</a></li>
                <li><a href="#food"><i class="fas fa-utensils"></i> 食事・ドリンク</a></li>
                <li><a href="#contents"><i class="fas fa-film"></i> 3つのコンテンツ</a></li>
                <li><a href="#nearby"><i class="fas fa-map-marked-alt"></i> 周辺スポット</a></li>
                <li><a href="#pricing"><i class="fas fa-yen-sign"></i> 料金目安</a></li>
                <li><a href="#reviews"><i class="fas fa-star"></i> レビュー</a></li>
                <li><a href="#shooting-tips"><i class="fas fa-exclamation-triangle"></i> 撮影の注意</a></li>
                <li><a href="#references"><i class="fas fa-newspaper"></i> 参考記事</a></li>
                <li><a href="#summary"><i class="fas fa-clipboard-check"></i> まとめ</a></li>
            </ul>
        </aside>
    </div><!-- /container -->

    <!-- モバイル目次ボタン -->
    <button class="mobile-toc-button" id="mobileTocButton" aria-label="目次を開く">
        <i class="fas fa-bars"></i>
    </button>

    <!-- モバイル目次オーバーレイ -->
    <div class="mobile-toc-overlay" id="mobileTocOverlay"></div>

    <!-- モバイル目次パネル -->
    <div class="mobile-toc-panel" id="mobileTocPanel">
        <div class="mobile-toc-header">
            <h3><i class="fas fa-list"></i> 目次</h3>
            <button class="mobile-toc-close" id="mobileTocClose" aria-label="閉じる">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-toc-content">
            <ul>
                <li><a href="#basic-info" class="mobile-toc-link"><i class="fas fa-map-marker-alt"></i> 基本情報</a></li>
                <li><a href="#sns-info" class="mobile-toc-link"><i class="fas fa-hashtag"></i> SNS情報</a></li>
                <li><a href="#facility" class="mobile-toc-link"><i class="fas fa-building"></i> 施設構成</a></li>
                <li><a href="#rooms" class="mobile-toc-link"><i class="fas fa-bed"></i> 客室タイプ</a></li>
                <li><a href="#photo-spots" class="mobile-toc-link"><i class="fas fa-camera"></i> 撮影スポット</a></li>
                <li><a href="#shot-list" class="mobile-toc-link"><i class="fas fa-video"></i> 撮影カットリスト</a></li>
                <li><a href="#amenities" class="mobile-toc-link"><i class="fas fa-spray-can"></i> アメニティ</a></li>
                <li><a href="#food" class="mobile-toc-link"><i class="fas fa-utensils"></i> 食事・ドリンク</a></li>
                <li><a href="#contents" class="mobile-toc-link"><i class="fas fa-film"></i> 3つのコンテンツ</a></li>
                <li><a href="#nearby" class="mobile-toc-link"><i class="fas fa-map-marked-alt"></i> 周辺スポット</a></li>
                <li><a href="#pricing" class="mobile-toc-link"><i class="fas fa-yen-sign"></i> 料金目安</a></li>
                <li><a href="#reviews" class="mobile-toc-link"><i class="fas fa-star"></i> レビュー</a></li>
                <li><a href="#shooting-tips" class="mobile-toc-link"><i class="fas fa-exclamation-triangle"></i> 撮影の注意</a></li>
                <li><a href="#references" class="mobile-toc-link"><i class="fas fa-newspaper"></i> 参考記事</a></li>
                <li><a href="#summary" class="mobile-toc-link"><i class="fas fa-clipboard-check"></i> まとめ</a></li>
            </ul>
        </div>
    </div>

    <script>
        // スクロールに連動した目次のハイライト
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section, .shot-list');
            const navLinks = document.querySelectorAll('.sidebar-toc a');
            const sidebar = document.querySelector('.sidebar-toc');

            // 現在のセクションを判定してハイライト
            function highlightCurrentSection() {
                let current = '';
                const scrollPosition = window.pageYOffset + 100; // オフセット調整

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                // 全てのリンクからactiveクラスを削除
                navLinks.forEach(link => {
                    link.classList.remove('active');

                    // 現在のセクションに対応するリンクにactiveクラスを追加
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');

                        // 目次内でアクティブな項目が見える位置にスクロール
                        if (sidebar) {
                            const linkRect = link.getBoundingClientRect();
                            const sidebarRect = sidebar.getBoundingClientRect();

                            // リンクが目次の表示範囲外にある場合
                            if (linkRect.top < sidebarRect.top || linkRect.bottom > sidebarRect.bottom) {
                                // アクティブな項目を中央に配置
                                const linkOffsetTop = link.offsetTop;
                                const sidebarScrollTop = sidebar.scrollTop;
                                const sidebarHeight = sidebar.clientHeight;
                                const linkHeight = link.clientHeight;

                                const scrollTo = linkOffsetTop - (sidebarHeight / 2) + (linkHeight / 2);

                                sidebar.scrollTo({
                                    top: scrollTo,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                });
            }

            // スクロールイベントでハイライトを更新
            window.addEventListener('scroll', highlightCurrentSection);

            // 初期ロード時にも実行
            highlightCurrentSection();

            // スムーススクロール
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        const offsetTop = targetSection.offsetTop - 20;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // チェックリストの機能
            const checklistItems = document.querySelectorAll('.checklist li');

            // LocalStorageからチェック状態を復元
            checklistItems.forEach((item, index) => {
                const checkKey = 'checklist-' + index;
                const isChecked = localStorage.getItem(checkKey) === 'true';

                if (isChecked) {
                    item.classList.add('checked');
                }

                // クリックイベント
                item.addEventListener('click', function() {
                    this.classList.toggle('checked');

                    // LocalStorageに保存
                    const isNowChecked = this.classList.contains('checked');
                    localStorage.setItem(checkKey, isNowChecked);
                });
            });

            // チェックリストリセット機能(オプション)
            // コンソールで resetChecklist() を実行するとすべてのチェックをリセット
            window.resetChecklist = function() {
                checklistItems.forEach((item, index) => {
                    item.classList.remove('checked');
                    localStorage.removeItem('checklist-' + index);
                });
                console.log('チェックリストをリセットしました');
            };

            // モバイル目次の開閉機能
            const mobileTocButton = document.getElementById('mobileTocButton');
            const mobileTocOverlay = document.getElementById('mobileTocOverlay');
            const mobileTocPanel = document.getElementById('mobileTocPanel');
            const mobileTocClose = document.getElementById('mobileTocClose');
            const mobileTocLinks = document.querySelectorAll('.mobile-toc-link');

            // 目次を開く
            function openMobileToc() {
                mobileTocOverlay.classList.add('active');
                mobileTocPanel.classList.add('active');
                document.body.style.overflow = 'hidden'; // スクロール防止
            }

            // 目次を閉じる
            function closeMobileToc() {
                mobileTocOverlay.classList.remove('active');
                mobileTocPanel.classList.remove('active');
                document.body.style.overflow = ''; // スクロール復元
            }

            // ボタンクリックで開く
            mobileTocButton.addEventListener('click', openMobileToc);

            // オーバーレイクリックで閉じる
            mobileTocOverlay.addEventListener('click', closeMobileToc);

            // 閉じるボタンで閉じる
            mobileTocClose.addEventListener('click', closeMobileToc);

            // モバイル目次リンククリックで閉じる＆スクロール
            mobileTocLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);

                    // 目次を閉じる
                    closeMobileToc();

                    // スクロール
                    if (targetSection) {
                        setTimeout(() => {
                            const offsetTop = targetSection.offsetTop - 20;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }, 300); // アニメーション後にスクロール
                    }
                });
            });

            // モバイル目次のアクティブハイライト（PC版と共通のロジック）
            function updateMobileTocHighlight() {
                let current = '';
                const scrollPosition = window.pageYOffset + 100;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                    }
                });

                mobileTocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
            }

            // スクロール時にモバイル目次もハイライト更新
            window.addEventListener('scroll', updateMobileTocHighlight);
            updateMobileTocHighlight(); // 初期実行
        });
    </script>
</body>
</html>
