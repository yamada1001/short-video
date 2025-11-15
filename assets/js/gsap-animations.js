/**
 * GSAP Animations - 統合アニメーションシステム
 * ヒーローセクション + スクロールアニメーション
 */

(function() {
    'use strict';

    console.log('🎬 GSAP Animations - 初期化開始');

    // GSAPライブラリチェック
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('⚠️ GSAP or ScrollTrigger is not loaded');
        return;
    }

    // ScrollTriggerプラグイン登録
    gsap.registerPlugin(ScrollTrigger);

    console.log('✅ GSAP + ScrollTrigger - 登録完了');

    /**
     * ヒーローセクションアニメーション
     */
    function initHeroAnimations() {
        console.log('🦸 Hero Animations - 初期化中...');

        const timeline = gsap.timeline({ delay: 0.5 });

        timeline
            .from('.hero-v2__label', {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: 'power2.out'
            })
            .from('.hero-v2__title-line', {
                opacity: 0,
                y: 50,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power3.out'
            }, '-=0.4')
            .from('.hero-v2__text', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: 'power2.out'
            }, '-=0.3')
            .from('.hero-v2__buttons', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: 'power2.out'
            }, '-=0.2')
            .from('.hero-v2__meta-item', {
                opacity: 0,
                x: 50,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out'
            }, '-=0.4')
            .from('.hero-v2__scroll', {
                opacity: 0,
                y: 20,
                duration: 0.6,
                ease: 'power2.out'
            }, '-=0.3');

        console.log('✅ Hero Animations - 完了');
    }

    /**
     * セクション登場アニメーション
     */
    function initSectionAnimations() {
        const sections = document.querySelectorAll('.section');

        sections.forEach((section) => {
            gsap.from(section, {
                opacity: 0,
                y: 60,
                duration: 1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        console.log(`✅ セクション登場 - ${sections.length}個に適用`);
    }

    /**
     * セクションタイトルアニメーション
     */
    function initTitleAnimations() {
        const titles = document.querySelectorAll('.section__title');

        titles.forEach((title) => {
            gsap.from(title, {
                opacity: 0,
                y: 40,
                scale: 0.95,
                duration: 0.8,
                ease: 'back.out(1.2)',
                scrollTrigger: {
                    trigger: title,
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        console.log(`✅ タイトルアニメーション - ${titles.length}個に適用`);
    }

    /**
     * カードスタガーアニメーション
     */
    function initCardAnimations() {
        // サービスカード
        const serviceCards = document.querySelectorAll('.service-card');
        if (serviceCards.length > 0) {
            gsap.from(serviceCards, {
                opacity: 0,
                y: 60,
                scale: 0.95,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.services-section',
                    start: 'top 65%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ サービスカード - ${serviceCards.length}個`);
        }

        // ニュースカード
        const newsCards = document.querySelectorAll('.news-card');
        if (newsCards.length > 0) {
            gsap.from(newsCards, {
                opacity: 0,
                x: -40,
                y: 20,
                duration: 0.7,
                stagger: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.news-section',
                    start: 'top 65%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ ニュースカード - ${newsCards.length}個`);
        }

        // ブログカード
        const blogCards = document.querySelectorAll('.blog-preview-card');
        if (blogCards.length > 0) {
            gsap.from(blogCards, {
                opacity: 0,
                y: 50,
                duration: 0.9,
                stagger: 0.12,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.blog-section',
                    start: 'top 65%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ ブログカード - ${blogCards.length}個`);
        }
    }

    /**
     * セクションラベルアニメーション
     */
    function initLabelAnimations() {
        const labels = document.querySelectorAll('.section-header__label');

        labels.forEach((label) => {
            gsap.from(label, {
                opacity: 0,
                scale: 0.8,
                duration: 0.8,
                ease: 'back.out(1.5)',
                scrollTrigger: {
                    trigger: label,
                    start: 'top 90%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        console.log(`✅ ラベルアニメーション - ${labels.length}個に適用`);
    }

    /**
     * ボタンホバーエフェクト
     */
    function initButtonHoverEffects() {
        const buttons = document.querySelectorAll('.btn, .hero-v2__btn--primary, .hero-v2__btn--secondary');

        buttons.forEach((button) => {
            button.addEventListener('mouseenter', () => {
                gsap.to(button, {
                    scale: 1.05,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });

            button.addEventListener('mouseleave', () => {
                gsap.to(button, {
                    scale: 1,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });
        });

        console.log(`✅ ボタンホバー - ${buttons.length}個に適用`);
    }

    /**
     * パララックス効果（控えめ）
     */
    function initParallaxEffects() {
        const heroShapes = document.querySelectorAll('.hero-v2__shape');

        heroShapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.2;
            gsap.to(shape, {
                y: () => window.innerHeight * speed,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero-v2',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });

        if (heroShapes.length > 0) {
            console.log(`✅ パララックス - ${heroShapes.length}個の背景装飾`);
        }
    }

    /**
     * 初期化
     */
    function init() {
        console.log('🚀 GSAP Animations - 全機能初期化開始');

        // 各アニメーションを初期化
        initHeroAnimations();
        initSectionAnimations();
        initTitleAnimations();
        initCardAnimations();
        initLabelAnimations();
        initButtonHoverEffects();
        initParallaxEffects();

        // ScrollTriggerを更新
        ScrollTrigger.refresh();

        console.log('✅ GSAP Animations - 全機能初期化完了');
    }

    // ページロード後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
