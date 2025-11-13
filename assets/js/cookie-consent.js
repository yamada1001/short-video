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
