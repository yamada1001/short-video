<?php
/**
 * 共通ヘッダー
 * $current_page: 現在のページを指定（'home', 'services', 'blog', 'about', 'contact'）
 */
$current_page = $current_page ?? '';
?>
<!-- ヘッダー -->
<header class="header" id="header">
    <div class="container header__container">
        <a href="<?php echo ($current_page === 'home') ? '/' : '/index.html'; ?>" class="header__logo">
            <svg width="120" height="40" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" r="18" fill="#8B7355" opacity="0.1"/>
                <path d="M12 10 L20 20 L28 10" stroke="#8B7355" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <line x1="20" y1="20" x2="20" y2="30" stroke="#8B7355" stroke-width="2" stroke-linecap="round"/>
                <text x="45" y="18" font-family="'Noto Sans JP', sans-serif" font-size="14" font-weight="400" fill="#4A4A4A" letter-spacing="0.15em">余日</text>
                <text x="45" y="30" font-family="'Noto Sans JP', sans-serif" font-size="9" font-weight="400" fill="#8B7355" letter-spacing="0.2em">YOJITSU</text>
            </svg>
        </a>
        <nav class="nav">
            <!-- デスクトップ用ナビゲーション（アイコンなし） -->
            <ul class="nav__list nav__list--desktop">
                <li><a href="<?php echo ($current_page === 'home') ? 'services.html' : '/services.html'; ?>" class="nav__link<?php echo ($current_page === 'services') ? ' nav__link--active' : ''; ?>">サービス</a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'blog/' : '/blog/'; ?>" class="nav__link<?php echo ($current_page === 'blog') ? ' nav__link--active' : ''; ?>">ブログ</a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'about.html' : '/about.html'; ?>" class="nav__link<?php echo ($current_page === 'about') ? ' nav__link--active' : ''; ?>">会社概要</a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'contact.html' : '/contact.html'; ?>" class="nav__link<?php echo ($current_page === 'contact') ? ' nav__link--active' : ''; ?>">お問い合わせ</a></li>
            </ul>

            <!-- モバイル用ナビゲーション（アイコンあり） -->
            <ul class="nav__list nav__list--mobile-menu" id="navList">
                <li><a href="<?php echo ($current_page === 'home') ? 'services.html' : '/services.html'; ?>" class="nav__link"><i class="fas fa-briefcase"></i><span>サービス</span></a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'blog/' : '/blog/'; ?>" class="nav__link"><i class="fas fa-blog"></i><span>ブログ</span></a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'about.html' : '/about.html'; ?>" class="nav__link"><i class="fas fa-building"></i><span>会社概要</span></a></li>
                <li><a href="<?php echo ($current_page === 'home') ? 'contact.html' : '/contact.html'; ?>" class="nav__link"><i class="fas fa-envelope"></i><span>お問い合わせ</span></a></li>
                <li class="mobile-menu__contact"><i class="fas fa-paper-plane"></i><a href="mailto:yamada@yojitu.com">yamada@yojitu.com</a></li>
                <li class="mobile-menu__contact"><i class="fas fa-phone"></i><a href="tel:+81367121467">03-6712-1467</a></li>
            </ul>

            <div class="hamburger" id="hamburger">
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
                <span class="hamburger__line"></span>
            </div>
        </nav>
    </div>
</header>
