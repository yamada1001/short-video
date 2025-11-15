/**
 * ブランディングサイト - インタラクションスクリプト
 * 完全にゼロから作成
 */

console.log('🚀 Yojitsu Branding Site - Loaded');

// ページロード後に初期化
window.addEventListener('load', function() {
    console.log('📄 ページロード完了');

    initMouseFollower();
    initScrollProgress();
    initScrollAnimations();
    initParallax();
    initSmoothScroll();
    initHeaderScroll();

    console.log('✅ 全ての初期化完了');
});

/**
 * マウスフォロワー（カーソル追従エフェクト）
 */
function initMouseFollower() {
    // モバイルではスキップ
    if (window.innerWidth < 768) {
        console.log('📱 モバイルデバイスのためマウスフォロワーをスキップ');
        return;
    }

    console.log('🖱️ マウスフォロワーを初期化中...');

    // 外側の円
    const follower = document.createElement('div');
    follower.style.cssText = `
        position: fixed;
        width: 50px;
        height: 50px;
        border: 2px solid rgba(139, 115, 85, 0.5);
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: width 0.3s ease, height 0.3s ease, border-color 0.3s ease;
        left: -100px;
        top: -100px;
    `;
    document.body.appendChild(follower);

    // 内側のドット
    const dot = document.createElement('div');
    dot.style.cssText = `
        position: fixed;
        width: 10px;
        height: 10px;
        background-color: rgba(139, 115, 85, 0.8);
        border-radius: 50%;
        pointer-events: none;
        z-index: 10000;
        transition: background-color 0.3s ease, transform 0.3s ease;
        left: -100px;
        top: -100px;
    `;
    document.body.appendChild(dot);

    let mouseX = 0, mouseY = 0;
    let followerX = 0, followerY = 0;
    let dotX = 0, dotY = 0;

    // マウス移動を追跡
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // アニメーションループ
    function animate() {
        // 外側の円（遅い追従）
        const dx = mouseX - followerX;
        const dy = mouseY - followerY;
        followerX += dx * 0.12;
        followerY += dy * 0.12;
        follower.style.transform = `translate(${followerX - 25}px, ${followerY - 25}px)`;

        // 内側のドット（速い追従）
        const dotDx = mouseX - dotX;
        const dotDy = mouseY - dotY;
        dotX += dotDx * 0.35;
        dotY += dotDy * 0.35;
        dot.style.transform = `translate(${dotX - 5}px, ${dotY - 5}px)`;

        requestAnimationFrame(animate);
    }
    animate();

    // ホバー時のエフェクト
    const hoverElements = document.querySelectorAll('a, button, .service-card');
    console.log(`🎯 ホバー対象要素: ${hoverElements.length}個`);

    hoverElements.forEach(function(el) {
        el.addEventListener('mouseenter', function() {
            follower.style.width = '70px';
            follower.style.height = '70px';
            follower.style.borderColor = 'rgba(139, 115, 85, 0.8)';
            dot.style.transform = `translate(${dotX - 5}px, ${dotY - 5}px) scale(1.5)`;
            dot.style.backgroundColor = 'rgba(139, 115, 85, 1)';
        });
        el.addEventListener('mouseleave', function() {
            follower.style.width = '50px';
            follower.style.height = '50px';
            follower.style.borderColor = 'rgba(139, 115, 85, 0.5)';
            dot.style.transform = `translate(${dotX - 5}px, ${dotY - 5}px) scale(1)`;
            dot.style.backgroundColor = 'rgba(139, 115, 85, 0.8)';
        });
    });

    console.log('✅ マウスフォロワー初期化完了');
}

/**
 * スクロール進捗バー
 */
function initScrollProgress() {
    console.log('📊 スクロール進捗バーを初期化中...');

    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #8B7355, #428570);
        width: 0%;
        z-index: 9998;
        transition: width 0.1s ease;
    `;
    document.body.appendChild(progressBar);

    window.addEventListener('scroll', function() {
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = window.pageYOffset;
        const progress = (scrolled / scrollHeight) * 100;
        progressBar.style.width = Math.min(progress, 100) + '%';
    });

    console.log('✅ スクロール進捗バー初期化完了');
}

/**
 * スクロールアニメーション
 */
function initScrollAnimations() {
    console.log('🎬 スクロールアニメーションを初期化中...');

    const targets = document.querySelectorAll('.service-card, .section-header, .about-content, .contact-content');
    console.log(`🎯 アニメーション対象: ${targets.length}個`);

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    let visibleCount = 0;

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(40px)';

                setTimeout(function() {
                    entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    visibleCount++;
                    console.log(`👁️ 要素が表示されました (${visibleCount}/${targets.length})`);
                }, 100);

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    targets.forEach(function(target) {
        observer.observe(target);
    });

    console.log('✅ スクロールアニメーション初期化完了');
}

/**
 * パララックス効果
 */
function initParallax() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.log('⚠️ GSAP が読み込まれていません');
        return;
    }

    console.log('🌊 パララックス効果を初期化中...');

    gsap.registerPlugin(ScrollTrigger);

    // ヒーローの背景シェイプ
    const shapes = document.querySelectorAll('.hero__bg-shape');
    shapes.forEach(function(shape, index) {
        gsap.to(shape, {
            y: 100 + (index * 50),
            ease: 'none',
            scrollTrigger: {
                trigger: '.hero',
                start: 'top top',
                end: 'bottom top',
                scrub: 1
            }
        });
    });

    // サービスカードのパララックス
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach(function(card, index) {
        gsap.to(card, {
            y: -30 - (index * 10),
            ease: 'none',
            scrollTrigger: {
                trigger: card,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1
            }
        });
    });

    // Aboutセクションの画像
    const aboutImage = document.querySelector('.about-image');
    if (aboutImage) {
        gsap.to(aboutImage, {
            y: -50,
            ease: 'none',
            scrollTrigger: {
                trigger: '.about',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1
            }
        });
    }

    console.log('✅ パララックス効果初期化完了');
}

/**
 * スムーズスクロール
 */
function initSmoothScroll() {
    console.log('🔗 スムーズスクロールを初期化中...');

    const links = document.querySelectorAll('a[href^="#"]');
    console.log(`🎯 アンカーリンク: ${links.length}個`);

    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                console.log(`🔗 スムーズスクロール: ${href}`);
            }
        });
    });

    console.log('✅ スムーズスクロール初期化完了');
}

/**
 * ヘッダーのスクロール時の挙動
 */
function initHeaderScroll() {
    console.log('📌 ヘッダースクロール効果を初期化中...');

    const header = document.querySelector('.header');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = 'none';
        }

        // スクロールダウン時はヘッダーを隠す
        if (currentScroll > lastScroll && currentScroll > 300) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }

        lastScroll = currentScroll;
    });

    console.log('✅ ヘッダースクロール効果初期化完了');
}

/**
 * リサイズ時の処理
 */
window.addEventListener('resize', function() {
    if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.refresh();
        console.log('🔄 ScrollTrigger リフレッシュ');
    }
});

console.log('🎉 スクリプト読み込み完了');
