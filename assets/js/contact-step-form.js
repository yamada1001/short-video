/**
 * ステップフォーム with GSAP Animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // 要素取得
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const contactTypeCards = document.querySelectorAll('.contact-type-card');
    const backButton = document.getElementById('backButton');
    const contactForm = document.getElementById('contactForm');
    const contactTypeInput = document.getElementById('contactType');
    const selectedTypeText = document.getElementById('selectedTypeText');
    const submitButton = document.getElementById('submitButton');
    const progressFill = document.getElementById('progressFill');
    const progressLabels = document.querySelectorAll('.contact-step-progress__label');

    let selectedType = '';
    const typeMapping = {
        'seo': 'SEO対策について',
        'ad': '広告運用について',
        'web': 'Web制作について',
        'video': 'ショート動画制作について',
        'freelance': '業務委託・協業について',
        'quote': '見積もり依頼',
        'sales': '営業のご連絡',
        'other': 'その他'
    };

    // 初期アニメーション（カードのスタガー表示）
    gsap.from('.contact-type-card', {
        opacity: 0,
        y: 30,
        duration: 0.6,
        stagger: 0.08,
        ease: 'power2.out',
        delay: 0.2
    });

    // カード選択時の処理
    contactTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            selectedType = type;

            // カードをハイライト（一瞬）
            gsap.to(this, {
                scale: 0.95,
                duration: 0.15,
                ease: 'power2.inOut',
                onComplete: () => {
                    gsap.to(this, {
                        scale: 1.05,
                        duration: 0.15,
                        ease: 'power2.out',
                        onComplete: () => {
                            // Step 1 → Step 2 への遷移
                            transitionToStep2(type);
                        }
                    });
                }
            });
        });

        // ホバーアニメーション強化
        card.addEventListener('mouseenter', function() {
            gsap.to(this.querySelector('.contact-type-card__icon'), {
                scale: 1.1,
                rotation: 5,
                duration: 0.3,
                ease: 'power2.out'
            });
        });

        card.addEventListener('mouseleave', function() {
            gsap.to(this.querySelector('.contact-type-card__icon'), {
                scale: 1,
                rotation: 0,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
    });

    // Step 1 → Step 2 遷移
    function transitionToStep2(type) {
        // プログレスバー更新
        updateProgress(2);

        // Step 1 フェードアウト（左へ）
        const tl = gsap.timeline();

        tl.to(step1, {
            opacity: 0,
            x: -50,
            duration: 0.4,
            ease: 'power2.inOut',
            onComplete: () => {
                step1.style.display = 'none';

                // Step 2 表示準備
                step2.style.display = 'block';
                gsap.set(step2, { opacity: 0, x: 50 });

                // フォーム情報設定
                contactTypeInput.value = type;
                selectedTypeText.textContent = typeMapping[type];

                // Step 2 フェードイン（右から）
                gsap.to(step2, {
                    opacity: 1,
                    x: 0,
                    duration: 0.5,
                    ease: 'power2.out'
                });

                // フォーム項目のスタガーアニメーション
                gsap.from('.form-group', {
                    opacity: 0,
                    y: 20,
                    duration: 0.4,
                    stagger: 0.08,
                    ease: 'power2.out',
                    delay: 0.2
                });
            }
        });
    }

    // 戻るボタン
    backButton.addEventListener('click', function() {
        // プログレスバー更新
        updateProgress(1);

        // Step 2 フェードアウト（右へ）
        gsap.to(step2, {
            opacity: 0,
            x: 50,
            duration: 0.4,
            ease: 'power2.inOut',
            onComplete: () => {
                step2.style.display = 'none';

                // Step 1 表示準備
                step1.style.display = 'block';
                gsap.set(step1, { opacity: 0, x: -50 });

                // Step 1 フェードイン（左から）
                gsap.to(step1, {
                    opacity: 1,
                    x: 0,
                    duration: 0.5,
                    ease: 'power2.out'
                });

                // カードの再アニメーション
                gsap.from('.contact-type-card', {
                    opacity: 0,
                    y: 20,
                    duration: 0.4,
                    stagger: 0.06,
                    ease: 'power2.out',
                    delay: 0.2
                });
            }
        });
    });

    // プログレスバー更新
    function updateProgress(step) {
        const percentage = (step / 3) * 100;
        gsap.to(progressFill, {
            width: percentage + '%',
            duration: 0.6,
            ease: 'power2.inOut'
        });

        progressLabels.forEach((label, index) => {
            const labelStep = index + 1;
            if (labelStep < step) {
                label.classList.add('completed');
                label.classList.remove('active');
            } else if (labelStep === step) {
                label.classList.add('active');
                label.classList.remove('completed');
            } else {
                label.classList.remove('active', 'completed');
            }
        });
    }

    // フォーム送信時
    contactForm.addEventListener('submit', function(e) {
        // バリデーション
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();

        if (!name || !email || !message) {
            e.preventDefault();

            // エラーフィールドをシェイク
            const errors = [];
            if (!name) errors.push(document.getElementById('name'));
            if (!email) errors.push(document.getElementById('email'));
            if (!message) errors.push(document.getElementById('message'));

            errors.forEach(field => {
                gsap.fromTo(field,
                    { x: -10 },
                    {
                        x: 10,
                        duration: 0.1,
                        repeat: 5,
                        yoyo: true,
                        ease: 'power2.inOut',
                        onComplete: () => {
                            gsap.set(field, { x: 0 });
                        }
                    }
                );
                field.classList.add('error');
            });
            return;
        }

        // ローディングアニメーション
        submitButton.disabled = true;

        const btnText = submitButton.querySelector('.btn-text');
        const btnLoader = submitButton.querySelector('.btn-loader');

        gsap.to(btnText, {
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                btnText.style.display = 'none';
                btnLoader.style.display = 'inline-block';
                gsap.fromTo(btnLoader,
                    { opacity: 0 },
                    { opacity: 1, duration: 0.2 }
                );
            }
        });

        // ボタンパルスアニメーション
        gsap.to(submitButton, {
            scale: 1.02,
            duration: 0.6,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut'
        });
    });

    // リアルタイムバリデーション（エラークリア）
    ['name', 'email', 'message'].forEach(id => {
        const field = document.getElementById(id);
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('error');
            }
        });
    });
});
