<?php
/**
 * 共通ヘッダー
 * $current_page: 現在のページを指定（'home', 'services', 'blog', 'about', 'contact'）
 */
// config.phpを読み込む（未読み込みの場合のみ）
if (!defined('CONTACT_EMAIL')) {
    require_once __DIR__ . '/config.php';
}
$current_page = $current_page ?? '';

// 言語切り替え用URL処理
$currentUrl = $_SERVER['REQUEST_URI'];
$isEnglish = strpos($currentUrl, '/en/') === 0 || strpos($currentUrl, '/en') === 0;
?>
<?php
// 英語版用のプレフィックス
$langPrefix = $isEnglish ? '/en' : '';

// 言語別ナビゲーションテキスト
if ($isEnglish) {
    $navTexts = [
        'services' => 'Services',
        'services_top' => 'All Services',
        'web_production' => 'Web Development',
        'video_production' => 'Video Production',
        'blog' => 'Blog',
        'about' => 'About',
        'contact' => 'Contact'
    ];
} else {
    $navTexts = [
        'services' => 'サービス',
        'services_top' => 'サービストップ',
        'web_production' => 'Webサイト制作',
        'video_production' => '動画制作',
        'blog' => 'ブログ',
        'about' => '会社概要',
        'contact' => 'お問い合わせ'
    ];
}
?>
<!-- ヘッダー -->
<header class="header" id="header">
    <div class="container header__container">
        <a href="<?php echo $isEnglish ? '/en/' : (($current_page === 'home') ? '/' : '/index.php'); ?>" class="header__logo">
            <svg width="120" height="40" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" r="18" fill="#8B7355" opacity="0.1"/>
                <path d="M12 10 L20 20 L28 10" stroke="#8B7355" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <line x1="20" y1="20" x2="20" y2="30" stroke="#8B7355" stroke-width="2" stroke-linecap="round"/>
                <text x="45" y="18" font-family="'Noto Sans JP', sans-serif" font-size="14" font-weight="400" fill="#4A4A4A" letter-spacing="0.15em">余日</text>
                <text x="45" y="30" font-family="'Noto Sans JP', sans-serif" font-size="9" font-weight="400" fill="#8B7355" letter-spacing="0.2em">YOJITSU</text>
            </svg>
        </a>
        <nav class="nav">
            <ul class="nav__list" id="navList">
                <li class="nav__item nav__item--dropdown">
                    <a href="<?php echo $langPrefix; ?>/services.php" class="nav__link<?php echo ($current_page === 'services') ? ' nav__link--active' : ''; ?>">
                        <i class="fas fa-briefcase nav__icon"></i><span><?php echo $navTexts['services']; ?></span>
                    </a>
                    <ul class="nav__dropdown">
                        <li><a href="<?php echo $langPrefix; ?>/services.php" class="nav__dropdown-link"><i class="fas fa-th"></i> <?php echo $navTexts['services_top']; ?></a></li>
                        <li><a href="<?php echo $langPrefix; ?>/web-production.php" class="nav__dropdown-link"><i class="fas fa-laptop-code"></i> <?php echo $navTexts['web_production']; ?></a></li>
                        <li><a href="<?php echo $langPrefix; ?>/video-production.php" class="nav__dropdown-link"><i class="fas fa-video"></i> <?php echo $navTexts['video_production']; ?></a></li>
                    </ul>
                </li>
                <li class="nav__item"><a href="<?php echo $isEnglish ? '/en/blog/' : (($current_page === 'home') ? 'blog/' : '/blog/'); ?>" class="nav__link<?php echo ($current_page === 'blog') ? ' nav__link--active' : ''; ?>"><i class="fas fa-blog nav__icon"></i><span><?php echo $navTexts['blog']; ?></span></a></li>
                <li class="nav__item"><a href="<?php echo $langPrefix; ?>/about.php" class="nav__link<?php echo ($current_page === 'about') ? ' nav__link--active' : ''; ?>"><i class="fas fa-building nav__icon"></i><span><?php echo $navTexts['about']; ?></span></a></li>
                <li class="nav__item"><a href="<?php echo $langPrefix; ?>/contact.php" class="nav__link<?php echo ($current_page === 'contact') ? ' nav__link--active' : ''; ?>"><i class="fas fa-envelope nav__icon"></i><span><?php echo $navTexts['contact']; ?></span></a></li>
                <li class="nav__item nav__item--contact"><i class="fas fa-paper-plane"></i><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></li>
                <li class="nav__item nav__item--contact"><i class="fas fa-phone"></i><a href="tel:<?php echo CONTACT_TEL_LINK; ?>"><?php echo CONTACT_TEL; ?></a></li>

                <!-- 言語切り替え -->
                <?php if (empty($hide_lang_switch)): ?>
                <li class="nav__item nav__item--lang">
                    <?php
                    if ($isEnglish) {
                        // 英語版 → 日本語版へのリンク
                        // /en/index.php → /index.php
                        // /en/services.php → /services.php
                        $jaUrl = preg_replace('#^/en(/|$)#', '/', $currentUrl);
                        echo '<a href="' . htmlspecialchars($jaUrl) . '" class="nav__lang-link" title="日本語"><i class="fas fa-globe"></i> 日本語</a>';
                    } else {
                        // 日本語版 → 英語版へのリンク
                        $enUrl = '/en' . $currentUrl;
                        echo '<a href="' . htmlspecialchars($enUrl) . '" class="nav__lang-link" title="English"><i class="fas fa-globe"></i> English</a>';
                    }
                    ?>
                </li>
                <?php endif; ?>
            </ul>

            <!-- スマホ用言語切り替え（ハンバーガーの隣に常時表示） -->
            <?php if (empty($hide_lang_switch)): ?>
            <div class="nav__lang-mobile">
                <?php
                if ($isEnglish) {
                    $jaUrl = preg_replace('#^/en(/|$)#', '/', $currentUrl);
                    echo '<a href="' . htmlspecialchars($jaUrl) . '" class="nav__lang-mobile-link" title="日本語">JA</a>';
                } else {
                    $enUrl = '/en' . $currentUrl;
                    echo '<a href="' . htmlspecialchars($enUrl) . '" class="nav__lang-mobile-link" title="English">EN</a>';
                }
                ?>
            </div>
            <?php endif; ?>

            <div class="hamburger" id="hamburger">
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
            </div>
        </nav>
    </div>
</header>
