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
     * セクションへスクロール（GSAP リッチアニメーション）
     */
    function scrollToSection(index) {
        if (index < 0 || index >= sections.length) return;
        if (isScrolling) return;

        isScrolling = true;
        checkLayoutMode();

        const currentSectionEl = sections[currentSection];
        const nextSectionEl = sections[index];
        const isHorizontal = isHorizontalLayout;

        // 背景エフェクトにアニメーション開始を通知
        window.dispatchEvent(new Event('pageAnimationStart'));

        // GSAPタイムライン作成
        const tl = gsap.timeline({
            onComplete: () => {
                isScrolling = false;
                currentSectionEl.classList.remove('section--leaving');
                nextSectionEl.classList.add('section--active');
                // 背景エフェクトにアニメーション終了を通知
                window.dispatchEvent(new Event('pageAnimationEnd'));
            }
        });

        // 現在のセクションをフェードアウト
        tl.to(currentSectionEl, {
            opacity: 0.3,
            scale: 0.95,
            duration: 0.6,
            ease: 'power2.in',
            onStart: () => {
                currentSectionEl.classList.add('section--leaving');
            }
        }, 0);

        // スクロールアニメーション（GSAPのScrollToPlugin使用）
        if (isHorizontal) {
            // SP版: 横スクロール
            tl.to(container, {
                scrollTo: {
                    x: nextSectionEl.offsetLeft,
                    autoKill: false
                },
                duration: 1.2,
                ease: 'power3.inOut'
            }, 0.3);
        } else {
            // PC版: 縦スクロール
            tl.to(container, {
                scrollTo: {
                    y: nextSectionEl.offsetTop,
                    autoKill: false
                },
                duration: 1.2,
                ease: 'power3.inOut'
            }, 0.3);
        }

        // 次のセクションをフェードイン
        tl.fromTo(nextSectionEl,
            {
                opacity: 0,
                scale: 1.05,
                x: isHorizontal ? 30 : 0,
                y: isHorizontal ? 0 : 20
            },
            {
                opacity: 1,
                scale: 1,
                x: 0,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                onStart: () => {
                    nextSectionEl.classList.remove('section--entering');
                    updateActiveSection(index);
                }
            }, 0.6);
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
                }, 1800); // アニメーション時間と同じ
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
            }, 800); // アニメーション時間と同じ
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
            // SP版: 横スワイプのみ検知（pull-to-refresh対策で縦スクロール無効化）
            const horizontalSwipe = touchStartX - touchEndX;

            if (Math.abs(horizontalSwipe) < minSwipeDistance) return;
            if (isScrolling) return;

            if (horizontalSwipe > 0 && currentSection < sections.length - 1) {
                // 左へスワイプ（次へ）
                scrollToSection(currentSection + 1);
            } else if (horizontalSwipe < 0 && currentSection > 0) {
                // 右へスワイプ（前へ）
                scrollToSection(currentSection - 1);
            }
        } else {
            // PC版: 縦スワイプのみ
            const swipeDistance = touchStartY - touchEndY;
            if (Math.abs(swipeDistance) < minSwipeDistance) return;
            if (isScrolling) return;

            if (swipeDistance > 0 && currentSection < sections.length - 1) {
                scrollToSection(currentSection + 1);
            } else if (swipeDistance < 0 && currentSection > 0) {
                scrollToSection(currentSection - 1);
            }
        }
    }

    /**
     * ホイールイベント（精度向上版・ゆっくりリッチアニメーション対応）
     */
    let wheelTimeout;
    let wheelDelta = 0;

    container.addEventListener('wheel', (e) => {
        clearTimeout(wheelTimeout);
        wheelDelta += e.deltaY;

        wheelTimeout = setTimeout(() => {
            // 閾値を上げて、より意図的なスクロールのみを検知
            if (Math.abs(wheelDelta) > 150 && !isScrolling) {
                if (wheelDelta > 0 && currentSection < sections.length - 1) {
                    scrollToSection(currentSection + 1);
                } else if (wheelDelta < 0 && currentSection > 0) {
                    scrollToSection(currentSection - 1);
                }
            }
            wheelDelta = 0;
        }, 100);
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

        // 最初のセクションをGSAPでセット（即座に表示）
        if (sections.length > 0) {
            gsap.set(sections[0], { opacity: 1, scale: 1, x: 0, y: 0 });
            sections[0].classList.add('section--active');

            // 他のセクションは非表示状態からスタート
            for (let i = 1; i < sections.length; i++) {
                gsap.set(sections[i], { opacity: 0 });
            }
        }
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

})();
