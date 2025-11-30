/**
 * タスクル - メインJavaScript
 * GSAP + ScrollTriggerアニメーション
 */

document.addEventListener('DOMContentLoaded', () => {
    // Lucideアイコンの初期化
    lucide.createIcons();

    // ========================================
    // ヘッダースクロール時の影
    // ========================================
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // ========================================
    // モバイルメニュー
    // ========================================
    const burgerBtn = document.getElementById('burgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    burgerBtn.addEventListener('click', () => {
        burgerBtn.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // メニューリンククリック時にメニューを閉じる
    const mobileMenuLinks = mobileMenu.querySelectorAll('a');
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            burgerBtn.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });

    // ========================================
    // FAQアコーディオン
    // ========================================
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const isActive = faqItem.classList.contains('active');

            // すべてのFAQを閉じる
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // クリックされたFAQを開く（既に開いていたら閉じる）
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // 最初のFAQを開いておく
    if (faqQuestions.length > 0) {
        faqQuestions[0].parentElement.classList.add('active');
    }

    // ========================================
    // GSAP + ScrollTrigger設定
    // ========================================
    gsap.registerPlugin(ScrollTrigger);

    // セクションのフェードインアニメーション
    gsap.utils.toArray('.fade-in-section').forEach(section => {
        gsap.from(section, {
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                toggleActions: 'play none none none'
            }
        });
    });

    // 課題カードのスタッガーアニメーション
    gsap.from('.problem-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.15,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#problems',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 機能カードのスタッガーアニメーション
    gsap.from('.feature-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.12,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#features',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 料金カードのスタッガーアニメーション
    gsap.from('.pricing-card', {
        opacity: 0,
        scale: 0.95,
        duration: 0.6,
        stagger: 0.15,
        ease: 'back.out(1.2)',
        scrollTrigger: {
            trigger: '#pricing',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 導入事例カードのスタッガーアニメーション
    gsap.from('.case-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.15,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#cases',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // ========================================
    // 数字カウントアップ（信頼指標）
    // ========================================
    // 1,500社のカウントアップ
    const companiesCount = { value: 0 };
    gsap.to(companiesCount, {
        value: 1500,
        duration: 2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.hero',
            start: 'top 50%',
            toggleActions: 'play none none none'
        },
        onUpdate: function() {
            const companiesElements = document.querySelectorAll('.hero strong');
            if (companiesElements[0]) {
                companiesElements[0].textContent = Math.ceil(companiesCount.value).toLocaleString() + '社';
            }
        }
    });

    // ========================================
    // スムーズスクロール（アンカーリンク）
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#cta') {
                // #cta は別途処理
                if (href === '#cta') {
                    e.preventDefault();
                    const ctaSection = document.getElementById('cta');
                    if (ctaSection) {
                        ctaSection.scrollIntoView({ behavior: 'smooth' });
                    }
                }
                return;
            }

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerHeight = 64; // ヘッダーの高さ
                const targetPosition = target.offsetTop - headerHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    console.log('タスクル LP初期化完了');
});
