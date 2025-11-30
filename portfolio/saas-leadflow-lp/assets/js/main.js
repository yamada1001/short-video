/**
 * LeadFlow - SaaS LP JavaScript
 * Rich Animations & Interactions
 */

(function() {
    'use strict';

    // ========================================
    // Scroll Animations
    // ========================================
    function initScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        const animateElements = document.querySelectorAll('.feature-card, .function-item, .stat-card, .testimonial-card, .pricing-card');
        animateElements.forEach(el => {
            el.classList.add('scroll-animate');
            observer.observe(el);
        });
    }

    // ========================================
    // Header Scroll Effect
    // ========================================
    function initHeaderScroll() {
        const header = document.querySelector('.header');
        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.style.boxShadow = '0 4px 16px rgba(0, 0, 0, 0.08)';
            } else {
                header.style.boxShadow = 'none';
            }

            lastScroll = currentScroll;
        });
    }

    // ========================================
    // Smooth Scroll
    // ========================================
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#cta') {
                    e.preventDefault();
                    const target = document.querySelector(href === '#' ? 'body' : href);
                    if (target) {
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }

    // ========================================
    // FAQ Accordion
    // ========================================
    function initFaqAccordion() {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-item__question');

            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                // Close all items
                faqItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    }

    // ========================================
    // Parallax Effect
    // ========================================
    function initParallax() {
        const heroBackground = document.querySelector('.hero__background');
        const ctaBackground = document.querySelector('.cta__background');

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;

            if (heroBackground) {
                heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
            }

            if (ctaBackground) {
                const ctaSection = document.querySelector('.cta');
                const ctaTop = ctaSection.offsetTop;
                const ctaScroll = scrolled - ctaTop;
                if (ctaScroll > -window.innerHeight && ctaScroll < window.innerHeight) {
                    ctaBackground.style.transform = `translateY(${ctaScroll * 0.3}px)`;
                }
            }
        });
    }

    // ========================================
    // Mockup 3D Effect
    // ========================================
    function initMockup3D() {
        const mockup = document.querySelector('.mockup__window');
        if (!mockup) return;

        const mockupContainer = mockup.closest('.mockup');

        mockupContainer.addEventListener('mousemove', (e) => {
            const rect = mockupContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 20;
            const rotateY = -(x - centerX) / 20;

            mockup.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        mockupContainer.addEventListener('mouseleave', () => {
            mockup.style.transform = 'perspective(1000px) rotateY(-8deg) rotateX(5deg)';
        });
    }

    // ========================================
    // Gradient Animation
    // ========================================
    function initGradientAnimation() {
        const gradientElements = document.querySelectorAll('.gradient-text, .btn--primary');

        gradientElements.forEach(el => {
            let hue = 0;

            setInterval(() => {
                hue = (hue + 1) % 360;
                if (el.classList.contains('gradient-text')) {
                    const lightness = 50 + Math.sin(hue / 60) * 10;
                    el.style.backgroundImage = `linear-gradient(135deg, hsl(${260 + hue * 0.2}, 80%, ${lightness}%) 0%, hsl(${320 + hue * 0.2}, 80%, ${lightness}%) 100%)`;
                    el.style.webkitBackgroundClip = 'text';
                    el.style.backgroundClip = 'text';
                }
            }, 100);
        });
    }

    // ========================================
    // CTA Form Handler
    // ========================================
    function initCTAForm() {
        const form = document.querySelector('.cta__form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const input = form.querySelector('.cta__input');
            const email = input.value;

            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (emailRegex.test(email)) {
                alert(`ありがとうございます！ ${email} 宛に確認メールを送信しました。`);
                input.value = '';
            } else {
                alert('有効なメールアドレスを入力してください。');
            }
        });
    }

    // ========================================
    // Floating Animation
    // ========================================
    function initFloatingAnimation() {
        const mockup = document.querySelector('.mockup__window');
        if (!mockup) return;

        let floatDirection = 1;
        let floatPosition = 0;

        function float() {
            floatPosition += floatDirection * 0.5;

            if (floatPosition > 10) floatDirection = -1;
            if (floatPosition < -10) floatDirection = 1;

            if (!mockup.matches(':hover')) {
                mockup.style.transform = `perspective(1000px) rotateY(-8deg) rotateX(5deg) translateY(${floatPosition}px)`;
            }

            requestAnimationFrame(float);
        }

        float();
    }

    // ========================================
    // Number Counter Animation
    // ========================================
    function initNumberCounters() {
        const counters = document.querySelectorAll('.stat-card__number');
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.counted) {
                    animateCounter(entry.target);
                    entry.target.dataset.counted = 'true';
                }
            });
        }, observerOptions);

        counters.forEach(counter => observer.observe(counter));

        function animateCounter(element) {
            const text = element.textContent;
            const hasPlus = text.includes('+');
            const hasPercent = text.includes('%');
            const number = parseInt(text.replace(/[^0-9]/g, ''));

            if (isNaN(number)) return;

            const duration = 2000;
            const steps = 60;
            const increment = number / steps;
            let current = 0;
            let step = 0;

            const timer = setInterval(() => {
                current += increment;
                step++;

                let displayValue = Math.floor(current);
                let displayText = displayValue.toLocaleString();

                if (hasPlus) displayText += '+';
                if (hasPercent) displayText += '%';
                if (text.includes('日')) displayText += '日';

                element.textContent = displayText;

                if (step >= steps) {
                    clearInterval(timer);
                    element.textContent = text; // Restore original text
                }
            }, duration / steps);
        }
    }

    // ========================================
    // Feature Card Tilt Effect
    // ========================================
    function initFeatureCardTilt() {
        const cards = document.querySelectorAll('.feature-card, .pricing-card');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 30;
                const rotateY = -(x - centerX) / 30;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });
    }

    // ========================================
    // Mobile Menu Toggle
    // ========================================
    function initMobileMenu() {
        const menuBtn = document.querySelector('.header__menu-btn');
        const nav = document.querySelector('.header__nav');

        if (!menuBtn || !nav) return;

        menuBtn.addEventListener('click', () => {
            nav.classList.toggle('active');
            menuBtn.classList.toggle('active');
        });

        // Close menu when clicking nav links
        const navLinks = nav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('active');
                menuBtn.classList.remove('active');
            });
        });
    }

    // ========================================
    // Testimonial Rotation
    // ========================================
    function initTestimonialRotation() {
        const testimonials = document.querySelectorAll('.testimonial-card');
        let currentIndex = 0;

        function highlightTestimonial() {
            testimonials.forEach((card, index) => {
                if (index === currentIndex) {
                    card.style.transform = 'translateY(-4px) scale(1.02)';
                    card.style.boxShadow = '0 8px 32px rgba(139, 92, 246, 0.2)';
                } else {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                }
            });

            currentIndex = (currentIndex + 1) % testimonials.length;
        }

        // Rotate every 5 seconds
        setInterval(highlightTestimonial, 5000);
    }

    // ========================================
    // Initialize All
    // ========================================
    function init() {
        initScrollAnimations();
        initHeaderScroll();
        initSmoothScroll();
        initFaqAccordion();
        initParallax();
        initMockup3D();
        initCTAForm();
        initFloatingAnimation();
        initNumberCounters();
        initFeatureCardTilt();
        initMobileMenu();
        initTestimonialRotation();

        // Optional: Gradient animation (can be CPU intensive)
        // initGradientAnimation();
    }

    // Run on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
