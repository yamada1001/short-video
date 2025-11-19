/**
 * Web制作ページ用アニメーション（GSAP）
 */

document.addEventListener('DOMContentLoaded', function() {
    // GSAPが読み込まれているか確認
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded');
        return;
    }

    // ScrollTriggerプラグインを登録
    if (typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }

    // ページヘッダーアニメーション
    gsap.from('.page-header__title', {
        opacity: 0,
        y: 30,
        duration: 0.8,
        ease: 'power2.out',
        delay: 0.2
    });

    gsap.from('.page-header__description', {
        opacity: 0,
        y: 20,
        duration: 0.8,
        ease: 'power2.out',
        delay: 0.4
    });

    // 料金カードのスクロールアニメーション
    gsap.utils.toArray('.pricing-card').forEach((card, index) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 50,
            duration: 0.6,
            delay: index * 0.15,
            ease: 'power2.out'
        });
    });

    // ハイライトボックスのアニメーション
    gsap.utils.toArray('.highlight-box').forEach(box => {
        gsap.from(box, {
            scrollTrigger: {
                trigger: box,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            x: -30,
            duration: 0.6,
            ease: 'power2.out'
        });
    });

    // ストーリーセクションのコンテンツアニメーション
    const storyContent = document.querySelector('.story-content');
    if (storyContent) {
        gsap.from(storyContent, {
            scrollTrigger: {
                trigger: storyContent,
                start: 'top 80%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 40,
            duration: 0.8,
            ease: 'power2.out'
        });

        // ストーリー内の見出しをスタガーアニメーション
        gsap.utils.toArray('.story-content h3').forEach((heading, index) => {
            gsap.from(heading, {
                scrollTrigger: {
                    trigger: heading,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                },
                opacity: 0,
                x: -20,
                duration: 0.5,
                delay: index * 0.1,
                ease: 'power2.out'
            });
        });
    }

    // チームロールのスタガーアニメーション
    gsap.utils.toArray('.team-role').forEach((role, index) => {
        gsap.from(role, {
            scrollTrigger: {
                trigger: role,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 30,
            duration: 0.5,
            delay: index * 0.08,
            ease: 'power2.out'
        });
    });

    // ポートフォリオアイテムのアニメーション
    gsap.utils.toArray('.portfolio-item').forEach((item, index) => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 40,
            scale: 0.95,
            duration: 0.6,
            delay: index * 0.1,
            ease: 'power2.out'
        });
    });

    // セクションタイトルのアニメーション
    gsap.utils.toArray('.section__subtitle, .story-section__title, .team-section__title, .portfolio-section__title').forEach(title => {
        gsap.from(title, {
            scrollTrigger: {
                trigger: title,
                start: 'top 85%',
                toggleActions: 'play none none none'
            },
            opacity: 0,
            y: 20,
            duration: 0.6,
            ease: 'power2.out'
        });
    });

    // 料金カードのホバー強化
    document.querySelectorAll('.pricing-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card.querySelector('.pricing-card__icon'), {
                scale: 1.1,
                rotation: 5,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card.querySelector('.pricing-card__icon'), {
                scale: 1,
                rotation: 0,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });

    // チームロールアイコンのホバー
    document.querySelectorAll('.team-role').forEach(role => {
        role.addEventListener('mouseenter', () => {
            gsap.to(role.querySelector('.team-role__icon'), {
                scale: 1.2,
                duration: 0.3,
                ease: 'back.out(1.7)'
            });
        });

        role.addEventListener('mouseleave', () => {
            gsap.to(role.querySelector('.team-role__icon'), {
                scale: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });

    // ポートフォリオアイテムの画像ホバー
    document.querySelectorAll('.portfolio-item').forEach(item => {
        const image = item.querySelector('.portfolio-item__image');

        item.addEventListener('mouseenter', () => {
            gsap.to(image, {
                scale: 1.05,
                duration: 0.4,
                ease: 'power2.out'
            });
            gsap.to(image.querySelector('i'), {
                scale: 1.2,
                rotation: 10,
                duration: 0.4,
                ease: 'power2.out'
            });
        });

        item.addEventListener('mouseleave', () => {
            gsap.to(image, {
                scale: 1,
                duration: 0.4,
                ease: 'power2.out'
            });
            gsap.to(image.querySelector('i'), {
                scale: 1,
                rotation: 0,
                duration: 0.4,
                ease: 'power2.out'
            });
        });
    });

    // スクロールに応じてページヘッダーをパララックス
    gsap.to('.page-header', {
        scrollTrigger: {
            trigger: '.page-header',
            start: 'top top',
            end: 'bottom top',
            scrub: true
        },
        backgroundPosition: '50% 30%',
        ease: 'none'
    });
});
