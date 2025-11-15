/**
 * Rich Animations - ポートフォリオ/ブランディングサイト用
 * Split Text、GPU Acceleration、60fps最適化
 */

(function() {
    'use strict';

    console.log('🎬 Rich Animations - 初期化開始');

    /**
     * Split Text Animation
     * テキストを1文字ずつ分割してアニメーション
     */
    function initSplitText() {
        if (typeof gsap === 'undefined') {
            console.warn('⚠️ GSAP is not loaded');
            return;
        }

        console.log('✂️ Split Text Animation - 初期化中...');

        // 分割対象の要素を取得
        const splitTargets = document.querySelectorAll('[data-split-text]');

        splitTargets.forEach(function(element) {
            const text = element.textContent;
            const chars = text.split('');

            // 空の要素にする
            element.innerHTML = '';

            // GPU acceleration用のスタイル
            element.style.willChange = 'transform';

            // 1文字ずつspan要素を作成
            chars.forEach(function(char, index) {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char; // スペースは&nbsp;に
                span.style.display = 'inline-block';
                span.style.willChange = 'transform, opacity';
                span.style.opacity = '0';
                span.style.transform = 'translateY(100px) rotateX(-90deg)';
                span.className = 'split-char';
                element.appendChild(span);
            });

            // GSAPでアニメーション
            const chars = element.querySelectorAll('.split-char');
            gsap.to(chars, {
                opacity: 1,
                y: 0,
                rotateX: 0,
                duration: 0.8,
                stagger: 0.03,
                ease: 'power3.out',
                delay: 0.5,
                onComplete: function() {
                    // アニメーション完了後、will-changeを削除
                    element.style.willChange = 'auto';
                    chars.forEach(c => c.style.willChange = 'auto');
                }
            });
        });

        console.log(`✅ Split Text Animation - ${splitTargets.length}個の要素を処理`);
    }

    /**
     * スクロールトリガーアニメーション（GPU最適化版）
     */
    function initScrollAnimations() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('⚠️ GSAP or ScrollTrigger is not loaded');
            return;
        }

        console.log('📜 Scroll Animations - 初期化中...');

        gsap.registerPlugin(ScrollTrigger);

        // セクションのフェードイン（GPU最適化）
        const sections = document.querySelectorAll('.section');
        sections.forEach(function(section, index) {
            // GPU acceleration
            section.style.willChange = 'transform, opacity';

            gsap.from(section, {
                opacity: 0,
                y: 100,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                    end: 'top 20%',
                    toggleActions: 'play none none reverse',
                    onEnter: () => console.log(`👁️ Section ${index + 1} visible`),
                    onComplete: () => {
                        section.style.willChange = 'auto';
                    }
                }
            });
        });

        // サービスカードのスタガーアニメーション
        const serviceCards = document.querySelectorAll('.service-card');
        if (serviceCards.length > 0) {
            serviceCards.forEach(card => card.style.willChange = 'transform, opacity');

            gsap.from(serviceCards, {
                opacity: 0,
                y: 80,
                scale: 0.9,
                duration: 0.8,
                stagger: 0.15,
                ease: 'back.out(1.2)',
                scrollTrigger: {
                    trigger: '.services-section',
                    start: 'top 70%',
                    end: 'top 30%',
                    toggleActions: 'play none none reverse',
                    onComplete: () => {
                        serviceCards.forEach(card => card.style.willChange = 'auto');
                    }
                }
            });
        }

        // ニュースカードの斜めスライドイン
        const newsCards = document.querySelectorAll('.news-card');
        if (newsCards.length > 0) {
            newsCards.forEach(card => card.style.willChange = 'transform, opacity');

            gsap.from(newsCards, {
                opacity: 0,
                x: -100,
                y: 50,
                rotation: -5,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.news-section',
                    start: 'top 70%',
                    toggleActions: 'play none none reverse',
                    onComplete: () => {
                        newsCards.forEach(card => card.style.willChange = 'auto');
                    }
                }
            });
        }

        // ブログカードの3D回転登場
        const blogCards = document.querySelectorAll('.blog-preview-card');
        if (blogCards.length > 0) {
            blogCards.forEach(card => {
                card.style.willChange = 'transform, opacity';
                card.style.transformStyle = 'preserve-3d';
            });

            gsap.from(blogCards, {
                opacity: 0,
                rotateY: 90,
                y: 50,
                duration: 1,
                stagger: 0.15,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.blog-section',
                    start: 'top 70%',
                    toggleActions: 'play none none reverse',
                    onComplete: () => {
                        blogCards.forEach(card => card.style.willChange = 'auto');
                    }
                }
            });
        }

        console.log('✅ Scroll Animations - 初期化完了');
    }

    /**
     * パララックス効果（requestAnimationFrame使用）
     */
    function initParallaxEffect() {
        console.log('🌊 Parallax Effect - 初期化中...');

        let ticking = false;
        let scrollY = window.pageYOffset;

        // パララックス対象要素
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        parallaxElements.forEach(el => {
            el.style.willChange = 'transform';
        });

        function updateParallax() {
            parallaxElements.forEach(function(element) {
                const speed = parseFloat(element.getAttribute('data-parallax')) || 0.5;
                const yPos = -(scrollY * speed);
                element.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }

        window.addEventListener('scroll', function() {
            scrollY = window.pageYOffset;
            requestTick();
        }, { passive: true });

        console.log(`✅ Parallax Effect - ${parallaxElements.length}個の要素に適用`);
    }

    /**
     * マウスフォロワー強化版（60fps最適化）
     */
    function initEnhancedMouseFollower() {
        if (window.innerWidth < 768) {
            console.log('📱 モバイルのためマウスフォロワーをスキップ');
            return;
        }

        console.log('🖱️ Enhanced Mouse Follower - 初期化中...');

        // 外側の円
        const follower = document.createElement('div');
        follower.className = 'mouse-follower-enhanced';
        follower.style.cssText = `
            position: fixed;
            width: 40px;
            height: 40px;
            border: 2px solid rgba(139, 115, 85, 0.5);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            will-change: transform;
            transition: width 0.3s ease, height 0.3s ease, border-color 0.3s ease;
            mix-blend-mode: difference;
        `;
        document.body.appendChild(follower);

        // 内側のドット
        const dot = document.createElement('div');
        dot.className = 'mouse-dot-enhanced';
        dot.style.cssText = `
            position: fixed;
            width: 8px;
            height: 8px;
            background-color: rgba(139, 115, 85, 0.9);
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            will-change: transform;
            transition: background-color 0.3s ease;
        `;
        document.body.appendChild(dot);

        let mouseX = 0, mouseY = 0;
        let followerX = 0, followerY = 0;
        let dotX = 0, dotY = 0;

        document.addEventListener('mousemove', function(e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animate() {
            // スムーズな追従
            const dx = mouseX - followerX;
            const dy = mouseY - followerY;
            followerX += dx * 0.1;
            followerY += dy * 0.1;

            const dotDx = mouseX - dotX;
            const dotDy = mouseY - dotY;
            dotX += dotDx * 0.4;
            dotY += dotDy * 0.4;

            // GPU accelerated transform
            follower.style.transform = `translate3d(${followerX - 20}px, ${followerY - 20}px, 0)`;
            dot.style.transform = `translate3d(${dotX - 4}px, ${dotY - 4}px, 0)`;

            requestAnimationFrame(animate);
        }

        animate();

        // ホバー時のエフェクト
        const hoverElements = document.querySelectorAll('a, button, .service-card, .news-card, .blog-preview-card');
        hoverElements.forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                follower.style.width = '60px';
                follower.style.height = '60px';
                follower.style.borderColor = 'rgba(139, 115, 85, 0.8)';
                dot.style.transform = `translate3d(${dotX - 4}px, ${dotY - 4}px, 0) scale(1.5)`;
            });
            el.addEventListener('mouseleave', function() {
                follower.style.width = '40px';
                follower.style.height = '40px';
                follower.style.borderColor = 'rgba(139, 115, 85, 0.5)';
                dot.style.transform = `translate3d(${dotX - 4}px, ${dotY - 4}px, 0) scale(1)`;
            });
        });

        console.log('✅ Enhanced Mouse Follower - 初期化完了');
    }

    /**
     * スムーズスクロール（ページ内リンク）
     */
    function initSmoothScroll() {
        console.log('🔗 Smooth Scroll - 初期化中...');

        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#top') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();

                    gsap.to(window, {
                        duration: 1.2,
                        scrollTo: {
                            y: target,
                            offsetY: 80
                        },
                        ease: 'power3.inOut'
                    });
                }
            });
        });

        console.log(`✅ Smooth Scroll - ${links.length}個のリンクに適用`);
    }

    /**
     * 初期化
     */
    function init() {
        console.log('🚀 Rich Animations - 全機能初期化開始');

        // Split Text Animation
        initSplitText();

        // Scroll Animations
        initScrollAnimations();

        // Parallax Effect
        initParallaxEffect();

        // Enhanced Mouse Follower
        initEnhancedMouseFollower();

        // Smooth Scroll
        if (typeof gsap !== 'undefined' && gsap.plugins.scrollTo) {
            initSmoothScroll();
        }

        console.log('✅ Rich Animations - 全機能初期化完了');
    }

    // ページロード後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
