/**
 * Common JavaScript
 * くるま買取ケイヴィレッジ
 * 全ページ共通のJavaScript
 */

(function() {
  'use strict';

  /* ========================================
     ヘッダースクロール処理
     ======================================== */

  const header = document.querySelector('.header');

  if (header) {
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      // スクロール位置が100px以上の場合、.scrolled クラスを追加
      if (scrollTop > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }

      lastScrollTop = scrollTop;
    });
  }

  /* ========================================
     ハンバーガーメニュー
     ======================================== */

  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');

  if (hamburger && mobileMenu) {
    // オーバーレイを作成
    const overlay = document.createElement('div');
    overlay.className = 'header__mobile-overlay';
    document.body.appendChild(overlay);

    hamburger.addEventListener('click', function() {
      this.classList.toggle('is-active');
      mobileMenu.classList.toggle('is-open');
      overlay.classList.toggle('is-visible');

      // body スクロール制御
      if (mobileMenu.classList.contains('is-open')) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // オーバーレイをクリックしたらメニューを閉じる
    overlay.addEventListener('click', function() {
      hamburger.classList.remove('is-active');
      mobileMenu.classList.remove('is-open');
      overlay.classList.remove('is-visible');
      document.body.style.overflow = '';
    });

    // モバイルメニュー内のリンクをクリックしたらメニューを閉じる
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', function() {
        hamburger.classList.remove('is-active');
        mobileMenu.classList.remove('is-open');
        overlay.classList.remove('is-visible');
        document.body.style.overflow = '';
      });
    });
  }

  /* ========================================
     スムーススクロール
     ======================================== */

  const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');

  smoothScrollLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      const href = this.getAttribute('href');

      // # だけの場合はページトップへ
      if (href === '#') {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
        return;
      }

      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  /* ========================================
     トップへ戻るボタン
     ======================================== */

  const backToTopBtn = document.getElementById('back-to-top');

  if (backToTopBtn) {
    // スクロールで表示/非表示切り替え
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTopBtn.style.opacity = '1';
        backToTopBtn.style.pointerEvents = 'auto';
      } else {
        backToTopBtn.style.opacity = '0';
        backToTopBtn.style.pointerEvents = 'none';
      }
    });

    // 初期状態
    backToTopBtn.style.opacity = '0';
    backToTopBtn.style.pointerEvents = 'none';
    backToTopBtn.style.transition = 'opacity 0.3s';
  }

  /* ========================================
     ヘッダー固定時の背景
     ======================================== */

  const header = document.querySelector('.header');

  if (header) {
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 50) {
        header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
      } else {
        header.style.boxShadow = '0 1px 2px 0 rgb(0 0 0 / 0.05)';
      }
    });
  }

  /* ========================================
     電話番号のフォーマット（SP）
     ======================================== */

  // SPでタップしやすいように電話リンクを調整
  const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
  phoneLinks.forEach(link => {
    link.style.textDecoration = 'none';
  });

  /* ========================================
     外部リンクに target="_blank" を追加
     ======================================== */

  const externalLinks = document.querySelectorAll('a[href^="http"]');
  externalLinks.forEach(link => {
    const url = new URL(link.href);
    // 自サイト以外の場合
    if (url.hostname !== window.location.hostname) {
      link.setAttribute('target', '_blank');
      link.setAttribute('rel', 'noopener noreferrer');
    }
  });

  /* ========================================
     画像の遅延読み込み（Lazy Loading）
     ======================================== */

  if ('loading' in HTMLImageElement.prototype) {
    // ブラウザがloading属性をサポートしている場合は何もしない
  } else {
    // Intersection Observer でフォールバック（古いブラウザ用）
    const images = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src || img.src;
            img.classList.add('loaded');
            observer.unobserve(img);
          }
        });
      });

      images.forEach(img => {
        imageObserver.observe(img);
      });
    } else {
      // Intersection Observer 非対応の場合は即座に読み込み
      images.forEach(img => {
        img.src = img.dataset.src || img.src;
      });
    }
  }

  /* ========================================
     フォームのEnterキー送信防止（誤送信防止）
     ======================================== */

  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('keypress', function(e) {
      // textareaは除外
      if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
      }
    });
  });

  /* ========================================
     現在時刻の表示（営業時間チェック用）
     ======================================== */

  function updateBusinessStatus() {
    const now = new Date();
    const day = now.getDay(); // 0:日曜
    const hour = now.getHours();

    const statusElements = document.querySelectorAll('.business-status');

    statusElements.forEach(element => {
      if (day === 0) {
        // 日曜日
        element.textContent = '本日は定休日です';
        element.style.color = '#ef4444';
      } else if (hour >= 9 && hour < 18) {
        // 営業時間内
        element.textContent = '営業中';
        element.style.color = '#10b981';
      } else {
        // 営業時間外
        element.textContent = '営業時間外';
        element.style.color = '#f59e0b';
      }
    });
  }

  // ページ読み込み時とリサイズ時に実行
  updateBusinessStatus();
  window.addEventListener('resize', updateBusinessStatus);

  /* ========================================
     コンソールログ（開発用）
     ======================================== */

  console.log('%cくるま買取ケイヴィレッジ', 'font-size: 20px; font-weight: bold; color: #2563eb;');
  console.log('Website developed by YOJITU.COM');

})();

/* ========================================
   Service Modal Functions
   ======================================== */

function openServiceModal(serviceId) {
  const modal = document.getElementById('service-modal');
  const modalItems = document.querySelectorAll('.service-modal__item');

  // すべてのモーダルアイテムを非表示
  modalItems.forEach(item => item.classList.remove('active'));

  // 指定されたサービスのモーダルアイテムを表示
  const targetItem = document.getElementById('modal-' + serviceId);
  if (targetItem) {
    targetItem.classList.add('active');
  }

  // モーダルを表示
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeServiceModal() {
  const modal = document.getElementById('service-modal');
  modal.classList.remove('active');
  document.body.style.overflow = '';
}

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeServiceModal();
  }
});

/* ========================================
   Inventory Swiper Initialization
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.inventory-swiper')) {
    new Swiper('.inventory-swiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 24,
        },
      },
    });
  }

  /* ========================================
     サービスタブ切り替え
     ======================================== */

  window.switchServiceTab = function(tabId) {
    // すべてのタブボタンから active クラスを削除
    const tabs = document.querySelectorAll('.service-tabs__tab');
    tabs.forEach(tab => tab.classList.remove('active'));

    // クリックされたタブに active クラスを追加
    const activeTab = document.querySelector(`.service-tabs__tab[data-tab="${tabId}"]`);
    if (activeTab) {
      activeTab.classList.add('active');
    }

    // すべてのパネルから active クラスを削除
    const panels = document.querySelectorAll('.service-tabs__panel');
    panels.forEach(panel => panel.classList.remove('active'));

    // 対応するパネルに active クラスを追加
    const activePanel = document.getElementById(`tab-${tabId}`);
    if (activePanel) {
      activePanel.classList.add('active');
    }
  };
});
