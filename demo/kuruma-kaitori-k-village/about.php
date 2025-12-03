<?php
/**
 * About Page (会社概要ページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
$page = 'about';
require_once __DIR__ . '/includes/header.php';
?>

<!-- About Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-building"></i>
            会社概要
        </h1>
        <p class="page-hero__lead">
            地域密着で安心・信頼のサービスを提供します
        </p>
    </div>
</section>

<!-- Company Profile Section -->
<section class="section about-section">
    <div class="container">

        <!-- 会社紹介 -->
        <div class="about-intro">
            <h2 class="section__title">ごあいさつ</h2>
            <div class="about-intro__content">
                <p>
                    くるま買取ケイヴィレッジは、大分市中判田に拠点を構える総合カーサービス企業です。<br>
                    お車の買取から販売、車検・整備、板金、リースまで、お車に関するあらゆるニーズにお応えします。
                </p>
                <p>
                    私たちは、お客様との信頼関係を何よりも大切にし、一台一台のお車と真摯に向き合っています。<br>
                    豊富な知識と経験を持つスタッフが、お客様に最適なご提案をさせていただきます。
                </p>
                <p>
                    地域の皆様に愛され、信頼されるお店を目指し、日々サービスの向上に努めております。<br>
                    お車のことなら、どんなことでもお気軽にご相談ください。
                </p>
            </div>
        </div>

        <!-- 会社情報テーブル -->
        <div class="company-table-wrapper">
            <h2 class="section__title">会社情報</h2>
            <table class="company-table">
                <tbody>
                    <tr>
                        <th>
                            <i class="fa-solid fa-building"></i>
                            店舗名
                        </th>
                        <td><?php echo COMPANY_NAME; ?></td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-certificate"></i>
                            古物商許可番号
                        </th>
                        <td><?php echo LICENSE_NUMBER; ?></td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-location-dot"></i>
                            所在地
                        </th>
                        <td>
                            <?php echo POSTAL_CODE; ?><br>
                            <?php echo ADDRESS; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-phone"></i>
                            電話番号
                        </th>
                        <td>
                            <a href="tel:<?php echo TEL; ?>" class="company-table__tel">
                                <?php echo TEL_DISPLAY; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-envelope"></i>
                            メールアドレス
                        </th>
                        <td>
                            <a href="mailto:<?php echo EMAIL; ?>" class="company-table__email">
                                <?php echo EMAIL; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-clock"></i>
                            営業時間
                        </th>
                        <td>
                            平日: 9:00〜18:00<br>
                            土曜: 9:00〜17:00<br>
                            <span class="text-muted">※日曜・祝日定休</span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <i class="fa-solid fa-briefcase"></i>
                            事業内容
                        </th>
                        <td>
                            <ul class="company-table__list">
                                <li>中古車買取</li>
                                <li>新車販売</li>
                                <li>中古車販売</li>
                                <li>車検・整備</li>
                                <li>板金・塗装</li>
                                <li>カーリース</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- アクセスマップ -->
        <div class="access-map">
            <h2 class="section__title">アクセス</h2>
            <div class="access-map__content">
                <div class="access-map__info">
                    <h3 class="access-map__title">
                        <i class="fa-solid fa-location-dot"></i>
                        <?php echo COMPANY_NAME; ?>
                    </h3>
                    <p class="access-map__address">
                        <?php echo POSTAL_CODE; ?><br>
                        <?php echo ADDRESS; ?>
                    </p>
                    <div class="access-map__buttons">
                        <a href="tel:<?php echo TEL; ?>" class="btn btn--primary">
                            <i class="fa-solid fa-phone"></i>
                            電話する
                        </a>
                        <a href="<?php echo url('contact'); ?>" class="btn btn--outline">
                            <i class="fa-solid fa-envelope"></i>
                            お問い合わせ
                        </a>
                    </div>
                    <div class="access-map__note">
                        <h4>
                            <i class="fa-solid fa-car"></i>
                            アクセス方法
                        </h4>
                        <ul>
                            <li>大分駅から車で約15分</li>
                            <li>大分自動車道 大分ICから車で約10分</li>
                            <li>駐車場完備（10台）</li>
                        </ul>
                    </div>
                </div>
                <div class="access-map__map">
                    <iframe
                        src="<?php echo GOOGLE_MAP_EMBED; ?>"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/includes/cta.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
