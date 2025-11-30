/**
 * 工務店LP - メインJavaScript（HONEYCOMB.LABO風リッチ版）
 */

document.addEventListener('DOMContentLoaded', () => {
    // ========================================
    // オープニングアニメーション（初回訪問時のみ）
    // ========================================
    const opening = document.querySelector('.lOpening');
    const hasVisited = localStorage.getItem('koumuten_visited');

    if (!hasVisited && opening) {
        // 初回訪問
        opening.classList.add('active');

        // 2.8秒後にアニメーション終了
        setTimeout(() => {
            opening.classList.add('visited');
            opening.classList.remove('active');
            localStorage.setItem('koumuten_visited', 'true');

            // ヘッダー、メインビジュアルのアニメーション開始
            initAnimations();
        }, 2800);
    } else {
        // 2回目以降の訪問
        if (opening) {
            opening.classList.add('visited');
        }
        initAnimations();
    }

    // ========================================
    // ヘッダーのスクロール時のスタイル変更
    // ========================================
    const header = document.querySelector('.cHeader');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // ========================================
    // ハンバーガーメニューの開閉
    // ========================================
    const menuButton = document.querySelector('.js-menu-button');
    const body = document.body;
    const menuLinks = document.querySelectorAll('.cMenu a');

    if (menuButton) {
        menuButton.addEventListener('click', () => {
            body.classList.toggle('menu-open');
        });
    }

    // メニューのリンクをクリックしたらメニューを閉じる
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            body.classList.remove('menu-open');
        });
    });

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
    // アニメーション初期化
    // ========================================
    function initAnimations() {
        // ヘッダー・コンタクトボタン・MVのフェードイン
        const headerInner = document.querySelector('.cHeader__inner');
        const mvSection = document.querySelector('.lMV');

        setTimeout(() => {
            if (headerInner) {
                headerInner.classList.add('is-visible');
            }
            if (mvSection) {
                mvSection.classList.add('is-visible');
            }
        }, 100);
    }

    // ========================================
    // ヘキサゴンの複雑なアニメーション（スクロール時）
    // ========================================
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -100px 0px',
        threshold: 0.1
    };

    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-active');

                // セクション内のヘキサゴン要素をアニメーション
                const hexagons = entry.target.querySelectorAll('.lMV__hexagon_item');
                hexagons.forEach((hex, index) => {
                    setTimeout(() => {
                        hex.classList.add('is-visible');
                    }, index * 150);
                });
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // アニメーション対象の要素
    const animateElements = document.querySelectorAll('.lMessage, .lWorks, .lVoice, .lCTA, .lMV');

    animateElements.forEach(el => {
        observer.observe(el);
    });

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

    console.log('工務店LP（リッチ版）: JavaScript initialized');
});
