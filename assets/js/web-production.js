/**
 * Web Production Page - GSAP Animations
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
            pricingCardsAnimation();
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
     * Section Fade-in Animations
     */
    function sectionsAnimation() {
        // Story Section
        gsap.from('.story-content', {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.story-section',
                start: 'top 75%',
                toggleActions: 'play none none none'
            }
        });

        // Team roles grid
        const teamRoles = gsap.utils.toArray('.team-role');
        if (teamRoles.length > 0) {
            gsap.from(teamRoles, {
                opacity: 0,
                y: 40,
                duration: 0.7,
                stagger: 0.1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.team-roles',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        }

        // Portfolio items
        const portfolioItems = gsap.utils.toArray('.portfolio-item');
        if (portfolioItems.length > 0) {
            gsap.from(portfolioItems, {
                opacity: 0,
                y: 50,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.portfolio-grid',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        }

        // Section titles fade in
        const sectionTitles = gsap.utils.toArray('.section__subtitle, .story-section__title, .team-section__title, .portfolio-section__title');
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
