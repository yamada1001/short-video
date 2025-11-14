/**
 * 余日（Yojitsu）GSAP アニメーションシステム
 * 無印良品スタイル：控えめで上品な動き
 *
 * 安全な実装：要素は初期状態で表示し、GSAPが読み込まれてからアニメーション
 */

(function() {
  'use strict';

  // アクセシビリティ確認
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /**
   * GSAPとScrollTriggerの読み込み確認
   */
  function checkGSAPLoaded() {
    return typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined';
  }

  /**
   * メイン初期化関数
   */
  function init() {
    // GSAP読み込み確認
    if (!checkGSAPLoaded()) {
      console.log('GSAP not loaded yet, animations skipped');
      return;
    }

    // ScrollTrigger登録
    gsap.registerPlugin(ScrollTrigger);

    // prefers-reduced-motionが有効な場合はアニメーションスキップ
    if (prefersReducedMotion) {
      console.log('Reduced motion preference detected, animations disabled');
      return;
    }

    // アニメーション実装
    initHeroAnimations();
    initScrollAnimations();

    console.log('GSAP animations initialized');
  }

  /**
   * ヒーローセクションのアニメーション
   * ページ読み込み時に実行
   */
  function initHeroAnimations() {
    const heroLabel = document.querySelector('.hero__label');
    const heroDescription = document.querySelector('.hero__description');
    const heroCta = document.querySelector('.hero__cta');

    // 要素が存在しない場合は終了
    if (!heroLabel || !heroDescription) {
      console.log('Hero elements not found');
      return;
    }

    // GSAPタイムライン作成
    const tl = gsap.timeline({
      defaults: {
        ease: 'power2.out'
      }
    });

    // 1. ラベルのフェードイン
    tl.from(heroLabel, {
      opacity: 0,
      y: 30,
      duration: 0.8
    });

    // 2. メインテキストのフェードイン（0.3秒遅れ）
    tl.from(heroDescription, {
      opacity: 0,
      y: 30,
      duration: 0.8
    }, '-=0.5');

    // 3. CTAボタンのフェードイン（存在する場合）
    if (heroCta) {
      tl.from(heroCta, {
        opacity: 0,
        y: 20,
        duration: 0.6
      }, '-=0.4');
    }

    // 背景要素のアニメーション
    const dataPoints = document.querySelectorAll('.hero__data-point');
    if (dataPoints.length > 0) {
      gsap.from(dataPoints, {
        scale: 0,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: 'back.out(1.2)',
        delay: 0.5
      });
    }
  }

  /**
   * スクロール連動アニメーション
   */
  function initScrollAnimations() {
    // セクションタイトル
    const sectionTitles = document.querySelectorAll('.section__title');
    sectionTitles.forEach((title) => {
      gsap.from(title, {
        scrollTrigger: {
          trigger: title,
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out'
      });
    });

    // セクション説明文
    const sectionDescriptions = document.querySelectorAll('.section__description');
    sectionDescriptions.forEach((desc) => {
      gsap.from(desc, {
        scrollTrigger: {
          trigger: desc,
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        ease: 'power2.out',
        delay: 0.1
      });
    });

    // サービスカード
    const serviceCards = document.querySelectorAll('.service-card');
    if (serviceCards.length > 0) {
      gsap.from(serviceCards, {
        scrollTrigger: {
          trigger: '.services-grid',
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 30,
        duration: 0.7,
        stagger: 0.15,
        ease: 'power2.out'
      });
    }

    // お知らせアイテム
    const newsItems = document.querySelectorAll('.news-item');
    if (newsItems.length > 0) {
      gsap.from(newsItems, {
        scrollTrigger: {
          trigger: '.news-list',
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.6,
        stagger: 0.1,
        ease: 'power2.out'
      });
    }

    // ブログカード
    const blogCards = document.querySelectorAll('.blog-preview-card');
    if (blogCards.length > 0) {
      gsap.from(blogCards, {
        scrollTrigger: {
          trigger: '.blog-preview-grid',
          start: 'top 80%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 25,
        duration: 0.7,
        stagger: 0.12,
        ease: 'power2.out'
      });
    }
  }

  /**
   * 初期化のタイミング制御
   */
  function startAnimations() {
    // GSAP読み込み待機（最大3秒）
    let attempts = 0;
    const maxAttempts = 30; // 30回 × 100ms = 3秒

    const checkInterval = setInterval(() => {
      attempts++;

      if (checkGSAPLoaded()) {
        clearInterval(checkInterval);
        init();
      } else if (attempts >= maxAttempts) {
        clearInterval(checkInterval);
        console.warn('GSAP loading timeout - animations skipped');
      }
    }, 100);
  }

  // DOMContentLoaded後に実行
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startAnimations);
  } else {
    startAnimations();
  }

  // リサイズ時のScrollTriggerリフレッシュ
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (checkGSAPLoaded()) {
        ScrollTrigger.refresh();
      }
    }, 250);
  });

})();
