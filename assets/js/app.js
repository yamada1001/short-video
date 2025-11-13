// ナビゲーション制御
(function() {
  'use strict';

  // DOMContentLoaded後に実行
  function init() {
    const hamburger = document.getElementById('hamburger');
    const navList = document.getElementById('navList');
    const header = document.getElementById('header');

    // ハンバーガーメニュートグル
    if (hamburger && navList) {
      hamburger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        hamburger.classList.toggle('hamburger--active');
        navList.classList.toggle('nav__list--active');
        navList.classList.toggle('nav__list--mobile');

        // body のスクロールを制御（スクロール位置を維持）
        if (navList.classList.contains('nav__list--active')) {
          const scrollY = window.scrollY;
          document.body.style.position = 'fixed';
          document.body.style.top = `-${scrollY}px`;
          document.body.style.width = '100%';
          document.body.style.overflow = 'hidden';
        } else {
          const scrollY = document.body.style.top;
          document.body.style.position = '';
          document.body.style.top = '';
          document.body.style.width = '';
          document.body.style.overflow = '';
          window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }
      });

      // モバイルメニュー内のリンクをクリックしたら閉じる
      const navLinks = navList.querySelectorAll('.nav__link');
      navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
          if (window.innerWidth <= 768) {
            hamburger.classList.remove('hamburger--active');
            navList.classList.remove('nav__list--active');
            navList.classList.remove('nav__list--mobile');
            const scrollY = document.body.style.top;
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflow = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
          }
        });
      });
    }

    // スクロール制御 - ヘッダーを常時表示（影のみ変更）
    if (header) {
      window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;

        // ヘッダーは常に表示し、スクロール時に影を追加
        if (currentScrollY > 50) {
          header.classList.add('header--scrolled');
        } else {
          header.classList.remove('header--scrolled');
        }
      }, { passive: true });
    }
  }

  // 初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
// 共通JavaScript
(function() {
  // スクロールアニメーション
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
  });

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.animate').forEach(function(el) {
      observer.observe(el);
    });
  });

  // スムーススクロール
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        const header = document.getElementById('header');
        const headerHeight = header ? header.offsetHeight : 0;
        const targetPosition = targetElement.offsetTop - headerHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
})();
/**
 * 外部リンク自動制御
 * yojitu.com以外のリンクを新しいタブで開く
 */

document.addEventListener('DOMContentLoaded', function() {
  // すべてのリンクを取得
  const links = document.querySelectorAll('a[href]');

  links.forEach(link => {
    const href = link.getAttribute('href');

    // 相対パスまたはアンカーリンクの場合はスキップ
    if (!href || href.startsWith('#') || href.startsWith('/') || href.startsWith('./') || href.startsWith('../')) {
      return;
    }

    try {
      const url = new URL(href, window.location.href);
      const currentDomain = window.location.hostname;

      // yojitu.com以外の外部リンクの場合
      if (url.hostname !== currentDomain && url.hostname !== 'yojitu.com' && url.hostname !== 'www.yojitu.com') {
        // target="_blank"を追加
        link.setAttribute('target', '_blank');
        // セキュリティ対策でrel属性を追加
        link.setAttribute('rel', 'noopener noreferrer');

        // 外部リンクアイコンを追加（オプション）
        if (!link.querySelector('.external-icon')) {
          const icon = document.createElement('span');
          icon.className = 'external-icon';
          icon.innerHTML = ' <i class="fas fa-external-link-alt" style="font-size: 0.8em; margin-left: 4px; opacity: 0.6;"></i>';
          link.appendChild(icon);
        }
      }
    } catch (e) {
      // URLのパースに失敗した場合はスキップ
      console.warn('Invalid URL:', href);
    }
  });
});
// Cookie同意バナー
(function() {
  'use strict';

  const COOKIE_NAME = 'cookie_consent';
  const COOKIE_EXPIRY_DAYS = 365;

  // Cookieを取得
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  // Cookieを設定
  function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
  }

  // Google Tag Managerを有効化
  function enableGTM() {
    // GTMのデータレイヤーに同意情報をプッシュ
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'event': 'cookie_consent_granted',
      'consent': 'granted'
    });

    // Google Consent Mode v2対応
    if (typeof gtag === 'function') {
      gtag('consent', 'update', {
        'analytics_storage': 'granted',
        'ad_storage': 'granted',
        'ad_user_data': 'granted',
        'ad_personalization': 'granted'
      });
    }
  }

  // Google Tag Managerを無効化
  function disableGTM() {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      'event': 'cookie_consent_denied',
      'consent': 'denied'
    });

    if (typeof gtag === 'function') {
      gtag('consent', 'update', {
        'analytics_storage': 'denied',
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied'
      });
    }
  }

  // バナーを表示
  function showBanner() {
    const banner = document.getElementById('cookieConsent');
    if (banner) {
      setTimeout(() => {
        banner.classList.add('show');
      }, 1000);
    }
  }

  // バナーを非表示
  function hideBanner() {
    const banner = document.getElementById('cookieConsent');
    if (banner) {
      banner.classList.remove('show');
    }
  }

  // 同意処理
  function acceptCookies() {
    setCookie(COOKIE_NAME, 'accepted', COOKIE_EXPIRY_DAYS);
    enableGTM();
    hideBanner();
  }

  // 拒否処理
  function declineCookies() {
    setCookie(COOKIE_NAME, 'declined', COOKIE_EXPIRY_DAYS);
    disableGTM();
    hideBanner();
  }

  // 初期化
  function init() {
    const consent = getCookie(COOKIE_NAME);

    if (consent === 'accepted') {
      // すでに同意済み
      enableGTM();
    } else if (consent === 'declined') {
      // すでに拒否済み
      disableGTM();
    } else {
      // 未選択の場合、バナーを表示
      showBanner();

      // 初期状態でGTMを無効化（Google Consent Mode v2）
      if (typeof gtag === 'function') {
        gtag('consent', 'default', {
          'analytics_storage': 'denied',
          'ad_storage': 'denied',
          'ad_user_data': 'denied',
          'ad_personalization': 'denied',
          'wait_for_update': 500
        });
      }
    }

    // ボタンのイベントリスナー設定
    const acceptBtn = document.getElementById('acceptCookies');
    const declineBtn = document.getElementById('declineCookies');

    if (acceptBtn) {
      acceptBtn.addEventListener('click', acceptCookies);
    }

    if (declineBtn) {
      declineBtn.addEventListener('click', declineCookies);
    }
  }

  // DOMContentLoadedで初期化
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
