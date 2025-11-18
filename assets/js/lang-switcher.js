/**
 * 言語切り替えアニメーション（GSAP使用）
 */

document.addEventListener('DOMContentLoaded', function() {
    const langLink = document.querySelector('.nav__lang-link');

    if (!langLink) return;

    // ホバー時のアニメーション
    langLink.addEventListener('mouseenter', function() {
        gsap.to(this, {
            scale: 1.1,
            duration: 0.3,
            ease: 'power2.out'
        });

        gsap.to(this.querySelector('i'), {
            rotation: 360,
            duration: 0.6,
            ease: 'power2.out'
        });
    });

    langLink.addEventListener('mouseleave', function() {
        gsap.to(this, {
            scale: 1,
            duration: 0.3,
            ease: 'power2.out'
        });

        gsap.to(this.querySelector('i'), {
            rotation: 0,
            duration: 0.3,
            ease: 'power2.out'
        });
    });

    // クリック時のページ遷移アニメーション
    langLink.addEventListener('click', function(e) {
        e.preventDefault();
        const targetUrl = this.getAttribute('href');

        // オーバーレイを作成
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #8B7355 0%, #428570 100%);
            z-index: 10000;
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        `;

        // ローディングアイコン
        const loadingIcon = document.createElement('div');
        loadingIcon.innerHTML = '<i class="fas fa-globe" style="font-size: 48px; color: white;"></i>';
        loadingIcon.style.cssText = 'margin-bottom: 20px;';
        overlay.appendChild(loadingIcon);

        // ローディングテキスト
        const loadingText = document.createElement('div');
        loadingText.textContent = targetUrl.includes('/en/') ? 'Switching to Japanese...' : 'Switching to English...';
        loadingText.style.cssText = 'color: white; font-size: 18px; letter-spacing: 0.1em;';
        overlay.appendChild(loadingText);

        document.body.appendChild(overlay);

        // アニメーションタイムライン
        const tl = gsap.timeline({
            onComplete: function() {
                window.location.href = targetUrl;
            }
        });

        // オーバーレイのフェードイン
        tl.to(overlay, {
            opacity: 1,
            duration: 0.4,
            ease: 'power2.inOut'
        });

        // アイコンの回転
        tl.to(loadingIcon.querySelector('i'), {
            rotation: 360,
            duration: 0.6,
            ease: 'power2.inOut',
            repeat: 1
        }, '-=0.2');

        // テキストのフェードイン
        tl.from(loadingText, {
            opacity: 0,
            y: 20,
            duration: 0.3,
            ease: 'power2.out'
        }, '-=0.5');
    });

    // ページロード時のアニメーション
    if (sessionStorage.getItem('langSwitched') === 'true') {
        sessionStorage.removeItem('langSwitched');

        // ページコンテンツのフェードイン
        const mainContent = document.querySelector('body');
        gsap.from(mainContent, {
            opacity: 0,
            duration: 0.5,
            ease: 'power2.out'
        });

        // セクションごとのスタガーアニメーション
        gsap.from('.section, .hero-v2', {
            opacity: 0,
            y: 30,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power2.out',
            delay: 0.2
        });
    }
});

// 言語切り替え後のフラグを設定
window.addEventListener('beforeunload', function() {
    if (event.target.activeElement.classList.contains('nav__lang-link')) {
        sessionStorage.setItem('langSwitched', 'true');
    }
});
