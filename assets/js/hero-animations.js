/**
 * ヒーローセクション v2 - モダンアニメーション
 * 3D効果 + 文字分割 + パーティクル
 */

(function() {
    'use strict';

    console.log('🎬 Hero Animations - モダン版初期化開始');

    // GSAP読み込み確認
    if (typeof gsap === 'undefined') {
        console.warn('GSAP is not loaded. Hero animations will be skipped.');
        return;
    }

    // JavaScript有効フラグを追加
    document.documentElement.classList.add('js-enabled');

    // DOM要素の取得
    const heroSection = document.querySelector('.hero-v2');
    if (!heroSection) {
        console.warn('Hero section not found');
        return;
    }

    /**
     * タイトルを1文字ずつ分割
     */
    function splitTextToChars(element) {
        const text = element.textContent;
        const chars = text.split('');
        element.innerHTML = '';

        chars.forEach((char, index) => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.display = 'inline-block';
            span.style.opacity = '0';
            span.style.transform = 'translateY(100px) rotateX(-90deg) scale(0.5)';
            span.className = 'char-split';
            element.appendChild(span);
        });

        return element.querySelectorAll('.char-split');
    }

    /**
     * メインアニメーション
     */
    function initHeroAnimation() {
        console.log('✨ ヒーローアニメーション - 実行中...');

        // 要素の取得
        const label = heroSection.querySelector('[data-hero-label]');
        const titleLines = heroSection.querySelectorAll('.hero-v2__title-line');
        const text = heroSection.querySelector('[data-hero-text]');
        const buttons = heroSection.querySelector('[data-hero-buttons]');
        const metaItems = heroSection.querySelectorAll('.hero-v2__meta-item');
        const scroll = heroSection.querySelector('[data-hero-scroll]');
        const shapes = heroSection.querySelectorAll('.hero-v2__shape');

        // タイトルを文字分割
        const allChars = [];
        titleLines.forEach(line => {
            const chars = splitTextToChars(line);
            allChars.push(...chars);
        });

        // メインタイムライン
        const tl = gsap.timeline({
            delay: 0.5,
            defaults: {
                ease: 'power3.out'
            }
        });

        // 背景シェイプのパルスアニメーション
        if (shapes.length > 0) {
            gsap.set(shapes, { scale: 0, opacity: 0 });
            tl.to(shapes, {
                scale: 1,
                opacity: 1,
                duration: 2,
                stagger: 0.3,
                ease: 'elastic.out(1, 0.5)'
            }, 0);

            // 継続的なパルス
            shapes.forEach((shape, index) => {
                gsap.to(shape, {
                    scale: 1.1,
                    duration: 3 + index,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut',
                    delay: index * 0.5
                });
            });
        }

        // ラベル - スケール + フェード
        if (label) {
            tl.from(label, {
                opacity: 0,
                scale: 0.8,
                y: -30,
                duration: 1,
                ease: 'back.out(1.7)'
            }, 0.3);
        }

        // タイトル文字 - 3Dアニメーション
        if (allChars.length > 0) {
            tl.to(allChars, {
                opacity: 1,
                y: 0,
                rotateX: 0,
                scale: 1,
                duration: 1.2,
                stagger: {
                    each: 0.03,
                    from: 'start',
                    ease: 'power2.inOut'
                },
                ease: 'back.out(1.5)'
            }, 0.6);
        }

        // テキスト - フェード + スライド
        if (text) {
            tl.from(text, {
                opacity: 0,
                y: 50,
                duration: 1,
                ease: 'power2.out'
            }, '-=0.8');
        }

        // ボタン - スケール + バウンス
        if (buttons) {
            const buttonElements = buttons.querySelectorAll('.hero-v2__btn');
            tl.from(buttonElements, {
                opacity: 0,
                scale: 0.8,
                y: 40,
                duration: 0.8,
                stagger: 0.15,
                ease: 'back.out(1.7)'
            }, '-=0.6');

            // ボタンのホバーアニメーション強化
            buttonElements.forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    gsap.to(btn, {
                        scale: 1.1,
                        rotateZ: 2,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });

                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, {
                        scale: 1,
                        rotateZ: 0,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });
        }

        // メタ情報 - 横からスライド + フェード
        if (metaItems.length > 0) {
            tl.from(metaItems, {
                opacity: 0,
                x: 100,
                rotateY: 45,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power3.out'
            }, '-=0.5');
        }

        // スクロールヒント - バウンスアニメーション
        if (scroll) {
            tl.from(scroll, {
                opacity: 0,
                y: -30,
                duration: 1,
                ease: 'bounce.out'
            }, '-=0.3');

            // 継続的なバウンス
            gsap.to(scroll, {
                y: 10,
                duration: 1.5,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut'
            });
        }

        console.log('✅ ヒーローアニメーション - 完了');
    }

    /**
     * マウス追従効果（オプション）
     */
    function initMouseFollowEffect() {
        let mouseX = 0;
        let mouseY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 20;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 20;
        });

        // タイトルをマウスに追従
        const title = heroSection.querySelector('.hero-v2__title');
        if (title) {
            gsap.to(title, {
                x: () => mouseX,
                y: () => mouseY,
                duration: 2,
                ease: 'power2.out',
                overwrite: 'auto'
            });

            // 継続的な更新
            gsap.ticker.add(() => {
                gsap.to(title, {
                    x: mouseX,
                    y: mouseY,
                    duration: 2,
                    ease: 'power2.out',
                    overwrite: 'auto'
                });
            });
        }
    }

    /**
     * 初期化
     */
    function init() {
        console.log('🚀 Hero Animations - 初期化実行');

        // メインアニメーション
        initHeroAnimation();

        // マウス追従効果（デスクトップのみ）
        if (window.innerWidth >= 1024) {
            initMouseFollowEffect();
        }

        console.log('✅ Hero Animations - すべて完了');
    }

    // ページロード後に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
