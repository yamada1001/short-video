<?php
$current_page = 'recruit';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = '業務委託募集・交流 | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）では業務委託パートナーを募集中。動画撮影者・編集者、デジタルマーケティング関連の交流を希望しています。';
$additional_css = ['assets/css/pages/about.css', 'assets/css/pages/top.css'];

$structured_data = <<<'EOD'
    {
      "@context": "https://schema.org",
      "@type": "JobPosting",
      "title": "業務委託パートナー募集",
      "description": "動画撮影者（大分県内）・動画編集者（リモート可）を募集しています",
      "hiringOrganization": {
        "@type": "Organization",
        "name": "余日（Yojitsu）",
        "sameAs": "https://yojitu.com/"
      },
      "jobLocation": [
        {
          "@type": "Place",
          "address": {
            "@type": "PostalAddress",
            "addressRegion": "大分県",
            "addressCountry": "JP"
          }
        },
        {
          "@type": "Place",
          "address": {
            "@type": "PostalAddress",
            "addressRegion": "福岡県",
            "addressCountry": "JP"
          }
        },
        {
          "@type": "Place",
          "address": {
            "@type": "PostalAddress",
            "addressRegion": "東京都",
            "addressCountry": "JP"
          }
        }
      ],
      "employmentType": "CONTRACTOR",
      "applicantLocationRequirements": {
        "@type": "Country",
        "name": "日本"
      },
      "datePosted": "2025-11-12",
      "validThrough": "2026-12-31"
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

    <!-- 募集内容 -->
    <section class="company-info" style="padding-top: 100px;">
        <div class="container">
            <h2 class="section__title animate">業務委託パートナー募集</h2>
            <p class="section__description animate" style="margin-bottom: 60px;">
                余日（Yojitsu）では、以下の職種で業務委託パートナーを募集しています
            </p>

            <!-- 動画撮影者 -->
            <div class="mission-card animate" style="margin-bottom: 40px;">
                <h3 class="mission-card__title">
                    <span class="mission-card__icon"><i class="fas fa-camera"></i></span>
                    動画撮影者
                </h3>
                <p class="mission-card__description" style="margin-bottom: 16px;">
                    TikTok・Instagram・YouTubeショート向けのショート動画撮影を担当していただける方を募集しています。
                </p>
                <table class="company-table">
                    <tr>
                        <th>業務内容</th>
                        <td>企業PR・商品紹介などのショート動画撮影</td>
                    </tr>
                    <tr>
                        <th>条件</th>
                        <td>大分県内在住の方（撮影は主に大分県内）</td>
                    </tr>
                    <tr>
                        <th>報酬</th>
                        <td>案件ごとに個別協議</td>
                    </tr>
                    <tr>
                        <th>機材</th>
                        <td>カメラ・ジンバルなど基本機材をお持ちの方歓迎</td>
                    </tr>
                </table>
            </div>

            <!-- 動画編集者 -->
            <div class="vision-card animate" style="margin-bottom: 40px;">
                <h3 class="vision-card__title">
                    <span class="vision-card__icon"><i class="fas fa-cut"></i></span>
                    動画編集者
                </h3>
                <p class="vision-card__description" style="margin-bottom: 16px;">
                    ショート動画の編集業務を担当していただける方を募集しています。
                </p>
                <table class="company-table">
                    <tr>
                        <th>業務内容</th>
                        <td>TikTok・Instagram・YouTubeショート向け動画編集</td>
                    </tr>
                    <tr>
                        <th>条件</th>
                        <td>在住地不問（完全リモートOK）</td>
                    </tr>
                    <tr>
                        <th>報酬</th>
                        <td>1本あたり5,000円〜（内容により変動）</td>
                    </tr>
                    <tr>
                        <th>ソフト</th>
                        <td>Premiere Pro、Final Cut Pro、CapCut等</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

    <!-- 交流希望 -->
    <section class="mission-vision">
        <div class="container">
            <h2 class="section__title animate">交流希望</h2>
            <p class="section__description animate" style="margin-bottom: 40px;">
                デジタルマーケティング・Web制作関連の方との<br>
                情報交換や協業の可能性を探りたいと考えています
            </p>

            <div class="values__grid">
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="value-item__title">大分県</h3>
                    <p class="value-item__description">
                        拠点のある大分県で、デジタルマーケティング・Web制作・動画制作などの業種の方とぜひお会いしたいです。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="value-item__title">福岡県</h3>
                    <p class="value-item__description">
                        福岡によく行くため、福岡在住の方との交流も歓迎します。情報交換や協業の機会があればぜひ。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-city"></i>
                    </div>
                    <h3 class="value-item__title">東京都</h3>
                    <p class="value-item__description">
                        東京にも定期的に訪問しています。東京での打ち合わせや交流の機会もお気軽にご連絡ください。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- お問い合わせ方法 -->
    <section class="profile">
        <div class="container profile__container">
            <h2 class="section__title animate">お問い合わせ方法</h2>
            <div class="profile__card animate">
                <div class="profile__description">
                    <p style="margin-bottom: 24px;">
                        業務委託のご応募や交流のご希望は、以下の方法でお気軽にご連絡ください。<br>
                        件名に「業務委託応募」または「交流希望」と記載していただけるとスムーズです。
                    </p>

                    <table class="company-table">
                        <tr>
                            <th>メールアドレス</th>
                            <td><a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: var(--color-natural-brown);"><?php echo CONTACT_EMAIL; ?></a></td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td><a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: var(--color-natural-brown);"><?php echo CONTACT_TEL; ?></a></td>
                        </tr>
                        <tr>
                            <th>LINE</th>
                            <td><a href="https://line.me/ti/p/CTOCx9YKjk" target="_blank" rel="noopener" style="color: var(--color-natural-brown);">https://line.me/ti/p/CTOCx9YKjk</a></td>
                        </tr>
                        <tr>
                            <th>お問い合わせフォーム</th>
                            <td><a href="contact.php" style="color: var(--color-natural-brown);">こちらから</a></td>
                        </tr>
                    </table>

                    <p style="margin-top: 24px; color: var(--color-text-light); font-size: 14px;">
                        ※ 業務委託応募の場合は、簡単な経歴やポートフォリオを添えていただけると助かります。<br>
                        ※ 交流希望の場合は、どのような内容でお会いしたいかを簡単にお知らせください。
                    </p>
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

    <script defer src="assets/js/app.js"></script>
</body>
</html>
