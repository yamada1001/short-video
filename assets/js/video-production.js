/**
 * Video Production Page - GSAP Animations
 * Instagram/TikTok-inspired animations using GSAP 3.12.5 + ScrollTrigger
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
            platformCardsAnimation();
            statsAnimation();
            pricingCardsAnimation();
            processTimelineAnimation();
            benefitsAnimation();
            sectionsAnimation();
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
            y: 100,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });

        gsap.to('.page-hero__shape--2', {
            y: -80,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    }

    /**
     * Platform Cards Stagger Animation
     */
    function platformCardsAnimation() {
        const cards = gsap.utils.toArray('.platform-card');

        if (cards.length === 0) return;

        gsap.from(cards, {
            opacity: 0,
            y: 60,
            duration: 0.8,
            stagger: 0.15,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.platforms-grid',
                start: 'top 80%',
                end: 'top 50%',
                toggleActions: 'play none none none'
            }
        });
    }

    /**
     * Stats Section Counter Animation
     */
    function statsAnimation() {
        const statNumbers = gsap.utils.toArray('.stat-item__number');

        if (statNumbers.length === 0) return;

        statNumbers.forEach(stat => {
            gsap.from(stat, {
                opacity: 0,
                scale: 0.5,
                duration: 0.8,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: stat,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Animate stat labels
        const statLabels = gsap.utils.toArray('.stat-item__label');
        statLabels.forEach(label => {
            gsap.from(label, {
                opacity: 0,
                y: 20,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: label,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });
    }

    /**
     * Pricing Cards Stagger Animation
     */
    function pricingCardsAnimation() {
        const cards = gsap.utils.toArray('.pricing-card');

        if (cards.length === 0) return;

        gsap.from(cards, {
            opacity: 0,
            y: 60,
            duration: 0.8,
            stagger: 0.15,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.pricing-grid',
                start: 'top 80%',
                end: 'top 50%',
                toggleActions: 'play none none none'
            }
        });
    }

    /**
     * Process Timeline Animation
     */
    function processTimelineAnimation() {
        const steps = gsap.utils.toArray('.process-step');

        if (steps.length === 0) return;

        steps.forEach((step, index) => {
            // Animate step number
            gsap.from(step.querySelector('.process-step__number'), {
                scale: 0,
                rotation: -180,
                duration: 0.6,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: step,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });

            // Animate step content
            gsap.from(step.querySelector('.process-step__title'), {
                opacity: 0,
                x: -30,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: step,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });

            gsap.from(step.querySelector('.process-step__description'), {
                opacity: 0,
                x: -20,
                duration: 0.6,
                delay: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: step,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });
    }

    /**
     * Benefits Grid Animation
     */
    function benefitsAnimation() {
        const benefits = gsap.utils.toArray('.benefit-card');

        if (benefits.length === 0) return;

        gsap.from(benefits, {
            opacity: 0,
            y: 40,
            duration: 0.7,
            stagger: 0.1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.benefits-grid',
                start: 'top 80%',
                toggleActions: 'play none none none'
            }
        });
    }

    /**
     * Section Fade-in Animations
     */
    function sectionsAnimation() {
        // Section titles fade in
        const sectionTitles = gsap.utils.toArray('.section__subtitle');
        sectionTitles.forEach(title => {
            gsap.from(title, {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: title,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Highlight boxes
        const highlightBoxes = gsap.utils.toArray('.highlight-box');
        highlightBoxes.forEach(box => {
            gsap.from(box, {
                opacity: 0,
                x: -30,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: box,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });
    }

})();
