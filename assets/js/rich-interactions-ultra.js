/**
 * Rich Interactions - ULTRA VERSION
 * mikicruise.com レベルのモダンでリッチなインタラクション
 * デバッグ機能付き
 */

(function() {
    'use strict';

    // デバッグログ
    const DEBUG = true;
    function log(message, data = '') {
        if (DEBUG) console.log(`[RICH-ULTRA] ${message}`, data);
    }

    log('Script loaded');

    // DOMContentLoaded で初期化（より確実）
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        log('Initializing...');

        // ライブラリの確認
        checkLibraries();

        // 各機能を初期化
        setTimeout(() => {
            initHeroSwiper();
            initParticleAnimations();
            initMouseFollower();
            initScrollProgressBar();
            initScrollAnimations();
            init3DCards();
            initTypewriterEffect();
            initCountUpAnimation();
            initRippleEffect();
            initParallaxEffects();
            initSectionPinning();
            initMagneticButtons();

            log('All features initialized');
        }, 100);
    }

    /**
     * ライブラリの読み込み確認
     */
    function checkLibraries() {
        log('Checking libraries...');
        log('Swiper available:', typeof Swiper !== 'undefined');
        log('GSAP available:', typeof gsap !== 'undefined');
        log('ScrollTrigger available:', typeof ScrollTrigger !== 'undefined');
    }

    /**
     * 1. ヒーローセクションのスライダー
     */
    function initHeroSwiper() {
        const heroSwiper = document.querySelector('.hero-swiper');
        if (!heroSwiper) {
            log('Hero swiper element not found');
            return;
        }

        if (typeof Swiper === 'undefined') {
            log('Swiper library not loaded');
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
            log('✅ Hero Swiper initialized');
        } catch (e) {
            log('❌ Swiper error:', e);
        }
    }

    /**
     * 2. パーティクルアニメーション
     */
    function initParticleAnimations() {
        const particles = document.querySelectorAll('.hero-v2__particles .particle');

        if (particles.length === 0) {
            log('No particles found');
            return;
        }

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

        log('✅ Particles initialized:', particles.length);
    }

    /**
     * 3. マウスフォロワー（カーソル追従）- より強化
     */
    function initMouseFollower() {
        if (window.innerWidth < 768) {
            log('Mouse follower skipped (mobile)');
            return;
        }

        // 大きい円
        const follower = document.createElement('div');
        follower.className = 'mouse-follower';
        follower.style.cssText = `
            position: fixed;
            width: 40px;
            height: 40px;
            border: 2px solid rgba(139, 117, 97, 0.5);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
            mix-blend-mode: difference;
        `;
        document.body.appendChild(follower);

        // 小さい点
        const followerDot = document.createElement('div');
        followerDot.className = 'mouse-follower-dot';
        followerDot.style.cssText = `
            position: fixed;
            width: 8px;
            height: 8px;
            background-color: rgba(139, 117, 97, 0.9);
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            transform: translate(-50%, -50%);
        `;
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
            followerX += distX * 0.15;
            followerY += distY * 0.15;
            follower.style.left = followerX + 'px';
            follower.style.top = followerY + 'px';

            const dotDistX = mouseX - dotX;
            const dotDistY = mouseY - dotY;
            dotX += dotDistX * 0.4;
            dotY += dotDistY * 0.4;
            followerDot.style.left = dotX + 'px';
            followerDot.style.top = dotY + 'px';

            requestAnimationFrame(animateFollower);
        }

        animateFollower();

        // ホバー時の拡大と色変更
        const interactiveElements = document.querySelectorAll('a, button, .service-card, .news-card, .blog-preview-card');
        interactiveElements.forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                follower.style.width = '60px';
                follower.style.height = '60px';
                follower.style.borderColor = 'rgba(139, 117, 97, 0.8)';
                follower.style.backgroundColor = 'rgba(139, 117, 97, 0.1)';
            });
            el.addEventListener('mouseleave', function() {
                follower.style.width = '40px';
                follower.style.height = '40px';
                follower.style.borderColor = 'rgba(139, 117, 97, 0.5)';
                follower.style.backgroundColor = 'transparent';
            });
        });

        log('✅ Mouse follower initialized');
    }

    /**
     * 4. スクロール進捗バー
     */
    function initScrollProgressBar() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress-bar';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #8B7355, #428570);
            z-index: 9999;
            width: 0%;
            transition: width 0.1s linear;
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

        log('✅ Scroll progress bar initialized');
    }

    /**
     * 5. スクロールアニメーション（フェードイン）
     */
    function initScrollAnimations() {
        const animateElements = document.querySelectorAll('.animate');

        if (animateElements.length === 0) {
            log('No animate elements found');
            return;
        }

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

        log('✅ Scroll animations initialized:', animateElements.length);
    }

    /**
     * 6. 3Dカード変形エフェクト
     */
    function init3DCards() {
        const cards = document.querySelectorAll('.service-card, .news-card, .blog-preview-card');

        if (cards.length === 0) {
            log('No cards found for 3D effect');
            return;
        }

        cards.forEach(function(card) {
            card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';

            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = ((y - centerY) / centerY) * -10;
                const rotateY = ((x - centerX) / centerX) * 10;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
                card.style.boxShadow = '0 20px 50px rgba(0,0,0,0.2)';
            });

            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
                card.style.boxShadow = '';
            });
        });

        log('✅ 3D card effects initialized:', cards.length);
    }

    /**
     * 7. タイプライターエフェクト
     */
    function initTypewriterEffect() {
        const typewriterElements = document.querySelectorAll('[data-typewriter]');

        if (typewriterElements.length === 0) {
            log('No typewriter elements found');
            return;
        }

        typewriterElements.forEach(function(element) {
            const text = element.textContent;
            element.textContent = '';
            element.style.opacity = '1';

            let index = 0;
            const speed = 50;

            function type() {
                if (index < text.length) {
                    element.textContent += text.charAt(index);
                    index++;
                    setTimeout(type, speed);
                }
            }

            // 要素が表示されたら開始
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting && index === 0) {
                        type();
                        observer.unobserve(element);
                    }
                });
            });

            observer.observe(element);
        });

        log('✅ Typewriter effect initialized:', typewriterElements.length);
    }

    /**
     * 8. カウントアップアニメーション
     */
    function initCountUpAnimation() {
        const countElements = document.querySelectorAll('[data-countup]');

        if (countElements.length === 0) {
            log('No countup elements found');
            return;
        }

        countElements.forEach(function(element) {
            const target = parseInt(element.getAttribute('data-countup'));
            const duration = 2000;
            let current = 0;
            let hasStarted = false;

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting && !hasStarted) {
                        hasStarted = true;
                        const increment = target / (duration / 16);

                        function updateCount() {
                            current += increment;
                            if (current < target) {
                                element.textContent = Math.floor(current).toLocaleString();
                                requestAnimationFrame(updateCount);
                            } else {
                                element.textContent = target.toLocaleString();
                            }
                        }

                        updateCount();
                        observer.unobserve(element);
                    }
                });
            });

            observer.observe(element);
        });

        log('✅ Count up animations initialized:', countElements.length);
    }

    /**
     * 9. リップルエフェクト（ボタンクリック時）
     */
    function initRippleEffect() {
        const buttons = document.querySelectorAll('button, .btn, .hero-v2__btn, a[class*="btn"]');

        buttons.forEach(function(button) {
            button.style.position = 'relative';
            button.style.overflow = 'hidden';

            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    left: ${x}px;
                    top: ${y}px;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

                button.appendChild(ripple);

                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });
        });

        // リップルアニメーション定義
        if (!document.getElementById('ripple-animation')) {
            const style = document.createElement('style');
            style.id = 'ripple-animation';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        log('✅ Ripple effect initialized:', buttons.length);
    }

    /**
     * 10. パララックス効果（GSAP）
     */
    function initParallaxEffects() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            log('GSAP or ScrollTrigger not available');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // 背景図形のパララックス
        const shapes = document.querySelectorAll('.hero-v2__shape');
        shapes.forEach(function(shape, index) {
            gsap.to(shape, {
                y: () => window.innerHeight * 0.4,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero-v2',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 1
                }
            });
        });

        // パーティクルのパララックス
        const particles = document.querySelectorAll('.hero-v2__particles .particle');
        particles.forEach(function(particle) {
            gsap.to(particle, {
                y: () => -window.innerHeight * 0.3,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.hero-v2',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 1
                }
            });
        });

        log('✅ Parallax effects initialized');
    }

    /**
     * 11. セクションピン留め
     */
    function initSectionPinning() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            return;
        }

        const sections = document.querySelectorAll('.section[data-pin]');

        sections.forEach(function(section) {
            ScrollTrigger.create({
                trigger: section,
                start: 'top top',
                end: '+=500',
                pin: true,
                pinSpacing: false
            });
        });

        if (sections.length > 0) {
            log('✅ Section pinning initialized:', sections.length);
        }
    }

    /**
     * 12. マグネティックボタン（磁石効果）
     */
    function initMagneticButtons() {
        if (window.innerWidth < 768) return;

        const magneticElements = document.querySelectorAll('.hero-v2__btn, .btn-primary');

        magneticElements.forEach(function(el) {
            el.addEventListener('mousemove', function(e) {
                const rect = el.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                el.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
            });

            el.addEventListener('mouseleave', function() {
                el.style.transform = 'translate(0, 0)';
            });
        });

        if (magneticElements.length > 0) {
            log('✅ Magnetic buttons initialized:', magneticElements.length);
        }
    }

    // リサイズ時の処理
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
                log('ScrollTrigger refreshed');
            }
        }, 250);
    });

    log('Script setup complete');

})();
