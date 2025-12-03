/**
 * Modern Effects
 * くるま買取ケイヴィレッジ
 * GSAP + Lenis + Custom Cursor + Scroll Animations
 */

(function() {
    'use strict';

    // ====================================
    // Lenis Smooth Scroll
    // ====================================
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smooth: true,
        smoothTouch: false,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    // GSAP ScrollTrigger と Lenis を連携
    lenis.on('scroll', ScrollTrigger.update);

    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
    });

    gsap.ticker.lagSmoothing(0);

    // ====================================
    // カスタムカーソル
    // ====================================
    const cursor = document.createElement('div');
    cursor.className = 'custom-cursor';
    document.body.appendChild(cursor);

    const cursorDot = document.createElement('div');
    cursorDot.className = 'custom-cursor-dot';
    document.body.appendChild(cursorDot);

    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;
    let dotX = 0, dotY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateCursor() {
        // カーソル（遅延追従）
        const dx = mouseX - cursorX;
        const dy = mouseY - cursorY;
        cursorX += dx * 0.15;
        cursorY += dy * 0.15;
        cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;

        // ドット（即座追従）
        dotX += (mouseX - dotX) * 0.8;
        dotY += (mouseY - dotY) * 0.8;
        cursorDot.style.transform = `translate(${dotX}px, ${dotY}px)`;

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // リンク・ボタンホバー時にカーソル拡大
    const interactiveElements = document.querySelectorAll('a, button, .card, .btn, .news-item');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
            cursorDot.classList.add('active');
        });
        el.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
            cursorDot.classList.remove('active');
        });
    });

    // ====================================
    // Header Scroll Effect
    // ====================================
    const header = document.querySelector('.header');
    let lastScroll = 0;

    lenis.on('scroll', ({ scroll }) => {
        if (scroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        lastScroll = scroll;
    });

    // ====================================
    // GSAP ScrollTrigger Animations
    // ====================================
    gsap.registerPlugin(ScrollTrigger);

    // Hero セクションのアニメーション
    gsap.from('.hero__content', {
        opacity: 0,
        y: 60,
        duration: 1.2,
        ease: 'power3.out',
        delay: 0.3
    });

    gsap.from('.hero__image', {
        opacity: 0,
        scale: 0.9,
        duration: 1.4,
        ease: 'power3.out',
        delay: 0.5
    });

    // セクションタイトルのアニメーション
    gsap.utils.toArray('.section__title').forEach(title => {
        gsap.from(title, {
            scrollTrigger: {
                trigger: title,
                start: 'top 85%',
                end: 'top 65%',
                toggleActions: 'play none none reverse',
            },
            opacity: 0,
            y: 40,
            duration: 0.8,
            ease: 'power2.out'
        });
    });

    // サービスカードのstagger animation
    gsap.utils.toArray('.service-card').forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            opacity: 0,
            y: 50,
            duration: 0.8,
            ease: 'power3.out',
            delay: index * 0.1
        });
    });

    // カード全般のstagger animation
    gsap.utils.toArray('.card').forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            opacity: 0,
            y: 50,
            scale: 0.95,
            duration: 0.9,
            ease: 'power3.out',
            delay: index * 0.15
        });
    });

    // ニュースアイテムのアニメーション
    gsap.utils.toArray('.news-item').forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            opacity: 0,
            x: -30,
            duration: 0.7,
            ease: 'power2.out',
            delay: index * 0.1
        });
    });

    // パララックス効果（セクション番号）
    gsap.utils.toArray('[data-section-number]').forEach(section => {
        const number = section.querySelector('::before');
        gsap.to(section, {
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1,
            },
            y: -100,
            opacity: 0.5
        });
    });

    // ====================================
    // テキストスプリットアニメーション
    // ====================================
    const splitTextElements = document.querySelectorAll('.hero__title');
    splitTextElements.forEach(element => {
        const text = element.textContent;
        element.innerHTML = '';

        text.split('').forEach((char, index) => {
            const span = document.createElement('span');
            span.textContent = char;
            span.style.display = 'inline-block';
            element.appendChild(span);

            gsap.from(span, {
                opacity: 0,
                y: 20,
                rotation: -10,
                duration: 0.6,
                ease: 'back.out(1.7)',
                delay: 0.5 + index * 0.03
            });
        });
    });

    // ====================================
    // マウス追従エフェクト（グラデーション）
    // ====================================
    const gradientBg = document.createElement('div');
    gradientBg.className = 'mouse-gradient';
    document.body.appendChild(gradientBg);

    document.addEventListener('mousemove', (e) => {
        const x = (e.clientX / window.innerWidth) * 100;
        const y = (e.clientY / window.innerHeight) * 100;

        gradientBg.style.background = `radial-gradient(600px at ${x}% ${y}%, rgba(37, 99, 235, 0.08), transparent 80%)`;
    });

    // ====================================
    // マグネティック効果（ボタン）
    // ====================================
    const magneticButtons = document.querySelectorAll('.btn');
    magneticButtons.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            gsap.to(btn, {
                x: x * 0.3,
                y: y * 0.3,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, {
                x: 0,
                y: 0,
                duration: 0.5,
                ease: 'elastic.out(1, 0.5)'
            });
        });
    });

    // ====================================
    // スクロール進捗バー
    // ====================================
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    document.body.appendChild(progressBar);

    lenis.on('scroll', ({ scroll, limit }) => {
        const progress = (scroll / limit) * 100;
        progressBar.style.width = `${progress}%`;
    });

})();
