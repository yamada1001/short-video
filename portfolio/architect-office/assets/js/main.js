/**
 * 建築設計事務所 - メインJavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // ========================================
    // ヘッダーのスクロール時のスタイル変更
    // ========================================
    const header = document.querySelector('.cHeader');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
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
    const mobileMenuLinks = document.querySelectorAll('.cMobileMenu__link');

    if (menuButton) {
        menuButton.addEventListener('click', () => {
            body.classList.toggle('menu-open');
        });
    }

    // モバイルメニューのリンクをクリックしたらメニューを閉じる
    mobileMenuLinks.forEach(link => {
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
    // プロジェクトフィルター機能
    // ========================================
    const filterButtons = document.querySelectorAll('.lProjects__filter_button');
    const projectItems = document.querySelectorAll('.lProjects__item');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            // ボタンのアクティブ状態を切り替え
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // プロジェクトアイテムの表示/非表示
            projectItems.forEach(item => {
                const category = item.getAttribute('data-category');

                if (filter === 'all' || category === filter) {
                    item.classList.remove('hidden');
                    // フェードイン効果
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.classList.add('hidden');
                    }, 300);
                }
            });
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
    const animateElements = document.querySelectorAll('.lAbout, .lProjects, .lTeam, .lContact');

    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(40px)';
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
    // ページトップへ戻るボタン
    // ========================================
    const pageTopLink = document.querySelector('.cFooter__pageTop_link');

    if (pageTopLink) {
        pageTopLink.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    console.log('建築設計事務所LP: JavaScript initialized');
});
