/**
 * ヒーローセクション v2 - GSAPアニメーション
 * ブランディングサイト風の段階的なアニメーション
 */

(function() {
    'use strict';

    // GSAP読み込み確認
    if (typeof gsap === 'undefined') {
        console.warn('GSAP is not loaded. Hero animations will be skipped.');
        return;
    }

    // JavaScript有効フラグを追加
    document.documentElement.classList.add('js-enabled');

    // DOM要素の取得
    const heroSection = document.querySelector('.hero-v2');
    if (!heroSection) {
        console.warn('Hero section not found');
        return;
    }

    // アニメーション対象要素
    const label = heroSection.querySelector('[data-hero-label]');
    const titleLine1 = heroSection.querySelector('[data-hero-title-1]');
    const titleLine2 = heroSection.querySelector('[data-hero-title-2]');
    const titleLine3 = heroSection.querySelector('[data-hero-title-3]');
    const text = heroSection.querySelector('[data-hero-text]');
    const buttons = heroSection.querySelector('[data-hero-buttons]');
    const meta1 = heroSection.querySelector('[data-hero-meta-1]');
    const meta2 = heroSection.querySelector('[data-hero-meta-2]');
    const meta3 = heroSection.querySelector('[data-hero-meta-3]');
    const scroll = heroSection.querySelector('[data-hero-scroll]');
    const bg = heroSection.querySelector('.hero-v2__bg');
    const shapes = heroSection.querySelectorAll('.hero-v2__shape');

    // タイトルの単語を取得
    const words1 = titleLine1 ? titleLine1.querySelectorAll('.hero-v2__title-word') : [];
    const words2 = titleLine2 ? titleLine2.querySelectorAll('.hero-v2__title-word') : [];
    const words3 = titleLine3 ? titleLine3.querySelectorAll('.hero-v2__title-word') : [];

    /**
     * パーティクルを動的に生成
     */
    function createParticles() {
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'hero-v2__particles';
        heroSection.appendChild(particlesContainer);

        // 30個のパーティクルを生成
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'hero-v2__particle';

            // ランダムな位置に配置
            const randomX = Math.random() * 100;
            const randomY = Math.random() * 100;
            particle.style.left = randomX + '%';
            particle.style.top = randomY + '%';

            // ランダムなサイズ
            const randomSize = 2 + Math.random() * 4;
            particle.style.width = randomSize + 'px';
            particle.style.height = randomSize + 'px';

            // ランダムなアニメーション遅延
            const randomDelay = Math.random() * 5;
            particle.style.animationDelay = randomDelay + 's';

            particlesContainer.appendChild(particle);
        }

        console.log('✨ パーティクル30個を生成');
    }

    /**
     * メインタイムラインアニメーション
     */
    function initHeroAnimation() {
        // GSAPのデフォルト設定
        gsap.defaults({
            ease: 'power3.out',
            duration: 1
        });

        // メインタイムライン
        const tl = gsap.timeline({
            delay: 0.3, // ページロード後の遅延
            onComplete: function() {
                // アニメーション完了後にヘッダーを表示
                showHeader();
                console.log('✅ ヒーローアニメーション完了 - ヘッダー表示');
            }
        });

        // 背景のシェイプをアニメーション
        if (shapes.length > 0) {
            gsap.set(shapes, { scale: 0, opacity: 0 });
            tl.to(shapes, {
                scale: 1,
                opacity: 1,
                duration: 1.5,
                stagger: 0.2,
                ease: 'power2.out'
            }, 0);
        }

        // ラベルをフェードイン
        if (label) {
            tl.to(label, {
                opacity: 1,
                y: 0,
                duration: 0.8
            }, 0.2);
        }

        // タイトル1行目の単語を順番に表示
        if (words1.length > 0) {
            tl.to(words1, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power3.out'
            }, 0.4);
        }

        // タイトル2行目の単語を順番に表示
        if (words2.length > 0) {
            tl.to(words2, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power3.out'
            }, 0.7);
        }

        // タイトル3行目の単語を順番に表示
        if (words3.length > 0) {
            tl.to(words3, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power3.out'
            }, 1.0);
        }

        // テキストをフェードイン
        if (text) {
            tl.to(text, {
                opacity: 1,
                y: 0,
                duration: 0.8
            }, 1.4);
        }

        // ボタンをフェードイン
        if (buttons) {
            const buttonElements = buttons.querySelectorAll('.hero-v2__btn');
            tl.to(buttonElements, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.15
            }, 1.6);
        }

        // サイドメタ情報を順番に表示
        const metaItems = [meta1, meta2, meta3].filter(Boolean);
        if (metaItems.length > 0) {
            tl.to(metaItems, {
                opacity: 1,
                x: 0,
                duration: 0.6,
                stagger: 0.2
            }, 1.8);
        }

        // スクロールヒントをフェードイン
        if (scroll) {
            tl.to(scroll, {
                opacity: 1,
                duration: 0.8
            }, 2.0);
        }
    }

    /**
     * スクロールアニメーション（ScrollTrigger使用）
     */
    function initScrollAnimations() {
        // ScrollTrigger読み込み確認
        if (typeof ScrollTrigger === 'undefined') {
            console.warn('ScrollTrigger is not loaded. Scroll animations will be skipped.');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // 背景の視差効果
        if (bg) {
            gsap.to(bg, {
                yPercent: 30,
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSection,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        }

        // シェイプの視差効果
        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.5;
            gsap.to(shape, {
                yPercent: 50 * speed,
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSection,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });

        // タイトルの視差効果
        const titleLines = [titleLine1, titleLine2, titleLine3].filter(Boolean);
        titleLines.forEach((line, index) => {
            gsap.to(line, {
                yPercent: (index + 1) * 10,
                opacity: 0.5,
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSection,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    /**
     * ホバーアニメーション
     */
    function initHoverAnimations() {
        // ボタンのホバーエフェクト強化
        const buttons = heroSection.querySelectorAll('.hero-v2__btn');
        buttons.forEach(btn => {
            const icon = btn.querySelector('.hero-v2__btn-icon');

            btn.addEventListener('mouseenter', () => {
                if (icon) {
                    gsap.to(icon, {
                        x: 5,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            });

            btn.addEventListener('mouseleave', () => {
                if (icon) {
                    gsap.to(icon, {
                        x: 0,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            });
        });

        // メタアイテムのホバーエフェクト
        const metaItems = heroSection.querySelectorAll('.hero-v2__meta-item');
        metaItems.forEach(item => {
            const line = item.querySelector('.hero-v2__meta-line');

            item.addEventListener('mouseenter', () => {
                if (line) {
                    gsap.to(line, {
                        width: 80,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                }
            });

            item.addEventListener('mouseleave', () => {
                if (line) {
                    gsap.to(line, {
                        width: 60,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                }
            });
        });
    }

    /**
     * ヘッダーを表示
     */
    function showHeader() {
        const header = document.querySelector('.header');
        if (header) {
            // is-visibleクラスを追加
            header.classList.add('is-visible');

            // GSAPアニメーションも適用
            gsap.to(header, {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power3.out'
            });
        }
    }

    /**
     * 初期化関数
     */
    function init() {
        try {
            // パーティクルを生成
            createParticles();

            // メインアニメーション実行
            initHeroAnimation();

            // スクロールアニメーション実行
            initScrollAnimations();

            // ホバーアニメーション実行
            initHoverAnimations();

            console.log('✅ Hero animations initialized successfully');
        } catch (error) {
            console.error('❌ Error initializing hero animations:', error);
            // エラー時は.js-enabledクラスを削除して通常表示に戻す
            document.documentElement.classList.remove('js-enabled');
        }
    }

    // ページロード後に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        // すでにDOMが読み込まれている場合
        init();
    }

})();
