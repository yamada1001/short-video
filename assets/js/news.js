/**
 * News Pages - GSAP Animations
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
            newsListAnimation();
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
            y: 50,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });

        gsap.to('.page-hero__shape--2', {
            y: -30,
            scrollTrigger: {
                trigger: '.page-hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    }

    /**
     * News List Items Animation
     */
    function newsListAnimation() {
        const newsItems = gsap.utils.toArray('.news-list-item');

        if (newsItems.length === 0) return;

        newsItems.forEach((item, index) => {
            gsap.from(item, {
                opacity: 0,
                x: -30,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: item,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });

        // Pagination animation
        const pagination = document.querySelector('.pagination');
        if (pagination) {
            gsap.from(pagination, {
                opacity: 0,
                y: 20,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: pagination,
                    start: 'top 90%',
                    toggleActions: 'play none none none'
                }
            });
        }
    }

})();
