/**
 * OAB × ゼロイチオオイタ × 余日
 * 映像×Web制作パッケージ提案LP
 * スワイプ/スクロール制御とナビゲーション
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
    let isScrolling = false;
    let scrollTimeout;
    let isHorizontalLayout = false;

    /**
     * レイアウトモードを判定
     */
    function checkLayoutMode() {
        isHorizontalLayout = window.innerWidth <= 768;
    }

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
    }

    /**
     * セクションへスクロール
     */
    function scrollToSection(index) {
        if (index < 0 || index >= sections.length) return;
        if (isScrolling) return;

        isScrolling = true;

        sections[index].scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        updateActiveSection(index);

        // スクロール完了後にフラグをリセット
        setTimeout(() => {
            isScrolling = false;
        }, 800);
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
        }
    }

    function closeMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
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
     * キーボードナビゲーション
     */
    document.addEventListener('keydown', (e) => {
        if (isScrolling) return;

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
     * スクロールイベントの監視
     * （縦スクロール・横スクロール両対応）
     */
    container.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);

        scrollTimeout = setTimeout(() => {
            checkLayoutMode();
            let scrollPosition;
            let activeIndex = 0;

            if (isHorizontalLayout) {
                // 横スクロール
                scrollPosition = container.scrollLeft;
                sections.forEach((section, index) => {
                    const sectionLeft = section.offsetLeft;
                    const sectionWidth = section.offsetWidth;

                    if (scrollPosition >= sectionLeft - sectionWidth / 3) {
                        activeIndex = index;
                    }
                });
            } else {
                // 縦スクロール
                scrollPosition = container.scrollTop;
                sections.forEach((section, index) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollPosition >= sectionTop - sectionHeight / 3) {
                        activeIndex = index;
                    }
                });
            }

            updateActiveSection(activeIndex);
        }, 100);
    });

    /**
     * タッチスワイプ対応（モバイル - 横スライド）
     */
    let touchStartX = 0;
    let touchEndX = 0;
    let touchStartY = 0;
    let touchEndY = 0;

    container.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    container.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].clientX;
        touchEndY = e.changedTouches[0].clientY;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        checkLayoutMode();
        const minSwipeDistance = 50;

        if (isHorizontalLayout) {
            // 横スワイプ
            const swipeDistance = touchStartX - touchEndX;
            if (Math.abs(swipeDistance) < minSwipeDistance) return;
            if (isScrolling) return;

            if (swipeDistance > 0 && currentSection < sections.length - 1) {
                // 左へスワイプ（次へ）
                scrollToSection(currentSection + 1);
            } else if (swipeDistance < 0 && currentSection > 0) {
                // 右へスワイプ（前へ）
                scrollToSection(currentSection - 1);
            }
        } else {
            // 縦スワイプ
            const swipeDistance = touchStartY - touchEndY;
            if (Math.abs(swipeDistance) < minSwipeDistance) return;
            if (isScrolling) return;

            if (swipeDistance > 0 && currentSection < sections.length - 1) {
                // 下へスワイプ
                scrollToSection(currentSection + 1);
            } else if (swipeDistance < 0 && currentSection > 0) {
                // 上へスワイプ
                scrollToSection(currentSection - 1);
            }
        }
    }

    /**
     * ホイールイベント（精度向上版）
     */
    let wheelTimeout;
    let wheelDelta = 0;

    container.addEventListener('wheel', (e) => {
        clearTimeout(wheelTimeout);
        wheelDelta += e.deltaY;

        wheelTimeout = setTimeout(() => {
            if (Math.abs(wheelDelta) > 100 && !isScrolling) {
                if (wheelDelta > 0 && currentSection < sections.length - 1) {
                    scrollToSection(currentSection + 1);
                } else if (wheelDelta < 0 && currentSection > 0) {
                    scrollToSection(currentSection - 1);
                }
            }
            wheelDelta = 0;
        }, 50);
    }, { passive: true });

    /**
     * 初期化
     */
    function init() {
        checkLayoutMode();

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

    /**
     * リサイズ時の処理
     */
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            checkLayoutMode();
            // 現在のセクション位置を再計算
            updateActiveSection(currentSection);
        }, 200);
    });

    /**
     * GSAP アニメーション
     */
    function initGSAPAnimations() {
        // GSAPとScrollTriggerが読み込まれているか確認
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('GSAP or ScrollTrigger not loaded');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // 初期状態を設定（アニメーション開始前は非表示）
        gsap.set('.section__title', { opacity: 0, y: 50 });
        gsap.set('.flow__item', { opacity: 0, y: 30 });
        gsap.set('.reason-card', { opacity: 0, y: 40, scale: 0.95 });
        gsap.set('.service-block', { opacity: 0, x: -30 });
        gsap.set('.case-card', { opacity: 0, y: 40, scale: 0.98 });
        gsap.set('.plan-card', { opacity: 0, y: 40, rotationY: -10 });
        gsap.set('.contact-team', { opacity: 0, y: 30 });

        // セクションタイトルのアニメーション
        gsap.utils.toArray('.section__title').forEach((title, index) => {
            gsap.to(title, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: title,
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // フローアイテムのアニメーション
        gsap.utils.toArray('.flow__item').forEach((item, index) => {
            gsap.to(item, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                delay: index * 0.15,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: item.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // 選ばれる理由カードのアニメーション
        gsap.utils.toArray('.reason-card').forEach((card, index) => {
            gsap.to(card, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.7,
                delay: index * 0.2,
                ease: 'back.out(1.2)',
                scrollTrigger: {
                    trigger: card.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // サービスブロックのアニメーション
        gsap.utils.toArray('.service-block').forEach((block, index) => {
            gsap.to(block, {
                opacity: 1,
                x: 0,
                duration: 0.8,
                delay: index * 0.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: block.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // 制作事例カードのアニメーション（QRコード回転付き）
        gsap.utils.toArray('.case-card').forEach((card, index) => {
            const qr = card.querySelector('.case-card__qr');

            gsap.to(card, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.8,
                delay: index * 0.15,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: card.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });

            // QRコードの回転アニメーション
            if (qr) {
                gsap.fromTo(qr,
                    { rotationY: -90, opacity: 0 },
                    {
                        rotationY: 0,
                        opacity: 1,
                        duration: 0.8,
                        delay: index * 0.15 + 0.3,
                        ease: 'back.out(1.5)',
                        scrollTrigger: {
                            trigger: card.parentElement,
                            start: 'top 75%',
                            toggleActions: 'play none none reverse'
                        }
                    }
                );
            }
        });

        // 料金プランカードのアニメーション
        gsap.utils.toArray('.plan-card').forEach((card, index) => {
            gsap.to(card, {
                opacity: 1,
                y: 0,
                rotationY: 0,
                duration: 0.9,
                delay: index * 0.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: card.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // お問い合わせチームのアニメーション
        gsap.utils.toArray('.contact-team').forEach((team, index) => {
            gsap.to(team, {
                opacity: 1,
                y: 0,
                duration: 0.7,
                delay: index * 0.15,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: team.parentElement,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        // カバータイトルのアニメーション（ページ読み込み時）
        const coverTitle = document.querySelector('.cover__title');
        if (coverTitle) {
            gsap.fromTo(coverTitle,
                { opacity: 0, y: 60, scale: 0.95 },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1.2,
                    delay: 0.3,
                    ease: 'power3.out'
                }
            );
        }

        const coverSubtitle = document.querySelector('.cover__subtitle');
        if (coverSubtitle) {
            gsap.fromTo(coverSubtitle,
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: 0.6,
                    ease: 'power2.out'
                }
            );
        }

        const coverTeam = document.querySelector('.cover__team');
        if (coverTeam) {
            gsap.fromTo(coverTeam,
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: 0.9,
                    ease: 'power2.out'
                }
            );
        }
    }

    // ページ読み込み完了後にGSAPアニメーションを初期化
    window.addEventListener('load', () => {
        setTimeout(() => {
            initGSAPAnimations();
        }, 100);
    });

})();
