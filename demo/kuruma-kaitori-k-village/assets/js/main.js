/**
 * Main JavaScript
 * くるま買取ケイヴィレッジ
 */

(function() {
    'use strict';

    // ====================================
    // DOM Elements
    // ====================================
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.header__nav');
    const navLinks = document.querySelectorAll('.header__nav-link');
    const body = document.body;

    // ====================================
    // Mobile Menu Toggle
    // ====================================
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('is-active');
            navMenu.classList.toggle('is-active');
            body.classList.toggle('menu-open');
        });

        // メニューリンククリック時に閉じる
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                hamburger.classList.remove('is-active');
                navMenu.classList.remove('is-active');
                body.classList.remove('menu-open');
            });
        });

        // メニュー外クリックで閉じる
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.header__nav') && !e.target.closest('.hamburger')) {
                hamburger.classList.remove('is-active');
                navMenu.classList.remove('is-active');
                body.classList.remove('menu-open');
            }
        });
    }

    // ====================================
    // Smooth Scroll
    // ====================================
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');

    smoothScrollLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            // ハッシュのみの場合はスキップ
            if (href === '#') {
                e.preventDefault();
                return;
            }

            const target = document.querySelector(href);

            if (target) {
                e.preventDefault();
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = target.offsetTop - headerHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ====================================
    // Header Scroll Effect
    // ====================================
    const header = document.querySelector('.header');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // 100px以上スクロールしたら背景を濃くする
        if (scrollTop > 100) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }

        lastScrollTop = scrollTop;
    });

    // ====================================
    // Scroll Fade In Animation
    // ====================================
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    // アニメーション対象要素を監視
    const fadeElements = document.querySelectorAll('.fade-in, .news-item, .service-card, .strength-item, .info-item');
    fadeElements.forEach(function(element) {
        observer.observe(element);
    });

    // ====================================
    // Form Validation (Contact Form)
    // ====================================
    const contactForm = document.querySelector('#contact-form');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // バリデーション
            let isValid = true;
            const requiredFields = contactForm.querySelectorAll('[required]');

            requiredFields.forEach(function(field) {
                const errorElement = field.parentElement.querySelector('.form__error');

                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (errorElement) {
                        errorElement.textContent = 'この項目は必須です';
                        errorElement.style.display = 'block';
                    }
                } else {
                    field.classList.remove('is-invalid');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });

            // メールアドレスバリデーション
            const emailField = contactForm.querySelector('input[type="email"]');
            if (emailField && emailField.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('is-invalid');
                    const errorElement = emailField.parentElement.querySelector('.form__error');
                    if (errorElement) {
                        errorElement.textContent = 'メールアドレスの形式が正しくありません';
                        errorElement.style.display = 'block';
                    }
                }
            }

            // 電話番号バリデーション
            const telField = contactForm.querySelector('input[type="tel"]');
            if (telField && telField.value) {
                const telPattern = /^[0-9\-]+$/;
                if (!telPattern.test(telField.value)) {
                    isValid = false;
                    telField.classList.add('is-invalid');
                    const errorElement = telField.parentElement.querySelector('.form__error');
                    if (errorElement) {
                        errorElement.textContent = '電話番号は数字とハイフンのみで入力してください';
                        errorElement.style.display = 'block';
                    }
                }
            }

            if (isValid) {
                // フォーム送信（実際にはPHPMailerで処理）
                contactForm.submit();
            }
        });

        // リアルタイムバリデーション
        const formFields = contactForm.querySelectorAll('input, textarea, select');
        formFields.forEach(function(field) {
            field.addEventListener('blur', function() {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            field.addEventListener('input', function() {
                if (field.classList.contains('is-invalid') && field.value.trim()) {
                    field.classList.remove('is-invalid');
                    const errorElement = field.parentElement.querySelector('.form__error');
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }
                }
            });
        });
    }

    // ====================================
    // Back to Top Button
    // ====================================
    const backToTopButton = document.createElement('button');
    backToTopButton.className = 'back-to-top';
    backToTopButton.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
    backToTopButton.setAttribute('aria-label', 'ページトップへ戻る');
    document.body.appendChild(backToTopButton);

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('is-visible');
        } else {
            backToTopButton.classList.remove('is-visible');
        }
    });

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // ====================================
    // External Links (新しいタブで開く)
    // ====================================
    const externalLinks = document.querySelectorAll('a[href^="http"]');
    externalLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        // 自サイトのリンクは除外
        if (!href.includes(window.location.hostname)) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        }
    });

    // ====================================
    // Loading Animation (初回のみ)
    // ====================================
    window.addEventListener('load', function() {
        document.body.classList.add('is-loaded');
    });

})();
