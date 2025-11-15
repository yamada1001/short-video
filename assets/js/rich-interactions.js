/**
 * Rich Interactions
 * トップページ専用のリッチなインタラクション機能
 */

(function() {
    'use strict';

    let locomotiveScroll;

    // ページロード完了後に初期化
    window.addEventListener('load', function() {
        initLocomotiveScroll();
        initHeroSwiper();
        initMouseFollower();
        initScrollProgress();
        initParallaxShapes();
        initScrollAnimations();
    });

    /**
     * Locomotive Scrollの初期化（スムーススクロール）
     */
    function initLocomotiveScroll() {
        const smoothWrapper = document.getElementById('smooth-wrapper');
        if (!smoothWrapper) return;

        locomotiveScroll = new LocomotiveScroll({
            el: smoothWrapper,
            smooth: true,
            smoothMobile: false, // モバイルでは通常スクロール
            multiplier: 0.8,
            lerp: 0.08,
            smartphone: {
                smooth: false
            },
            tablet: {
                smooth: false
            }
        });

        // ScrollTriggerとの連携
        if (typeof ScrollTrigger !== 'undefined') {
            locomotiveScroll.on('scroll', ScrollTrigger.update);

            ScrollTrigger.scrollerProxy(smoothWrapper, {
                scrollTop(value) {
                    return arguments.length
                        ? locomotiveScroll.scrollTo(value, 0, 0)
                        : locomotiveScroll.scroll.instance.scroll.y;
                },
                getBoundingClientRect() {
                    return {
                        top: 0,
                        left: 0,
                        width: window.innerWidth,
                        height: window.innerHeight
                    };
                },
                pinType: smoothWrapper.style.transform ? 'transform' : 'fixed'
            });

            ScrollTrigger.addEventListener('refresh', () => locomotiveScroll.update());
            ScrollTrigger.refresh();
        }
    }

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
            allowTouchMove: false, // ユーザーのスワイプを無効化（自動再生のみ）
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
            // 大きい円（遅延あり）
            const distX = mouseX - followerX;
            const distY = mouseY - followerY;
            followerX += distX * 0.1;
            followerY += distY * 0.1;
            follower.style.transform = `translate(${followerX}px, ${followerY}px)`;

            // 小さい点（高速）
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
     * スクロール進捗インジケーター
     */
    function initScrollProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress-bar';
        document.body.appendChild(progressBar);

        function updateProgress() {
            const scrollElement = document.getElementById('smooth-wrapper');
            if (!scrollElement) return;

            const scrollableHeight = scrollElement.scrollHeight - window.innerHeight;
            const scrolled = locomotiveScroll
                ? locomotiveScroll.scroll.instance.scroll.y
                : window.pageYOffset;
            const progress = (scrolled / scrollableHeight) * 100;
            progressBar.style.width = progress + '%';
        }

        if (locomotiveScroll) {
            locomotiveScroll.on('scroll', updateProgress);
        } else {
            window.addEventListener('scroll', updateProgress);
        }
    }

    /**
     * パララックス形状アニメーション
     */
    function initParallaxShapes() {
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

    // リサイズ時の処理
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (locomotiveScroll) {
                locomotiveScroll.update();
            }
        }, 250);
    });

    // ページ離脱時のクリーンアップ
    window.addEventListener('beforeunload', function() {
        if (locomotiveScroll) {
            locomotiveScroll.destroy();
        }
    });

})();
