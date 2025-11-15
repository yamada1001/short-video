/**
 * Rich Interactions - Safe Version
 * 既存のレイアウトに影響を与えない安全な実装
 * Locomotive Scrollは使用せず、既存の構造を維持
 */

(function() {
    'use strict';

    // ページロード完了後に初期化
    window.addEventListener('load', function() {
        initHeroSwiper();
        initParticleAnimations();
        initMouseFollower();
        initScrollProgressBar();
        initScrollAnimations();
        initParallaxEffects();
    });

    /**
     * ヒーローセクションのスライダー初期化
     */
    function initHeroSwiper() {
        const heroSwiper = document.querySelector('.hero-swiper');
        if (!heroSwiper) return;

        new Swiper('.hero-swiper', {
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            speed: 2000,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            loop: true,
            allowTouchMove: false,
        });
    }

    /**
     * パーティクルアニメーション（ランダム配置）
     */
    function initParticleAnimations() {
        const particles = document.querySelectorAll('.hero-v2__particles .particle');

        particles.forEach(function(particle, index) {
            const randomX = Math.random() * 100;
            const randomY = Math.random() * 100;
            const randomDelay = Math.random() * 2;
            const randomDuration = 20 + Math.random() * 10;

            particle.style.left = randomX + '%';
            particle.style.top = randomY + '%';
            particle.style.animationDelay = randomDelay + 's';
            particle.style.animationDuration = randomDuration + 's';
        });
    }

    /**
     * マウスフォロワー（カーソル追従エフェクト）
     */
    function initMouseFollower() {
        // モバイルではスキップ
        if (window.innerWidth < 768) return;

        const follower = document.createElement('div');
        follower.className = 'mouse-follower';
        document.body.appendChild(follower);

        const followerDot = document.createElement('div');
        followerDot.className = 'mouse-follower-dot';
        document.body.appendChild(followerDot);

        let mouseX = 0, mouseY = 0;
        let followerX = 0, followerY = 0;
        let dotX = 0, dotY = 0;

        document.addEventListener('mousemove', function(e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animateFollower() {
            const distX = mouseX - followerX;
            const distY = mouseY - followerY;
            followerX += distX * 0.1;
            followerY += distY * 0.1;
            follower.style.transform = `translate(${followerX}px, ${followerY}px)`;

            const dotDistX = mouseX - dotX;
            const dotDistY = mouseY - dotY;
            dotX += dotDistX * 0.3;
            dotY += dotDistY * 0.3;
            followerDot.style.transform = `translate(${dotX}px, ${dotY}px)`;

            requestAnimationFrame(animateFollower);
        }

        animateFollower();

        // ホバー時の拡大
        const interactiveElements = document.querySelectorAll('a, button, .service-card, .news-card, .blog-preview-card');
        interactiveElements.forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                follower.classList.add('mouse-follower--active');
            });
            el.addEventListener('mouseleave', function() {
                follower.classList.remove('mouse-follower--active');
            });
        });
    }

    /**
     * スクロール進捗バー
     */
    function initScrollProgressBar() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress-bar';
        document.body.appendChild(progressBar);

        function updateProgress() {
            const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = window.pageYOffset;
            const progress = (scrolled / scrollableHeight) * 100;
            progressBar.style.width = Math.min(progress, 100) + '%';
        }

        window.addEventListener('scroll', updateProgress);
        updateProgress();
    }

    /**
     * スクロールアニメーション（要素の表示時にフェードイン）
     */
    function initScrollAnimations() {
        const animateElements = document.querySelectorAll('.animate');

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-inview');
                }
            });
        }, observerOptions);

        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    /**
     * パララックス効果（GSAP ScrollTrigger使用）
     */
    function initParallaxEffects() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

        gsap.registerPlugin(ScrollTrigger);

        // 背景図形のパララックス
        const shapes = document.querySelectorAll('.hero-v2__shape');
        shapes.forEach(function(shape, index) {
            const speed = -2 - (index * 0.5);
            gsap.to(shape, {
                y: () => window.innerHeight * 0.3,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero-v2',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });

        // パーティクルのパララックス
        const particles = document.querySelectorAll('.hero-v2__particles .particle');
        particles.forEach(function(particle) {
            gsap.to(particle, {
                y: () => -window.innerHeight * 0.2,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero-v2',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    // リサイズ時の処理
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        }, 250);
    });

})();
