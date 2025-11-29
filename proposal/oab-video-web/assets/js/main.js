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
    const container = document.querySelector('.container');

    let currentSection = 0;
    let isScrolling = false;
    let scrollTimeout;

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
     * （scroll-snap-typeと併用して現在位置を追跡）
     */
    container.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);

        scrollTimeout = setTimeout(() => {
            // 現在表示されているセクションを判定
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

    /**
     * タッチスワイプ対応（モバイル）
     */
    let touchStartY = 0;
    let touchEndY = 0;

    container.addEventListener('touchstart', (e) => {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    container.addEventListener('touchend', (e) => {
        touchEndY = e.changedTouches[0].clientY;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeDistance = touchStartY - touchEndY;
        const minSwipeDistance = 50;

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
            // 現在のセクション位置を再計算
            updateActiveSection(currentSection);
        }, 200);
    });

})();
