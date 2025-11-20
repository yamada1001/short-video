    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $page_description ?? '大分県を拠点に、Web制作・ショート動画制作を提供する余日（Yojitsu）。'; ?>">
<?php if (isset($page_keywords)): ?>
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
<?php endif; ?>
<?php if (isset($robots_meta)): ?>
    <meta name="robots" content="<?php echo $robots_meta; ?>">
<?php endif; ?>
    <title><?php echo $page_title ?? '余日（Yojitsu）'; ?></title>

    <!-- hreflang tags for multilingual support -->
<?php
// 現在のURLを取得
$current_url = $_SERVER['REQUEST_URI'];
$is_english = strpos($current_url, '/en/') === 0;

// ベースURLを構築
$base_url = 'https://yojitu.com';

if ($is_english) {
    // 英語版ページの場合
    $ja_url = $base_url . str_replace('/en/', '/', $current_url);
    $en_url = $base_url . $current_url;
} else {
    // 日本語版ページの場合
    $ja_url = $base_url . $current_url;
    $en_url = $base_url . '/en' . $current_url;
}
?>
    <link rel="alternate" hreflang="ja" href="<?php echo htmlspecialchars($ja_url); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo htmlspecialchars($en_url); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($ja_url); ?>">
<?php if (isset($ogp_tags)): ?>

    <!-- OGP -->
<?php echo $ogp_tags; ?>
<?php endif; ?>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <?php require_once __DIR__ . '/favicon.php'; ?>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <!-- 共通CSS -->
<?php
// CSSのベースパス（デフォルトは絶対パス）
$css_base_path = $css_base_path ?? '/';
?>
    <link rel="stylesheet" href="<?php echo $css_base_path; ?>assets/css/base.css">
    <link rel="stylesheet" href="<?php echo $css_base_path; ?>assets/css/cta.css">
<?php
// 追加のCSSファイル
if (isset($additional_css) && is_array($additional_css)) {
    foreach ($additional_css as $css_file) {
        echo '    <link rel="stylesheet" href="' . $css_base_path . $css_file . '">' . "\n";
    }
}
?>

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- GSAP for animations (loaded globally for language switcher and other animations) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Language switcher with GSAP animations -->
    <script defer src="<?php echo $css_base_path ?? '/'; ?>assets/js/lang-switcher.js"></script>

    <!-- Google Tag Manager - Async -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>
<?php if (isset($structured_data)): ?>

    <!-- Structured Data -->
    <script type="application/ld+json">
<?php echo $structured_data; ?>
    </script>
<?php endif; ?>
<?php if (isset($faq_structured_data)): ?>

    <!-- FAQ Structured Data -->
    <script type="application/ld+json">
<?php echo $faq_structured_data; ?>
    </script>
<?php endif; ?>
<?php if (isset($inline_styles)): ?>

    <style>
<?php echo $inline_styles; ?>
    </style>
<?php endif; ?>
