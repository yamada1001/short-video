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
                    <strong>高品質・高単価</strong> - 安売りはしません<br>
                    <strong>リスク管理を最優先</strong> - セキュリティリスクが高い案件は限定的に受けます<br>
                    <strong>前入金制</strong> - 作業開始前の入金必須<br>
                    <strong>免責事項の明記</strong> - セキュリティインシデント時の責任範囲を明確化
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
                        <th>備考</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>PHP</strong></td>
                        <td>12,000円/h</td>
                        <td>1.0x</td>
                        <td>標準的な開発</td>
                    </tr>
                    <tr>
                        <td><strong>Python</strong></td>
                        <td>15,000円/h</td>
                        <td>1.2x</td>
                        <td>データ処理・自動化に強い</td>
                    </tr>
                    <tr>
                        <td><strong>JavaScript (Node.js)</strong></td>
                        <td>13,000円/h</td>
                        <td>1.1x</td>
                        <td>リアルタイム処理</td>
                    </tr>
                    <tr>
                        <td><strong>GAS</strong></td>
                        <td>10,000円/h</td>
                        <td>0.8x</td>
                        <td>Googleサービス連携</td>
                    </tr>
                </tbody>
            </table>

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">データベース</h3>
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

            <h3 style="margin: 32px 0 16px; font-size: 20px; color: var(--color-charcoal);">外部API連携</h3>
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
                        <td><strong>Stripe決済</strong></td>
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
                </tbody>
            </table>
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
                        <td>2段階認証（2FA）</td>
                        <td>150,000円</td>
                        <td>12-15h</td>
                        <td><span class="badge badge--warning">案件次第</span></td>
                    </tr>
                    <tr>
                        <td>権限管理（RBAC）</td>
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
                        <td>CSV出力</td>
                        <td>80,000円</td>
                        <td>6-8h</td>
                    </tr>
                    <tr>
                        <td>CSV インポート</td>
                        <td>100,000円</td>
                        <td>8-10h</td>
                    </tr>
                    <tr>
                        <td>Excel出力</td>
                        <td>120,000円</td>
                        <td>10-12h</td>
                    </tr>
                    <tr>
                        <td>PDF生成</td>
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
                        <td>サブスクリプション</td>
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
                        <td>GASスクリプト（基本）</td>
                        <td>80,000円</td>
                        <td>8-10h</td>
                    </tr>
                    <tr>
                        <td>データ収集（スクレイピング）</td>
                        <td>150,000円</td>
                        <td>12-15h</td>
                    </tr>
                    <tr>
                        <td>定期実行（cron）</td>
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

    <script defer src="assets/js/app.js"></script>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

</body>
</html>
