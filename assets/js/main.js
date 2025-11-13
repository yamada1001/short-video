// 統合JSファイル - パフォーマンス最適化のため統合

// ===== nav.js =====
(function() {
    'use strict';

    const header = document.querySelector('.header');
    const menuButton = document.getElementById('menuButton');
    const navMenu = document.getElementById('navMenu');
    const navOverlay = document.getElementById('navOverlay');
    const navLinks = document.querySelectorAll('.nav__link');

    let lastScrollY = window.pageYOffset;
    let scrollTimer;

    function handleScroll() {
        const currentScrollY = window.pageYOffset;

        if (header) {
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
        }

        lastScrollY = currentScrollY;

        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(function() {
            if (header) {
                header.style.transform = 'translateY(0)';
            }
        }, 1000);
    }

    function toggleMenu() {
        if (navMenu && navOverlay) {
            const isOpen = navMenu.classList.contains('nav--open');
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }
    }

    function openMenu() {
        if (navMenu && navOverlay) {
            navMenu.classList.add('nav--open');
            navOverlay.classList.add('nav__overlay--visible');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMenu() {
        if (navMenu && navOverlay) {
            navMenu.classList.remove('nav--open');
            navOverlay.classList.remove('nav__overlay--visible');
            document.body.style.overflow = '';
        }
    }

    if (menuButton) {
        menuButton.addEventListener('click', toggleMenu);
    }

    if (navOverlay) {
        navOverlay.addEventListener('click', closeMenu);
    }

    navLinks.forEach(function(link) {
        link.addEventListener('click', closeMenu);
    });

    window.addEventListener('scroll', handleScroll, { passive: true });
})();

// ===== common.js =====
(function() {
    'use strict';

    const animateElements = document.querySelectorAll('.animate');

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animateElements.forEach(function(element) {
        observer.observe(element);
    });
})();

// ===== external-links.js =====
(function() {
    'use strict';

    const externalLinks = document.querySelectorAll('a[href^="http"]:not([href*="yojitu.com"])');

    externalLinks.forEach(function(link) {
        if (!link.hasAttribute('target')) {
            link.setAttribute('target', '_blank');
        }
        if (!link.hasAttribute('rel')) {
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });
})();

// ===== cookie-consent.js =====
(function() {
    'use strict';

    const cookieConsent = document.getElementById('cookieConsent');
    const acceptButton = document.getElementById('acceptCookies');
    const declineButton = document.getElementById('declineCookies');

    const COOKIE_NAME = 'cookie_consent';
    const COOKIE_EXPIRES = 365;

    function getCookie(name) {
        const value = '; ' + document.cookie;
        const parts = value.split('; ' + name + '=');
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }

    function showConsent() {
        if (cookieConsent) {
            setTimeout(function() {
                cookieConsent.style.display = 'block';
                setTimeout(function() {
                    cookieConsent.classList.add('cookie-consent--visible');
                }, 10);
            }, 1000);
        }
    }

    function hideConsent() {
        if (cookieConsent) {
            cookieConsent.classList.remove('cookie-consent--visible');
            setTimeout(function() {
                cookieConsent.style.display = 'none';
            }, 300);
        }
    }

    function acceptCookies() {
        setCookie(COOKIE_NAME, 'accepted', COOKIE_EXPIRES);
        hideConsent();
    }

    function declineCookies() {
        setCookie(COOKIE_NAME, 'declined', COOKIE_EXPIRES);
        hideConsent();
    }

    if (acceptButton) {
        acceptButton.addEventListener('click', acceptCookies);
    }

    if (declineButton) {
        declineButton.addEventListener('click', declineCookies);
    }

    const consent = getCookie(COOKIE_NAME);
    if (!consent) {
        showConsent();
    }
})();
