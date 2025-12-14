<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>クライアント用マニュアル | BNI Slide System V2</title>

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
            line-height: 1.8;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            padding: 40px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
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

        /* Search Box */
        .search-box {
            background: white;
            padding: 20px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .search-input {
            width: 100%;
            max-width: 600px;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #C8102E;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 40px;
        }

        /* Table of Contents */
        .toc {
            position: sticky;
            top: 120px;
            height: fit-content;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 25px;
        }

        .toc h3 {
            color: #C8102E;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #C8102E;
        }

        .toc ul {
            list-style: none;
        }

        .toc li {
            margin-bottom: 10px;
        }

        .toc a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .toc a:hover, .toc a.active {
            background: #C8102E;
            color: white;
        }

        /* Main Content */
        .main-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 40px;
        }

        .section {
            margin-bottom: 60px;
            padding-bottom: 40px;
            border-bottom: 1px solid #eee;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section h2 {
            color: #C8102E;
            font-size: 28px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section h2 .icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .section h3 {
            color: #C8102E;
            font-size: 22px;
            margin: 30px 0 15px 0;
            padding-left: 15px;
            border-left: 4px solid #C8102E;
        }

        .section h4 {
            color: #333;
            font-size: 18px;
            margin: 20px 0 10px 0;
            font-weight: 600;
        }

        .section p {
            margin-bottom: 15px;
            color: #555;
        }

        .section ul, .section ol {
            margin: 15px 0 15px 30px;
        }

        .section li {
            margin-bottom: 10px;
            color: #555;
        }

        /* Info Box */
        .info-box {
            background: #e8f4fd;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }

        .info-box.success {
            background: #d4edda;
            border-left-color: #28a745;
        }

        .info-box.danger {
            background: #f8d7da;
            border-left-color: #dc3545;
        }

        .info-box h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .info-box p {
            margin: 0;
        }

        /* Step Box */
        .step-box {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .step-box .step-number {
            background: #C8102E;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 10px;
        }

        .step-box h4 {
            display: flex;
            align-items: center;
            margin: 0 0 15px 0;
        }

        /* Feature Grid */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .feature-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-card .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 28px;
        }

        .feature-card h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .feature-card p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th {
            background: #C8102E;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        /* Code Block */
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #C8102E;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 14px;
        }

        /* Print Styles */
        @media print {
            .header, .search-box, .toc, .footer {
                display: none;
            }

            .container {
                grid-template-columns: 1fr;
            }

            .main-content {
                box-shadow: none;
            }

            .section {
                page-break-inside: avoid;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-book"></i> クライアント用マニュアル</h1>
        <div class="subtitle">BNI Slide System V2 - 操作ガイド・FAQ・トラブルシューティング</div>
    </div>

    <div class="search-box">
        <input type="text" class="search-input" id="searchInput" placeholder="🔍 マニュアルを検索...">
    </div>

    <div class="container">
        <!-- 目次 -->
        <aside class="toc">
            <h3><i class="fas fa-list"></i> 目次</h3>
            <ul>
                <li><a href="#intro">1. はじめに</a></li>
                <li><a href="#admin">2. 管理画面の使い方</a></li>
                <li><a href="#slides">3. スライド表示の使い方</a></li>
                <li><a href="#faq">4. よくある質問</a></li>
                <li><a href="#troubleshooting">5. トラブルシューティング</a></li>
                <li><a href="#contact">6. お問い合わせ先</a></li>
            </ul>
        </aside>

        <!-- メインコンテンツ -->
        <main class="main-content">
            <!-- 1. はじめに -->
            <section class="section" id="intro">
                <h2>
                    <span class="icon"><i class="fas fa-rocket"></i></span>
                    1. はじめに
                </h2>

                <p>BNI Slide System V2へようこそ！このシステムは、BNIの定例会で使用するスライドを簡単に管理・表示するために開発されました。</p>

                <div class="info-box success">
                    <h4><i class="fas fa-check-circle"></i> システムの特徴</h4>
                    <p>
                        <strong>18個の管理画面</strong>と<strong>309枚のスライド</strong>を統合管理できます。<br>
                        メンバー情報、ビジター情報、統計データなど、あらゆる情報を一元管理し、定例会をスムーズに進行できます。
                    </p>
                </div>

                <h3>主な機能</h3>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h4>メンバー管理</h4>
                        <p>48名のメンバー情報を一括管理</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-user-friends"></i></div>
                        <h4>ビジター管理</h4>
                        <p>ビジター情報を登録し、4種類のスライドを自動生成</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-chart-line"></i></div>
                        <h4>統計情報</h4>
                        <p>リファーラル・売上・ビジター数などを可視化</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-trophy"></i></div>
                        <h4>チャンピオン管理</h4>
                        <p>5種類のチャンピオンを表彰</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-stopwatch"></i></div>
                        <h4>タイマー機能</h4>
                        <p>2分・23秒・33秒・5分の各種タイマー</p>
                    </div>
                    <div class="feature-card">
                        <div class="icon"><i class="fas fa-eye"></i></div>
                        <h4>表示管理</h4>
                        <p>全スライドの表示/非表示を自由に設定</p>
                    </div>
                </div>

                <h3>システム構成</h3>
                <p>本システムは以下の5つのフェーズに分かれて実装されています：</p>
                <ul>
                    <li><strong>Phase 1:</strong> メンバー管理（基盤）</li>
                    <li><strong>Phase 2:</strong> 基本管理画面（座席・プレゼン・ローテーション・スタートダッシュ）</li>
                    <li><strong>Phase 3:</strong> ビジター・メンバー関連（8つの管理画面）</li>
                    <li><strong>Phase 4:</strong> チャンピオン・統計関連（3つの管理画面）</li>
                    <li><strong>Phase 5:</strong> その他の管理画面（カテゴリ・QR・リファーラル真正度など）</li>
                </ul>
            </section>

            <!-- 2. 管理画面の使い方 -->
            <section class="section" id="admin">
                <h2>
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    2. 管理画面の使い方
                </h2>

                <p>管理画面は<code>https://yojitu.com/bni-slide-system/slides_v2/admin/</code>からアクセスできます。</p>

                <!-- メンバー管理 -->
                <h3>2.1 メンバー管理</h3>
                <div class="step-box">
                    <h4><span class="step-number">1</span>メンバー管理画面を開く</h4>
                    <p><code>admin/members.php</code>にアクセスします。現在48名のメンバーが登録されています。</p>
                </div>

                <div class="step-box">
                    <h4><span class="step-number">2</span>メンバーを追加する</h4>
                    <p>「新規メンバー追加」ボタンをクリックし、以下の情報を入力します：</p>
                    <ul>
                        <li>名前（必須）</li>
                        <li>会社名（任意）</li>
                        <li>カテゴリ（業種）（必須）</li>
                        <li>誕生日（YYYY-MM-DD形式）</li>
                        <li>写真（JPG/PNG、最大2MB）</li>
                    </ul>
                </div>

                <div class="step-box">
                    <h4><span class="step-number">3</span>メンバーを編集・削除する</h4>
                    <p>各メンバーの「編集」ボタンをクリックして情報を変更できます。削除する場合は「削除」ボタンをクリックします。</p>
                </div>

                <div class="info-box warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> 注意事項</h4>
                    <p>メンバーを削除すると、関連する全てのスライドからそのメンバー情報が削除されます。慎重に操作してください。</p>
                </div>

                <!-- 座席管理 -->
                <h3>2.2 座席管理（p.7）</h3>
                <p>テーブル配置とメンバーの座席を管理します。ドラッグ&ドロップで直感的に配置できます。</p>
                <ol>
                    <li><code>admin/seating.php</code>にアクセス</li>
                    <li>左側のメンバーリストから、右側のテーブルにドラッグ&ドロップ</li>
                    <li>「保存」ボタンをクリックして確定</li>
                </ol>

                <!-- メインプレゼン -->
                <h3>2.3 メインプレゼン（p.8, p.204）</h3>
                <p>メインプレゼンターを設定し、PDF資料やYouTube動画を埋め込めます。</p>
                <ol>
                    <li><code>admin/main_presenter.php</code>にアクセス</li>
                    <li>プレゼンターをメンバーリストから選択</li>
                    <li>PDF資料をアップロード（任意）→ 自動的に画像に変換されp.204以降に挿入</li>
                    <li>YouTube URL（限定公開）を入力（任意）</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- スピーカーローテーション -->
                <h3>2.4 スピーカーローテーション（p.9-14, p.199-203, p.297-301）</h3>
                <p>6週分のスピーカーローテーションを一括管理します。過去3週・今週・未来2週を同時に編集できます。</p>
                <ol>
                    <li><code>admin/speaker_rotation.php</code>にアクセス</li>
                    <li>各週のメインプレゼンターを選択</li>
                    <li>「ご紹介してほしい人」を自由記述で入力</li>
                    <li>「一括保存」ボタンで全6週分を保存</li>
                </ol>

                <!-- スタートダッシュプレゼン -->
                <h3>2.5 スタートダッシュプレゼン（p.15, p.107）</h3>
                <p>2分間のカウントダウンタイマー付きプレゼンを設定します。</p>
                <ol>
                    <li><code>admin/start_dash.php</code>にアクセス</li>
                    <li>p.15とp.107のプレゼンターをそれぞれ選択</li>
                    <li>「保存」ボタンをクリック</li>
                    <li>スライド表示時に「スタート」ボタンでタイマー開始</li>
                </ol>

                <!-- ビジター管理 -->
                <h3>2.6 ビジター管理（p.19, p.169-180, p.213-224, p.235）</h3>
                <p>ビジター情報を登録すると、4種類のスライドが自動生成されます。</p>

                <table>
                    <thead>
                        <tr>
                            <th>スライド種類</th>
                            <th>ページ</th>
                            <th>内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ビジター紹介</td>
                            <td>p.19</td>
                            <td>スポンサーによるビジター紹介</td>
                        </tr>
                        <tr>
                            <td>ビジター自己紹介</td>
                            <td>p.169-180</td>
                            <td>23秒カウントダウン付き自己紹介</td>
                        </tr>
                        <tr>
                            <td>ビジター感想</td>
                            <td>p.213-224</td>
                            <td>本日の一言感想（23秒）</td>
                        </tr>
                        <tr>
                            <td>ビジター感謝</td>
                            <td>p.235</td>
                            <td>テーブル形式で一覧表示</td>
                        </tr>
                    </tbody>
                </table>

                <h4>ビジター登録手順</h4>
                <ol>
                    <li><code>admin/visitors.php</code>にアクセス</li>
                    <li>「ビジター追加」ボタンをクリック</li>
                    <li>以下の情報を入力：
                        <ul>
                            <li>ビジター名（必須）</li>
                            <li>会社名（任意）</li>
                            <li>専門分野（任意）</li>
                            <li>スポンサー（メンバーから選択）</li>
                            <li>アテンド（メンバーから選択）</li>
                            <li>お仕事内容（自由記述）</li>
                            <li>ご紹介してほしい方・職業（自由記述）</li>
                        </ul>
                    </li>
                    <li>「保存」ボタンをクリック → 4種類のスライドが自動生成</li>
                </ol>

                <!-- 代理出席 -->
                <h3>2.7 代理出席（p.22-24）</h3>
                <p>最大3名の代理出席を管理します。</p>
                <ol>
                    <li><code>admin/substitutes.php</code>にアクセス</li>
                    <li>代理出席メンバーを選択</li>
                    <li>代理出席者の会社名・名前を入力</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- 新入会メンバー -->
                <h3>2.8 新入会メンバー（p.25-27, p.100-102）</h3>
                <p>最大3名の新入会メンバーを登録します。</p>
                <ol>
                    <li><code>admin/new_members.php</code>にアクセス</li>
                    <li>新入会メンバーをメンバーリストから選択（最大3名）</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- 週間No.1 -->
                <h3>2.9 週間No.1（p.28）</h3>
                <p>外部リファーラル・ビジター招待・1to1の1位を表彰します。</p>
                <ol>
                    <li><code>admin/weekly_no1.php</code>にアクセス</li>
                    <li>各カテゴリの1位メンバーを選択</li>
                    <li>件数を入力</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- ネットワーキング学習 -->
                <h3>2.10 ネットワーキング学習（p.74-85）</h3>
                <p>PDF資料をアップロードすると、自動的に画像に変換されてスライドに挿入されます。</p>
                <ol>
                    <li><code>admin/networking_pdf.php</code>にアクセス</li>
                    <li>「PDFファイルを選択」ボタンでPDFをアップロード</li>
                    <li>自動的にPDF→画像変換が実行され、p.86以降に挿入</li>
                </ol>

                <!-- チャンピオン管理 -->
                <h3>2.11 チャンピオン管理（p.91-96）</h3>
                <p>5種類のチャンピオンを管理します。1位には豪華なアニメーションが表示されます。</p>
                <ol>
                    <li><code>admin/champions.php</code>にアクセス</li>
                    <li>各チャンピオン（リファーラル・バリュー・ビジター・1to1・CEU）の1位～3位を選択</li>
                    <li>件数を入力</li>
                    <li>同率の場合は複数名入力可能</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- 統計情報 -->
                <h3>2.12 統計情報（p.188-190, p.302）</h3>
                <p>ビジター・リファーラル・売上・週次統計を管理します。</p>
                <ol>
                    <li><code>admin/statistics.php</code>にアクセス</li>
                    <li>各統計データを入力：
                        <ul>
                            <li>ビジター合計数（これまで・先週・本日・現在のメンバー数）</li>
                            <li>リファーラル件数（これまで・先週・先週平均）</li>
                            <li>売上統計（期間までの売上・伸び率）</li>
                            <li>週次統計（先週・今週のビジター、150名までのカウントダウン、目標数）</li>
                        </ul>
                    </li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- 募集カテゴリ -->
                <h3>2.13 募集カテゴリ管理（p.185, p.194）</h3>
                <p>激しく募集中のカテゴリとアンケート結果を管理します。</p>
                <ol>
                    <li><code>admin/categories.php</code>にアクセス</li>
                    <li>激しく募集中のカテゴリを複数入力</li>
                    <li>アンケート結果（1位～4位）と得票数を入力</li>
                    <li>「保存」ボタンをクリック</li>
                </ol>

                <!-- リファーラル真正度確認 -->
                <h3>2.14 リファーラル真正度確認（p.227）</h3>
                <p>リファーラルの真正度を確認します。</p>
                <ol>
                    <li><code>admin/referral_check.php</code>にアクセス</li>
                    <li>リファーラル提供者を選択</li>
                    <li>リファーラル受領者を選択</li>
                    <li>「保存」ボタンをクリック</li>
                    <li>スライドには以下の質問が自動表示されます：
                        <ul>
                            <li>リファーラル先と連絡は取れましたか？</li>
                            <li>話は通じてましたか？</li>
                            <li>純粋にビジネスの機会となり得るものでしたか？</li>
                        </ul>
                    </li>
                </ol>

                <!-- QRコード生成 -->
                <h3>2.15 QRコード生成（p.242）</h3>
                <p>アンケートURLからQRコードを生成します。</p>
                <ol>
                    <li><code>admin/qr_code.php</code>にアクセス</li>
                    <li>アンケートURLを入力</li>
                    <li>「QRコード生成」ボタンをクリック</li>
                    <li>生成されたQRコードがスライドに表示されます</li>
                </ol>

                <!-- スライド表示管理 -->
                <h3>2.16 スライド表示管理</h3>
                <p>全スライドの表示/非表示を一括管理します。</p>
                <ol>
                    <li><code>admin/slide_visibility.php</code>にアクセス</li>
                    <li>非表示にしたいスライドにチェックを入れる</li>
                    <li>「保存」ボタンをクリック</li>
                    <li>基本は全て表示、チェックを入れたスライドだけ非表示になります</li>
                </ol>
            </section>

            <!-- 3. スライド表示の使い方 -->
            <section class="section" id="slides">
                <h2>
                    <span class="icon"><i class="fas fa-play-circle"></i></span>
                    3. スライド表示の使い方
                </h2>

                <p>スライドは<code>https://yojitu.com/bni-slide-system/slides_v2/index.php</code>から表示できます。</p>

                <div class="info-box success">
                    <h4><i class="fas fa-sync-alt"></i> PHPベースのリアルタイム表示</h4>
                    <p>
                        V2では、スライドショーがPHPベースで動作します。管理画面で保存した内容が<strong>即座にスライドショーに反映</strong>されます。<br>
                        画像の再生成やキャッシュのクリアは不要で、リアルタイムにデータを表示できます。
                    </p>
                </div>

                <h3>基本操作</h3>
                <table>
                    <thead>
                        <tr>
                            <th>操作</th>
                            <th>キー</th>
                            <th>説明</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>次のスライド</td>
                            <td><code>→</code> または <code>↓</code> または <code>Space</code></td>
                            <td>次のページに進む</td>
                        </tr>
                        <tr>
                            <td>前のスライド</td>
                            <td><code>←</code> または <code>↑</code></td>
                            <td>前のページに戻る</td>
                        </tr>
                        <tr>
                            <td>最初のスライド</td>
                            <td><code>Home</code></td>
                            <td>1ページ目に移動</td>
                        </tr>
                        <tr>
                            <td>最後のスライド</td>
                            <td><code>End</code></td>
                            <td>最終ページに移動</td>
                        </tr>
                        <tr>
                            <td>フルスクリーン</td>
                            <td><code>F11</code> または <code>F</code></td>
                            <td>フルスクリーン表示切り替え</td>
                        </tr>
                        <tr>
                            <td>タイマースタート</td>
                            <td><code>Enter</code></td>
                            <td>カウントダウンタイマー開始</td>
                        </tr>
                        <tr>
                            <td>タイマーリセット</td>
                            <td><code>R</code></td>
                            <td>タイマーをリセット</td>
                        </tr>
                    </tbody>
                </table>

                <h3>タイマー機能</h3>
                <p>以下のスライドにはカウントダウンタイマーが搭載されています：</p>
                <ul>
                    <li><strong>2分タイマー:</strong> スタートダッシュプレゼン（p.15, p.107）</li>
                    <li><strong>23秒タイマー:</strong> ビジター自己紹介（p.169-180）、ビジター感想（p.213-224）</li>
                    <li><strong>33秒タイマー:</strong> メンバーピッチ（p.112-166）</li>
                    <li><strong>5分タイマー:</strong> ビジネスブレイクアウト（p.184）</li>
                </ul>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> タイマーの動作</h4>
                    <p>
                        タイマーは残り30秒以下になると警告色（オレンジ）に変わり、0:00に到達すると赤く点滅します。<br>
                        ビープ音も鳴るため、時間管理が簡単です。
                    </p>
                </div>

                <h3>自動生成されるスライド</h3>
                <p>以下のスライドは管理画面のデータから自動生成されます：</p>
                <ul>
                    <li>ハッピーバースデー（p.31）- 毎週金曜日、誕生日メンバーを自動表示</li>
                    <li>メンバーピッチ（p.112-166）- メンバー数に応じて自動生成</li>
                    <li>ビジター関連スライド（p.19, p.169-180, p.213-224, p.235）- ビジター人数に応じて自動生成</li>
                    <li>各チャンピオン一覧（p.96）- 5つのチャンピオンから1位の情報を自動引用</li>
                </ul>
            </section>

            <!-- 4. よくある質問 -->
            <section class="section" id="faq">
                <h2>
                    <span class="icon"><i class="fas fa-question-circle"></i></span>
                    4. よくある質問（FAQ）
                </h2>

                <h3>Q1. メンバーを追加したらスライドも自動で増えますか？</h3>
                <p><strong>A:</strong> はい。メンバーピッチ（p.112-166）は、メンバー追加時に自動的にスライドが追加されます。1名につき1ページ生成されます。</p>

                <h3>Q2. ビジターを何名まで登録できますか？</h3>
                <p><strong>A:</strong> 制限はありません。ビジター人数に応じて、各スライドが自動的に増減します。</p>

                <h3>Q3. PDFは何ページまでアップロードできますか？</h3>
                <p><strong>A:</strong> 制限はありませんが、大きすぎるファイルは変換に時間がかかる場合があります。推奨は20ページ以内です。</p>

                <h3>Q4. YouTube動画は限定公開でないとダメですか？</h3>
                <p><strong>A:</strong> いいえ。公開・限定公開・非公開のいずれでも埋め込み可能です。ただし、セキュリティの観点から限定公開を推奨します。</p>

                <h3>Q5. スライドを一時的に非表示にできますか？</h3>
                <p><strong>A:</strong> はい。<code>admin/slide_visibility.php</code>で全スライドの表示/非表示を切り替えられます。</p>

                <h3>Q6. 写真のサイズはどれくらいがいいですか？</h3>
                <p><strong>A:</strong> 推奨サイズは800×800px以上、2MB以下です。アップロード時に自動的にリサイズされます。</p>

                <h3>Q7. データのバックアップはできますか？</h3>
                <p><strong>A:</strong> データベースファイル（<code>bni_slide_v2.db</code>）をダウンロードすることでバックアップできます。管理者に依頼してください。</p>

                <h3>Q8. タイマーが動きません</h3>
                <p><strong>A:</strong> JavaScriptが無効になっている可能性があります。ブラウザの設定でJavaScriptを有効にしてください。</p>

                <h3>Q9. 複数人で同時に編集できますか？</h3>
                <p><strong>A:</strong> 技術的には可能ですが、データの整合性を保つため、1名ずつ編集することを推奨します。</p>

                <h3>Q10. スマートフォンでも使えますか？</h3>
                <p><strong>A:</strong> はい。レスポンシブデザインのため、スマートフォン・タブレットでも利用できます。ただし、スライド表示はPCでの利用を推奨します。</p>
            </section>

            <!-- 5. トラブルシューティング -->
            <section class="section" id="troubleshooting">
                <h2>
                    <span class="icon"><i class="fas fa-tools"></i></span>
                    5. トラブルシューティング
                </h2>

                <h3>問題1: スライドが真っ白になる</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>データベース接続エラーの可能性があります。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>ページを再読み込み（F5）</li>
                    <li>それでも解決しない場合は、管理者に連絡</li>
                </ol>

                <h3>問題2: 保存ボタンを押しても反映されない</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>必須項目が未入力の可能性があります。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>赤い枠で表示されている項目を入力</li>
                    <li>エラーメッセージを確認</li>
                    <li>再度「保存」ボタンをクリック</li>
                </ol>

                <h3>問題3: 写真がアップロードできない</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>ファイルサイズが大きすぎるか、対応していない形式です。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>ファイルサイズを2MB以下に縮小</li>
                    <li>JPG/PNG形式に変換</li>
                    <li>再度アップロード</li>
                </ol>

                <h3>問題4: PDFが画像に変換されない</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>Python環境の問題、またはPDFファイルの破損です。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>PDFファイルを再度保存して、破損していないか確認</li>
                    <li>別のPDFで試す</li>
                    <li>それでも解決しない場合は、管理者に連絡</li>
                </ol>

                <h3>問題5: タイマーが0:00で止まらない</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>ブラウザのキャッシュが原因の可能性があります。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>Ctrl + Shift + R（スーパーリロード）でページを再読み込み</li>
                    <li>ブラウザのキャッシュをクリア</li>
                </ol>

                <h3>問題6: ドラッグ&ドロップが動かない</h3>
                <div class="info-box danger">
                    <h4><i class="fas fa-exclamation-circle"></i> 原因</h4>
                    <p>ブラウザが古いか、JavaScriptライブラリの読み込みエラーです。</p>
                </div>
                <p><strong>解決策:</strong></p>
                <ol>
                    <li>最新のブラウザ（Chrome、Firefox、Edge）を使用</li>
                    <li>ページを再読み込み</li>
                </ol>

                <h3>緊急時の対応</h3>
                <div class="info-box warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> 定例会直前にエラーが発生した場合</h4>
                    <p>
                        <strong>Step 1:</strong> 別のブラウザで試す（Chrome → Firefox など）<br>
                        <strong>Step 2:</strong> シークレットモード（プライベートブラウジング）で開く<br>
                        <strong>Step 3:</strong> それでも解決しない場合、管理者に緊急連絡
                    </p>
                </div>
            </section>

            <!-- 6. お問い合わせ先 -->
            <section class="section" id="contact">
                <h2>
                    <span class="icon"><i class="fas fa-envelope"></i></span>
                    6. お問い合わせ先
                </h2>

                <p>ご不明な点やトラブルがございましたら、以下までお問い合わせください。</p>

                <div class="info-box">
                    <h4><i class="fas fa-phone"></i> サポート窓口</h4>
                    <p>
                        <strong>システム管理者:</strong> [管理者名を記載]<br>
                        <strong>Email:</strong> <code>support@example.com</code><br>
                        <strong>電話:</strong> <code>03-XXXX-XXXX</code><br>
                        <strong>対応時間:</strong> 平日 9:00-18:00
                    </p>
                </div>

                <h3>関連リンク</h3>
                <ul>
                    <li><a href="https://yojitu.com/bni-slide-system/slides_v2/" target="_blank">スライド表示画面</a></li>
                    <li><a href="https://yojitu.com/bni-slide-system/slides_v2/admin/" target="_blank">管理画面ダッシュボード</a></li>
                    <li><a href="https://yojitu.com/bni-slide-system/slides_v2/sitemap.php" target="_blank">サイトマップ</a></li>
                </ul>

                <h3>システム情報</h3>
                <table>
                    <tbody>
                        <tr>
                            <td><strong>バージョン</strong></td>
                            <td>BNI Slide System V2</td>
                        </tr>
                        <tr>
                            <td><strong>リリース日</strong></td>
                            <td>2025年12月14日</td>
                        </tr>
                        <tr>
                            <td><strong>技術スタック</strong></td>
                            <td>PHP 7.4+, SQLite3, JavaScript, Python</td>
                        </tr>
                        <tr>
                            <td><strong>対応ブラウザ</strong></td>
                            <td>Chrome, Firefox, Edge（最新版）</td>
                        </tr>
                    </tbody>
                </table>

                <div class="info-box success">
                    <h4><i class="fas fa-heart"></i> 最後に</h4>
                    <p>
                        BNI Slide System V2をご利用いただき、ありがとうございます。<br>
                        このシステムが、皆様のBNI活動をより円滑に、より効果的にするお手伝いができれば幸いです。<br>
                        <strong>素晴らしい定例会を！</strong>
                    </p>
                </div>
            </section>
        </main>
    </div>

    <div class="footer">
        BNI Slide System V2 - User Manual &copy; 2025 | Powered by PHP & SQLite3
    </div>

    <script>
        // 検索機能
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const sections = document.querySelectorAll('.section');

            sections.forEach(function(section) {
                const text = section.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });

        // スムーススクロール
        document.querySelectorAll('.toc a').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 100,
                        behavior: 'smooth'
                    });

                    // アクティブ状態更新
                    document.querySelectorAll('.toc a').forEach(function(link) {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });

        // スクロール時のアクティブリンク更新
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.section');
            const scrollPos = window.scrollY + 150;

            sections.forEach(function(section) {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                    document.querySelectorAll('.toc a').forEach(function(link) {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
