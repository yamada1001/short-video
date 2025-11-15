// ========================================
// トップページ - GSAPアニメーション
// ========================================

(function() {
  'use strict';

  // GSAPとScrollTriggerが読み込まれるまで待機
  function initAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
      setTimeout(initAnimations, 100);
      return;
    }

    // ScrollTriggerプラグインを登録
    gsap.registerPlugin(ScrollTrigger);

    // ========================================
    // ヒーローセクション - エントランスアニメーション
    // ========================================

    const heroTimeline = gsap.timeline({
      delay: 0.3 // ページローダーの後に開始
    });

    // ラベルのアニメーション
    heroTimeline.to('.hero__label', {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    });

    // タイトル各行のアニメーション（順番に表示）
    heroTimeline.to('.hero__title-line--1', {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: 'power3.out'
    }, '-=0.4');

    heroTimeline.to('.hero__title-line--2', {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: 'power3.out'
    }, '-=0.7');

    heroTimeline.to('.hero__title-line--3', {
      opacity: 1,
      y: 0,
      duration: 1,
      ease: 'power3.out'
    }, '-=0.7');

    // 説明文のアニメーション
    heroTimeline.to('.hero__description-line', {
      opacity: 1,
      y: 0,
      duration: 0.8,
      stagger: 0.2,
      ease: 'power2.out'
    }, '-=0.5');

    // CTAボタンのアニメーション
    heroTimeline.to('.hero__cta', {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    }, '-=0.4');

    // メタ情報のアニメーション
    heroTimeline.to('.hero__meta', {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    }, '-=0.6');

    // スクロールヒントのアニメーション
    heroTimeline.to('.hero__scroll-hint', {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    }, '-=0.4');

    // 背景シェイプの微妙な動き
    gsap.to('.hero__shape--1', {
      x: 30,
      y: -30,
      duration: 8,
      ease: 'sine.inOut',
      repeat: -1,
      yoyo: true
    });

    gsap.to('.hero__shape--2', {
      x: -20,
      y: 20,
      duration: 10,
      ease: 'sine.inOut',
      repeat: -1,
      yoyo: true
    });

    gsap.to('.hero__shape--3', {
      scale: 1.2,
      duration: 6,
      ease: 'sine.inOut',
      repeat: -1,
      yoyo: true
    });

    // ========================================
    // セクションタイトル - スクロールトリガー
    // ========================================

    gsap.utils.toArray('.section__title').forEach(title => {
      gsap.to(title, {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: title,
          start: 'top 80%',
          end: 'top 50%',
          toggleActions: 'play none none none'
        }
      });
    });

    // ========================================
    // セクション説明文 - スクロールトリガー
    // ========================================

    gsap.utils.toArray('.section__description').forEach(desc => {
      gsap.to(desc, {
        opacity: 1,
        y: 0,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: desc,
          start: 'top 80%',
          toggleActions: 'play none none none'
        }
      });
    });

    // ========================================
    // サービスカード - スクロールトリガー（順番に表示）
    // ========================================

    gsap.utils.toArray('.service-card').forEach((card, index) => {
      gsap.to(card, {
        opacity: 1,
        y: 0,
        duration: 1,
        delay: index * 0.2, // カードごとに0.2秒遅延
        ease: 'power3.out',
        scrollTrigger: {
          trigger: card,
          start: 'top 85%',
          toggleActions: 'play none none none'
        }
      });
    });

    // ========================================
    // 汎用アニメーション要素（.animate）
    // ========================================

    gsap.utils.toArray('.animate').forEach(element => {
      // 既にヒーローセクション内の要素はアニメーション済みなのでスキップ
      if (element.closest('.hero')) {
        return;
      }

      gsap.from(element, {
        opacity: 0,
        y: 40,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: element,
          start: 'top 85%',
          toggleActions: 'play none none none'
        }
      });
    });

    // ========================================
    // パララックス効果（背景シェイプ）
    // ========================================

    gsap.to('.hero__bg-shapes', {
      y: 200,
      ease: 'none',
      scrollTrigger: {
        trigger: '.hero',
        start: 'top top',
        end: 'bottom top',
        scrub: 1 // スクロールに追従
      }
    });

    // ========================================
    // スムーズスクロール（アンカーリンク）
    // ========================================

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || !href) return;

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          gsap.to(window, {
            duration: 1,
            scrollTo: {
              y: target,
              offsetY: 80 // ヘッダー分のオフセット
            },
            ease: 'power3.inOut'
          });
        }
      });
    });

    console.log('✨ GSAP animations initialized');
  }

  // ページロード完了後に初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnimations);
  } else {
    initAnimations();
  }
})();
