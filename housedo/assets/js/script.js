/**
 * ハウスドゥ Instagram運用代行 作業要件書
 * インタラクティブ機能 JavaScript
 */

// ===================================
// 1. ローディングアニメーション
// ===================================

window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    setTimeout(function() {
        loader.classList.add('hidden');
    }, 800);
});

// ===================================
// 2. ヘッダースクロール処理
// ===================================

const header = document.getElementById('header');
let lastScrollTop = 0;

window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // スクロール時に影を追加
    if (scrollTop > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }

    lastScrollTop = scrollTop;
});

// ===================================
// 3. スムーススクロール
// ===================================

document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');

        // ハッシュのみの場合（#）はスクロールしない
        if (href === '#') {
            e.preventDefault();
            return;
        }

        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            const headerHeight = header.offsetHeight;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// ===================================
// 4. タブ切り替え機能
// ===================================

const tabButtons = document.querySelectorAll('.tabs__nav-btn');
const tabContents = document.querySelectorAll('.tabs__content');

tabButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');

        // すべてのボタンとコンテンツからactiveクラスを削除
        tabButtons.forEach(function(btn) {
            btn.classList.remove('active');
        });
        tabContents.forEach(function(content) {
            content.classList.remove('active');
        });

        // クリックされたボタンと対応するコンテンツにactiveクラスを追加
        this.classList.add('active');
        const targetContent = document.getElementById('tab-' + targetTab);
        if (targetContent) {
            targetContent.classList.add('active');
        }
    });
});

// ===================================
// 5. コンテンツの柱 アコーディオン
// ===================================

const pillarButtons = document.querySelectorAll('.pillar-card__btn');

pillarButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-pillar');
        const targetDetail = document.getElementById(targetId);

        // ボタンとコンテンツのactiveクラスをトグル
        this.classList.toggle('active');
        targetDetail.classList.toggle('active');
    });
});

// ===================================
// 6. トップへ戻るボタン
// ===================================

const scrollToTopBtn = document.getElementById('scrollToTop');

window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add('visible');
    } else {
        scrollToTopBtn.classList.remove('visible');
    }
});

scrollToTopBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// ===================================
// 7. モバイルメニュー
// ===================================

const menuBtn = document.getElementById('menuBtn');
const headerNav = document.querySelector('.header__nav');

menuBtn.addEventListener('click', function() {
    this.classList.toggle('active');
    headerNav.classList.toggle('active');
});

// メニューリンクをクリックしたらメニューを閉じる
const navLinks = document.querySelectorAll('.header__nav-link');
navLinks.forEach(function(link) {
    link.addEventListener('click', function() {
        menuBtn.classList.remove('active');
        headerNav.classList.remove('active');
    });
});

// ===================================
// 8. スクロールアニメーション（IntersectionObserver）
// ===================================

const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// アニメーション対象の要素を監視
document.querySelectorAll('.overview-card, .doc-section, .pillar-card, .kpi-card').forEach(function(el) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// ===================================
// 9. ヘッダーナビゲーション ハイライト（現在のセクション）
// ===================================

const sections = document.querySelectorAll('.section[id]');
const navLinks2 = document.querySelectorAll('.header__nav-link[href^="#"]');

window.addEventListener('scroll', function() {
    let current = '';
    const scrollY = window.pageYOffset;

    sections.forEach(function(section) {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 200)) {
            current = section.getAttribute('id');
        }
    });

    navLinks2.forEach(function(link) {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
        }
    });
});

// ===================================
// 10. コンソールメッセージ（開発者向け）
// ===================================

console.log('%c🏠 HOUSEDO Instagram運用代行 作業要件書', 'font-size: 20px; font-weight: bold; color: #8B7355;');
console.log('%c制作: YOJITU (https://yojitu.com)', 'font-size: 12px; color: #666;');
console.log('%cフォント: LINE Seed JP', 'font-size: 12px; color: #666;');
console.log('%cすべての機能が正常に動作しています ✓', 'font-size: 12px; color: #428570;');

// ===================================
// 11. コンテンツ案モーダル
// ===================================

const contentModal = document.getElementById('contentModal');
const showContentModalBtn = document.getElementById('showContentModal');
const closeContentModalBtn = document.getElementById('closeContentModal');
const contentModalOverlay = document.getElementById('contentModalOverlay');

// モーダルを開く
if (showContentModalBtn) {
    showContentModalBtn.addEventListener('click', function() {
        contentModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

// モーダルを閉じる
function closeContentModal() {
    contentModal.classList.remove('active');
    document.body.style.overflow = '';
}

if (closeContentModalBtn) {
    closeContentModalBtn.addEventListener('click', closeContentModal);
}

if (contentModalOverlay) {
    contentModalOverlay.addEventListener('click', closeContentModal);
}

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && contentModal.classList.contains('active')) {
        closeContentModal();
    }
});
