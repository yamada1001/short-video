<?php
/**
 * Privacy Policy Page (プライバシーポリシーページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
$page = 'privacy';
require_once __DIR__ . '/includes/header.php';

// パンくずリスト
$breadcrumbs = [
    ['name' => 'ホーム', 'url' => url('')],
    ['name' => 'プライバシーポリシー', 'url' => '']
];
?>

<?php require_once __DIR__ . '/includes/breadcrumb.php'; ?>

<!-- Privacy Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-shield-halved"></i>
            プライバシーポリシー
        </h1>
        <p class="page-hero__lead">
            個人情報の取り扱いについて
        </p>
    </div>
</section>

<!-- Privacy Content Section -->
<section class="section policy-section">
    <div class="container">
        <div class="policy-content">

            <div class="policy-intro">
                <p>
                    <?php echo COMPANY_NAME; ?>（以下「当店」といいます）は、お客様の個人情報の重要性を認識し、
                    個人情報保護法および関連法令を遵守し、以下のプライバシーポリシーに従って、
                    お客様の個人情報を適切に取り扱います。
                </p>
            </div>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">01</span>
                    個人情報の定義
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        本プライバシーポリシーにおいて「個人情報」とは、個人情報保護法第2条第1項に定義される
                        個人情報、すなわち、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日
                        その他の記述等により特定の個人を識別できるもの（他の情報と容易に照合することができ、
                        それにより特定の個人を識別することができることとなるものを含みます）を指します。
                    </p>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">02</span>
                    個人情報の収集方法
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店は、以下の方法により個人情報を収集することがあります:
                    </p>
                    <ul>
                        <li>お客様が当店のウェブサイトのお問い合わせフォームを利用される場合</li>
                        <li>お客様が当店にお電話またはメールでお問い合わせいただく場合</li>
                        <li>お客様が当店で車両の買取・販売・車検等のサービスをご利用いただく場合</li>
                        <li>お客様が当店のキャンペーンやイベントにご参加いただく場合</li>
                    </ul>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">03</span>
                    個人情報の利用目的
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店は、収集した個人情報を以下の目的で利用いたします:
                    </p>
                    <ul>
                        <li>お客様へのサービス提供および各種ご案内</li>
                        <li>お問い合わせやご依頼への対応</li>
                        <li>車両の買取、販売、車検、整備、板金、リース等の契約の締結および履行</li>
                        <li>お客様に有益な情報やキャンペーン情報のご案内</li>
                        <li>アフターサービスのご提供</li>
                        <li>市場調査、データ分析、サービス改善のための統計資料作成</li>
                        <li>法令に基づく対応</li>
                    </ul>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">04</span>
                    個人情報の第三者提供
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店は、以下の場合を除き、お客様の個人情報を第三者に提供することはありません:
                    </p>
                    <ul>
                        <li>お客様の同意がある場合</li>
                        <li>法令に基づく場合</li>
                        <li>人の生命、身体または財産の保護のために必要がある場合であって、お客様の同意を得ることが困難である場合</li>
                        <li>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、お客様の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがある場合</li>
                    </ul>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">05</span>
                    個人情報の安全管理措置
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店は、個人情報の漏洩、滅失またはき損の防止その他の個人情報の安全管理のために必要かつ適切な措置を講じます。
                        また、個人情報を取り扱う従業員および委託先に対して、必要かつ適切な監督を行います。
                    </p>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">06</span>
                    個人情報の開示・訂正・削除
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        お客様は、当店に対して、個人情報保護法の定めるところにより、
                        ご自身の個人情報の開示、訂正、追加、削除、利用停止等を請求することができます。
                        ご請求される場合は、下記お問い合わせ窓口までご連絡ください。
                    </p>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">07</span>
                    Cookie（クッキー）の使用について
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店のウェブサイトでは、お客様により良いサービスを提供するため、Cookie（クッキー）を使用しています。
                        Cookieは、お客様のブラウザを識別する小さなデータファイルです。
                        お客様は、ブラウザの設定によりCookieの受け取りを拒否することができますが、
                        その場合、ウェブサイトの一部機能がご利用いただけなくなる可能性があります。
                    </p>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">08</span>
                    プライバシーポリシーの変更
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        当店は、法令の変更や当店の事業内容の変更等に伴い、本プライバシーポリシーを変更することがあります。
                        変更後のプライバシーポリシーは、当店のウェブサイトに掲載した時点から効力を生じるものとします。
                    </p>
                </div>
            </section>

            <section class="policy-section-item">
                <h2 class="policy-section-item__title">
                    <span class="policy-section-item__number">09</span>
                    お問い合わせ窓口
                </h2>
                <div class="policy-section-item__content">
                    <p>
                        個人情報の取り扱いに関するお問い合わせは、以下までご連絡ください:
                    </p>
                    <div class="policy-contact">
                        <div class="policy-contact__item">
                            <strong><?php echo COMPANY_NAME; ?></strong>
                        </div>
                        <div class="policy-contact__item">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo POSTAL_CODE; ?> <?php echo ADDRESS; ?>
                        </div>
                        <div class="policy-contact__item">
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:<?php echo TEL; ?>"><?php echo TEL_DISPLAY; ?></a>
                        </div>
                        <div class="policy-contact__item">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="policy-footer">
                <p>制定日: 2025年12月3日</p>
                <p>最終更新日: 2025年12月3日</p>
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
