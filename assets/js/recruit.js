/**
 * Recruit Page - GSAP Animations
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
            missionCardsAnimation();
            sectionsAnimation();
        }
    }

    /**
     * Hero Section Animation
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
            y: 60,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });

        gsap.to('.page-hero__shape--2', {
            y: -40,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    }

    /**
     * Mission Cards Animation
     */
    function missionCardsAnimation() {
        const missionCards = gsap.utils.toArray('.mission-card');

        if (missionCards.length === 0) return;

        missionCards.forEach((card, index) => {
            gsap.from(card, {
                opacity: 0,
                y: 60,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: card,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });

            // Animate table rows within the card
            const table = card.querySelector('.company-table');
            if (table) {
                const rows = table.querySelectorAll('tr');
                gsap.from(rows, {
                    opacity: 0,
                    x: -20,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: table,
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    }
                });
            }
        });
    }

    /**
     * Section Fade-in Animations
     */
    function sectionsAnimation() {
        // Section titles
        const sectionTitles = gsap.utils.toArray('.section__title');
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

        // Section descriptions
        const sectionDescs = gsap.utils.toArray('.section__description');
        sectionDescs.forEach(desc => {
            gsap.from(desc, {
                opacity: 0,
                y: 20,
                duration: 0.7,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: desc,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });
    }

})();
