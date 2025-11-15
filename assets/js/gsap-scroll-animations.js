/**
 * GSAP Scroll Animations - 本格的なスクロールアニメーション
 * 1. セクション登場アニメーション
 * 2. タイトルスプリット演出
 * 3. カードスタガー
 * 4. パララックス効果
 */

(function() {
    'use strict';

    console.log('🎬 GSAP Scroll Animations - 初期化開始');

    // GSAPライブラリチェック
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('⚠️ GSAP or ScrollTrigger is not loaded');
        return;
    }

    // ScrollTriggerプラグイン登録
    gsap.registerPlugin(ScrollTrigger);

    console.log('✅ GSAP + ScrollTrigger - 登録完了');

    /**
     * 1. セクション登場アニメーション
     * フェードイン + スライドアップ
     */
    function initSectionAnimations() {
        const sections = document.querySelectorAll('.section');

        sections.forEach((section, index) => {
            gsap.from(section, {
                opacity: 0,
                y: 80,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    end: 'top 50%',
                    toggleActions: 'play none none reverse',
                    // markers: true // デバッグ用
                }
            });
        });

        console.log(`✅ セクション登場アニメーション - ${sections.length}個のセクションに適用`);
    }

    /**
     * 2. タイトルスプリット演出
     * 文字を1文字ずつ分割してアニメーション
     */
    function initTitleSplitAnimations() {
        const titles = document.querySelectorAll('.section__title, h2');

        titles.forEach(title => {
            // 既にdata-split-textがある場合はスキップ
            if (title.hasAttribute('data-split-text')) return;

            const text = title.textContent;
            const chars = text.split('');

            // 空にする
            title.innerHTML = '';
            title.style.overflow = 'hidden';

            // 1文字ずつspan要素を作成
            chars.forEach((char, index) => {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char;
                span.style.display = 'inline-block';
                span.style.opacity = '0';
                span.style.transform = 'translateY(50px)';
                title.appendChild(span);
            });

            // GSAPでアニメーション
            const charElements = title.querySelectorAll('span');
            gsap.to(charElements, {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.03,
                ease: 'back.out(1.2)',
                scrollTrigger: {
                    trigger: title,
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                }
            });
        });

        console.log(`✅ タイトルスプリット - ${titles.length}個のタイトルを処理`);
    }

    /**
     * 3. カードスタガー
     * サービスカード・ニュースカード・ブログカードを順番にアニメーション
     */
    function initCardStaggerAnimations() {
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
                    start: 'top 70%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ サービスカード - ${serviceCards.length}個をスタガーアニメーション`);
        }

        // ニュースカード
        const newsCards = document.querySelectorAll('.news-card');
        if (newsCards.length > 0) {
            gsap.from(newsCards, {
                opacity: 0,
                x: -50,
                y: 30,
                rotation: -3,
                duration: 0.7,
                stagger: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.news-section',
                    start: 'top 70%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ ニュースカード - ${newsCards.length}個をスタガーアニメーション`);
        }

        // ブログカード
        const blogCards = document.querySelectorAll('.blog-preview-card');
        if (blogCards.length > 0) {
            gsap.from(blogCards, {
                opacity: 0,
                y: 50,
                rotateY: 15,
                duration: 0.9,
                stagger: 0.12,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.blog-section',
                    start: 'top 70%',
                    toggleActions: 'play none none reverse'
                }
            });
            console.log(`✅ ブログカード - ${blogCards.length}個をスタガーアニメーション`);
        }
    }

    /**
     * 4. パララックス効果
     * 背景装飾要素をスクロールに応じて動かす
     */
    function initParallaxEffects() {
        // ヒーローセクションの背景装飾
        const heroShapes = document.querySelectorAll('.hero-v2__shape');
        heroShapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.3;
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
            console.log(`✅ ヒーロー背景パララックス - ${heroShapes.length}個の要素に適用`);
        }

        // セクションヘッダーラベル
        const sectionLabels = document.querySelectorAll('.section-header__label');
        sectionLabels.forEach(label => {
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

        if (sectionLabels.length > 0) {
            console.log(`✅ セクションラベル - ${sectionLabels.length}個をアニメーション`);
        }
    }

    /**
     * 5. ヒーローセクション特別演出
     * 最初のインパクトを強化
     */
    function initHeroAnimations() {
        const heroContent = document.querySelector('.hero-v2__content');
        if (!heroContent) return;

        // ヒーローコンテンツ全体のアニメーション
        const timeline = gsap.timeline({ delay: 0.3 });

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

        console.log('✅ ヒーローセクション - タイムラインアニメーション完了');
    }

    /**
     * 6. ボタンホバーエフェクト強化
     */
    function initButtonHoverEffects() {
        const buttons = document.querySelectorAll('.btn, .hero-v2__btn--primary, .hero-v2__btn--secondary');

        buttons.forEach(button => {
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

        console.log(`✅ ボタンホバーエフェクト - ${buttons.length}個のボタンに適用`);
    }

    /**
     * 初期化
     */
    function init() {
        console.log('🚀 GSAP Scroll Animations - 全機能初期化開始');

        // 各アニメーションを初期化
        initSectionAnimations();
        initTitleSplitAnimations();
        initCardStaggerAnimations();
        initParallaxEffects();
        initHeroAnimations();
        initButtonHoverEffects();

        // ScrollTriggerを更新
        ScrollTrigger.refresh();

        console.log('✅ GSAP Scroll Animations - 全機能初期化完了');
    }

    // ページロード後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
