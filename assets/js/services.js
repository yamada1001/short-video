/**
 * Services Page - GSAP Animations
 * MUJI-inspired subtle animations using GSAP 3.12.5 + ScrollTrigger
 */

(function() {
    'use strict';

    // Add .js-enabled class to enable CSS animations
    document.documentElement.classList.add('js-enabled');

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnimations);
    } else {
        initAnimations();
    }

    function initAnimations() {
        // Register ScrollTrigger plugin
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // Initialize all animations
            heroAnimation();
            parallaxShapes();
            serviceCardsAnimation();
        }
    }

    /**
     * Hero Section Animation
     * Fade in label, title, and description sequentially
     */
    function heroAnimation() {
        const timeline = gsap.timeline({
            defaults: {
                ease: 'power2.out',
                duration: 1.2
            }
        });

        timeline
            .from('.page-hero__label', {
                opacity: 0,
                y: 30,
                duration: 0.8
            })
            .from('.page-hero__title', {
                opacity: 0,
                y: 40,
                duration: 1
            }, '-=0.4')
            .from('.page-hero__description', {
                opacity: 0,
                y: 30,
                duration: 0.9
            }, '-=0.6');
    }

    /**
     * Parallax effect on background shapes
     */
    function parallaxShapes() {
        gsap.to('.page-hero__shape--1', {
            y: 80,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });

        gsap.to('.page-hero__shape--2', {
            y: -60,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    }

    /**
     * Service Cards Stagger Animation
     */
    function serviceCardsAnimation() {
        const cards = gsap.utils.toArray('.service-card');

        if (cards.length === 0) return;

        // Animate each card
        cards.forEach((card, index) => {
            // Card container
            gsap.from(card, {
                opacity: 0,
                y: 80,
                duration: 1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: card,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });

            // Card header (icon, title, subtitle)
            const header = card.querySelector('.service-card__header');
            if (header) {
                gsap.from(header.querySelector('.service-card__icon'), {
                    scale: 0,
                    rotation: -180,
                    duration: 0.8,
                    ease: 'back.out(1.7)',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 75%',
                        toggleActions: 'play none none none'
                    }
                });

                gsap.from(header.querySelector('.service-card__title'), {
                    opacity: 0,
                    y: 20,
                    duration: 0.6,
                    delay: 0.2,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 75%',
                        toggleActions: 'play none none none'
                    }
                });

                gsap.from(header.querySelector('.service-card__subtitle'), {
                    opacity: 0,
                    y: 15,
                    duration: 0.5,
                    delay: 0.3,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 75%',
                        toggleActions: 'play none none none'
                    }
                });
            }

            // Card content (description, features, CTA)
            const content = card.querySelector('.service-card__content');
            if (content) {
                gsap.from(content.querySelector('.service-card__description'), {
                    opacity: 0,
                    y: 20,
                    duration: 0.6,
                    delay: 0.4,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 75%',
                        toggleActions: 'play none none none'
                    }
                });

                // Feature items stagger
                const features = content.querySelectorAll('.service-card__features li');
                if (features.length > 0) {
                    gsap.from(features, {
                        opacity: 0,
                        x: -20,
                        duration: 0.5,
                        stagger: 0.08,
                        delay: 0.5,
                        ease: 'power2.out',
                        scrollTrigger: {
                            trigger: card,
                            start: 'top 75%',
                            toggleActions: 'play none none none'
                        }
                    });
                }

                // CTA button
                const cta = content.querySelector('.service-card__link');
                if (cta) {
                    gsap.from(cta, {
                        opacity: 0,
                        y: 20,
                        duration: 0.6,
                        delay: 0.7,
                        ease: 'power2.out',
                        scrollTrigger: {
                            trigger: card,
                            start: 'top 75%',
                            toggleActions: 'play none none none'
                        }
                    });
                }
            }
        });
    }

})();
