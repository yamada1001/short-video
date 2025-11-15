/**
 * Rich Interactions - Branding Version
 * ブランディングサイト用のリッチインタラクション
 * デバッグログ付きで動作確認可能
 */

(function() {
    'use strict';

    console.log('🚀 Rich Interactions - Branding Version 起動');

    // 初期化フラグ
    const initStatus = {
        swiper: false,
        mouseFollower: false,
        scrollProgress: false,
        scrollAnimations: false,
        parallax: false
    };

    // ページロード完了後に初期化
    window.addEventListener('load', function() {
        console.log('📄 ページロード完了');

        initHeroSwiper();
        initMouseFollower();
        initScrollProgressBar();
        initScrollAnimations();
        initParallaxEffects();
        initSmoothLinks();

        // 初期化ステータスを表示
        setTimeout(function() {
            console.log('📊 初期化ステータス:', initStatus);
        }, 1000);
    });

    /**
     * ヒーローセクションのスライダー初期化
     */
    function initHeroSwiper() {
        const heroSwiper = document.querySelector('.hero-swiper');
        if (!heroSwiper) {
            console.log('⚠️ ヒーロースライダーが見つかりません');
            return;
        }

        try {
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
            initStatus.swiper = true;
            console.log('✅ ヒーロースライダー初期化成功');
        } catch (error) {
            console.error('❌ ヒーロースライダー初期化エラー:', error);
        }
    }

    /**
     * マウスフォロワー（カーソル追従エフェクト）
     */
    function initMouseFollower() {
        // モバイルではスキップ
        if (window.innerWidth < 768) {
            console.log('📱 モバイルデバイスのためマウスフォロワーをスキップ');
            return;
        }

        try {
            // メインフォロワー（大きい円）
            const follower = document.createElement('div');
            follower.className = 'mouse-follower';
            follower.style.cssText = `
                position: fixed;
                width: 40px;
                height: 40px;
                border: 2px solid rgba(139, 115, 85, 0.4);
                border-radius: 50%;
                pointer-events: none;
                z-index: 9999;
                transition: transform 0.15s ease-out, width 0.3s ease, height 0.3s ease, border-color 0.3s ease;
                left: -50px;
                top: -50px;
            `;
            document.body.appendChild(follower);

            // ドット（小さい円）
            const followerDot = document.createElement('div');
            followerDot.className = 'mouse-follower-dot';
            followerDot.style.cssText = `
                position: fixed;
                width: 8px;
                height: 8px;
                background-color: rgba(139, 115, 85, 0.8);
                border-radius: 50%;
                pointer-events: none;
                z-index: 10000;
                transition: transform 0.1s ease-out, background-color 0.3s ease;
                left: -50px;
                top: -50px;
            `;
            document.body.appendChild(followerDot);

            let mouseX = 0, mouseY = 0;
            let followerX = 0, followerY = 0;
            let dotX = 0, dotY = 0;
            let isVisible = false;

            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;

                if (!isVisible) {
                    isVisible = true;
                    console.log('👆 マウスフォロワー表示');
                }
            });

            function animateFollower() {
                // メインフォロワーの位置更新（遅延あり）
                const distX = mouseX - followerX;
                const distY = mouseY - followerY;
                followerX += distX * 0.15;
                followerY += distY * 0.15;
                follower.style.transform = `translate(${followerX - 20}px, ${followerY - 20}px)`;

                // ドットの位置更新（速い追従）
                const dotDistX = mouseX - dotX;
                const dotDistY = mouseY - dotY;
                dotX += dotDistX * 0.4;
                dotY += dotDistY * 0.4;
                followerDot.style.transform = `translate(${dotX - 4}px, ${dotY - 4}px)`;

                requestAnimationFrame(animateFollower);
            }

            animateFollower();

            // ホバー時の拡大
            const interactiveElements = document.querySelectorAll('a, button, .service-card, .news-card, .blog-preview-card, .category-filter-btn');
            console.log(`🎯 インタラクティブ要素: ${interactiveElements.length}個`);

            interactiveElements.forEach(function(el) {
                el.addEventListener('mouseenter', function() {
                    follower.style.width = '60px';
                    follower.style.height = '60px';
                    follower.style.borderColor = 'rgba(139, 115, 85, 0.6)';
                    followerDot.style.backgroundColor = 'rgba(139, 115, 85, 1)';
                });
                el.addEventListener('mouseleave', function() {
                    follower.style.width = '40px';
                    follower.style.height = '40px';
                    follower.style.borderColor = 'rgba(139, 115, 85, 0.4)';
                    followerDot.style.backgroundColor = 'rgba(139, 115, 85, 0.8)';
                });
            });

            initStatus.mouseFollower = true;
            console.log('✅ マウスフォロワー初期化成功');
        } catch (error) {
            console.error('❌ マウスフォロワー初期化エラー:', error);
        }
    }

    /**
     * スクロール進捗バー
     */
    function initScrollProgressBar() {
        try {
            const progressBar = document.createElement('div');
            progressBar.className = 'scroll-progress-bar';
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #8B7355, #428570);
                width: 0%;
                z-index: 9998;
                transition: width 0.1s ease-out;
            `;
            document.body.appendChild(progressBar);

            function updateProgress() {
                const scrollableHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrolled = window.pageYOffset;
                const progress = (scrolled / scrollableHeight) * 100;
                progressBar.style.width = Math.min(progress, 100) + '%';
            }

            window.addEventListener('scroll', updateProgress);
            updateProgress();

            initStatus.scrollProgress = true;
            console.log('✅ スクロール進捗バー初期化成功');
        } catch (error) {
            console.error('❌ スクロール進捗バー初期化エラー:', error);
        }
    }

    /**
     * スクロールアニメーション（要素の表示時にフェードイン）
     */
    function initScrollAnimations() {
        const animateElements = document.querySelectorAll('.animate');
        console.log(`🎬 アニメーション対象要素: ${animateElements.length}個`);

        if (animateElements.length === 0) {
            console.log('⚠️ .animate クラスを持つ要素が見つかりません');
            return;
        }

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -80px 0px'
        };

        let visibleCount = 0;

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !entry.target.classList.contains('is-inview')) {
                    entry.target.classList.add('is-inview');
                    visibleCount++;
                    console.log(`👁️ 要素が表示されました (${visibleCount}/${animateElements.length})`);
                }
            });
        }, observerOptions);

        animateElements.forEach(function(el) {
            observer.observe(el);
        });

        initStatus.scrollAnimations = true;
        console.log('✅ スクロールアニメーション初期化成功');
    }

    /**
     * パララックス効果（GSAP ScrollTrigger使用）
     */
    function initParallaxEffects() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.log('⚠️ GSAP または ScrollTrigger が読み込まれていません');
            return;
        }

        try {
            gsap.registerPlugin(ScrollTrigger);

            // セクションヘッダーのパララックス
            const sectionHeaders = document.querySelectorAll('.section-header');
            sectionHeaders.forEach(function(header) {
                gsap.to(header, {
                    y: 50,
                    opacity: 0.8,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: header,
                        start: 'top 20%',
                        end: 'bottom top',
                        scrub: 1
                    }
                });
            });

            // サービスカードのパララックス
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(function(card, index) {
                const speed = 1 + (index * 0.2);
                gsap.to(card, {
                    y: -30 * speed,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: 1
                    }
                });
            });

            initStatus.parallax = true;
            console.log('✅ パララックス効果初期化成功');
        } catch (error) {
            console.error('❌ パララックス効果初期化エラー:', error);
        }
    }

    /**
     * スムーズスクロールリンク
     */
    function initSmoothLinks() {
        const smoothLinks = document.querySelectorAll('a[href^="#"]');

        smoothLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#top') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const offsetTop = target.offsetTop - 80; // ヘッダー分の余白
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    console.log(`🔗 スムーズスクロール: ${href}`);
                }
            });
        });

        console.log(`✅ スムーズスクロールリンク初期化成功 (${smoothLinks.length}個)`);
    }

    /**
     * リサイズ時の処理
     */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
                console.log('🔄 ScrollTrigger リフレッシュ');
            }
        }, 250);
    });

    // ページ離脱時のログ
    window.addEventListener('beforeunload', function() {
        console.log('👋 ページを離脱します');
    });

})();
