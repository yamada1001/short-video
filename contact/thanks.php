<?php
$current_page = 'contact';
require_once dirname(__DIR__) . '/includes/functions.php';

// URLパラメータ取得
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$cv_id = isset($_GET['cv_id']) ? trim($_GET['cv_id']) : '';

// 種別マッピング
$typeMapping = [
    'seo' => [
        'label' => 'SEO対策について',
        'icon' => 'fa-search',
        'message' => 'SEO対策に関するお問い合わせありがとうございます。',
        'next_step' => '検索順位向上のための最適な施策をご提案させていただきます。'
    ],
    'ad' => [
        'label' => '広告運用について',
        'icon' => 'fa-bullhorn',
        'message' => '広告運用に関するお問い合わせありがとうございます。',
        'next_step' => '最適な広告戦略とROI改善の施策をご提案させていただきます。'
    ],
    'web' => [
        'label' => 'Web制作について',
        'icon' => 'fa-laptop-code',
        'message' => 'Web制作に関するお問い合わせありがとうございます。',
        'next_step' => 'ご要望に合わせた最適なプランとお見積もりをご提案させていただきます。'
    ],
    'video' => [
        'label' => 'ショート動画制作について',
        'icon' => 'fa-video',
        'message' => 'ショート動画制作に関するお問い合わせありがとうございます。',
        'next_step' => '効果的な動画制作のご提案と制作フローをご説明させていただきます。'
    ],
    'freelance' => [
        'label' => '業務委託・協業について',
        'icon' => 'fa-handshake',
        'message' => '業務委託・協業に関するお問い合わせありがとうございます。',
        'next_step' => '協業の詳細についてお話しさせていただきます。'
    ],
    'quote' => [
        'label' => '見積もり依頼',
        'icon' => 'fa-file-invoice-dollar',
        'message' => 'お見積もりのご依頼ありがとうございます。',
        'next_step' => '詳細なお見積もりを作成してご提案させていただきます。'
    ],
    'sales' => [
        'label' => '営業のご連絡',
        'icon' => 'fa-briefcase',
        'message' => 'ご連絡ありがとうございます。',
        'next_step' => '内容を確認の上、ご連絡させていただきます。'
    ],
    'other' => [
        'label' => 'その他',
        'icon' => 'fa-envelope',
        'message' => 'お問い合わせありがとうございます。',
        'next_step' => '内容を確認の上、適切にご対応させていただきます。'
    ]
];

$typeInfo = isset($typeMapping[$type]) ? $typeMapping[$type] : $typeMapping['other'];

// Head用の変数設定
$page_title = '送信完了 | お問い合わせ | 余日（Yojitsu）';
$page_description = 'お問い合わせを受け付けました。';
$additional_css = ['assets/css/pages/contact.css', 'assets/css/pages/thanks.css'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once dirname(__DIR__) . '/includes/head.php'; ?>

<!-- GA4 Conversion Event -->
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('event', 'conversion', {
    'send_to': 'GTM-T7NGQDC2',
    'event_category': 'contact',
    'event_label': '<?php echo $type; ?>',
    'cv_id': '<?php echo h($cv_id); ?>',
    'contact_type': '<?php echo h($typeInfo['label']); ?>',
    'value': 1.0,
    'currency': 'JPY'
});

// カスタムイベント（種別ごと）
gtag('event', 'contact_<?php echo $type; ?>', {
    'cv_id': '<?php echo h($cv_id); ?>',
    'category': 'contact_conversion'
});
</script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include dirname(__DIR__) . '/includes/header.php'; ?>

    <!-- サンクスページ -->
    <section class="thanks-page">
        <!-- 紙吹雪用コンテナ -->
        <div class="confetti-container" id="confettiContainer"></div>

        <div class="container">
            <div class="thanks-page__content">
                <!-- アイコン -->
                <div class="thanks-page__icon-wrapper">
                    <div class="thanks-page__icon">
                        <i class="fas <?php echo h($typeInfo['icon']); ?>"></i>
                    </div>
                    <div class="thanks-page__check">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <!-- タイトル -->
                <h1 class="thanks-page__title">送信完了しました</h1>

                <!-- 種別ラベル -->
                <div class="thanks-page__type-label">
                    <i class="fas fa-tag"></i>
                    <?php echo h($typeInfo['label']); ?>
                </div>

                <!-- メッセージ -->
                <div class="thanks-page__message">
                    <p class="thanks-page__main-message">
                        <?php echo h($typeInfo['message']); ?><br>
                        内容を確認後、担当者より<strong>2営業日以内</strong>にご連絡させていただきます。
                    </p>

                    <div class="thanks-page__next-step">
                        <i class="fas fa-arrow-right"></i>
                        <span><?php echo h($typeInfo['next_step']); ?></span>
                    </div>

                    <div class="thanks-page__info">
                        <p>
                            <i class="fas fa-envelope"></i>
                            自動返信メールをお送りしておりますので、ご確認ください。
                        </p>
                        <p class="thanks-page__info-note">
                            メールが届かない場合は、迷惑メールフォルダをご確認いただくか、<br>
                            お手数ですが再度お問い合わせください。
                        </p>
                    </div>

                    <!-- CV ID -->
                    <?php if ($cv_id): ?>
                    <div class="thanks-page__cv-id">
                        <span>受付番号</span>
                        <code><?php echo h($cv_id); ?></code>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- アクションボタン -->
                <div class="thanks-page__actions">
                    <a href="../index.php" class="btn btn-primary btn--large">
                        <i class="fas fa-home"></i>
                        トップページへ戻る
                    </a>
                    <a href="../services.php" class="btn btn-secondary">
                        <i class="fas fa-th"></i>
                        サービス一覧を見る
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include dirname(__DIR__) . '/includes/footer.php'; ?>

    <script defer src="../assets/js/app.js"></script>
    <script defer src="../assets/js/thanks-animation.js"></script>

</body>
</html>
