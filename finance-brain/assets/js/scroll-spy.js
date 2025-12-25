/**
 * スクロールスパイ機能
 * スクロール位置に応じて目次のアクティブ項目をハイライト
 */

(function() {
  'use strict';

  // 設定
  const config = {
    tocLinkSelector: '.toc-link',
    sectionSelector: 'section[id]',
    activeClass: 'active',
    offset: 100, // ヘッダーの高さなどを考慮したオフセット
    smoothScrollDuration: 800
  };

  // 全ての目次リンクとセクションを取得
  const tocLinks = document.querySelectorAll(config.tocLinkSelector);
  const sections = document.querySelectorAll(config.sectionSelector);
  const sidebar = document.querySelector('.sidebar');

  // セクションIDとTOCリンクのマッピングを作成
  const sectionMap = new Map();
  sections.forEach(section => {
    const id = section.getAttribute('id');
    const link = document.querySelector(`.toc-link[href="#${id}"]`);
    if (link) {
      sectionMap.set(id, {
        section: section,
        link: link
      });
    }
  });

  /**
   * 現在表示されているセクションを検出
   */
  function getCurrentSection() {
    const scrollPosition = window.scrollY + config.offset;

    let currentSection = null;
    let currentDistance = Infinity;

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionBottom = sectionTop + sectionHeight;

      // スクロール位置がセクション内にあるかチェック
      if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
        const distance = Math.abs(scrollPosition - sectionTop);
        if (distance < currentDistance) {
          currentDistance = distance;
          currentSection = section;
        }
      }
    });

    // セクション内にない場合、最も近いセクションを選択
    if (!currentSection) {
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const distance = Math.abs(scrollPosition - sectionTop);
        if (distance < currentDistance) {
          currentDistance = distance;
          currentSection = section;
        }
      });
    }

    return currentSection;
  }

  /**
   * アクティブな目次項目を更新
   */
  function updateActiveLink() {
    const currentSection = getCurrentSection();

    if (!currentSection) return;

    const currentId = currentSection.getAttribute('id');
    const mapItem = sectionMap.get(currentId);

    if (!mapItem) return;

    // 全てのリンクからアクティブクラスを削除
    tocLinks.forEach(link => {
      link.classList.remove(config.activeClass);
    });

    // 現在のセクションのリンクにアクティブクラスを追加
    mapItem.link.classList.add(config.activeClass);

    // サイドバー内でアクティブ項目が見えるようにスクロール
    scrollActiveItemIntoView(mapItem.link);
  }

  /**
   * サイドバー内でアクティブ項目をスクロール
   */
  function scrollActiveItemIntoView(activeLink) {
    if (!sidebar || !activeLink) return;

    const sidebarRect = sidebar.getBoundingClientRect();
    const linkRect = activeLink.getBoundingClientRect();

    // リンクがサイドバーの表示範囲外にある場合、スクロール
    const isAboveViewport = linkRect.top < sidebarRect.top;
    const isBelowViewport = linkRect.bottom > sidebarRect.bottom;

    if (isAboveViewport || isBelowViewport) {
      const scrollTop = sidebar.scrollTop;
      const linkOffsetTop = activeLink.offsetTop;
      const sidebarHeight = sidebar.clientHeight;
      const linkHeight = activeLink.offsetHeight;

      // リンクをサイドバーの中央に配置
      const targetScrollTop = linkOffsetTop - (sidebarHeight / 2) + (linkHeight / 2);

      // スムーズスクロール
      sidebar.scrollTo({
        top: targetScrollTop,
        behavior: 'smooth'
      });
    }
  }

  /**
   * スムーズスクロールの実装
   */
  function setupSmoothScroll() {
    tocLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();

        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        if (targetSection) {
          const targetPosition = targetSection.offsetTop - config.offset + 20;

          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  }

  /**
   * スクロールイベントのthrottle処理
   */
  function throttle(func, delay) {
    let lastCall = 0;
    return function(...args) {
      const now = new Date().getTime();
      if (now - lastCall < delay) {
        return;
      }
      lastCall = now;
      return func(...args);
    };
  }

  /**
   * Intersection Observer を使った最適化版（モダンブラウザ用）
   */
  function setupIntersectionObserver() {
    // Intersection Observer がサポートされているかチェック
    if (!('IntersectionObserver' in window)) {
      // フォールバック: スクロールイベントを使用
      window.addEventListener('scroll', throttle(updateActiveLink, 100));
      return;
    }

    const observerOptions = {
      root: null,
      rootMargin: `-${config.offset}px 0px -50% 0px`,
      threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute('id');
          const mapItem = sectionMap.get(id);

          if (mapItem) {
            // 全てのリンクからアクティブクラスを削除
            tocLinks.forEach(link => {
              link.classList.remove(config.activeClass);
            });

            // 現在のセクションのリンクにアクティブクラスを追加
            mapItem.link.classList.add(config.activeClass);

            // サイドバー内でアクティブ項目が見えるようにスクロール
            scrollActiveItemIntoView(mapItem.link);
          }
        }
      });
    }, observerOptions);

    // 全セクションを監視
    sections.forEach(section => {
      observer.observe(section);
    });
  }

  /**
   * 初期化
   */
  function init() {
    // ページ読み込み時に最初のアクティブリンクを設定
    updateActiveLink();

    // スムーズスクロールの設定
    setupSmoothScroll();

    // Intersection Observer または スクロールイベントの設定
    setupIntersectionObserver();

    // リサイズ時にも更新
    window.addEventListener('resize', throttle(updateActiveLink, 200));
  }

  // DOMContentLoaded後に初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
