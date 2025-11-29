/**
 * Modern Rich Interactions
 * ========================
 * モダンでリッチなインタラクションを提供するJavaScript
 * - 3Dチルトエフェクト
 * - 磁気ホバー効果
 * - スムーズスクロールアニメーション
 * - パララックス効果
 * - テキストスプリット・アニメーション
 */

(function() {
    'use strict';

    // デバイスチェック
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // アニメーションをスキップする場合
    if (isReducedMotion) {
        document.querySelectorAll('.animate').forEach(el => {
            el.classList.add('is-inview');
        });
        return;
    }

    /**
     * 3Dチルトエフェクト
     * カードにマウスを乗せると3D的に傾く効果
     */
    class TiltEffect {
        constructor(element, options = {}) {
            this.element = element;
            this.options = {
                maxTilt: options.maxTilt || 10,
                scale: options.scale || 1.02,
                speed: options.speed || 400,
                glare: options.glare || true,
                ...options
            };

            this.init();
        }

        init() {
            if (isMobile) return;

            // グレア用の要素を追加
            if (this.options.glare) {
                const glare = document.createElement('div');
                glare.className = 'tilt-glare';
                glare.innerHTML = '<div class="tilt-glare-inner"></div>';
                glare.style.cssText = `
                    position: absolute;
                    inset: 0;
                    overflow: hidden;
                    pointer-events: none;
                    border-radius: inherit;
                `;
                glare.querySelector('.tilt-glare-inner').style.cssText = `
                    position: absolute;
                    width: 200%;
                    height: 200%;
                    background: linear-gradient(
                        45deg,
                        rgba(255, 255, 255, 0) 0%,
                        rgba(255, 255, 255, 0.3) 50%,
                        rgba(255, 255, 255, 0) 100%
                    );
                    transform: rotate(45deg) translate(-50%, -50%);
                    opacity: 0;
                    transition: opacity 0.3s ease;
                `;
                this.element.style.position = 'relative';
                this.element.appendChild(glare);
                this.glareElement = glare.querySelector('.tilt-glare-inner');
            }

            this.element.style.transition = `transform ${this.options.speed}ms cubic-bezier(0.16, 1, 0.3, 1)`;
            this.element.style.transformStyle = 'preserve-3d';

            this.element.addEventListener('mousemove', this.onMouseMove.bind(this));
            this.element.addEventListener('mouseleave', this.onMouseLeave.bind(this));
            this.element.addEventListener('mouseenter', this.onMouseEnter.bind(this));
        }

        onMouseMove(e) {
            const rect = this.element.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            const tiltX = (this.options.maxTilt * 2 * (0.5 - y)).toFixed(2);
            const tiltY = (this.options.maxTilt * 2 * (x - 0.5)).toFixed(2);

            this.element.style.transform = `
                perspective(1000px)
                rotateX(${tiltX}deg)
                rotateY(${tiltY}deg)
                scale3d(${this.options.scale}, ${this.options.scale}, ${this.options.scale})
            `;

            if (this.glareElement) {
                const glareX = x * 100;
                const glareY = y * 100;
                this.glareElement.style.transform = `
                    translate(${glareX - 50}%, ${glareY - 50}%)
                    rotate(45deg)
                `;
            }
        }

        onMouseEnter() {
            if (this.glareElement) {
                this.glareElement.style.opacity = '1';
            }
        }

        onMouseLeave() {
            this.element.style.transform = `
                perspective(1000px)
                rotateX(0deg)
                rotateY(0deg)
                scale3d(1, 1, 1)
            `;

            if (this.glareElement) {
                this.glareElement.style.opacity = '0';
            }
        }
    }

    /**
     * 磁気ホバー効果
     * ボタンがマウスに引き寄せられるような効果
     */
    class MagneticEffect {
        constructor(element, options = {}) {
            this.element = element;
            this.options = {
                strength: options.strength || 30,
                distance: options.distance || 100,
                ...options
            };

            this.init();
        }

        init() {
            if (isMobile) return;

            this.element.style.transition = 'transform 0.3s cubic-bezier(0.16, 1, 0.3, 1)';

            document.addEventListener('mousemove', this.onMouseMove.bind(this));
        }

        onMouseMove(e) {
            const rect = this.element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            const deltaX = e.clientX - centerX;
            const deltaY = e.clientY - centerY;
            const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

            if (distance < this.options.distance) {
                const factor = 1 - (distance / this.options.distance);
                const moveX = deltaX * factor * (this.options.strength / 100);
                const moveY = deltaY * factor * (this.options.strength / 100);

                this.element.style.transform = `translate(${moveX}px, ${moveY}px)`;
            } else {
                this.element.style.transform = 'translate(0, 0)';
            }
        }
    }

    /**
     * スクロールアニメーション
     * 要素がビューポートに入ったらアニメーション
     */
    class ScrollAnimator {
        constructor() {
            this.elements = document.querySelectorAll('.animate, .animate-slide-left, .animate-slide-right, .animate-scale, .animate-rotate');
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                const options = {
                    threshold: 0.15,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // 遅延を追加
                            const delay = entry.target.dataset.delay || 0;
                            setTimeout(() => {
                                entry.target.classList.add('is-inview');
                            }, delay);
                            observer.unobserve(entry.target);
                        }
                    });
                }, options);

                this.elements.forEach(el => observer.observe(el));
            } else {
                // フォールバック：すべて表示
                this.elements.forEach(el => el.classList.add('is-inview'));
            }
        }
    }

    /**
     * パララックス効果
     */
    class ParallaxEffect {
        constructor() {
            this.elements = document.querySelectorAll('[data-parallax]');
            this.init();
        }

        init() {
            if (isMobile || this.elements.length === 0) return;

            this.onScroll = this.onScroll.bind(this);
            window.addEventListener('scroll', this.throttle(this.onScroll, 16), { passive: true });
            this.onScroll();
        }

        onScroll() {
            const scrollY = window.pageYOffset;

            this.elements.forEach(el => {
                const speed = parseFloat(el.dataset.parallax) || 0.5;
                const rect = el.getBoundingClientRect();
                const visible = rect.top < window.innerHeight && rect.bottom > 0;

                if (visible) {
                    const offset = (scrollY - el.offsetTop) * speed;
                    el.style.transform = `translateY(${offset}px)`;
                }
            });
        }

        throttle(func, limit) {
            let inThrottle;
            return function() {
                if (!inThrottle) {
                    func.apply(this, arguments);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    }

    /**
     * テキストスプリットアニメーション
     */
    class TextSplitAnimation {
        constructor(element) {
            this.element = element;
            this.text = element.textContent;
            this.init();
        }

        init() {
            // テキストを文字ごとに分割
            const chars = this.text.split('');
            this.element.textContent = '';
            this.element.style.opacity = '1';

            chars.forEach((char, index) => {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char;
                span.style.cssText = `
                    display: inline-block;
                    opacity: 0;
                    transform: translateY(30px);
                    transition: opacity 0.5s ease, transform 0.5s ease;
                    transition-delay: ${index * 0.03}s;
                `;
                this.element.appendChild(span);
            });

            // Intersection Observerで監視
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const spans = this.element.querySelectorAll('span');
                        spans.forEach(span => {
                            span.style.opacity = '1';
                            span.style.transform = 'translateY(0)';
                        });
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(this.element);
        }
    }

    /**
     * スムーズカウンターアニメーション
     */
    class CounterAnimation {
        constructor(element) {
            this.element = element;
            this.target = parseInt(element.dataset.count) || parseInt(element.textContent) || 0;
            this.duration = parseInt(element.dataset.duration) || 2000;
            this.init();
        }

        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animate();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(this.element);
        }

        animate() {
            const start = 0;
            const startTime = performance.now();

            const update = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / this.duration, 1);

                // イージング関数（easeOutExpo）
                const eased = 1 - Math.pow(2, -10 * progress);
                const current = Math.floor(start + (this.target - start) * eased);

                this.element.textContent = current.toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            };

            requestAnimationFrame(update);
        }
    }

    /**
     * カーソルトレイル効果
     */
    class CursorTrail {
        constructor() {
            if (isMobile) return;

            this.trails = [];
            this.trailCount = 5;
            this.init();
        }

        init() {
            for (let i = 0; i < this.trailCount; i++) {
                const trail = document.createElement('div');
                trail.className = 'cursor-trail';
                trail.style.cssText = `
                    position: fixed;
                    width: ${8 - i}px;
                    height: ${8 - i}px;
                    background: rgba(139, 115, 85, ${0.3 - i * 0.05});
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 9998;
                    transform: translate(-50%, -50%);
                    transition: transform ${0.1 + i * 0.03}s ease;
                `;
                document.body.appendChild(trail);
                this.trails.push(trail);
            }

            document.addEventListener('mousemove', this.onMouseMove.bind(this));
        }

        onMouseMove(e) {
            this.trails.forEach((trail, index) => {
                setTimeout(() => {
                    trail.style.left = e.clientX + 'px';
                    trail.style.top = e.clientY + 'px';
                }, index * 50);
            });
        }
    }

    /**
     * 初期化
     */
    document.addEventListener('DOMContentLoaded', () => {
        // js-enabled クラスを追加
        document.documentElement.classList.add('js-enabled');

        // スクロールアニメーション
        new ScrollAnimator();

        // パララックス
        new ParallaxEffect();

        // 3Dチルト効果をサービスカードに適用
        document.querySelectorAll('.service-card').forEach(card => {
            new TiltEffect(card, { maxTilt: 8, scale: 1.03 });
        });

        // 3Dチルト効果をブログカードに適用
        document.querySelectorAll('.blog-preview-card').forEach(card => {
            new TiltEffect(card, { maxTilt: 6, scale: 1.02 });
        });

        // 磁気効果をボタンに適用
        document.querySelectorAll('.hero-v2__btn, .btn-primary, .cta-btn').forEach(btn => {
            new MagneticEffect(btn, { strength: 25 });
        });

        // テキストスプリットアニメーション
        document.querySelectorAll('[data-split-text]').forEach(el => {
            new TextSplitAnimation(el);
        });

        // カウンターアニメーション
        document.querySelectorAll('[data-count]').forEach(el => {
            new CounterAnimation(el);
        });

        // カーソルトレイル（オプション - PCのみ）
        // new CursorTrail();

        // スクロール進捗バー
        const progressBar = document.querySelector('.scroll-progress-bar');
        if (progressBar) {
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = (scrollTop / docHeight) * 100;
                progressBar.style.width = `${progress}%`;
            }, { passive: true });
        }

        // 画像のレイジーロード強化
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.add('loaded');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    });

    // ページロード時のアニメーション
    window.addEventListener('load', () => {
        // ローダーを非表示
        const loader = document.getElementById('pageLoader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }

        // 初期表示要素のアニメーション
        setTimeout(() => {
            document.querySelectorAll('.hero-v2 .animate').forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('is-inview');
                }, index * 100);
            });
        }, 300);
    });

})();
