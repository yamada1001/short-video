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
            font-size: 1.2em;
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
            font-size: 0.95em;
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
            font-size: 2em;
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
            font-size: 1.05em;
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
            font-size: 1.4em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .highlight-box h3 i {
            margin-right: 10px;
        }

        .highlight-box p {
            font-size: 1.05em;
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
            font-size: 2.5em;
            color: #333;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .price-example .budget i {
            margin-right: 12px;
        }

        .price-example .details {
            font-size: 1.2em;
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
            font-size: 1.05em;
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
            font-size: 1.3em;
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
            font-size: 1.3em;
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
            font-size: 1.3em;
            font-weight: 300;
            margin-bottom: 15px;
        }

        .service-card p {
            color: #666;
            font-size: 1em;
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
            font-size: 2em;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .cta-section h3 i {
            margin-right: 12px;
        }

        .cta-section p {
            font-size: 1.2em;
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

            .main-layout {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 25px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.3em;
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
                font-size: 2em;
            }

            .cta-section {
                padding: 35px 25px;
            }

            .cta-section h3 {
                font-size: 1.5em;
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
                    <h3 style="color: #333; font-size: 1.4em; margin-bottom: 20px; font-weight: 300;">
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
                        <ol style="font-size: 1.1em; line-height: 2.5; padding-left: 25px; color: #333;">
                            <li><strong>Instagram運用を弊社で代行</strong>（撮影・編集・投稿）</li>
                            <li><strong>ご予算に応じた柔軟なプラン設定</strong></li>
                            <li><strong>コンサルティング業務もインクルード</strong></li>
                            <li><strong>社員育成サポートも可能</strong></li>
                            <li><strong>人脈からの集客後のコンバージョン率改善</strong>が主な役割</li>
                            <li><strong>マーケター視点での制作</strong>で最大限のリーチを実現</li>
                        </ol>
                    </div>

                    <p style="margin-top: 40px; font-size: 1.3em; text-align: center; color: #0066cc; font-weight: 600; line-height: 2;">
                        <i class="fas fa-star"></i><br>
                        継続的で質の高いコンテンツ発信により、<br>
                        貴社のInstagramアカウントを成長させます。
                    </p>
                </div>
            </section>
        </main>
    </div>

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
