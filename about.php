<?php
$current_page = 'about';
require_once __DIR__ . '/includes/functions.php';

// 会社情報を取得
$company_data = file_get_contents(__DIR__ . '/includes/data/company.json');
$company_json = json_decode($company_data, true);
$company = $company_json['company'] ?? [];

// Head用の変数設定
$page_title = '会社概要 | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）の会社概要。大分県を拠点に、デジタルマーケティングで地域企業を支援しています。';
$additional_css = ['assets/css/pages/about.css', 'assets/css/cookie-consent.css'];
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

    <!-- 会社情報 -->
    <section class="company-info">
        <div class="container">
            <div class="company-info__grid">
                <div class="company-info__image animate">
                    <i class="fas fa-building"></i>
                </div>
                <div class="company-info__content animate">
                    <h2 class="section__title" style="text-align: left;">余日について</h2>
                    <p class="company-info__description">
                        余日（Yojitsu）は、大分県を拠点としたデジタルマーケティング・Web制作会社です。「余日」という名前には、日々の業務の中で生まれる余白の時間を大切にし、その時間でクリエイティブな発想を生み出すという想いが込められています。
                    </p>
                    <p class="company-info__description">
                        SEO対策、広告運用、Web制作、ショート動画制作など、デジタルマーケティング全般にわたるサービスを提供。特に大分県内の企業様を中心に、地域に根ざしたマーケティング支援を行っています。
                    </p>
                    <p class="company-info__description">
                        Web制作会社での3年間の経験を活かし、マーケティング視点を持ったWebサイト制作と、データに基づいた広告運用で、お客様のビジネス成長をサポートします。
                    </p>
                </div>
            </div>

            <!-- 会社データ -->
            <table class="company-table animate">
                <tr>
                    <th>屋号</th>
                    <td><?php echo h($company['name']); ?></td>
                </tr>
                <tr>
                    <th>代表</th>
                    <td><?php echo h($company['representative']); ?></td>
                </tr>
                <tr>
                    <th>拠点</th>
                    <td><?php echo h($company['location']); ?>（オンライン対応可）</td>
                </tr>
                <tr>
                    <th>設立</th>
                    <td><?php echo h($company['foundedJp']); ?>（<?php echo h($company['founded']); ?>）</td>
                </tr>
                <tr>
                    <th>登録番号</th>
                    <td>適格請求書発行事業者<br><?php echo h($company['taxId']); ?></td>
                </tr>
                <tr>
                    <th>事業内容</th>
                    <td>
                        <?php foreach ($company['services'] as $service): ?>
                            <?php echo h($service); ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td><?php echo CONTACT_TEL; ?></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><?php echo CONTACT_EMAIL; ?></td>
                </tr>
            </table>
        </div>
    </section>

    <!-- バリュー -->
    <section class="values">
        <div class="container">
            <h2 class="section__title animate">私たちの価値観</h2>
            <div class="values__grid">
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="value-item__title">顧客第一</h3>
                    <p class="value-item__description">
                        お客様の成功が私たちの成功。常にお客様の立場に立ち、最善の提案を行います。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="value-item__title">データドリブン</h3>
                    <p class="value-item__description">
                        感覚ではなくデータに基づいた戦略立案。確実な成果を追求します。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <h3 class="value-item__title">継続的改善</h3>
                    <p class="value-item__description">
                        常に最新のトレンドをキャッチアップし、より良いサービスを提供し続けます。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="value-item__title">地域貢献</h3>
                    <p class="value-item__description">
                        大分県の企業様と共に成長し、地域経済の発展に寄与します。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 代表プロフィール -->
    <section class="profile">
        <div class="container profile__container">
            <h2 class="section__title animate">代表プロフィール</h2>
            <div class="profile__card animate">
                <div class="profile__header">
                    <div class="profile__image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile__info">
                        <h3 class="profile__name">山田 蓮</h3>
                        <p class="profile__title">代表 / デジタルマーケター</p>
                    </div>
                </div>
                <div class="profile__description">
                    <p>Web制作会社にて、マーケティング・Web制作業務を3年間担当。SEO・SNS・広告運用代行を中心に、多数の企業様のデジタルマーケティングを支援。</p>
                    <p style="margin-top: 16px;">独立後は外部CMOやプロジェクトマネージャー、動画制作、Web制作を行いながら、大分県を拠点にデジタルマーケティング事業を展開。</p>
                    <p style="margin-top: 16px;">データに基づいた戦略立案と、クリエイティブな発想を掛け合わせた提案を得意としています。</p>
                    <div class="certifications">
                        <h4 class="certifications__title">保有資格</h4>
                        <div class="certifications__grid">
                            <div class="certification-card">
                                <img src="https://api.accredible.com/v1/frontend/credential_website_embed_image/certificate/166225387" alt="Google広告認定資格" class="certification-card__image">
                                <p class="certification-card__label">Google広告認定資格</p>
                            </div>
                            <div class="certification-card">
                                <img src="https://api.accredible.com/v1/frontend/credential_website_embed_image/certificate/166239443" alt="Google Analytics認定資格" class="certification-card__image">
                                <p class="certification-card__label">Google Analytics認定資格</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <?php
    $cta_base_path = '';
    $cta_show_info = true;
    include __DIR__ . '/includes/cta.php';
    ?>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <script defer src="assets/js/app.js"></script>

</body>
</html>
