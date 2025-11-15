/**
 * About Page - GSAP Animations
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
            companyInfoAnimation();
            valuesAnimation();
            profileAnimation();
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
     * Company Info Section Animation
     */
    function companyInfoAnimation() {
        const image = document.querySelector('.company-info__image');
        const content = document.querySelector('.company-info__content');
        const table = document.querySelector('.company-table');

        if (image) {
            gsap.from(image, {
                opacity: 0,
                x: -50,
                duration: 1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.company-info',
                    start: 'top 75%',
                    toggleActions: 'play none none none'
                }
            });
        }

        if (content) {
            gsap.from(content, {
                opacity: 0,
                x: 50,
                duration: 1,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.company-info',
                    start: 'top 75%',
                    toggleActions: 'play none none none'
                }
            });
        }

        if (table) {
            const rows = table.querySelectorAll('tr');
            gsap.from(rows, {
                opacity: 0,
                y: 20,
                duration: 0.5,
                stagger: 0.08,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: table,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        }
    }

    /**
     * Values Section Animation
     */
    function valuesAnimation() {
        const valueItems = gsap.utils.toArray('.value-item');

        if (valueItems.length === 0) return;

        gsap.from(valueItems, {
            opacity: 0,
            y: 50,
            duration: 0.8,
            stagger: 0.15,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.values__grid',
                start: 'top 80%',
                toggleActions: 'play none none none'
            }
        });
    }

    /**
     * Profile Section Animation
     */
    function profileAnimation() {
        const profileCard = document.querySelector('.profile__card');

        if (!profileCard) return;

        gsap.from(profileCard, {
            opacity: 0,
            y: 60,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: profileCard,
                start: 'top 75%',
                toggleActions: 'play none none none'
            }
        });

        // Certification cards
        const certCards = gsap.utils.toArray('.certification-card');
        if (certCards.length > 0) {
            gsap.from(certCards, {
                opacity: 0,
                scale: 0.9,
                duration: 0.6,
                stagger: 0.1,
                ease: 'back.out(1.7)',
                scrollTrigger: {
                    trigger: '.certifications__grid',
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        }
    }

})();
