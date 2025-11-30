/**
 * 工務店LP - メインJavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // ========================================
    // ハンバーガーメニューの開閉
    // ========================================
    const menuButton = document.querySelector('.js-menu-button');
    const body = document.body;

    if (menuButton) {
        menuButton.addEventListener('click', () => {
            body.classList.toggle('menu-open');
        });
    }

    // ========================================
    // スムーズスクロール
    // ========================================
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');

    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');

            // #のみの場合はスキップ
            if (href === '#') {
                e.preventDefault();
                return;
            }

            const targetId = href.replace('#', '');
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                e.preventDefault();

                // ヘッダーの高さを考慮
                const headerHeight = 80;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });

                // メニューが開いていたら閉じる
                if (body.classList.contains('menu-open')) {
                    body.classList.remove('menu-open');
                }
            }
        });
    });

    // ========================================
    // スクロールアニメーション（Intersection Observer）
    // ========================================
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -100px 0px',
        threshold: 0.1
    };

    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // アニメーション対象の要素
    const animateElements = document.querySelectorAll('.lMessage, .lWorks, .lVoice, .lCTA');

    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';

        observer.observe(el);
    });

    // is-visibleクラスが付与されたらアニメーション
    const style = document.createElement('style');
    style.textContent = `
        .is-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);

    // ========================================
    // お問い合わせボタンの表示/非表示制御
    // ========================================
    const contactButton = document.querySelector('.js-menu-contact');
    const ctaSection = document.querySelector('.lCTA');

    if (contactButton && ctaSection) {
        const contactObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    contactButton.setAttribute('data-menu-contact', 'false');
                } else {
                    contactButton.setAttribute('data-menu-contact', 'true');
                }
            });
        }, {
            rootMargin: '0px 0px -50% 0px'
        });

        contactObserver.observe(ctaSection);
    }

    // ========================================
    // ページトップへ戻るボタン
    // ========================================
    const pageTopLink = document.querySelector('.cFooter__pageTop a');

    if (pageTopLink) {
        pageTopLink.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    console.log('工務店LP: JavaScript initialized');
});
