<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'システム開発パッケージ詳細｜余日（Yojitsu）';
$page_description = '事務ゼロ化パック30万円〜、売上最大化パック80万円〜。各パッケージの詳細な機能、ROI、ベネフィットをご紹介。大分県の経営者様向けシステム開発サービス。';
$page_keywords = 'システム開発,パッケージ,料金,ROI,業務効率化,大分県,余日';
$additional_css = ['assets/css/pages/system-development-packages.css', 'assets/css/cookie-consent.css'];
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

    <!-- パンくずリスト -->
    <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="index.php"><i class="fas fa-home"></i> ホーム</a></li>
                <li><a href="services.php">サービス一覧</a></li>
                <li><a href="system-development.php">システム開発</a></li>
                <li>パッケージ詳細</li>
            </ul>
        </div>
    </div>

    <!-- ページヘッダー -->
    <section class="packages-header">
        <div class="container">
            <h1 class="packages-header__title">システム開発パッケージ詳細</h1>
            <p class="packages-header__subtitle">
                御社の課題に合わせた3つのパッケージをご用意しています。<br>
                時給ではなく、「成果（=課題解決）」に対しての明確な価格設定です。
            </p>
        </div>
    </section>

    <!-- パッケージ①：事務ゼロ化パック -->
    <section class="package-detail" id="pack-1">
        <div class="container">
            <div class="package-detail__header">
                <div class="package-detail__icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="package-detail__title-area">
                    <h2 class="package-detail__name">事務ゼロ化パック</h2>
                    <p class="package-detail__tagline">転記作業から解放される、第一歩</p>
                </div>
                <div class="package-detail__price-area">
                    <p class="package-detail__price">
                        <span class="package-detail__amount">30万円</span>
                        <span class="package-detail__unit">〜</span>
                    </p>
                    <p class="package-detail__period">工期: 2週間〜</p>
                </div>
            </div>

            <div class="package-detail__content">
                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-lightbulb"></i>
                        こんな課題を解決します
                    </h3>
                    <ul class="package-detail__list">
                        <li>事務員が一日中、エクセルへの転記作業をしている</li>
                        <li>メールやWebフォームから来た注文を、手作業で台帳に入力している</li>
                        <li>処理完了のメールを、毎回手動で送っている</li>
                        <li>月末の集計作業に、丸一日かかっている</li>
                    </ul>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-check-circle"></i>
                        含まれる機能
                    </h3>
                    <div class="package-detail__features-grid">
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h4 class="feature-item__title">自動取り込み</h4>
                            <p class="feature-item__text">
                                メールやWebサイトからの注文情報を、自動で読み取るロボットを設置。人の手を介さず、ミスなくデータを取得します。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-table"></i>
                            </div>
                            <h4 class="feature-item__title">台帳連携</h4>
                            <p class="feature-item__text">
                                読み取ったデータを、現在お使いのスプレッドシートやExcelへズレなく自動記入。書式も崩れません。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4 class="feature-item__title">完了通知</h4>
                            <p class="feature-item__text">
                                「処理しました」という完了メールを、担当者へ自動送信。進捗がリアルタイムで把握できます。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h4 class="feature-item__title">カスタマイズ対応</h4>
                            <p class="feature-item__text">
                                御社の既存フォーマットに合わせて調整。使い慣れた画面のまま、自動化を実現します。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section package-detail__section--highlight">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-chart-line"></i>
                        費用対効果（ROI）
                    </h3>
                    <div class="roi-card">
                        <div class="roi-card__main">
                            <p class="roi-card__label">投資回収期間</p>
                            <p class="roi-card__value">約10ヶ月</p>
                            <p class="roi-card__note">月40時間の削減換算（時給1,000円の場合）</p>
                        </div>
                        <div class="roi-card__breakdown">
                            <h4 class="roi-card__breakdown-title">削減効果の内訳</h4>
                            <ul>
                                <li>
                                    <span class="roi-card__item-label">転記作業時間:</span>
                                    <span class="roi-card__item-value">月30時間 → 0時間</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">メール送信作業:</span>
                                    <span class="roi-card__item-value">月5時間 → 0時間</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">集計作業:</span>
                                    <span class="roi-card__item-value">月5時間 → 0.5時間</span>
                                </li>
                            </ul>
                            <div class="roi-card__total">
                                <strong>合計削減時間:</strong> 約40時間/月<br>
                                <strong>年間削減コスト:</strong> 約48万円<br>
                                <strong>10ヶ月で投資額を回収</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-gift"></i>
                        導入後に得られるメリット
                    </h3>
                    <ul class="package-detail__benefits">
                        <li>
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>時間的余裕:</strong> 転記作業がゼロになり、事務員が本来の業務（顧客対応や企画業務）に集中できます。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>ミス防止:</strong> 手入力によるヒューマンエラーが完全に排除され、顧客からのクレームが激減します。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-smile"></i>
                            <div>
                                <strong>従業員満足度向上:</strong> 単純作業から解放され、やりがいのある仕事に時間を使えるようになります。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-tachometer-alt"></i>
                            <div>
                                <strong>処理スピード向上:</strong> 注文から処理完了まで、即座に自動実行。お客様の待ち時間が大幅に短縮されます。
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="package-detail__cta">
                    <a href="contact.php" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        このパッケージについて相談する
                    </a>
                    <a href="system-development.php" class="btn btn-outline btn--large">
                        <i class="fas fa-arrow-left"></i>
                        パッケージ一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- パッケージ②：売上最大化パック -->
    <section class="package-detail package-detail--alt" id="pack-2">
        <div class="container">
            <div class="package-detail__header">
                <div class="package-detail__badge">おすすめ</div>
                <div class="package-detail__icon package-detail__icon--accent">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="package-detail__title-area">
                    <h2 class="package-detail__name">売上最大化パック</h2>
                    <p class="package-detail__tagline">24時間365日、売上を生み出す仕組み</p>
                </div>
                <div class="package-detail__price-area">
                    <p class="package-detail__price">
                        <span class="package-detail__amount">80万円</span>
                        <span class="package-detail__unit">〜</span>
                    </p>
                    <p class="package-detail__period">工期: 2ヶ月〜</p>
                </div>
            </div>

            <div class="package-detail__content">
                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-lightbulb"></i>
                        こんな課題を解決します
                    </h3>
                    <ul class="package-detail__list">
                        <li>電話でしか予約できないため、夜間や休日の問い合わせを取りこぼしている</li>
                        <li>無断キャンセルが多く、機会損失が発生している</li>
                        <li>一度来店したお客様への追客ができていない</li>
                        <li>顧客情報がバラバラで、効果的なアプローチができない</li>
                    </ul>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-check-circle"></i>
                        含まれる機能
                    </h3>
                    <div class="package-detail__features-grid">
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="feature-item__title">Web予約カレンダー</h4>
                            <p class="feature-item__text">
                                24時間、空き状況がリアルタイムで分かる予約画面をHPに設置。深夜2時でも予約を逃しません。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4 class="feature-item__title">事前決済導入</h4>
                            <p class="feature-item__text">
                                予約と同時に支払いを完了させ、無断キャンセルを防止。キャンセル率が大幅に低下します。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="feature-item__title">顧客管理システム</h4>
                            <p class="feature-item__text">
                                「最終来店日」「購入履歴」で顧客を自動ランク分け。優良顧客を見逃しません。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h4 class="feature-item__title">自動追客（ステップ配信）</h4>
                            <p class="feature-item__text">
                                「3日後にお礼」「誕生日にクーポン」など、最適なタイミングでメールやLINEを自動送信。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h4 class="feature-item__title">リマインダー機能</h4>
                            <p class="feature-item__text">
                                予約前日に自動リマインドメールを送信。当日キャンセルを大幅に削減します。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="feature-item__title">売上レポート機能</h4>
                            <p class="feature-item__text">
                                月別・顧客別の売上を自動集計。データに基づいた経営判断が可能になります。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section package-detail__section--highlight">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-chart-line"></i>
                        費用対効果（ROI）
                    </h3>
                    <div class="roi-card">
                        <div class="roi-card__main">
                            <p class="roi-card__label">年間増益効果</p>
                            <p class="roi-card__value roi-card__value--large">約100万円</p>
                            <p class="roi-card__note">月3件の取りこぼし防止換算（客単価3万円の場合）</p>
                        </div>
                        <div class="roi-card__breakdown">
                            <h4 class="roi-card__breakdown-title">増益効果の内訳</h4>
                            <ul>
                                <li>
                                    <span class="roi-card__item-label">夜間・休日予約:</span>
                                    <span class="roi-card__item-value">月2件 × 3万円 = 6万円/月</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">無断キャンセル防止:</span>
                                    <span class="roi-card__item-value">月1件 × 3万円 = 3万円/月</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">リピート売上:</span>
                                    <span class="roi-card__item-value">月0.5件 × 3万円 = 1.5万円/月</span>
                                </li>
                            </ul>
                            <div class="roi-card__total">
                                <strong>月間増益:</strong> 約10.5万円<br>
                                <strong>年間増益:</strong> 約126万円<br>
                                <strong>8ヶ月で投資額を回収</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-gift"></i>
                        導入後に得られるメリット
                    </h3>
                    <ul class="package-detail__benefits">
                        <li>
                            <i class="fas fa-moon"></i>
                            <div>
                                <strong>機会損失ゼロ:</strong> 深夜や休日の問い合わせも逃さず受注。売上の取りこぼしが劇的に減ります。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone-slash"></i>
                            <div>
                                <strong>電話対応削減:</strong> 予約の電話対応が減り、本来の業務に集中できます。従業員のストレスも軽減。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-money-bill-wave"></i>
                            <div>
                                <strong>キャンセル率低下:</strong> 事前決済により、無断キャンセルが大幅に減少。安定した売上を確保できます。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-sync-alt"></i>
                            <div>
                                <strong>リピート率向上:</strong> 自動追客により、既存顧客との接点が増加。リピート率が向上します。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-chart-pie"></i>
                            <div>
                                <strong>データ分析:</strong> 顧客データが蓄積され、効果的なマーケティング施策が打てるようになります。
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="package-detail__cta">
                    <a href="contact.php" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        このパッケージについて相談する
                    </a>
                    <a href="system-development.php" class="btn btn-outline btn--large">
                        <i class="fas fa-arrow-left"></i>
                        パッケージ一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- パッケージ③：オーダーメイド開発 -->
    <section class="package-detail" id="pack-3">
        <div class="container">
            <div class="package-detail__header">
                <div class="package-detail__icon">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="package-detail__title-area">
                    <h2 class="package-detail__name">オーダーメイド開発</h2>
                    <p class="package-detail__tagline">御社だけの、最強のシステムを</p>
                </div>
                <div class="package-detail__price-area">
                    <p class="package-detail__price">
                        <span class="package-detail__amount">都度</span>
                        <span class="package-detail__unit">お見積り</span>
                    </p>
                    <p class="package-detail__period">ヒアリング後に提示</p>
                </div>
            </div>

            <div class="package-detail__content">
                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-lightbulb"></i>
                        こんな課題を解決します
                    </h3>
                    <ul class="package-detail__list">
                        <li>10年前に作った古いシステムが、いつ壊れるか不安</li>
                        <li>業界特有の複雑な業務フローがあり、既製品では対応できない</li>
                        <li>複数のシステムを使っており、データがバラバラで管理が大変</li>
                        <li>独自の基幹システムが欲しいが、どこから手をつければいいか分からない</li>
                    </ul>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-check-circle"></i>
                        対応可能な開発内容
                    </h3>
                    <div class="package-detail__features-grid">
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h4 class="feature-item__title">基幹システム構築</h4>
                            <p class="feature-item__text">
                                御社の業務フロー全体を管理する基幹システムを、ゼロから構築。受発注・在庫・顧客管理を一元化します。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h4 class="feature-item__title">老朽化システム改修</h4>
                            <p class="feature-item__text">
                                古いシステムを最新技術で書き換え。使い勝手はそのまま、中身を安全・高速に生まれ変わらせます。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-link"></i>
                            </div>
                            <h4 class="feature-item__title">システム統合</h4>
                            <p class="feature-item__text">
                                複数のシステムをAPIで連携。バラバラだったデータを統合し、一つの画面で管理できるようにします。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h4 class="feature-item__title">スマホアプリ開発</h4>
                            <p class="feature-item__text">
                                外出先からでも業務が可能なスマホアプリを開発。社長や営業マンの働き方が変わります。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h4 class="feature-item__title">セキュリティ強化</h4>
                            <p class="feature-item__text">
                                最新のセキュリティ対策を実装。情報漏洩のリスクを最小限に抑え、安心して使えるシステムに。
                            </p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-item__icon">
                                <i class="fas fa-cloud"></i>
                            </div>
                            <h4 class="feature-item__title">クラウド移行</h4>
                            <p class="feature-item__text">
                                オンプレミスからクラウドへ移行。サーバー管理の手間から解放され、どこからでもアクセス可能に。
                            </p>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section package-detail__section--highlight">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-chart-line"></i>
                        費用対効果（ROI）
                    </h3>
                    <div class="roi-card">
                        <div class="roi-card__main">
                            <p class="roi-card__label">経営リスクの排除</p>
                            <p class="roi-card__value roi-card__value--custom">数千万円規模の<br>機会損失防止</p>
                            <p class="roi-card__note">システム停止による業務停止リスクを回避</p>
                        </div>
                        <div class="roi-card__breakdown">
                            <h4 class="roi-card__breakdown-title">想定されるリスク</h4>
                            <ul>
                                <li>
                                    <span class="roi-card__item-label">システム停止:</span>
                                    <span class="roi-card__item-value">1日停止で数百万円の損失</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">情報漏洩:</span>
                                    <span class="roi-card__item-value">賠償金・信用失墜による損失</span>
                                </li>
                                <li>
                                    <span class="roi-card__item-label">非効率な業務:</span>
                                    <span class="roi-card__item-value">年間数百時間の無駄な作業</span>
                                </li>
                            </ul>
                            <div class="roi-card__total">
                                これらのリスクを事前に排除することで、<br>
                                <strong>安定した経営基盤を構築</strong>できます。
                            </div>
                        </div>
                    </div>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-gift"></i>
                        導入後に得られるメリット
                    </h3>
                    <ul class="package-detail__benefits">
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>安心感:</strong> 「いつ壊れるか」というストレスから解放され、安心して経営に集中できます。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-rocket"></i>
                            <div>
                                <strong>業務スピード向上:</strong> 御社専用に最適化されたシステムで、業務効率が飛躍的に向上します。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <strong>競争力強化:</strong> 他社にはない独自のシステムで、競合との差別化が可能になります。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-expand-arrows-alt"></i>
                            <div>
                                <strong>拡張性:</strong> 将来の事業拡大にも柔軟に対応できる、成長に合わせたシステム設計。
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-handshake"></i>
                            <div>
                                <strong>長期サポート:</strong> 納品後も継続的にサポート。困った時にすぐ相談できる安心感。
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="package-detail__section">
                    <h3 class="package-detail__section-title">
                        <i class="fas fa-route"></i>
                        開発の流れ
                    </h3>
                    <div class="development-flow">
                        <div class="flow-step">
                            <div class="flow-step__number">1</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">無料診断・ヒアリング</h4>
                                <p class="flow-step__text">現状のシステムや課題を詳しくお伺いします。御社の業務フローを理解し、最適な解決策を検討します。</p>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step__number">2</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">要件定義・お見積り</h4>
                                <p class="flow-step__text">必要な機能を明確にし、開発範囲を確定。工期と費用を明示したお見積書を提出します。</p>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step__number">3</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">設計・プロトタイプ作成</h4>
                                <p class="flow-step__text">画面設計やデータベース設計を行い、動作するプロトタイプをお見せします。イメージの齟齬を早期に解消。</p>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step__number">4</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">開発・テスト</h4>
                                <p class="flow-step__text">実際のシステムを開発。定期的に進捗報告を行い、御社の確認を得ながら進めます。</p>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step__number">5</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">納品・操作レクチャー</h4>
                                <p class="flow-step__text">システムを納品し、現場の方へ使い方をレクチャー。スムーズに運用開始できるようサポートします。</p>
                            </div>
                        </div>
                        <div class="flow-step">
                            <div class="flow-step__number">6</div>
                            <div class="flow-step__content">
                                <h4 class="flow-step__title">運用サポート</h4>
                                <p class="flow-step__text">納品後1ヶ月間は無償で修正対応。その後も保守契約で継続的にサポートいたします。</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="package-detail__cta">
                    <a href="contact.php" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        このパッケージについて相談する
                    </a>
                    <a href="system-development.php" class="btn btn-outline btn--large">
                        <i class="fas fa-arrow-left"></i>
                        パッケージ一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- よくある質問 -->
    <section class="packages-faq">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">よくある質問</span>
            </h2>

            <div class="faq-list">
                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        パッケージに含まれない追加費用はありますか？
                    </h3>
                    <div class="faq-item__answer">
                        <p>お見積りに含まれていない仕様変更や追加機能の開発が発生した場合のみ、追加費用が発生します。ただし、事前に必ずお見積りをご提示し、ご承認いただいてから着手いたしますのでご安心ください。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        納品後のサポートはありますか？
                    </h3>
                    <div class="faq-item__answer">
                        <p>はい、納品後1ヶ月間は無償で不具合修正を行います。それ以降は、月額保守契約（5万円〜）をご用意しております。システムの監視、セキュリティアップデート、軽微な修正などが含まれます。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        途中で仕様変更はできますか？
                    </h3>
                    <div class="faq-item__answer">
                        <p>可能ですが、工数が増える場合は追加費用が発生します。大幅な仕様変更の場合は、再度お見積りをご提示いたします。仕様変更を最小限に抑えるため、要件定義と設計段階で時間をかけて擦り合わせを行います。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        他社で作ったシステムの改修もお願いできますか？
                    </h3>
                    <div class="faq-item__answer">
                        <p>はい、可能です。まずは現状のシステムを診断させていただき、改修可能かどうかを判断いたします。場合によっては、新規で作り直した方が費用対効果が高いこともありますので、最適なご提案をさせていただきます。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        パッケージの内容をカスタマイズできますか？
                    </h3>
                    <div class="faq-item__answer">
                        <p>はい、もちろん可能です。パッケージはあくまで目安であり、御社の課題に合わせて柔軟にカスタマイズいたします。不要な機能を削除したり、必要な機能を追加したりすることで、最適なシステムをご提案いたします。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <h3 class="faq-item__question">
                        <i class="fas fa-question-circle"></i>
                        支払い方法を教えてください
                    </h3>
                    <div class="faq-item__answer">
                        <p>前入金制となります。小規模案件（100万円未満）は全額前入金、中規模案件（100-300万円）は50%前入金・50%納品時、大規模案件（300万円以上）は30%前入金・40%中間・30%納品時となります。銀行振込でお願いしております。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="packages-final-cta">
        <div class="container">
            <div class="packages-final-cta__content">
                <h2 class="packages-final-cta__title">まずは無料診断から</h2>
                <p class="packages-final-cta__text">
                    「うちの会社に、どのパッケージが合うか分からない」<br>
                    そんな方こそ、まずは無料診断をご利用ください。<br>
                    御社の課題を整理し、最適なプランをご提案いたします。
                </p>
                <div class="packages-final-cta__buttons">
                    <a href="contact.php" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        無料診断を申し込む
                    </a>
                    <a href="tel:<?php echo str_replace('-', '', CONTACT_TEL); ?>" class="btn btn-outline btn--large">
                        <i class="fas fa-phone"></i>
                        <?php echo CONTACT_TEL; ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <script defer src="assets/js/app.js"></script>

</body>
</html>
