/**
 * サンクスページアニメーション（紙吹雪）
 */

document.addEventListener('DOMContentLoaded', function() {
    const confettiContainer = document.getElementById('confettiContainer');

    if (!confettiContainer) return;

    // 紙吹雪の色
    const colors = ['#8B7355', '#428570', '#6B9B87', '#A08968', '#4A9D7F'];

    // 紙吹雪を生成
    function createConfetti() {
        const confettiCount = 50;

        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');

            // ランダムな色
            confetti.style.background = colors[Math.floor(Math.random() * colors.length)];

            // ランダムなサイズ
            const size = Math.random() * 10 + 5;
            confetti.style.width = size + 'px';
            confetti.style.height = size + 'px';

            // ランダムな形状
            if (Math.random() > 0.5) {
                confetti.style.borderRadius = '50%';
            }

            // ランダムな開始位置（画面上部の外側）
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.top = '-20px';

            confettiContainer.appendChild(confetti);

            // GSAPでアニメーション
            const duration = Math.random() * 2 + 2; // 2-4秒
            const rotation = Math.random() * 360;
            const xMovement = (Math.random() - 0.5) * 200; // -100px ~ 100px

            gsap.to(confetti, {
                y: window.innerHeight + 100,
                x: xMovement,
                rotation: rotation,
                opacity: 0,
                duration: duration,
                ease: 'power1.in',
                delay: Math.random() * 0.5,
                onComplete: () => {
                    confetti.remove();
                }
            });

            // 回転アニメーション（追加）
            gsap.to(confetti, {
                rotation: rotation + 360,
                duration: duration * 0.7,
                repeat: -1,
                ease: 'none'
            });
        }
    }

    // ページロード時に紙吹雪を生成
    setTimeout(() => {
        createConfetti();
    }, 500);

    // アイコンとチェックマークのアニメーション
    const icon = document.querySelector('.thanks-page__icon');
    const check = document.querySelector('.thanks-page__check');

    if (icon) {
        gsap.from(icon, {
            scale: 0,
            rotation: -180,
            duration: 0.8,
            ease: 'back.out(1.7)',
            delay: 0.2
        });
    }

    if (check) {
        gsap.from(check, {
            scale: 0,
            duration: 0.6,
            ease: 'back.out(1.7)',
            delay: 0.7
        });
    }

    // タイトルのシェイクエフェクト
    const title = document.querySelector('.thanks-page__title');
    if (title) {
        setTimeout(() => {
            gsap.to(title, {
                x: -5,
                duration: 0.1,
                repeat: 5,
                yoyo: true,
                ease: 'power2.inOut',
                onComplete: () => {
                    gsap.set(title, { x: 0 });
                }
            });
        }, 1200);
    }

    // ボタンのホバーエフェクト強化
    const buttons = document.querySelectorAll('.thanks-page__actions .btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            gsap.to(this, {
                scale: 1.05,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        btn.addEventListener('mouseleave', function() {
            gsap.to(this, {
                scale: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });
});
