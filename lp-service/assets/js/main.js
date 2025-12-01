/**
 * LP制作サービス - メインJavaScript
 */

(function() {
  'use strict';

  /**
   * 初期化
   */
  function init() {
    setupSmoothScroll();
    setupScrollAnimations();
    setupMobileMenu();
    setupModalHandlers();
    setupFAQAccordion();
  }

  /**
   * スムーススクロール
   */
  function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          const headerHeight = document.querySelector('.header').offsetHeight;
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  }

  /**
   * スクロールアニメーション
   */
  function setupScrollAnimations() {
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
        }
      });
    }, observerOptions);

    document.querySelectorAll('[data-anim]').forEach(el => {
      observer.observe(el);
    });
  }

  /**
   * モバイルメニュー
   */
  function setupMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    if (!hamburger || !mobileMenu) return;

    // ハンバーガークリック
    hamburger.addEventListener('click', function() {
      toggleMenu();
    });

    // メニュー外クリックで閉じる
    document.addEventListener('click', function(e) {
      if (mobileMenu.classList.contains('active') &&
          !mobileMenu.contains(e.target) &&
          !hamburger.contains(e.target)) {
        toggleMenu();
      }
    });
  }

  /**
   * メニュートグル（グローバル関数）
   */
  window.toggleMenu = function() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    const body = document.body;

    if (mobileMenu.classList.contains('active')) {
      mobileMenu.classList.remove('active');
      hamburger.classList.remove('active');
      body.style.overflow = '';
    } else {
      mobileMenu.classList.add('active');
      hamburger.classList.add('active');
      body.style.overflow = 'hidden';
    }
  };

  /**
   * モーダルハンドラ
   */
  function setupModalHandlers() {
    const modal = document.getElementById('contactModal');
    if (!modal) return;

    // ESCキーで閉じる
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modal.classList.contains('active')) {
        closeModal();
      }
    });
  }

  /**
   * モーダルを開く（グローバル関数）
   */
  window.openModal = function() {
    const modal = document.getElementById('contactModal');
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';

      // Google Analytics イベント送信（GA4が設定されている場合）
      if (typeof gtag !== 'undefined') {
        gtag('event', 'modal_open', {
          'event_category': 'engagement',
          'event_label': 'contact_form'
        });
      }
    }
  };

  /**
   * モーダルを閉じる（グローバル関数）
   */
  window.closeModal = function() {
    const modal = document.getElementById('contactModal');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  /**
   * FAQアコーディオン（グローバル関数）
   */
  window.toggleFAQ = function(element) {
    element.classList.toggle('active');
  };

  /**
   * ページ読み込み完了時に初期化
   */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
