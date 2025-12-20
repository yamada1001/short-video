<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>くるま買取ケイヴィレッジ様 サイト制作のご提案 | 余日</title>
    <meta name="description" content="車買取サイト制作のご提案。広告費の無駄を減らし、問い合わせを増やすサイトを作ります。">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header__content">
                <div class="header__logo">
                    <span class="logo">YOJITU.COM</span>
                </div>
                <nav class="header__nav">
                    <a href="#proposal" class="header__link">提案内容</a>
                    <a href="#plans" class="header__link">料金プラン</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero / Cover Page -->
    <section class="hero">
        <div class="container">
            <div class="hero__content">
                <p class="hero__label">くるま買取ケイヴィレッジ 木村様</p>
                <h1 class="hero__title">
                    ホームページ制作<br>
                    <span class="hero__title--accent">ご提案書</span>
                </h1>

                <!-- 価格の段階的提示 -->
                <div class="price-stages">
                    <div class="price-stage price-stage--crossed">
                        <span class="price-stage__label">通常価格</span>
                        <div class="price-stage__amount">
                            <span class="price-stage__number">143</span>
                            <span class="price-stage__unit">万円</span>
                        </div>
                    </div>

                    <div class="price-stage__arrow">↓</div>

                    <div class="price-stage price-stage--crossed">
                        <span class="price-stage__label">特別価格</span>
                        <div class="price-stage__amount">
                            <span class="price-stage__number">88</span>
                            <span class="price-stage__unit">万円</span>
                        </div>
                    </div>

                    <div class="price-stage__arrow">↓</div>

                    <div class="price-stage price-stage--final">
                        <span class="price-stage__label">本日限定価格</span>
                        <div class="price-stage__amount">
                            <span class="price-stage__number">55</span>
                            <span class="price-stage__unit">万円</span>
                        </div>
                        <div class="price-stage__note">（スタンダードプラン）</div>
                    </div>
                </div>

                <p class="hero__date">
                    提案日: <?php echo date('Y年m月d日'); ?>
                </p>
                <p class="hero__company">
                    余日（Yojitsu）<br>
                    代表: 山田 蓮
                </p>
            </div>
        </div>
    </section>

    <!-- 提案の背景 / Current Issues -->
    <section class="section background">
        <div class="container">
            <h2 class="section__title">
                <i class="fa-solid fa-lightbulb"></i> 提案の背景
            </h2>
            <p class="section__lead">
                現在、以下のような課題を抱えておられるかと思います
            </p>

            <div class="issues__grid">
                <div class="issue-card">
                    <div class="issue-card__number">01</div>
                    <h3 class="issue-card__title">
                        <i class="fa-solid fa-chart-line"></i> 広告効果が見えない
                    </h3>
                    <p class="issue-card__text">
                        YouTube広告やチラシを出しているが、どの広告から問い合わせが来たのか分からない。効果的な広告に予算を集中できていない。
                    </p>
                </div>

                <div class="issue-card">
                    <div class="issue-card__number">02</div>
                    <h3 class="issue-card__title">
                        <i class="fa-solid fa-envelope-open-text"></i> 問い合わせ管理が煩雑
                    </h3>
                    <p class="issue-card__text">
                        メールで問い合わせを受けているが、過去のデータを見返すのが大変。どのお客様がどんな車を探していたか、すぐに確認できない。
                    </p>
                </div>

                <div class="issue-card">
                    <div class="issue-card__number">03</div>
                    <h3 class="issue-card__title">
                        <i class="fa-solid fa-car"></i> 在庫車両の更新が手間
                    </h3>
                    <p class="issue-card__text">
                        車が売れたら業者に連絡して削除依頼、新しい車が入ったら写真を送って掲載依頼。この作業に時間とコストがかかっている。
                    </p>
                </div>

                <div class="issue-card">
                    <div class="issue-card__number">04</div>
                    <h3 class="issue-card__title">
                        <i class="fa-solid fa-database"></i> データが蓄積されない
                    </h3>
                    <p class="issue-card__text">
                        問い合わせデータが散らばっていて、分析できない。どのエリアからの問い合わせが多いか、どんな車種が人気かが分からない。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 提案内容 / Proposal Details -->
    <section class="section proposal-details" id="proposal">
        <div class="container">
            <h2 class="section__title">
                <i class="fa-solid fa-bullseye"></i> 提案内容
            </h2>
            <p class="section__lead">
                上記の課題を解決するため、以下の機能を実装したホームページを制作いたします
            </p>

            <div class="features__list">
                <div class="feature-item">
                    <div class="feature-item__header">
                        <div class="feature-item__icon">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <h3 class="feature-item__title">ホームページ作成</h3>
                    </div>
                    <p class="feature-item__text">
                        スマートフォン・タブレット・PCに対応したレスポンシブデザインで、どのデバイスからでも快適に閲覧できます。
                    </p>
                </div>

                <div class="feature-item">
                    <div class="feature-item__header">
                        <div class="feature-item__icon">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <h3 class="feature-item__title">問い合わせフォーム × 4種類</h3>
                    </div>
                    <p class="feature-item__text">
                        <strong>買取</strong>・<strong>販売</strong>・<strong>リース</strong>・<strong>相談</strong> の4つの問い合わせフォームを設置。お客様の目的に合わせたフォームで、問い合わせのハードルを下げます。
                    </p>
                </div>

                <div class="feature-item">
                    <div class="feature-item__header">
                        <div class="feature-item__icon">
                            <i class="fa-solid fa-reply"></i>
                        </div>
                        <h3 class="feature-item__title">自動返信メール</h3>
                    </div>
                    <p class="feature-item__text">
                        問い合わせがあった際、お客様に自動で確認メールを送信。「ちゃんと届いているか不安」という不安を解消します。
                    </p>
                </div>

                <div class="feature-item">
                    <div class="feature-item__header">
                        <div class="feature-item__icon">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <h3 class="feature-item__title">車両管理システム（スタンダードプランのみ）</h3>
                    </div>
                    <p class="feature-item__text">
                        在庫車両の追加・変更・削除を、<strong>管理画面から自分で行えます</strong>。業者に依頼する手間とコストが削減できます。
                    </p>
                    <ul class="feature-item__sub-list">
                        <li><i class="fa-solid fa-check-circle"></i> 車両情報（車種、年式、走行距離、価格など）を入力</li>
                        <li><i class="fa-solid fa-check-circle"></i> 写真を最大10枚まで登録可能</li>
                        <li><i class="fa-solid fa-check-circle"></i> 売却済みの車はワンクリックで非表示</li>
                    </ul>
                </div>

                <div class="feature-item">
                    <div class="feature-item__header">
                        <div class="feature-item__icon">
                            <i class="fa-solid fa-table"></i>
                        </div>
                        <h3 class="feature-item__title">問い合わせ管理機能（スタンダードプランのみ）</h3>
                    </div>
                    <p class="feature-item__text">
                        過去の問い合わせを一覧で確認でき、<strong>Excelでダウンロード</strong>も可能。顧客データとして活用できます。
                    </p>
                    <ul class="feature-item__sub-list">
                        <li><i class="fa-solid fa-check-circle"></i> 問い合わせ日時、お名前、連絡先、内容を一覧表示</li>
                        <li><i class="fa-solid fa-check-circle"></i> 問い合わせ種別（買取/販売/リース/相談）でフィルタリング</li>
                        <li><i class="fa-solid fa-check-circle"></i> 月別の問い合わせ件数をグラフで表示</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- 料金詳細 / Pricing Details -->
    <section class="section pricing-details" id="plans">
        <div class="container">
            <h2 class="section__title">
                <i class="fa-solid fa-yen-sign"></i> 料金詳細
            </h2>

            <!-- 料金プラン比較表 -->
            <div class="pricing-table">
                <table>
                    <thead>
                        <tr>
                            <th>機能</th>
                            <th>ライトプラン<br><span class="price-highlight">30万円</span></th>
                            <th class="recommended-col">スタンダードプラン<br><span class="price-highlight-lg">55万円</span><br><span class="recommended-badge">おすすめ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fa-solid fa-laptop-code"></i> ホームページ作成</td>
                            <td><i class="fa-solid fa-circle-check text-success"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-clipboard-list"></i> 問い合わせフォーム × 4種類</td>
                            <td><i class="fa-solid fa-circle-check text-success"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-reply"></i> 自動返信メール</td>
                            <td><i class="fa-solid fa-circle-check text-success"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-mobile-screen"></i> スマホ・タブレット対応</td>
                            <td><i class="fa-solid fa-circle-check text-success"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr class="feature-divider">
                            <td colspan="3"><strong>管理機能</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-car-side"></i> 車両管理システム（自分で追加・変更・削除）</td>
                            <td><i class="fa-solid fa-circle-xmark text-muted"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-table"></i> 問い合わせ一覧・管理</td>
                            <td><i class="fa-solid fa-circle-xmark text-muted"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-file-excel"></i> Excelダウンロード</td>
                            <td><i class="fa-solid fa-circle-xmark text-muted"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-chart-line"></i> 月別問い合わせグラフ</td>
                            <td><i class="fa-solid fa-circle-xmark text-muted"></i></td>
                            <td class="recommended-col"><i class="fa-solid fa-circle-check text-success"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 運用費について -->
            <div class="pricing-note">
                <div class="pricing-note__item">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>
                        <strong>お支払い条件：</strong>前払い制となります。ご契約後、着手前にお支払いをお願いしております。
                    </div>
                </div>
                <div class="pricing-note__item">
                    <i class="fa-solid fa-server"></i>
                    <div>
                        <strong>運用費：</strong>月額5,000円（税別）<br>
                        <span class="text-small">データベース管理、サーバー維持、セキュリティ対応、バックアップなどの継続的な管理費用です。</span>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- 納期 / Delivery Schedule -->
    <section class="section delivery-schedule">
        <div class="container">
            <h2 class="section__title">
                <i class="fa-solid fa-calendar-days"></i> 納期
            </h2>

            <div class="delivery-timeline">
                <div class="delivery-period">
                    <div class="delivery-period__duration">
                        <i class="fa-solid fa-clock"></i>
                        <span class="delivery-period__number">約4週間</span>
                    </div>
                    <p class="delivery-period__note">
                        ご契約・ご入金確認後、約1ヶ月で納品いたします
                    </p>
                </div>

                <div class="delivery-steps">
                    <div class="delivery-step">
                        <div class="delivery-step__week">Week 1-2</div>
                        <div class="delivery-step__content">
                            <i class="fa-solid fa-pen-ruler"></i>
                            <h4>設計・デザイン</h4>
                            <p>ヒアリングを基に、サイト構成とデザインを作成します</p>
                        </div>
                    </div>

                    <div class="delivery-step">
                        <div class="delivery-step__week">Week 3</div>
                        <div class="delivery-step__content">
                            <i class="fa-solid fa-code"></i>
                            <h4>フロントエンド実装</h4>
                            <p>ホームページのデザインをコーディングします</p>
                        </div>
                    </div>

                    <div class="delivery-step">
                        <div class="delivery-step__week">Week 4</div>
                        <div class="delivery-step__content">
                            <i class="fa-solid fa-database"></i>
                            <h4>バックエンド・管理画面</h4>
                            <p>問い合わせフォーム、車両管理システムなどを実装します</p>
                        </div>
                    </div>

                    <div class="delivery-step">
                        <div class="delivery-step__week">Week 5</div>
                        <div class="delivery-step__content">
                            <i class="fa-solid fa-check-double"></i>
                            <h4>テスト・納品</h4>
                            <p>動作確認、修正を行い、納品・公開いたします</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 次のステップ / Next Steps -->
    <section class="section next-steps" id="contact">
        <div class="container">
            <h2 class="section__title">
                <i class="fa-solid fa-handshake"></i> 次のステップ
            </h2>
            <p class="section__lead">
                この提案書をご確認いただき、ご納得いただけましたら、以下の流れで進めさせていただきます
            </p>

            <div class="steps-flow">
                <div class="step-item">
                    <div class="step-item__number">
                        <i class="fa-solid fa-1"></i>
                    </div>
                    <div class="step-item__content">
                        <h3 class="step-item__title">ご検討</h3>
                        <p class="step-item__text">
                            この提案書をご確認いただき、ご不明点やご質問がございましたら、お気軽にお問い合わせください。
                        </p>
                    </div>
                </div>

                <div class="step-arrow">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="step-item">
                    <div class="step-item__number">
                        <i class="fa-solid fa-2"></i>
                    </div>
                    <div class="step-item__content">
                        <h3 class="step-item__title">ご契約・お支払い</h3>
                        <p class="step-item__text">
                            内容にご納得いただけましたら、ご契約書を交わします。<strong>お支払いは前払い制</strong>となります（銀行振込）。
                        </p>
                    </div>
                </div>

                <div class="step-arrow">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="step-item">
                    <div class="step-item__number">
                        <i class="fa-solid fa-3"></i>
                    </div>
                    <div class="step-item__content">
                        <h3 class="step-item__title">制作開始</h3>
                        <p class="step-item__text">
                            ご入金確認後、すぐに制作に着手いたします。ヒアリングを行い、デザイン・開発を進めます。
                        </p>
                    </div>
                </div>

                <div class="step-arrow">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>

                <div class="step-item step-item--final">
                    <div class="step-item__number">
                        <i class="fa-solid fa-4"></i>
                    </div>
                    <div class="step-item__content">
                        <h3 class="step-item__title">納品・公開</h3>
                        <p class="step-item__text">
                            約4週間後、サイトを納品いたします。動作確認後、公開となります。
                        </p>
                    </div>
                </div>
            </div>

            <!-- お問い合わせCTA -->
            <div class="contact-cta">
                <div class="contact-cta__content">
                    <h3 class="contact-cta__title">
                        <i class="fa-solid fa-envelope"></i> お問い合わせ
                    </h3>
                    <p class="contact-cta__text">
                        ご不明点やご質問がございましたら、お気軽にお問い合わせください。
                    </p>
                    <div class="contact-cta__info">
                        <p>
                            <i class="fa-solid fa-phone"></i>
                            <strong>電話：</strong><a href="tel:08046929681">080-4692-9681</a>
                        </p>
                        <p>
                            <i class="fa-solid fa-envelope"></i>
                            <strong>メール：</strong><a href="mailto:yamada@yojitu.com">yamada@yojitu.com</a>
                        </p>
                        <p>
                            <i class="fa-brands fa-line"></i>
                            <strong>LINE：</strong><a href="https://line.me/ti/p/CTOCx9YKjk" target="_blank">お問い合わせ</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__logo">
                    <span class="logo">YOJITU.COM</span>
                    <p class="footer__tagline">ビジネスに役立つWebサイト制作</p>
                </div>
                <div class="footer__info">
                    <p class="footer__company">余日（個人事業主）</p>
                    <p class="footer__text">URL: <a href="https://yojitu.com" target="_blank">https://yojitu.com</a></p>
                </div>
            </div>
            <div class="footer__copyright">
                <p>&copy; 2025 YOJITU.COM. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
