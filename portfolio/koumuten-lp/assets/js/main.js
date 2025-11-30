/**
 * 山田工務店 ランディングページ - メインJavaScript
 * 最高峰のリッチなインタラクション実装
 *
 * @author Yamada Koumuten
 * @version 1.0.0
 */

(function() {
  'use strict';

  // ========================================
  // ユーティリティ関数
  // ========================================

  /**
   * Throttle - 連続実行を制限（パフォーマンス最適化）
   * @param {Function} func - 実行する関数
   * @param {number} limit - 実行間隔（ミリ秒）
   * @returns {Function}
   */
  const throttle = (func, limit) => {
    let inThrottle;
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  };

  /**
   * Debounce - 最後の実行から一定時間後に実行（パフォーマンス最適化）
   * @param {Function} func - 実行する関数
   * @param {number} delay - 遅延時間（ミリ秒）
   * @returns {Function}
   */
  const debounce = (func, delay) => {
    let timeoutId;
    return function(...args) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
  };

  /**
   * スムーズスクロール関数
   * @param {HTMLElement} target - スクロール先の要素
   * @param {number} offset - オフセット値（px）
   */
  const smoothScrollTo = (target, offset = 0) => {
    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    const duration = 800; // スクロール時間（ミリ秒）
    let start = null;

    const easeInOutCubic = (t) => {
      return t < 0.5
        ? 4 * t * t * t
        : 1 - Math.pow(-2 * t + 2, 3) / 2;
    };

    const animation = (currentTime) => {
      if (start === null) start = currentTime;
      const timeElapsed = currentTime - start;
      const progress = Math.min(timeElapsed / duration, 1);
      const ease = easeInOutCubic(progress);

      window.scrollTo(0, startPosition + distance * ease);

      if (timeElapsed < duration) {
        requestAnimationFrame(animation);
      }
    };

    requestAnimationFrame(animation);
  };

  // ========================================
  // 1. オープニングアニメーション
  // ========================================

  const initOpeningAnimation = () => {
    const VISITED_KEY = 'yamada_koumuten_visited';
    const openingScreen = document.querySelector('.js-opening');
    const header = document.querySelector('.header');
    const hero = document.querySelector('.hero');

    if (!openingScreen) return;

    try {
      // 初回訪問判定
      const hasVisited = localStorage.getItem(VISITED_KEY);

      if (!hasVisited) {
        // 初回訪問時の処理
        document.body.style.overflow = 'hidden';

        // 2.5秒後にオープニング終了
        setTimeout(() => {
          openingScreen.classList.add('is-hidden');
          document.body.style.overflow = '';

          // ヘッダーとHeroをフェードイン
          if (header) {
            header.style.opacity = '0';
            header.style.transition = 'opacity 1s ease';
            setTimeout(() => {
              header.style.opacity = '1';
            }, 100);
          }

          if (hero) {
            hero.style.opacity = '0';
            hero.style.transition = 'opacity 1.2s ease';
            setTimeout(() => {
              hero.style.opacity = '1';
            }, 200);
          }

          // localStorageに訪問記録を保存
          try {
            localStorage.setItem(VISITED_KEY, 'true');
          } catch (e) {
            console.warn('localStorage書き込みエラー:', e);
          }

          // オープニング要素を削除（メモリ解放）
          setTimeout(() => {
            if (openingScreen.parentNode) {
              openingScreen.parentNode.removeChild(openingScreen);
            }
          }, 1000);
        }, 2500);
      } else {
        // 2回目以降の訪問時は即座に非表示
        openingScreen.style.display = 'none';
        if (header) header.style.opacity = '1';
        if (hero) hero.style.opacity = '1';
      }
    } catch (e) {
      console.error('オープニングアニメーションエラー:', e);
      // エラー時は即座に表示
      if (openingScreen) openingScreen.style.display = 'none';
      if (header) header.style.opacity = '1';
      if (hero) hero.style.opacity = '1';
    }
  };

  // ========================================
  // 2. メニュー開閉
  // ========================================

  const initMenuToggle = () => {
    const menuButton = document.querySelector('.js-menu-toggle');
    const menuLinks = document.querySelectorAll('.js-menu-link');
    const body = document.body;

    if (!menuButton) return;

    // ハンバーガーメニューのクリックイベント
    menuButton.addEventListener('click', (e) => {
      e.preventDefault();
      body.classList.toggle('menu-open');

      // アクセシビリティ対応
      const isOpen = body.classList.contains('menu-open');
      menuButton.setAttribute('aria-expanded', isOpen);

      // メニューオープン時はスクロール無効化
      if (isOpen) {
        body.style.overflow = 'hidden';
      } else {
        body.style.overflow = '';
      }
    });

    // メニュー内のリンククリックでメニューを閉じる
    menuLinks.forEach(link => {
      link.addEventListener('click', () => {
        body.classList.remove('menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
        body.style.overflow = '';
      });
    });

    // ESCキーでメニューを閉じる
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && body.classList.contains('menu-open')) {
        body.classList.remove('menu-open');
        menuButton.setAttribute('aria-expanded', 'false');
        body.style.overflow = '';
      }
    });
  };

  // ========================================
  // 3. スムーズスクロール
  // ========================================

  const initSmoothScroll = () => {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    const headerHeight = document.querySelector('.header')?.offsetHeight || 80;

    anchorLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');

        // "#"のみの場合はページトップへ
        if (href === '#') {
          e.preventDefault();
          smoothScrollTo(document.body, 0);
          return;
        }

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          smoothScrollTo(target, headerHeight);

          // URLハッシュを更新（履歴には追加しない）
          history.replaceState(null, '', href);
        }
      });
    });
  };

  // ========================================
  // 4. ヘッダースクロール制御
  // ========================================

  const initHeaderScroll = () => {
    const header = document.querySelector('.header');
    if (!header) return;

    const SCROLL_THRESHOLD = 50;
    let lastScrollTop = 0;

    const handleScroll = () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      // スクロール量が閾値を超えたらクラス追加
      if (scrollTop > SCROLL_THRESHOLD) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }

      lastScrollTop = scrollTop;
    };

    // throttleでパフォーマンス最適化
    window.addEventListener('scroll', throttle(handleScroll, 100), { passive: true });

    // 初期状態チェック
    handleScroll();
  };

  // ========================================
  // 5. スクロールアニメーション（Intersection Observer）
  // ========================================

  const initScrollAnimation = () => {
    // 対応するアニメーションクラス
    const animationClasses = [
      '.js-fadeIn',
      '.js-slideInLeft',
      '.js-slideInRight',
      '.js-scaleIn'
    ];

    const targets = document.querySelectorAll(animationClasses.join(','));
    if (targets.length === 0) return;

    const observerOptions = {
      root: null,
      rootMargin: '0px 0px -100px 0px', // ビューポート下部100px手前で発火
      threshold: 0
    };

    const observerCallback = (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // 要素が表示されたらアニメーション開始
          entry.target.classList.add('is-visible');

          // 一度アニメーションしたら監視解除（パフォーマンス向上）
          observer.unobserve(entry.target);
        }
      });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    targets.forEach(target => {
      observer.observe(target);
    });
  };

  // ========================================
  // 6. スタッガーアニメーション
  // ========================================

  const initStaggerAnimation = () => {
    const staggerContainers = document.querySelectorAll('.js-stagger');
    if (staggerContainers.length === 0) return;

    const observerOptions = {
      root: null,
      rootMargin: '0px 0px -100px 0px',
      threshold: 0.1
    };

    const observerCallback = (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const children = entry.target.children;

          // 各子要素に順次遅延を設定
          Array.from(children).forEach((child, index) => {
            setTimeout(() => {
              child.classList.add('is-visible');
            }, index * 100); // 100ms間隔
          });

          // 監視解除
          observer.unobserve(entry.target);
        }
      });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    staggerContainers.forEach(container => {
      observer.observe(container);
    });
  };

  // ========================================
  // 7. パララックス効果
  // ========================================

  const initParallax = () => {
    const parallaxElements = document.querySelectorAll('.js-parallax');
    if (parallaxElements.length === 0) return;

    // 各要素のパララックス速度を設定
    const parallaxData = Array.from(parallaxElements).map(el => {
      const speed = el.dataset.parallaxSpeed || 'medium';
      const speedValue = {
        'slow': 0.2,
        'medium': 0.5,
        'fast': 0.8
      }[speed] || 0.5;

      return {
        element: el,
        speed: speedValue,
        initialTop: el.getBoundingClientRect().top + window.pageYOffset
      };
    });

    let ticking = false;

    const updateParallax = () => {
      const scrolled = window.pageYOffset;

      parallaxData.forEach(({ element, speed, initialTop }) => {
        const elementTop = element.getBoundingClientRect().top + window.pageYOffset;
        const distance = scrolled - initialTop;
        const yPos = -(distance * speed);

        // GPU アクセラレーションを使用
        element.style.transform = `translate3d(0, ${yPos}px, 0)`;
      });

      ticking = false;
    };

    const requestTick = () => {
      if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
      }
    };

    window.addEventListener('scroll', requestTick, { passive: true });

    // 初期位置設定
    updateParallax();
  };

  // ========================================
  // 8. ページトップボタン
  // ========================================

  const initPageTop = () => {
    const pageTopButton = document.querySelector('.js-pagetop');
    if (!pageTopButton) return;

    // ページトップへスクロール
    pageTopButton.addEventListener('click', (e) => {
      e.preventDefault();
      smoothScrollTo(document.body, 0);
    });

    // スクロール位置に応じて表示/非表示
    const SHOW_THRESHOLD = 300;

    const handleScroll = () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > SHOW_THRESHOLD) {
        pageTopButton.classList.add('is-visible');
      } else {
        pageTopButton.classList.remove('is-visible');
      }
    };

    window.addEventListener('scroll', throttle(handleScroll, 200), { passive: true });

    // 初期状態チェック
    handleScroll();
  };

  // ========================================
  // 9. リップルエフェクト
  // ========================================

  const initRippleEffect = () => {
    const rippleButtons = document.querySelectorAll('.js-ripple');
    if (rippleButtons.length === 0) return;

    rippleButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        // 既存のリップルを削除
        const existingRipple = this.querySelector('.ripple');
        if (existingRipple) {
          existingRipple.remove();
        }

        // リップル要素を作成
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');

        // クリック位置を計算
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        // リップルのスタイル設定
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        // ボタンに相対配置がない場合は追加
        if (getComputedStyle(this).position === 'static') {
          this.style.position = 'relative';
        }

        // ボタンにoverflowがない場合は追加
        if (getComputedStyle(this).overflow === 'visible') {
          this.style.overflow = 'hidden';
        }

        // リップルを追加
        this.appendChild(ripple);

        // アニメーション終了後に削除
        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });
  };

  // ========================================
  // 10. パフォーマンス最適化 - 画像遅延読み込み
  // ========================================

  const initLazyLoading = () => {
    const lazyImages = document.querySelectorAll('img[data-src]');
    if (lazyImages.length === 0) return;

    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          const src = img.dataset.src;

          if (src) {
            img.src = src;
            img.removeAttribute('data-src');
            img.classList.add('loaded');
          }

          observer.unobserve(img);
        }
      });
    }, {
      rootMargin: '50px'
    });

    lazyImages.forEach(img => imageObserver.observe(img));
  };

  // ========================================
  // フォームバリデーション（追加機能）
  // ========================================

  const initFormValidation = () => {
    const forms = document.querySelectorAll('.js-form-validate');
    if (forms.length === 0) return;

    forms.forEach(form => {
      const inputs = form.querySelectorAll('input, textarea, select');

      inputs.forEach(input => {
        // リアルタイムバリデーション
        input.addEventListener('blur', () => {
          validateField(input);
        });

        input.addEventListener('input', debounce(() => {
          if (input.classList.contains('is-invalid')) {
            validateField(input);
          }
        }, 300));
      });

      // フォーム送信時のバリデーション
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        let isValid = true;
        inputs.forEach(input => {
          if (!validateField(input)) {
            isValid = false;
          }
        });

        if (isValid) {
          // フォーム送信処理
          console.log('フォーム送信:', new FormData(form));
          form.classList.add('is-submitted');
        }
      });
    });

    function validateField(field) {
      const value = field.value.trim();
      const type = field.type;
      const required = field.hasAttribute('required');
      let isValid = true;
      let errorMessage = '';

      // 必須チェック
      if (required && !value) {
        isValid = false;
        errorMessage = 'この項目は必須です';
      }

      // メールアドレスチェック
      if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          isValid = false;
          errorMessage = '正しいメールアドレスを入力してください';
        }
      }

      // 電話番号チェック
      if (type === 'tel' && value) {
        const telRegex = /^[\d\-\+\(\)\s]+$/;
        if (!telRegex.test(value)) {
          isValid = false;
          errorMessage = '正しい電話番号を入力してください';
        }
      }

      // エラー表示
      const errorElement = field.parentElement.querySelector('.error-message');

      if (!isValid) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');

        if (errorElement) {
          errorElement.textContent = errorMessage;
          errorElement.style.display = 'block';
        }
      } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');

        if (errorElement) {
          errorElement.style.display = 'none';
        }
      }

      return isValid;
    }
  };

  // ========================================
  // スクロール進捗インジケーター（追加機能）
  // ========================================

  const initScrollProgress = () => {
    const progressBar = document.querySelector('.js-scroll-progress');
    if (!progressBar) return;

    const updateProgress = () => {
      const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = (window.pageYOffset / windowHeight) * 100;

      progressBar.style.width = scrolled + '%';
    };

    window.addEventListener('scroll', throttle(updateProgress, 50), { passive: true });
    updateProgress();
  };

  // ========================================
  // カウントアップアニメーション（追加機能）
  // ========================================

  const initCountUp = () => {
    const countElements = document.querySelectorAll('.js-count-up');
    if (countElements.length === 0) return;

    const animateCount = (element) => {
      const target = parseInt(element.dataset.count) || 0;
      const duration = parseInt(element.dataset.duration) || 2000;
      const start = 0;
      const increment = target / (duration / 16); // 60fps

      let current = start;
      const timer = setInterval(() => {
        current += increment;

        if (current >= target) {
          element.textContent = target.toLocaleString();
          clearInterval(timer);
        } else {
          element.textContent = Math.floor(current).toLocaleString();
        }
      }, 16);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCount(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.5
    });

    countElements.forEach(el => observer.observe(el));
  };

  // ========================================
  // モーダル制御（追加機能）
  // ========================================

  const initModal = () => {
    const modalTriggers = document.querySelectorAll('.js-modal-trigger');
    const modals = document.querySelectorAll('.js-modal');

    if (modalTriggers.length === 0 || modals.length === 0) return;

    modalTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const modalId = trigger.dataset.modal;
        const modal = document.getElementById(modalId);

        if (modal) {
          modal.classList.add('is-active');
          document.body.style.overflow = 'hidden';
        }
      });
    });

    modals.forEach(modal => {
      const closeButtons = modal.querySelectorAll('.js-modal-close');

      closeButtons.forEach(button => {
        button.addEventListener('click', () => {
          modal.classList.remove('is-active');
          document.body.style.overflow = '';
        });
      });

      // モーダル外クリックで閉じる
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.remove('is-active');
          document.body.style.overflow = '';
        }
      });
    });

    // ESCキーでモーダルを閉じる
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        modals.forEach(modal => {
          if (modal.classList.contains('is-active')) {
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
          }
        });
      }
    });
  };

  // ========================================
  // テキストタイピングアニメーション（追加機能）
  // ========================================

  const initTypingAnimation = () => {
    const typingElements = document.querySelectorAll('.js-typing');
    if (typingElements.length === 0) return;

    const typeText = (element) => {
      const text = element.dataset.text || element.textContent;
      const speed = parseInt(element.dataset.speed) || 100;

      element.textContent = '';
      element.style.opacity = '1';

      let i = 0;
      const timer = setInterval(() => {
        if (i < text.length) {
          element.textContent += text.charAt(i);
          i++;
        } else {
          clearInterval(timer);
        }
      }, speed);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          typeText(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.5
    });

    typingElements.forEach(el => observer.observe(el));
  };

  // ========================================
  // パフォーマンスモニタリング（開発用）
  // ========================================

  const logPerformance = () => {
    if (window.performance && window.performance.timing) {
      window.addEventListener('load', () => {
        setTimeout(() => {
          const perfData = window.performance.timing;
          const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
          const connectTime = perfData.responseEnd - perfData.requestStart;
          const renderTime = perfData.domComplete - perfData.domLoading;

          console.log('=== パフォーマンスメトリクス ===');
          console.log(`ページ読み込み時間: ${pageLoadTime}ms`);
          console.log(`接続時間: ${connectTime}ms`);
          console.log(`レンダリング時間: ${renderTime}ms`);
          console.log('============================');
        }, 0);
      });
    }
  };

  // ========================================
  // 初期化処理
  // ========================================

  const init = () => {
    console.log('山田工務店 LP - 初期化開始');

    // 各機能を初期化
    initOpeningAnimation();
    initMenuToggle();
    initSmoothScroll();
    initHeaderScroll();
    initScrollAnimation();
    initStaggerAnimation();
    initParallax();
    initPageTop();
    initRippleEffect();
    initLazyLoading();
    initFormValidation();
    initScrollProgress();
    initCountUp();
    initModal();
    initTypingAnimation();

    // 開発環境のみパフォーマンスログを出力
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
      logPerformance();
    }

    console.log('山田工務店 LP - 初期化完了');
  };

  // DOM読み込み完了後に初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // ページ離脱前のクリーンアップ
  window.addEventListener('beforeunload', () => {
    // 必要に応じてクリーンアップ処理を追加
    console.log('ページ離脱');
  });

})();
