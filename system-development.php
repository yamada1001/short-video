<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'システム開発｜忙しいのに利益が残らないをITで終わらせる｜余日（Yojitsu）';
$page_description = '大分県の経営者様へ。24時間365日働く「デジタル社員」で業務効率化を実現。手入力の自動化、属人化の解消、売上アップなど8つのIT処方箋をご提案。事務ゼロ化パック30万円〜、無料診断実施中。';
$page_keywords = 'システム開発,業務効率化,IT化,自動化,大分県,DX,デジタル化,余日';
$additional_css = ['assets/css/pages/system-development.css', 'assets/css/cookie-consent.css'];
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

    <!-- MVセクション -->
    <section class="system-mv">
        <div class="container">
            <div class="system-mv__content">
                <p class="system-mv__label">大分県の経営者様へ</p>
                <h1 class="system-mv__title">
                    「忙しいのに利益が残らない」を<br>
                    ITで終わらせる。
                </h1>
                <p class="system-mv__subtitle">
                    24時間365日、文句を言わず働く<br class="sp-only">「デジタル社員」採用のご提案
                </p>
                <div class="system-mv__cta">
                    <a href="#contact" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        <span>無料診断を申し込む</span>
                    </a>
                    <a href="#packages" class="btn btn-outline btn--large">
                        <i class="fas fa-box"></i>
                        <span>料金プランを見る</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 問題提起セクション -->
    <section class="system-problems">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">こんな「見ないふり」をしていませんか？</span>
            </h2>
            <div class="problems-grid">
                <div class="problem-card">
                    <div class="problem-card__icon">
                        <i class="fas fa-copy"></i>
                    </div>
                    <p class="problem-card__text">
                        事務員が一日中、パソコンで<br>「コピペ」をしているのを知っている。
                    </p>
                </div>
                <div class="problem-card">
                    <div class="problem-card__icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <p class="problem-card__text">
                        「あの担当者が辞めたら<br>業務が回らない」という恐怖がある。
                    </p>
                </div>
                <div class="problem-card">
                    <div class="problem-card__icon">
                        <i class="fas fa-moon"></i>
                    </div>
                    <p class="problem-card__text">
                        夜中や休日の問い合わせを<br>取りこぼしている気がする。
                    </p>
                </div>
                <div class="problem-card">
                    <div class="problem-card__icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <p class="problem-card__text">
                        実は、今月の利益がいくらか<br>把握できていない。
                    </p>
                </div>
            </div>
            <div class="problems-message">
                <p>私たちは、難しい専門用語（PHPやPythonなど）は一切使いません。</p>
                <p>ご提案するのは、<strong>御社の「時間」を増やし、「利益」を守るための具体的な『仕組み』</strong>です。</p>
            </div>
        </div>
    </section>

    <!-- 8つのIT処方箋セクション -->
    <section class="system-solutions">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">経営の詰まりを取り除く「8つのIT処方箋」</span>
                <span class="section-title__sub">御社の悩みに当てはまる番号はどれでしょうか？ それが、私たちが解決できることです。</span>
            </h2>

            <!-- 処方箋①：脱・手入力 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">01</span>
                    <h3 class="solution-item__title">【脱・手入力】<br>「その給料、『コピペ』に払うんですか？」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>事務員が一日中、エクセルへの転記作業をしている。その時間を、より価値のある業務に使えます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-inbox"></i>
                                <div>
                                    <strong>自動取り込み:</strong> メールやWebサイトからの注文情報を、自動で読み取るロボットを設置。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-table"></i>
                                <div>
                                    <strong>台帳連携:</strong> 読み取ったデータを、現在お使いのスプレッドシートへズレなく自動記入。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <div>
                                    <strong>完了通知:</strong> 「処理しました」という完了メールを、担当者へ自動送信。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋②：脱・属人化 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">02</span>
                    <h3 class="solution-item__title">【脱・属人化】<br>「明日、その担当者が辞めたらパニックになりませんか？」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>業務の手順や顧客情報が「特定個人の頭の中」にしかない状態。誰でも対応できる体制を作ることができます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-cloud"></i>
                                <div>
                                    <strong>クラウド共有化:</strong> 個人PCにしかないデータを、安全なクラウド（ネット上の保管庫）へ移行。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-edit"></i>
                                <div>
                                    <strong>入力統一:</strong> 誰が入力しても同じ形式になる「カンタン入力フォーム」を作成。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-mobile-alt"></i>
                                <div>
                                    <strong>共有画面:</strong> スマホ一つで、全社員がリアルタイムに進捗を確認できる画面を構築。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋③：売上アップ -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">03</span>
                    <h3 class="solution-item__title">【売上アップ】<br>「御社が寝ている間、ライバルはネットで受注しています」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>電話でしか予約できない不便さで、機会を逃している可能性があります。24時間いつでも予約できる仕組みを作れます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <strong>Web予約カレンダー:</strong> 24時間、空き状況がリアルタイムで分かる画面をHPに設置。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-credit-card"></i>
                                <div>
                                    <strong>事前決済導入:</strong> 予約と同時に支払いを完了させ、無断キャンセルを防止。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-bell"></i>
                                <div>
                                    <strong>自動追客:</strong> 予約完了メールや、前日のリマインドメールを自動送信。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋④：経営判断 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">04</span>
                    <h3 class="solution-item__title">【経営判断】<br>「『たぶん黒字』での経営は、目隠し運転と同じです」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>月末に税理士から試算表が来るまで、成績がわからない状態。リアルタイムで数字を把握できる仕組みを作れます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-chart-line"></i>
                                <div>
                                    <strong>データ集約:</strong> バラバラな売上・経費・勤怠データを一箇所に集めるプログラム開発。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-tachometer-alt"></i>
                                <div>
                                    <strong>社長専用ダッシュボード:</strong> 「今月の粗利」「着地見込み」が一目でわかる社長専用画面を作成。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <strong>アラート設定:</strong> 赤字ラインや予算オーバーの兆候をLINEで警告。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋⑤：ミス撲滅 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">05</span>
                    <h3 class="solution-item__title">【ミス撲滅】<br>「謝罪に行くガソリン代と時間、あと何回払いますか？」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>入力ミスによるトラブルが度々発生している状態。ミスを防ぐ仕組みを作ることができます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <strong>入力ブロッカー:</strong> 必須項目漏れやあり得ない数字（桁間違い）を自動で弾く設定。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-check-double"></i>
                                <div>
                                    <strong>自動チェック:</strong> 発注金額と請求金額のズレをシステムが自動照合。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-redo"></i>
                                <div>
                                    <strong>二重防止:</strong> 重複入力を検知し「登録済みです」と警告する機能。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋⑥：リスク回避 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">06</span>
                    <h3 class="solution-item__title">【リスク回避】<br>「そのシステム、いつ爆発するか分からない『時限爆弾』ですよ」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>古いシステムで、いつ止まるか不安な状態。安全に長く使えるシステムに更新できます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-search"></i>
                                <div>
                                    <strong>現状診断:</strong> 古いシステムを解析し、「どこが危険か」を特定するレポート作成。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-tools"></i>
                                <div>
                                    <strong>エンジンの載せ替え:</strong> 使い勝手はそのまま、中身を最新言語（PHP/Python等）に書き換え。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-lock"></i>
                                <div>
                                    <strong>セキュリティ強化:</strong> 最新のウイルス対策と自動バックアップ機能を付与。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋⑦：リピート増 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">07</span>
                    <h3 class="solution-item__title">【リピート増】<br>「釣った魚に餌をやらない会社だと、思われていませんか？」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>一度購入してくれたお客様への継続的なフォローができていない状態。リピートを促す仕組みを作れます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-folder-open"></i>
                                <div>
                                    <strong>顧客カルテ整理:</strong> 「最終来店日」「購入履歴」で顧客を自動ランク分け。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-paper-plane"></i>
                                <div>
                                    <strong>ステップ配信:</strong> 「3日後にお礼」「誕生日にクーポン」など、最適なタイミングでメールやLINEを自動送信。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 処方箋⑧：働き方改革 -->
            <div class="solution-item">
                <div class="solution-item__header">
                    <span class="solution-item__number">08</span>
                    <h3 class="solution-item__title">【働き方改革】<br>「社長、あなたは会社の『警備員』ですか？」</h3>
                </div>
                <div class="solution-item__content">
                    <div class="solution-item__insight">
                        <p>承認のためだけに会社に戻る必要がある状態。どこからでも業務を進められる仕組みを作れます。</p>
                    </div>
                    <div class="solution-item__actions">
                        <p class="solution-item__label">
                            <i class="fas fa-wrench"></i>
                            私たちがやること（例）
                        </p>
                        <ul class="solution-item__list">
                            <li>
                                <i class="fas fa-globe"></i>
                                <div>
                                    <strong>リモートアクセス:</strong> 自宅や出張先から、会社のPCを安全に操作できる環境構築。
                                </div>
                            </li>
                            <li>
                                <i class="fas fa-file-signature"></i>
                                <div>
                                    <strong>ペーパーレス承認:</strong> スマホ上で「承認ボタン」を押すだけのフローに変更。
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 料金プランセクション -->
    <section class="system-packages" id="packages">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">わかりやすい料金・導入イメージ</span>
                <span class="section-title__sub">時給ではなく、「成果（=課題解決）」に対してのパッケージ価格です。</span>
            </h2>

            <div class="packages-grid">
                <!-- 事務ゼロ化パック -->
                <div class="package-card">
                    <div class="package-card__header">
                        <div class="package-card__icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3 class="package-card__name">事務ゼロ化パック</h3>
                    </div>
                    <div class="package-card__price">
                        <span class="package-card__amount">30万円</span>
                        <span class="package-card__unit">〜</span>
                    </div>
                    <p class="package-card__period">工期: 2週間〜</p>
                    <div class="package-card__features">
                        <p class="package-card__label">解決できること</p>
                        <ul>
                            <li><i class="fas fa-check"></i> 転記作業の自動化</li>
                            <li><i class="fas fa-check"></i> メール自動送信</li>
                        </ul>
                    </div>
                    <div class="package-card__roi">
                        <p class="package-card__label">費用対効果（ROI）</p>
                        <p class="package-card__roi-text">
                            <i class="fas fa-chart-line"></i>
                            約10ヶ月で元が取れます<br>
                            <span class="package-card__roi-note">（月40時間の削減換算）</span>
                        </p>
                        <p class="package-card__insight">
                            💡 毎日1時間強のコピペ作業がゼロになります
                        </p>
                    </div>
                    <a href="system-development-packages.php#pack-1" class="btn btn-outline btn--block">
                        詳しく見る
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- 売上最大化パック -->
                <div class="package-card package-card--recommended">
                    <div class="package-card__badge">おすすめ</div>
                    <div class="package-card__header">
                        <div class="package-card__icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="package-card__name">売上最大化パック</h3>
                    </div>
                    <div class="package-card__price">
                        <span class="package-card__amount">60万円</span>
                        <span class="package-card__unit">〜</span>
                    </div>
                    <p class="package-card__period">工期: 2ヶ月〜</p>
                    <div class="package-card__features">
                        <p class="package-card__label">解決できること</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Web予約・決済導入</li>
                            <li><i class="fas fa-check"></i> 顧客管理・自動追客</li>
                        </ul>
                    </div>
                    <div class="package-card__roi">
                        <p class="package-card__label">費用対効果（ROI）</p>
                        <p class="package-card__roi-text">
                            <i class="fas fa-chart-line"></i>
                            月3件の取りこぼし防止で<br>
                            <strong>年間100万円増益</strong>
                        </p>
                    </div>
                    <a href="system-development-packages.php#pack-2" class="btn btn-primary btn--block">
                        詳しく見る
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- オーダーメイド開発 -->
                <div class="package-card">
                    <div class="package-card__header">
                        <div class="package-card__icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3 class="package-card__name">オーダーメイド開発</h3>
                    </div>
                    <div class="package-card__price">
                        <span class="package-card__amount">都度</span>
                        <span class="package-card__unit">お見積り</span>
                    </div>
                    <p class="package-card__period">ヒアリング後に提示</p>
                    <div class="package-card__features">
                        <p class="package-card__label">解決できること</p>
                        <ul>
                            <li><i class="fas fa-check"></i> 独自の基幹システム</li>
                            <li><i class="fas fa-check"></i> 老朽化システムの改修</li>
                            <li><i class="fas fa-check"></i> 経営リスクの排除</li>
                        </ul>
                    </div>
                    <div class="package-card__roi">
                        <p class="package-card__label">費用対効果（ROI）</p>
                        <p class="package-card__roi-text">
                            <i class="fas fa-chart-line"></i>
                            数千万円規模の<br>
                            機会損失防止
                        </p>
                    </div>
                    <a href="system-development-packages.php#pack-3" class="btn btn-outline btn--block">
                        詳しく見る
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <p class="packages-note">
                <i class="fas fa-info-circle"></i>
                上記は目安です。ヒアリングの上、着手前に「確定金額」をご提示します。
            </p>
        </div>
    </section>

    <!-- パッケージ比較表セクション -->
    <section class="package-comparison">
        <div class="container">
            <h2 class="section-title center">
                <i class="fas fa-table"></i>
                パッケージ比較表
            </h2>
            <p class="section-lead center">
                3つのパッケージを比較して、御社に最適なプランをお選びください。
            </p>

            <div class="comparison-table-wrapper">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="comparison-table__header">項目</th>
                            <th class="comparison-table__header comparison-table__header--highlight">
                                事務ゼロ化パック
                            </th>
                            <th class="comparison-table__header">
                                売上最大化パック
                            </th>
                            <th class="comparison-table__header">
                                オーダーメイド開発
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="comparison-table__label">価格</td>
                            <td class="comparison-table__cell comparison-table__cell--highlight">
                                <strong>30万円〜</strong>
                            </td>
                            <td class="comparison-table__cell">
                                <strong>60万円〜</strong>
                            </td>
                            <td class="comparison-table__cell">
                                <strong>100万円〜</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__label">開発期間</td>
                            <td class="comparison-table__cell comparison-table__cell--highlight">
                                1〜2ヶ月
                            </td>
                            <td class="comparison-table__cell">
                                2〜3ヶ月
                            </td>
                            <td class="comparison-table__cell">
                                3〜6ヶ月
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__label">向いている会社</td>
                            <td class="comparison-table__cell comparison-table__cell--highlight">
                                手作業が多い<br>事務負担を減らしたい
                            </td>
                            <td class="comparison-table__cell">
                                新規顧客を増やしたい<br>売上を伸ばしたい
                            </td>
                            <td class="comparison-table__cell">
                                独自の業務フロー<br>既存システムを刷新
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__label">主な機能（例）</td>
                            <td class="comparison-table__cell comparison-table__cell--highlight">
                                ・自動データ取り込み<br>
                                ・帳票自動生成<br>
                                ・メール自動送信
                            </td>
                            <td class="comparison-table__cell">
                                ・予約システム<br>
                                ・ECサイト<br>
                                ・顧客管理
                            </td>
                            <td class="comparison-table__cell">
                                ・完全カスタム<br>
                                ・既存連携<br>
                                ・独自ルール対応
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__label">投資対効果</td>
                            <td class="comparison-table__cell comparison-table__cell--highlight">
                                10ヶ月で回収
                            </td>
                            <td class="comparison-table__cell">
                                初年度+40万円
                            </td>
                            <td class="comparison-table__cell">
                                リスク回避・効率化
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="package-comparison__cta">
                <p>どのパッケージが合うか迷ったら、まずは無料診断をご利用ください。</p>
                <a href="#contact" class="btn btn-primary btn--large">
                    <i class="fas fa-comments"></i>
                    無料診断を受ける
                </a>
            </div>
        </div>
    </section>

    <!-- 導入事例セクション -->
    <section class="case-studies">
        <div class="container">
            <h2 class="section-title center">
                <i class="fas fa-briefcase"></i>
                導入事例（例）
            </h2>
            <p class="section-lead center">
                実際に導入された企業様の課題と効果をご紹介します。
            </p>

            <div class="case-studies__grid">
                <!-- 事例1: 事務ゼロ化パック -->
                <div class="case-study-card">
                    <div class="case-study-card__label">事務ゼロ化パック</div>
                    <div class="case-study-card__company">
                        <i class="fas fa-building"></i>
                        <div>
                            <h3>大分県の建設会社様</h3>
                            <p class="case-study-card__industry">従業員15名 / 建設業</p>
                        </div>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                        <p>見積書・請求書の作成に毎日2時間かかっており、事務員の負担が大きかった。手書き→エクセル転記→PDF化という二度手間も発生。</p>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                        <ul>
                            <li>見積書・請求書作成が<strong>自動化</strong>され、月60時間削減</li>
                            <li>事務員が営業サポートに集中できるようになった</li>
                            <li>ミスがなくなり、顧客からの信頼も向上</li>
                        </ul>
                    </div>
                    <div class="case-study-card__result">
                        <strong>投資回収期間: 8ヶ月</strong>
                    </div>
                </div>

                <!-- 事例2: 売上最大化パック -->
                <div class="case-study-card">
                    <div class="case-study-card__label case-study-card__label--sales">売上最大化パック</div>
                    <div class="case-study-card__company">
                        <i class="fas fa-store"></i>
                        <div>
                            <h3>福岡県の美容院様</h3>
                            <p class="case-study-card__industry">スタッフ8名 / 美容業</p>
                        </div>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                        <p>電話予約のみで、営業時間外の予約を逃していた。新規顧客のリピート率も低く、売上が伸び悩んでいた。</p>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                        <ul>
                            <li>24時間Web予約で<strong>月15件の新規予約</strong>を獲得</li>
                            <li>LINE連携で来店3日前にリマインド → キャンセル率30%減</li>
                            <li>顧客管理で誕生日クーポン配信 → リピート率20%向上</li>
                        </ul>
                    </div>
                    <div class="case-study-card__result">
                        <strong>年間売上: +150万円</strong>
                    </div>
                </div>

                <!-- 事例3: オーダーメイド開発 -->
                <div class="case-study-card">
                    <div class="case-study-card__label case-study-card__label--custom">オーダーメイド開発</div>
                    <div class="case-study-card__company">
                        <i class="fas fa-industry"></i>
                        <div>
                            <h3>熊本県の製造業様</h3>
                            <p class="case-study-card__industry">従業員50名 / 製造業</p>
                        </div>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                        <p>20年前の生産管理システムが老朽化し、サポート終了のリスクがあった。新しいパッケージソフトでは自社の複雑な工程に対応できない。</p>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                        <ul>
                            <li>独自の工程管理ルールに完全対応した<strong>専用システム</strong>を構築</li>
                            <li>在庫管理の精度が向上し、過剰在庫を20%削減</li>
                            <li>リアルタイムで生産状況を把握でき、納期遵守率が向上</li>
                        </ul>
                    </div>
                    <div class="case-study-card__result">
                        <strong>システムリスクの完全排除</strong>
                    </div>
                </div>

                <!-- 事例4: 事務ゼロ化パック -->
                <div class="case-study-card">
                    <div class="case-study-card__label">事務ゼロ化パック</div>
                    <div class="case-study-card__company">
                        <i class="fas fa-hospital"></i>
                        <div>
                            <h3>宮崎県の整骨院様</h3>
                            <p class="case-study-card__industry">スタッフ5名 / 医療・福祉</p>
                        </div>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                        <p>保険請求の集計作業に毎月丸2日かかっており、その間は施術ができない状態。計算ミスも頻発していた。</p>
                    </div>
                    <div class="case-study-card__section">
                        <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                        <ul>
                            <li>保険請求の<strong>自動集計</strong>で作業時間を2日→2時間に短縮</li>
                            <li>計算ミスがゼロになり、返戻率が大幅に減少</li>
                            <li>空いた時間で施術に専念でき、患者様の満足度も向上</li>
                        </ul>
                    </div>
                    <div class="case-study-card__result">
                        <strong>投資回収期間: 6ヶ月</strong>
                    </div>
                </div>
            </div>

            <div class="case-studies__note">
                <i class="fas fa-info-circle"></i>
                <p>※ 上記は一例です。業種や規模、課題によって最適なプランは異なります。まずは無料診断でご相談ください。</p>
            </div>
        </div>
    </section>

    <!-- 導入までのロードマップ -->
    <section class="system-roadmap">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">導入までのロードマップ</span>
                <span class="section-title__sub">丸投げでOKです</span>
            </h2>

            <div class="roadmap-timeline">
                <div class="roadmap-step">
                    <div class="roadmap-step__number">1</div>
                    <div class="roadmap-step__content">
                        <h3 class="roadmap-step__title">
                            <i class="fas fa-comments"></i>
                            無料診断（30分）
                        </h3>
                        <p class="roadmap-step__text">
                            業務の「めんどくさい」を全て吐き出してください。
                        </p>
                    </div>
                </div>

                <div class="roadmap-step">
                    <div class="roadmap-step__number">2</div>
                    <div class="roadmap-step__content">
                        <h3 class="roadmap-step__title">
                            <i class="fas fa-code"></i>
                            開発&テスト
                        </h3>
                        <p class="roadmap-step__text">
                            私たちが作ります。その間、社長は本業に集中してください。
                        </p>
                    </div>
                </div>

                <div class="roadmap-step">
                    <div class="roadmap-step__number">3</div>
                    <div class="roadmap-step__content">
                        <h3 class="roadmap-step__title">
                            <i class="fas fa-chalkboard-teacher"></i>
                            操作レクチャー
                        </h3>
                        <p class="roadmap-step__text">
                            現場の方へ、使い方の説明会を行います。
                        </p>
                    </div>
                </div>

                <div class="roadmap-step">
                    <div class="roadmap-step__number">4</div>
                    <div class="roadmap-step__content">
                        <h3 class="roadmap-step__title">
                            <i class="fas fa-rocket"></i>
                            運用開始
                        </h3>
                        <p class="roadmap-step__text">
                            実際に使い始めます。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3つの約束 -->
    <section class="system-promises">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">大分の経営者様への「3つの約束」</span>
                <span class="section-title__sub">私たちは「作って終わり」のIT業者ではありません。</span>
            </h2>

            <div class="promises-grid">
                <div class="promise-card">
                    <div class="promise-card__icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="promise-card__title">修正保証</h3>
                    <p class="promise-card__text">
                        納品後1ヶ月以内の「ちょっと使いにくい」は無償で直します。
                    </p>
                </div>

                <div class="promise-card">
                    <div class="promise-card__icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h3 class="promise-card__title">駆けつけ保証</h3>
                    <p class="promise-card__text">
                        トラブル時、Zoomで解決できなければ大分県内なら最短当日に駆けつけます。
                    </p>
                </div>

                <div class="promise-card">
                    <div class="promise-card__icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3 class="promise-card__title">脱・専門用語</h3>
                    <p class="promise-card__text">
                        難しい言葉は禁止。「経営の言葉」だけでお話しします。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 比較表 -->
    <section class="system-comparison">
        <div class="container">
            <h2 class="section-title">
                <span class="section-title__main">よくあるIT会社との違い</span>
            </h2>

            <div class="comparison-table-wrapper">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="comparison-table__header">よくあるIT会社</th>
                            <th class="comparison-table__header comparison-table__header--ours">
                                <i class="fas fa-star"></i>
                                私たち（余日）
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="comparison-table__cell">
                                <i class="fas fa-times-circle"></i>
                                「仕様書通りに作りました」と突き放す
                            </td>
                            <td class="comparison-table__cell comparison-table__cell--ours">
                                <i class="fas fa-check-circle"></i>
                                「現場が使いやすいか」にとことんこだわる
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__cell">
                                <i class="fas fa-times-circle"></i>
                                専門用語だらけで話が通じない
                            </td>
                            <td class="comparison-table__cell comparison-table__cell--ours">
                                <i class="fas fa-check-circle"></i>
                                専門用語禁止。わかりやすく説明します
                            </td>
                        </tr>
                        <tr>
                            <td class="comparison-table__cell">
                                <i class="fas fa-times-circle"></i>
                                納品したら連絡が取れなくなる
                            </td>
                            <td class="comparison-table__cell comparison-table__cell--ours">
                                <i class="fas fa-check-circle"></i>
                                納品してからが本当のお付き合い
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <section class="system-cta" id="contact">
        <div class="container">
            <div class="system-cta__content">
                <div class="system-cta__icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h2 class="system-cta__title">まずは「無料診断」から</h2>
                <p class="system-cta__text">
                    「うちの会社の場合、何から手をつければいい？」<br>
                    まずはそこからご相談ください。
                </p>
                <p class="system-cta__limit">
                    <i class="fas fa-users"></i>
                    毎月3社限定：業務のムダ発見・無料診断（30分）実施中
                </p>
                <div class="system-cta__buttons">
                    <a href="contact.php" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i>
                        <span>お問い合わせはこちら</span>
                    </a>
                    <a href="tel:<?php echo str_replace('-', '', CONTACT_TEL); ?>" class="btn btn-outline btn--large">
                        <i class="fas fa-phone"></i>
                        <span><?php echo CONTACT_TEL; ?></span>
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
