<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'システム開発 詳細料金表｜余日（Yojitsu）';
$page_description = '発注者様向けの詳細料金表。技術スタック別・機能別の料金を明記。PHP、Python、GAS対応。前入金制。免責事項あり。';
$page_keywords = 'システム開発,料金表,発注,PHP,Python,GAS,API連携,決済システム,余日';
$additional_css = ['assets/css/cookie-consent.css'];

$inline_styles = <<<'EOD'
        .page-header {
            background: linear-gradient(135deg, var(--color-natural-brown) 0%, var(--color-charcoal) 100%);
            padding: var(--spacing-xxl) 0;
            text-align: center;
            color: var(--color-bg-white);
        }

        .page-header__title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: var(--spacing-md);
            letter-spacing: 0.05em;
            color: var(--color-bg-white);
        }

        .page-header__description {
            font-size: 18px;
            line-height: 1.8;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
        }

        .content-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .content-section--alt {
            background: var(--color-bg-gray);
        }

        .section__title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: var(--spacing-xl);
            color: var(--color-charcoal);
            border-left: 4px solid var(--color-natural-brown);
            padding-left: var(--spacing-md);
        }

        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ff9800;
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            border-radius: 4px;
        }

        .alert-box__title {
            font-size: 18px;
            font-weight: 700;
            color: #ff9800;
            margin-bottom: var(--spacing-sm);
        }

        .alert-box ul {
            margin: var(--spacing-sm) 0 0 var(--spacing-lg);
            color: var(--color-text);
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--color-bg-white);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: var(--spacing-xl);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .price-table th {
            background: var(--color-natural-brown);
            color: var(--color-bg-white);
            padding: var(--spacing-md);
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .price-table td {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--color-border);
            font-size: 14px;
            color: var(--color-text);
        }

        .price-table tr:last-child td {
            border-bottom: none;
        }

        .price-table tr:hover {
            background: #fafafa;
        }

        .price-table--limited {
            border-left: 3px solid #ff9800;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge--warning {
            background: #fff3cd;
            color: #ff9800;
        }

        .badge--success {
            background: #d4edda;
            color: #28a745;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196f3;
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
            border-radius: 4px;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            line-height: 1.8;
            color: var(--color-text);
        }

        .example-card {
            background: var(--color-bg-white);
            border: 2px solid var(--color-border);
            border-radius: 8px;
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .example-card__title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
        }

        .example-card__price {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-charcoal);
            margin-bottom: var(--spacing-md);
        }

        .example-card ul {
            margin: 0 0 var(--spacing-md) var(--spacing-lg);
        }

        .example-card ul li {
            margin-bottom: 4px;
            font-size: 14px;
            color: var(--color-text);
        }

        @media (max-width: 768px) {
            .page-header__title {
                font-size: 28px;
            }

            .price-table {
                font-size: 12px;
            }

            .price-table th,
            .price-table td {
                padding: var(--spacing-sm);
            }

            .section__title {
                font-size: 24px;
            }
        }

        /* モーダル用スタイル */
        .term {
            color: var(--color-natural-brown);
            text-decoration: underline;
            text-decoration-style: dotted;
            cursor: help;
            position: relative;
        }

        .term:hover {
            color: var(--color-charcoal);
            text-decoration-style: solid;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.2s ease;
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal__content {
            background: var(--color-bg-white);
            padding: var(--spacing-xl);
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .modal__close {
            position: absolute;
            top: 16px;
            right: 16px;
            font-size: 28px;
            font-weight: 700;
            color: #999;
            cursor: pointer;
            border: none;
            background: none;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .modal__close:hover {
            background: #f0f0f0;
            color: var(--color-charcoal);
        }

        .modal__title {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
            padding-right: 40px;
        }

        .modal__body {
            font-size: 16px;
            line-height: 1.8;
            color: var(--color-text);
        }

        .modal__body p {
            margin-bottom: var(--spacing-md);
        }

        .modal__body ul {
            margin: var(--spacing-sm) 0 var(--spacing-md) var(--spacing-lg);
        }

        .modal__body ul li {
            margin-bottom: 8px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .modal__content {
                padding: var(--spacing-lg);
                width: 95%;
            }

            .modal__title {
                font-size: 20px;
            }

            .modal__body {
                font-size: 14px;
            }
        }
EOD;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">
                <i class="fas fa-cogs"></i> システム開発 詳細料金表
            </h1>
            <p class="page-header__description">
                発注者様向けの技術スタック別・機能別料金表<br>
                前入金制 | 免責事項あり
            </p>
        </div>
    </section>

    <!-- 基本方針 -->
    <section class="content-section">
        <div class="container">
            <h2 class="section__title">基本方針</h2>
            <div class="info-box">
                <p>
                    <strong>リスク管理を最優先</strong> - セキュリティリスクが高い案件は限定的に受けます<br>
                    <strong>前入金制</strong> - 作業開始前の入金必須<br>
                    <strong><span class="term" data-term="disclaimer">免責事項</span>の明記</strong> - <span class="term" data-term="security">セキュリティインシデント</span>時の責任範囲を明確化<br>
                    <strong>対応環境</strong> - <span class="term" data-term="xserver">Xserver</span>推奨、AWS/GCP/Azure等にも対応可能
                </p>
            </div>

            <div class="alert-box">
                <div class="alert-box__title">⚠️ 受けない案件</div>
                <ul>
                    <li>金融系システム（銀行、証券、保険等）</li>
                    <li>医療系システム（電子カルテ等）</li>
                    <li>大規模決済システム（月間取引額1,000万円超）</li>
                    <li>クリティカルミッション系（24時間365日稼働必須）</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 技術スタック別単価 -->
    <section class="content-section content-section--alt">
        <div class="container">
            <h2 class="section__title">技術スタック別 基本単価</h2>

            <h3 style="margin-bottom: 16px; font-size: 20px; color: var(--color-charcoal);">バックエンド言語</h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>言語</th>
                        <th>基本時給</th>
                        <th>複雑度係数</th>
                        <th>できること（具体例）</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>PHP</strong></td>
                        <td>12,000円/h</td>
                        <td>1.0x</td>
                        <td>会員サイト、予約システム、管理画面、お問い合わせフォーム（Xserver推奨）</td>
                    </tr>
                    <tr>
                        <td><strong>Python</strong></td>
                        <td>15,000円/h</td>
                        <td>1.2x</td>
                        <td>データ収集・自動化、Excel/CSV処理、レポート生成、機械学習（Xserver推奨）</td>
                    </tr>
                    <tr>
                        <td><strong>JavaScript (Node.js)</strong></td>
                        <td>13,000円/h</td>
                        <td>1.1x</td>
                        <td>チャット、リアルタイム通知、WebSocket、REST API（Xserver推奨）</td>
                    </tr>
                    <tr>
                        <td><strong>GAS</strong></td>
                        <td>10,000円/h</td>
                        <td>0.8x</td>
                        <td>Googleスプレッドシート自動化、Gmail自動送信、フォーム連携</td>
                    </tr>
                    <tr>
                        <td><strong>Ruby</strong></td>
                        <td>18,000円/h</td>
                        <td>1.5x</td>
                        <td>Ruby on Rails、高速なWebアプリ開発、スタートアップ向け（Xserver対応、外注の可能性あり）</td>
                    </tr>
                    <tr>
                        <td><strong>Perl</strong></td>
                        <td>16,000円/h</td>
                        <td>1.3x</td>
                        <td>CGI、テキスト処理、レガシーシステム改修（Xserver対応）</td>
                    </tr>
                    <tr>
                        <td><strong>Go</strong></td>
                        <td>20,000円/h</td>
                        <td>1.6x</td>
                        <td>高速API、大量データ処理、マイクロサービス（AWS/GCP等推奨、外注の可能性あり）</td>
                    </tr>
                </tbody>
            </table>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);"><span class="term" data-term="database">データベース</span></h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>データベース</th>
                        <th>設計料</th>
                        <th>構築料</th>
                        <th>複雑度係数</th>
                        <th>備考</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>SQLite</strong></td>
                        <td>50,000円</td>
                        <td>30,000円</td>
                        <td>0.8x</td>
                        <td>小規模・単独利用</td>
                    </tr>
                    <tr>
                        <td><strong>MySQL</strong></td>
                        <td>80,000円</td>
                        <td>50,000円</td>
                        <td>1.0x</td>
                        <td>標準的なDB</td>
                    </tr>
                    <tr>
                        <td><strong>PostgreSQL</strong></td>
                        <td>100,000円</td>
                        <td>70,000円</td>
                        <td>1.2x</td>
                        <td>高度なクエリ対応</td>
                    </tr>
                </tbody>
            </table>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">外部<span class="term" data-term="api">API</span>連携</h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>サービス</th>
                        <th>連携料</th>
                        <th>複雑度係数</th>
                        <th>対応範囲</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="price-table--limited">
                        <td><strong><span class="term" data-term="stripe">Stripe</span>決済</strong></td>
                        <td>150,000円</td>
                        <td>1.5x</td>
                        <td><span class="badge badge--warning">小規模のみ</span> 100万円未満の案件のみ</td>
                    </tr>
                    <tr class="price-table--limited">
                        <td><strong>PAY.JP決済</strong></td>
                        <td>150,000円</td>
                        <td>1.5x</td>
                        <td><span class="badge badge--warning">小規模のみ</span> 100万円未満の案件のみ</td>
                    </tr>
                    <tr>
                        <td><strong>Google Maps API</strong></td>
                        <td>80,000円</td>
                        <td>1.2x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>Gmail API</strong></td>
                        <td>60,000円</td>
                        <td>1.0x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>Google Calendar API</strong></td>
                        <td>70,000円</td>
                        <td>1.1x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>Twilio（SMS/電話）</strong></td>
                        <td>100,000円</td>
                        <td>1.3x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>SendGrid（メール配信）</strong></td>
                        <td>90,000円</td>
                        <td>1.2x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>LINE Messaging API</strong></td>
                        <td>120,000円</td>
                        <td>1.4x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>AWS S3（ファイル保存）</strong></td>
                        <td>150,000円</td>
                        <td>1.5x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>Notion API</strong></td>
                        <td>180,000円</td>
                        <td>1.6x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><strong>Airtable API</strong></td>
                        <td>180,000円</td>
                        <td>1.6x</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                </tbody>
            </table>
            <div class="info-box">
                <p>
                    ※上記はあくまで一部です。Shopify、WordPress REST API、Facebook/Instagram API、Twitter API、YouTube API等、その他のAPIについてもお問い合わせください。
                </p>
            </div>
        </div>
    </section>

    <!-- 機能別料金表 -->
    <section class="content-section">
        <div class="container">
            <h2 class="section__title">機能別 料金表</h2>

            <h3 style="margin-bottom: 16px; font-size: 20px; color: var(--color-charcoal);">認証・ユーザー管理</h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>機能</th>
                        <th>料金</th>
                        <th>工数目安</th>
                        <th>対応範囲</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ログイン機能（基本）</td>
                        <td>100,000円</td>
                        <td>8-10h</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td>新規登録機能</td>
                        <td>80,000円</td>
                        <td>6-8h</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td>パスワードリセット</td>
                        <td>60,000円</td>
                        <td>5-6h</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="2fa">2段階認証（2FA）</span></td>
                        <td>150,000円</td>
                        <td>12-15h</td>
                        <td><span class="badge badge--warning">案件次第</span></td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="rbac">権限管理（RBAC）</span></td>
                        <td>180,000円</td>
                        <td>15-18h</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                </tbody>
            </table>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">ファイル処理</h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>機能</th>
                        <th>料金</th>
                        <th>工数目安</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ファイルアップロード</td>
                        <td>100,000円</td>
                        <td>8-10h</td>
                    </tr>
                    <tr>
                        <td>画像リサイズ</td>
                        <td>60,000円</td>
                        <td>5-6h</td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="csv">CSV</span>出力</td>
                        <td>80,000円</td>
                        <td>6-8h</td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="csv">CSV</span> インポート</td>
                        <td>100,000円</td>
                        <td>8-10h</td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="pdf">PDF</span>生成</td>
                        <td>150,000円</td>
                        <td>12-15h</td>
                    </tr>
                </tbody>
            </table>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">決済機能 ⚠️ 制限あり</h3>
            <table class="price-table price-table--limited">
                <thead>
                    <tr>
                        <th>機能</th>
                        <th>料金</th>
                        <th>工数目安</th>
                        <th>対応範囲</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>クレカ決済（単発）</td>
                        <td>200,000円</td>
                        <td>16-20h</td>
                        <td><span class="badge badge--warning">小規模のみ</span> 100万円未満</td>
                    </tr>
                    <tr>
                        <td><span class="term" data-term="subscription">サブスクリプション</span></td>
                        <td>300,000円</td>
                        <td>25-30h</td>
                        <td><span class="badge badge--warning">小規模のみ</span> 100万円未満</td>
                    </tr>
                    <tr>
                        <td>決済履歴管理</td>
                        <td>100,000円</td>
                        <td>8-10h</td>
                        <td><span class="badge badge--warning">小規模のみ</span></td>
                    </tr>
                    <tr>
                        <td>領収書発行</td>
                        <td>80,000円</td>
                        <td>6-8h</td>
                        <td><span class="badge badge--success">全案件</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="alert-box" style="margin-top: 16px;">
                <div class="alert-box__title">⚠️ 決済機能に関する重要事項</div>
                <ul>
                    <li>対応範囲: 小規模案件（総額100万円未満）のみ</li>
                    <li>月間取引額: 100万円未満のシステムのみ</li>
                    <li>免責事項: 決済システムの不具合・セキュリティインシデントによる損害は責任を負いません</li>
                </ul>
            </div>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">自動化・スクリプト</h3>
            <table class="price-table">
                <thead>
                    <tr>
                        <th>機能</th>
                        <th>料金</th>
                        <th>工数目安</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="term" data-term="gas">GAS</span>スクリプト（基本）</td>
                        <td>80,000円</td>
                        <td>8-10h</td>
                    </tr>
                    <tr>
                        <td>データ収集（<span class="term" data-term="scraping">スクレイピング</span>）</td>
                        <td>150,000円</td>
                        <td>12-15h</td>
                    </tr>
                    <tr>
                        <td>定期実行（<span class="term" data-term="cron">cron</span>）</td>
                        <td>60,000円</td>
                        <td>5-6h</td>
                    </tr>
                    <tr>
                        <td>Pythonスクリプト</td>
                        <td>120,000円</td>
                        <td>10-12h</td>
                    </tr>
                    <tr>
                        <td>レポート自動生成</td>
                        <td>180,000円</td>
                        <td>15-18h</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- システム規模別料金 -->
    <section class="content-section content-section--alt">
        <div class="container">
            <h2 class="section__title">システム規模別 料金目安</h2>

            <div class="example-card">
                <div class="example-card__title">小規模システム</div>
                <div class="example-card__price">50万円〜100万円</div>
                <p style="margin-bottom: 12px;"><strong>機能数:</strong> 1-3機能 | <strong>開発期間:</strong> 1-2ヶ月 | <strong>想定工数:</strong> 40-80時間</p>
                <p style="margin-bottom: 12px; font-weight: 600;">例）GAS自動化ツール</p>
                <ul>
                    <li>GASスクリプト（基本）: 80,000円</li>
                    <li>スプレッドシート操作: 80,000円</li>
                    <li>Gmail自動送信: 60,000円</li>
                    <li>定期実行設定: 60,000円</li>
                    <li><strong>合計: 280,000円 → 提案価格: 50万円</strong></li>
                </ul>
            </div>

            <div class="example-card">
                <div class="example-card__title">中規模システム</div>
                <div class="example-card__price">150万円〜300万円</div>
                <p style="margin-bottom: 12px;"><strong>機能数:</strong> 4-10機能 | <strong>開発期間:</strong> 2-4ヶ月 | <strong>想定工数:</strong> 100-200時間</p>
                <p style="margin-bottom: 12px; font-weight: 600;">例）会員管理システム</p>
                <ul>
                    <li>データベース設計（MySQL）: 80,000円</li>
                    <li>ログイン機能: 100,000円</li>
                    <li>新規登録機能: 80,000円</li>
                    <li>会員情報CRUD: 120,000円</li>
                    <li>検索・フィルタ: 140,000円</li>
                    <li>管理画面: 200,000円</li>
                    <li>セキュリティ対策: 220,000円</li>
                    <li>レスポンシブ対応: 150,000円</li>
                    <li><strong>合計: 1,230,000円 → 提案価格: 200万円</strong></li>
                </ul>
                <div class="alert-box" style="margin-top: 12px;">
                    <div class="alert-box__title">⚠️ 免責条項</div>
                    <ul>
                        <li>個人情報漏洩が発生した場合の損害賠償責任は負いません</li>
                        <li>セキュリティ対策は業界標準レベルを実装しますが、100%の安全を保証するものではありません</li>
                    </ul>
                </div>
            </div>

            <div class="example-card">
                <div class="example-card__title">大規模システム</div>
                <div class="example-card__price">500万円〜</div>
                <p style="margin-bottom: 12px;"><strong>機能数:</strong> 11機能以上 | <strong>開発期間:</strong> 4-6ヶ月以上 | <strong>想定工数:</strong> 300時間以上</p>
                <p style="margin-bottom: 12px; font-weight: 600;">対応範囲: 内容次第で受諾判断</p>
                <p style="margin-bottom: 12px;"><strong>✅ 受けられる例:</strong></p>
                <ul>
                    <li>GAS自動化の大規模版（データ量が多い）</li>
                    <li>社内システム（外部公開なし）</li>
                    <li>個人情報を扱わないシステム</li>
                </ul>
                <p style="margin: 12px 0; font-weight: 600;"><strong>❌ 受けられない例:</strong></p>
                <ul>
                    <li>決済システム（月間取引額が大きい）</li>
                    <li>会員数1,000人以上の会員制サイト</li>
                    <li>24時間365日稼働必須のシステム</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 月額保守契約 -->
    <section class="content-section">
        <div class="container">
            <h2 class="section__title">月額保守契約</h2>

            <table class="price-table">
                <thead>
                    <tr>
                        <th>プラン</th>
                        <th>料金</th>
                        <th>含まれるもの</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>ライトプラン</strong></td>
                        <td>50,000円/月</td>
                        <td>サーバー監視、セキュリティアップデート、軽微な修正（月2時間まで）、メールサポート</td>
                    </tr>
                    <tr>
                        <td><strong>スタンダードプラン</strong></td>
                        <td>100,000円/月</td>
                        <td>ライトプラン全内容、機能追加・改修（月5時間まで）、電話サポート、データバックアップ</td>
                    </tr>
                    <tr>
                        <td><strong>プレミアムプラン</strong></td>
                        <td>200,000円/月</td>
                        <td>スタンダードプラン全内容、無制限の機能追加・改修対応、優先サポート（24時間以内対応）、専属担当者</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- 契約条件・免責事項 -->
    <section class="content-section content-section--alt">
        <div class="container">
            <h2 class="section__title">契約条件・免責事項</h2>

            <div class="info-box">
                <p><strong>前入金制</strong></p>
                <p>
                    小規模（100万円未満）: 全額前入金<br>
                    中規模（100-300万円）: 50%前入金、50%納品時<br>
                    大規模（300万円以上）: 30%前入金、40%中間、30%納品時
                </p>
            </div>

            <div class="alert-box">
                <div class="alert-box__title">⚠️ 免責事項・責任範囲</div>
                <p style="margin-bottom: 8px;"><strong>以下の場合、損害賠償責任を負いません：</strong></p>
                <ul>
                    <li>個人情報漏洩</li>
                    <li>不正アクセス</li>
                    <li>データ改ざん</li>
                    <li>サービス停止</li>
                    <li>第三者サービス（Stripe、Google等）の障害</li>
                    <li>自然災害、戦争、テロ、サイバー攻撃等の不可抗力</li>
                </ul>
                <p style="margin-top: 8px;"><strong>責任の上限:</strong> いかなる場合も、損害賠償責任の上限は受領済みの契約金額とします</p>
            </div>
        </div>
    </section>

    <!-- お問い合わせCTA -->
    <section class="content-section">
        <div class="container" style="text-align: center;">
            <h2 style="font-size: 28px; margin-bottom: 16px;">お見積り・お問い合わせ</h2>
            <p style="margin-bottom: 24px; font-size: 16px; color: var(--color-text);">
                ご不明な点がございましたら、お気軽にお問い合わせください。<br>
                お見積りは無料です。
            </p>
            <a href="contact.php" class="btn btn-primary btn--large">
                <i class="fas fa-envelope"></i> お問い合わせはこちら
            </a>
        </div>
    </section>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- モーダル -->
    <div id="termModal" class="modal">
        <div class="modal__content">
            <button class="modal__close" aria-label="閉じる">&times;</button>
            <h3 class="modal__title" id="modalTitle"></h3>
            <div class="modal__body" id="modalBody"></div>
        </div>
    </div>

    <script defer src="assets/js/app.js"></script>

    <script>
        // 専門用語の説明データ
        const termDefinitions = {
            'api': {
                title: 'API（エーピーアイ）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「他のサービスとデータをやり取りするための仕組み」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>あなたのサイトに「Googleマップ」を表示する</li>
                        <li>LINEで自動返信する</li>
                        <li>クレジットカード決済をする</li>
                    </ul>
                    <p>これらは全て、他の会社が提供しているAPIを使って実現しています。</p>
                    <p>APIを使うことで、自分で一から作らなくても、便利な機能を追加できます。</p>
                `
            },
            'database': {
                title: 'データベースとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「情報を整理して保存する場所」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>会員の名前、メールアドレス、パスワード</li>
                        <li>商品の名前、値段、在庫数</li>
                        <li>予約の日時、お客様の情報</li>
                    </ul>
                    <p>これらの情報を、Excelのような表形式で保存し、必要な時にすぐに取り出せるようにします。</p>
                    <p>データベースがあることで、たくさんの情報を素早く検索したり、更新したりできます。</p>
                `
            },
            'crud': {
                title: 'CRUDとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「データの基本的な4つの操作」のことです。</p>
                    <p><strong>4つの操作：</strong></p>
                    <ul>
                        <li><strong>C</strong>reate（作成）- 新しいデータを追加する</li>
                        <li><strong>R</strong>ead（読取）- データを見る・検索する</li>
                        <li><strong>U</strong>pdate（更新）- データを書き換える</li>
                        <li><strong>D</strong>elete（削除）- データを消す</li>
                    </ul>
                    <p><strong>例えば、会員管理システムなら：</strong></p>
                    <ul>
                        <li>新しい会員を登録する（Create）</li>
                        <li>会員情報を見る（Read）</li>
                        <li>会員情報を変更する（Update）</li>
                        <li>会員を削除する（Delete）</li>
                    </ul>
                `
            },
            'subscription': {
                title: 'サブスクリプションとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「毎月決まった金額を自動で支払ってもらう仕組み」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>Netflixの月額980円</li>
                        <li>Spotifyの月額980円</li>
                        <li>オンラインサロンの月額500円</li>
                    </ul>
                    <p>お客様が一度クレジットカードを登録すれば、毎月自動で課金されます。</p>
                    <p>毎月お金をもらいに行かなくていいので、安定した収益が得られます。</p>
                `
            },
            '2fa': {
                title: '2段階認証（2FA）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「2回確認することで、セキュリティを強くする仕組み」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>1回目：メールアドレスとパスワードでログイン</li>
                        <li>2回目：スマホに送られてくる6桁の番号を入力</li>
                    </ul>
                    <p>パスワードが盗まれても、スマホがないとログインできないので安全です。</p>
                    <p>銀行のアプリやLINEなどでも使われている、セキュリティを高める方法です。</p>
                `
            },
            'rbac': {
                title: '権限管理（RBAC）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「誰が何をできるかを決める仕組み」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li><strong>管理者：</strong>全ての機能が使える（データの追加・編集・削除）</li>
                        <li><strong>スタッフ：</strong>データを見るだけ（編集・削除はできない）</li>
                        <li><strong>お客様：</strong>自分のデータだけ見られる</li>
                    </ul>
                    <p>会社やお店で、役職によって使える機能を変えたい時に使います。</p>
                    <p>間違って大事なデータを消されないように、権限を分けることができます。</p>
                `
            },
            'csv': {
                title: 'CSVとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「ExcelやGoogleスプレッドシートで開けるファイル形式」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>会員リストをCSVファイルでダウンロード</li>
                        <li>Excelで編集して、CSVファイルで一括登録</li>
                        <li>売上データをCSVで出力して、Excelで集計</li>
                    </ul>
                    <p>システムとExcelの間でデータをやり取りする時によく使います。</p>
                    <p>カンマ（,）で区切られたシンプルなテキストファイルなので、どのソフトでも開けます。</p>
                `
            },
            'pdf': {
                title: 'PDF生成とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「システムから自動でPDFファイルを作ること」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>領収書をPDFで自動発行</li>
                        <li>請求書をPDFでダウンロード</li>
                        <li>レポートをPDFで出力</li>
                    </ul>
                    <p>PDFは誰でも開けて、印刷しても綺麗に見えるので、ビジネス書類によく使われます。</p>
                    <p>自動でPDFを作れると、手作業で書類を作る時間が大幅に減ります。</p>
                `
            },
            'stripe': {
                title: 'Stripe（ストライプ）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「サイトでクレジットカード決済ができるサービス」です。</p>
                    <p><strong>できること：</strong></p>
                    <ul>
                        <li>クレジットカード決済（Visa、Mastercard等）</li>
                        <li>毎月自動で課金（サブスク）</li>
                        <li>コンビニ払い</li>
                    </ul>
                    <p>世界中で使われている決済サービスで、使いやすく安全です。</p>
                    <p>決済手数料は約3.6%です。</p>
                `
            },
            'gas': {
                title: 'GAS（Google Apps Script）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「Googleのサービスを自動化できるプログラム」です。</p>
                    <p><strong>できること：</strong></p>
                    <ul>
                        <li>Googleスプレッドシートを自動で更新</li>
                        <li>Gmailで自動返信</li>
                        <li>Googleカレンダーに予定を自動追加</li>
                        <li>Googleフォームの回答を自動処理</li>
                    </ul>
                    <p>無料で使えて、Googleのサービスと相性抜群です。</p>
                    <p>プログラミング知識がなくても、単純作業を自動化できます。</p>
                `
            },
            'scraping': {
                title: 'スクレイピングとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「Webサイトから自動でデータを集めること」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>競合他社の価格を自動で収集</li>
                        <li>ニュースサイトから記事タイトルを集める</li>
                        <li>不動産サイトから物件情報を取得</li>
                    </ul>
                    <p>手作業でコピペする代わりに、プログラムが自動でデータを集めてくれます。</p>
                    <p><strong>注意：</strong>利用規約で禁止されている場合があるので、確認が必要です。</p>
                `
            },
            'cron': {
                title: 'cron（クーロン）とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「決まった時間に自動で処理を実行する仕組み」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>毎日朝9時に、売上レポートをメール送信</li>
                        <li>毎週月曜日に、データのバックアップを取る</li>
                        <li>毎月1日に、請求書を自動発行</li>
                    </ul>
                    <p>人間が操作しなくても、決まった時間に自動で動くので便利です。</p>
                    <p>夜中や休日でも、自動で作業が進みます。</p>
                `
            },
            'security': {
                title: 'セキュリティインシデントとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「セキュリティの問題が起きること」です。</p>
                    <p><strong>例えば：</strong></p>
                    <ul>
                        <li>個人情報が漏れる</li>
                        <li>不正アクセスされる</li>
                        <li>データが改ざんされる</li>
                        <li>ウイルスに感染する</li>
                    </ul>
                    <p>100%防ぐことは難しいため、万が一起きた時の責任範囲を事前に決めておきます。</p>
                    <p>セキュリティ対策は業界標準レベルで実装しますが、完全に防げる保証はありません。</p>
                `
            },
            'disclaimer': {
                title: '免責事項とは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「責任を負わない範囲を明確にすること」です。</p>
                    <p><strong>なぜ必要？</strong></p>
                    <ul>
                        <li>どんなシステムでも、100%完璧にはできない</li>
                        <li>予想外のトラブルが起きることがある</li>
                        <li>第三者サービス（GoogleやStripe等）の障害もある</li>
                    </ul>
                    <p>例えば、地震でサーバーが壊れたり、ハッカーに攻撃されたりした時に、全ての損害を補償することは難しいです。</p>
                    <p>そのため、事前に「どこまで責任を負うか」を明確にしておきます。</p>
                `
            },
            'xserver': {
                title: 'Xserverとは？',
                body: `
                    <p><strong>簡単に言うと：</strong>「Webサイトを公開するためのサーバー（場所）」です。</p>
                    <p><strong>特徴：</strong></p>
                    <ul>
                        <li>日本の会社が運営していて、サポートが充実</li>
                        <li>価格が安い（月額990円〜）</li>
                        <li>速度が速く、安定している</li>
                        <li>PHP、MySQL、WordPressに対応</li>
                    </ul>
                    <p>個人事業主や中小企業に人気のレンタルサーバーです。</p>
                    <p>このサービスでは、Xserverを推奨していますが、AWS、GCP、Azure等の他のサーバーにも対応可能です。</p>
                `
            }
        };

        // モーダル開閉処理
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('termModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const closeBtn = document.querySelector('.modal__close');

            // 専門用語をクリックした時
            document.querySelectorAll('.term').forEach(term => {
                term.addEventListener('click', function(e) {
                    e.preventDefault();
                    const termId = this.getAttribute('data-term');
                    const definition = termDefinitions[termId];

                    if (definition) {
                        modalTitle.textContent = definition.title;
                        modalBody.innerHTML = definition.body;
                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            // 閉じるボタン
            closeBtn.addEventListener('click', function() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });

            // 背景をクリックして閉じる
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // ESCキーで閉じる
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

</body>
</html>
