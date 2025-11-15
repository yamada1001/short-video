<?php
$current_page = 'privacy';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'プライバシーポリシー | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）のプライバシーポリシー・個人情報保護方針について。';
$robots_meta = 'noindex, follow';
$additional_css = ['assets/css/pages/about.css', 'assets/css/cookie-consent.css'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">プライバシーポリシー</h1>
            <p class="page-header__description">
                個人情報の取り扱いについて
            </p>
        </div>
    </section>

    <!-- プライバシーポリシー本文 -->
    <section class="company-info">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto;">
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    余日（Yojitsu）（以下「当社」）は、お客様の個人情報保護の重要性について認識し、個人情報の保護に関する法律（以下「個人情報保護法」）を遵守すると共に、以下のプライバシーポリシー（以下「本ポリシー」）に従い、適切な取扱い及び保護に努めます。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">1. 個人情報の定義</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    本ポリシーにおいて、個人情報とは、個人情報保護法第2条第1項により定義された個人情報、すなわち、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日その他の記述等により特定の個人を識別することができるもの（他の情報と容易に照合することができ、それにより特定の個人を識別することができることとなるものを含む）を指します。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">2. 個人情報の収集方法</h2>
                <p style="margin-bottom: var(--spacing-md); line-height: 1.9; color: var(--color-text-light);">
                    当社は、以下の方法により個人情報を収集することがあります。
                </p>
                <ul style="margin-bottom: var(--spacing-lg); padding-left: 24px; line-height: 2.0; color: var(--color-text-light);">
                    <li>お問い合わせフォームへの入力</li>
                    <li>メールでのお問い合わせ</li>
                    <li>電話でのお問い合わせ</li>
                    <li>LINEでのお問い合わせ</li>
                    <li>サービス申込み時の情報提供</li>
                </ul>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">3. 個人情報の利用目的</h2>
                <p style="margin-bottom: var(--spacing-md); line-height: 1.9; color: var(--color-text-light);">
                    当社は、収集した個人情報を以下の目的で利用いたします。
                </p>
                <ul style="margin-bottom: var(--spacing-lg); padding-left: 24px; line-height: 2.0; color: var(--color-text-light);">
                    <li>お問い合わせ対応</li>
                    <li>サービスの提供・運営</li>
                    <li>見積書・請求書等の発行</li>
                    <li>契約に基づく業務遂行</li>
                    <li>サービスに関するご案内・情報提供</li>
                    <li>マーケティング・統計分析</li>
                </ul>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">4. Cookieの使用について</h2>
                <p style="margin-bottom: var(--spacing-md); line-height: 1.9; color: var(--color-text-light);">
                    当社のウェブサイトでは、より良いサービスを提供するため、Cookieを使用しています。
                </p>
                <h3 style="font-size: 18px; margin: var(--spacing-lg) 0 var(--spacing-sm) 0; font-weight: 700;">Cookieとは</h3>
                <p style="margin-bottom: var(--spacing-md); line-height: 1.9; color: var(--color-text-light);">
                    Cookieとは、ウェブサイトを訪問した際に、ブラウザに保存される小さなテキストファイルです。これにより、次回訪問時にウェブサイトがユーザーを認識し、より快適なブラウジング体験を提供できます。
                </p>

                <h3 style="font-size: 18px; margin: var(--spacing-lg) 0 var(--spacing-sm) 0; font-weight: 700;">使用目的</h3>
                <ul style="margin-bottom: var(--spacing-lg); padding-left: 24px; line-height: 2.0; color: var(--color-text-light);">
                    <li>ウェブサイトの利用状況の把握・分析（Google Analytics等）</li>
                    <li>広告配信の最適化</li>
                    <li>ユーザー体験の向上</li>
                </ul>

                <h3 style="font-size: 18px; margin: var(--spacing-lg) 0 var(--spacing-sm) 0; font-weight: 700;">Cookieの無効化</h3>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    ブラウザの設定により、Cookieの受け取りを拒否することができます。ただし、Cookieを無効にした場合、ウェブサイトの一部機能が正常に動作しない可能性があります。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">5. Google Analyticsの使用について</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    当社のウェブサイトでは、Google Inc.が提供するアクセス解析サービス「Google Analytics」を利用しています。Google Analyticsは、Cookieを使用してウェブサイトの利用状況を分析します。収集されたデータは、Googleのプライバシーポリシーに基づいて管理されます。Google Analyticsの詳細については、<a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" style="color: var(--color-primary); text-decoration: underline;">Googleのプライバシーポリシー</a>をご確認ください。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">6. 個人情報の第三者提供</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    当社は、以下の場合を除き、お客様の同意なく個人情報を第三者に提供することはありません。
                </p>
                <ul style="margin-bottom: var(--spacing-lg); padding-left: 24px; line-height: 2.0; color: var(--color-text-light);">
                    <li>法令に基づく場合</li>
                    <li>人の生命、身体又は財産の保護のために必要がある場合であって、本人の同意を得ることが困難である場合</li>
                    <li>公衆衛生の向上又は児童の健全な育成の推進のために特に必要がある場合であって、本人の同意を得ることが困難である場合</li>
                    <li>国の機関若しくは地方公共団体又はその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがある場合</li>
                </ul>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">7. 個人情報の管理</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    当社は、個人情報の正確性を保ち、これを安全に管理いたします。個人情報の漏洩、滅失、き損等を防止するため、必要かつ適切な安全管理措置を講じます。また、個人情報を取り扱う従業員や委託先に対して、必要かつ適切な監督を行います。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">8. 個人情報の開示・訂正・削除</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    お客様は、当社が保有する個人情報について、開示、訂正、利用停止、削除を求めることができます。ご希望される場合は、下記のお問い合わせ先までご連絡ください。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">9. プライバシーポリシーの変更</h2>
                <p style="margin-bottom: var(--spacing-lg); line-height: 1.9; color: var(--color-text-light);">
                    当社は、法令の変更等に伴い、本ポリシーを予告なく変更することがあります。変更後のプライバシーポリシーは、本ページに掲載した時点で効力を生じるものとします。
                </p>

                <h2 style="font-size: 24px; margin: var(--spacing-xl) 0 var(--spacing-md) 0; font-weight: 700;">10. お問い合わせ窓口</h2>
                <p style="margin-bottom: var(--spacing-md); line-height: 1.9; color: var(--color-text-light);">
                    個人情報の取り扱いに関するお問い合わせは、以下までご連絡ください。
                </p>
                <div style="background-color: var(--color-bg-gray); padding: var(--spacing-lg); border-radius: 8px; margin-bottom: var(--spacing-xl);">
                    <p style="line-height: 2.0; color: var(--color-text-light);">
                        <strong>余日（Yojitsu）</strong><br>
                        Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: var(--color-primary);"><?php echo CONTACT_EMAIL; ?></a><br>
                        Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: var(--color-primary);"><?php echo CONTACT_TEL; ?></a><br>
                        営業時間: 10時~22時（年中無休）
                    </p>
                </div>

                <p style="text-align: right; color: var(--color-text-light); margin-top: var(--spacing-xl);">
                    制定日：令和7年5月14日<br>
                    最終改定日：令和7年11月13日
                </p>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Cookie同意バナー -->
    <div id="cookieConsent" class="cookie-consent">
        <div class="cookie-consent__container">
            <div class="cookie-consent__content">
                <p class="cookie-consent__text">
                    当サイトは、ウェブサイトにおけるお客様の利用状況を把握するためにCookieを使用しています。「同意する」をクリックすると、当サイトでのCookieの使用に同意することになります。
                    <a href="privacy.php" class="cookie-consent__link">プライバシーポリシー</a>
                </p>
            </div>
            <div class="cookie-consent__actions">
                <button id="acceptCookies" class="cookie-consent__button cookie-consent__button--accept">同意する</button>
                <button id="declineCookies" class="cookie-consent__button cookie-consent__button--decline">拒否する</button>
            </div>
        </div>
    </div>

    <script defer src="assets/js/app.js"></script>
</body>
</html>
