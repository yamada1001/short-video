/**
 * 余日（Yojitsu）トップページ アニメーションシステム
 * GSAP 3.x + ScrollTrigger使用
 * 無印良品スタイル：控えめで上品な動き
 */

(function() {
  'use strict';

  // アクセシビリティ: prefers-reduced-motionの確認
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 初期化関数
  function init() {
    // GSAPが読み込まれているか確認
    if (typeof gsap === 'undefined') {
      console.warn('GSAP is not loaded. Animations will be skipped.');
      return;
    }

    // prefers-reduced-motionが有効な場合は、アニメーションをスキップ
    if (prefersReducedMotion) {
      console.log('Reduced motion preference detected. Disabling animations.');
      // 要素を即座に表示
      gsap.set('.hero__content > *', { opacity: 1, y: 0 });
      gsap.set('.animate', { opacity: 1, y: 0 });
      return;
    }

    // 既存のIntersectionObserverアニメーションを無効化
    // (.fade-in クラスが追加されないように)
    document.querySelectorAll('.animate').forEach(el => {
      el.style.opacity = '0';
    });

    initHeroAnimations();
    initScrollAnimations();
  }

  /**
   * ヒーローセクションのアニメーション
   * ページ読み込み時に実行
   */
  function initHeroAnimations() {
    const heroContent = document.querySelector('.hero__content');
    if (!heroContent) return;

    // タイムライン作成
    const tl = gsap.timeline({
      defaults: {
        ease: 'power2.out',
        duration: 0.8
      }
    });

    // 1. ラベル（Digital Marketing）のフェードイン
    tl.from('.hero__label', {
      opacity: 0,
      y: -10,
      duration: 0.6
    });

    // 2. メインテキスト（本質に向き合う...）のフェードイン（0.3秒遅れ）
    tl.from('.hero__description', {
      opacity: 0,
      y: 15,
      duration: 0.8
    }, '-=0.5');

    // 3. CTAボタンのフェードイン（さらに0.2秒遅れ）
    tl.from('.hero__cta', {
      opacity: 0,
      y: 10,
      duration: 0.7
    }, '-=0.5');

    // 4. スクロール誘導アニメーション
    tl.from('.hero__scroll', {
      opacity: 0,
      duration: 0.6
    }, '-=0.4');

    // 背景SVGアニメーションの微調整
    gsap.from('.hero__data-point', {
      scale: 0,
      opacity: 0,
      duration: 0.8,
      stagger: 0.1,
      ease: 'back.out(1.2)',
      delay: 0.5
    });

    // 接続線のアニメーション（DrawSVGの代わりにopacityとscaleを使用）
    gsap.from('.hero__line', {
      opacity: 0,
      scaleX: 0,
      transformOrigin: 'left',
      duration: 1.2,
      ease: 'power1.inOut',
      delay: 0.8
    });

    gsap.from('.hero__bar', {
      scaleY: 0,
      transformOrigin: 'bottom',
      duration: 0.8,
      stagger: 0.15,
      ease: 'power2.out',
      delay: 0.6
    });
  }

  /**
   * スクロール連動アニメーション
   * 各セクションがビューポートに入ったら実行
   */
  function initScrollAnimations() {
    // ScrollTriggerが利用可能か確認
    if (typeof ScrollTrigger === 'undefined') {
      console.warn('ScrollTrigger is not loaded. Scroll animations will be skipped.');
      // フォールバック: 全要素を表示
      gsap.set('.animate', { opacity: 1, y: 0 });
      return;
    }

    // セクションタイトル + 説明文
    gsap.utils.toArray('.section__title').forEach((title, index) => {
      gsap.from(title, {
        scrollTrigger: {
          trigger: title,
          start: 'top 85%',
          end: 'top 60%',
          toggleActions: 'play none none none',
          // markers: true // デバッグ用（本番ではコメントアウト）
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out'
      });
    });

    gsap.utils.toArray('.section__description').forEach((desc, index) => {
      gsap.from(desc, {
        scrollTrigger: {
          trigger: desc,
          start: 'top 85%',
          end: 'top 60%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 15,
        duration: 0.6,
        ease: 'power2.out',
        delay: 0.1
      });
    });

    // サービスカード（stagger効果で順番に表示）
    const serviceCards = document.querySelectorAll('.service-card');
    if (serviceCards.length > 0) {
      gsap.from(serviceCards, {
        scrollTrigger: {
          trigger: '.services-grid',
          start: 'top 80%',
          end: 'top 50%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 30,
        duration: 0.7,
        stagger: 0.15,
        ease: 'power2.out'
      });
    }

    // お知らせリスト
    const newsItems = document.querySelectorAll('.news-item');
    if (newsItems.length > 0) {
      gsap.from(newsItems, {
        scrollTrigger: {
          trigger: '.news-list',
          start: 'top 80%',
          end: 'top 50%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        stagger: 0.1,
        ease: 'power2.out'
      });
    }

    // ブログプレビューカード
    const blogCards = document.querySelectorAll('.blog-preview-card');
    if (blogCards.length > 0) {
      gsap.from(blogCards, {
        scrollTrigger: {
          trigger: '.blog-preview-grid',
          start: 'top 80%',
          end: 'top 50%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 25,
        duration: 0.7,
        stagger: 0.12,
        ease: 'power2.out'
      });
    }

    // CTAセクション
    const ctaSection = document.querySelector('.cta-section');
    if (ctaSection) {
      gsap.from('.cta-section__title', {
        scrollTrigger: {
          trigger: ctaSection,
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out'
      });

      gsap.from('.cta-section__description', {
        scrollTrigger: {
          trigger: ctaSection,
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 15,
        duration: 0.6,
        ease: 'power2.out',
        delay: 0.1
      });

      gsap.from('.cta-buttons', {
        scrollTrigger: {
          trigger: ctaSection,
          start: 'top 75%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 15,
        duration: 0.6,
        ease: 'power2.out',
        delay: 0.2
      });
    }

    // 汎用的な.animateクラス（上記で対応していない要素用）
    gsap.utils.toArray('.animate').forEach((element) => {
      // 既にアニメーション対象として処理済みか確認
      const isProcessed = element.closest('.hero__content') ||
                          element.classList.contains('section__title') ||
                          element.classList.contains('section__description') ||
                          element.classList.contains('service-card') ||
                          element.classList.contains('news-item') ||
                          element.classList.contains('blog-preview-card');

      if (!isProcessed) {
        gsap.from(element, {
          scrollTrigger: {
            trigger: element,
            start: 'top 85%',
            toggleActions: 'play none none none'
          },
          opacity: 0,
          y: 20,
          duration: 0.6,
          ease: 'power2.out'
        });
      }
    });
  }

  /**
   * パフォーマンス最適化: スムーズスクロールの有効化
   */
  function enableSmoothScrolling() {
    if (typeof ScrollTrigger !== 'undefined') {
      // ScrollTriggerのパフォーマンス最適化
      ScrollTrigger.config({
        limitCallbacks: true,
        syncInterval: 150
      });

      // モバイルでのスクロール最適化
      if (window.innerWidth < 768) {
        ScrollTrigger.normalizeScroll(true);
      }
    }
  }

  /**
   * 初期化実行
   * DOMContentLoadedまたはGSAP読み込み後に実行
   */
  function startAnimations() {
    enableSmoothScrolling();
    init();
  }

  // GSAP読み込み待機
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      // GSAPが読み込まれるまで少し待つ
      setTimeout(startAnimations, 100);
    });
  } else {
    setTimeout(startAnimations, 100);
  }

  // ウィンドウリサイズ時にScrollTriggerをリフレッシュ
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.refresh();
      }
    }, 250);
  });

})();
