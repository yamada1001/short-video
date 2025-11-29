/**
 * OAB × ゼロイチオオイタ × 余日
 * 映像×Web制作パッケージ提案LP
 * 普通の縦長LP（シンプル版）
 */

(function() {
    'use strict';

    // DOM要素
    const sections = document.querySelectorAll('.section');
    const navDots = document.querySelectorAll('.nav-dot');
    const tocItems = document.querySelectorAll('.toc__item');
    const container = document.querySelector('.container');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuBtn = document.querySelector('.nav-menu-btn');
    const mobileMenuClose = document.querySelector('.mobile-menu__close');
    const mobileMenuOverlay = document.querySelector('.mobile-menu__overlay');
    const mobileMenuItems = document.querySelectorAll('.mobile-menu__item');

    let currentSection = 0;

    /**
     * アクティブセクションを更新
     */
    function updateActiveSection(index) {
        if (index < 0 || index >= sections.length) return;

        currentSection = index;

        // ナビゲーションドットを更新
        navDots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        // 目次を更新
        tocItems.forEach((item, i) => {
            if (i === index) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        // 目次のデザインを切り替え（セクション0と7は暗い背景なので白背景を追加）
        const toc = document.querySelector('.toc');
        if (toc) {
            if (index === 0 || index === 7) {
                toc.classList.add('toc--light');
            } else {
                toc.classList.remove('toc--light');
            }
        }
    }

    /**
     * セクションへスクロール
     */
    function scrollToSection(index) {
        if (index < 0 || index >= sections.length) return;

        sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
        updateActiveSection(index);
    }

    /**
     * ナビゲーションドットのクリックイベント
     */
    navDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            scrollToSection(index);
        });
    });

    /**
     * 目次のクリックイベント
     */
    tocItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            scrollToSection(index);
        });
    });

    /**
     * モバイルメニューの開閉
     */
    function openMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';

            // 現在のセクションが0または7（暗い背景）の場合、テキストを白に
            if (currentSection === 0 || currentSection === 7) {
                mobileMenu.classList.add('mobile-menu--dark');
            } else {
                mobileMenu.classList.remove('mobile-menu--dark');
            }

            // ハンバーガーボタンにクリックアニメーションを追加
            if (mobileMenuBtn) {
                mobileMenuBtn.classList.add('clicked');
                setTimeout(() => {
                    mobileMenuBtn.classList.remove('clicked');
                }, 1800);
            }
        }
    }

    function closeMobileMenu() {
        if (mobileMenu) {
            // 閉じるアニメーション開始
            mobileMenu.classList.add('closing');

            // アニメーション終了後にメニューを非表示
            setTimeout(() => {
                mobileMenu.classList.remove('active');
                mobileMenu.classList.remove('closing');
                mobileMenu.classList.remove('mobile-menu--dark');
                document.body.style.overflow = '';
            }, 800);
        }
    }

    // メニューボタンクリック
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openMobileMenu);
    }

    // 閉じるボタンクリック
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    // オーバーレイクリック
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }

    // メニュー項目クリック
    mobileMenuItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            const sectionIndex = parseInt(item.dataset.section);
            scrollToSection(sectionIndex);
            closeMobileMenu();
        });
    });

    /**
     * キーボードナビゲーション（PC版）
     */
    document.addEventListener('keydown', (e) => {
        // SP版（768px以下）では十字キー無効化
        if (window.innerWidth <= 768) return;

        switch(e.key) {
            case 'ArrowDown':
            case 'PageDown':
                e.preventDefault();
                if (currentSection < sections.length - 1) {
                    scrollToSection(currentSection + 1);
                }
                break;

            case 'ArrowUp':
            case 'PageUp':
                e.preventDefault();
                if (currentSection > 0) {
                    scrollToSection(currentSection - 1);
                }
                break;

            case 'Home':
                e.preventDefault();
                scrollToSection(0);
                break;

            case 'End':
                e.preventDefault();
                scrollToSection(sections.length - 1);
                break;
        }
    });

    /**
     * スクロール監視（PC版のみ）
     */
    let scrollTimeout;
    if (container && window.innerWidth > 768) {
        container.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);

            scrollTimeout = setTimeout(() => {
                const scrollPosition = container.scrollTop;
                let activeIndex = 0;

                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollPosition >= sectionTop - sectionHeight / 3) {
                        activeIndex = index;
                    }
                });

                updateActiveSection(activeIndex);
            }, 100);
        });
    }

    /**
     * 初期化
     */
    function init() {
        // URLハッシュがあれば該当セクションへ
        const hash = window.location.hash;
        if (hash) {
            const targetSection = document.querySelector(hash);
            if (targetSection) {
                const index = Array.from(sections).indexOf(targetSection);
                if (index !== -1) {
                    setTimeout(() => {
                        scrollToSection(index);
                    }, 100);
                    return;
                }
            }
        }

        // デフォルトは最初のセクション
        updateActiveSection(0);
    }

    // ページ読み込み完了後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
