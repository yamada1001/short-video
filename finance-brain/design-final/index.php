<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイナンスブレーン | 大分の【保険・投資信託・資産運用】のコンサルタント</title>
  <meta name="description" content="FPや投資診断士など、大分県内の専門資格所有者が多数在籍し、お金に関するご相談を幅広く承っています。お金の貯め方・増やし方・残し方に迷うことなく、安心して将来に備えたい方は他にいませんか？">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;900&family=Noto+Serif+JP:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- ClashDisplay Variable Font (ローカルホスト用はCDN、本番はローカルファイル推奨) -->
  <style>
    @font-face {
      font-family: 'ClashDisplay-Variable';
      font-style: normal;
      font-weight: 200 700;
      src: url('https://cdn.jsdelivr.net/gh/SorkinType/ClashDisplay@latest/fonts/variable/ClashDisplay-Variable.woff2') format('woff2');
      font-display: swap;
    }
  </style>

  <style>
    /* ========================================
       CSS Custom Properties (BuySell Technologies方式)
    ======================================== */
    :root {
      /* Viewport計算 */
      --viewport-width: 100vw;
      --mw: max(1440px, 90rem);
      --scale: 1;
      --px: 1px;
      --rem: 1rem;

      /* Colors - Finance Brain */
      --white: #fff;
      --black: #222;
      --blue: #5767bf;
      --dark-blue: #4a5ab3;
      --darker-blue: #3a4a8f;
      --light-blue: #cfe2ff;
      --orange: #ff8c42;
      --dark-orange: #e67e22;

      /* Gradients (6種類 - BuySell Technologies方式) */
      --gradient-1: linear-gradient(90deg, #5767bf 0%, #4a5ab3 100%);
      --gradient-2: linear-gradient(90deg, #5767bf 0%, #6b7ac7 30.29%, #5767bf 80.29%, #4a5ab3 100%);
      --gradient-3: linear-gradient(90deg, #e8ecff 0%, #e4f2fe 50%, #e1e9ff 100%);
      --gradient-4: linear-gradient(90deg, #f0f3ff 0%, #f7f9fd 50.48%, #e8ecff 100%);
      --gradient-5: linear-gradient(114deg, #d9e3ff 0%, #c8e6ff 39.4%, #cddeff 84.03%, #d0d6f5 98.75%);
      --gradient-6: linear-gradient(93deg, #afd9ff 20.13%, #bdb9ff 81.55%), #5767bf;

      /* Text Colors */
      --text-dark: #333;
      --text-medium: #666;
      --text-light: #999;

      /* Background */
      --bg-white: #fff;
      --bg-light: #f5f7fa;
      --bg-lighter: #fafbfc;

      /* Border */
      --border-01: #cbd4db;
      --border-02: rgb(221 221 221 / 20%);

      /* Spacing (clamp()で流動的) */
      --grid-gutter: clamp(20px, 2.5vw, 40px);
      --inline-space-sm: clamp(8px, 1vw, 16px);
      --inline-space-md: clamp(10px, 1.25vw, 20px);
      --inline-space-lg: clamp(20px, 2.5vw, 40px);
      --inline-space-xl: clamp(30px, 4vw, 80px);

      /* Font Sizes (clamp()で流動的) */
      --fz-root: clamp(0.9375rem, 1vw, 1rem);
      --fz-3xlg: clamp(1.5rem, 2vw + 0.5rem, 2.5rem);
      --fz-2xlg: clamp(1.375rem, 1.5vw + 0.5rem, 2rem);
      --fz-xlg: clamp(1.25rem, 1.25vw + 0.5rem, 1.75rem);
      --fz-lg: clamp(1.125rem, 1vw + 0.25rem, 1.5rem);
      --fz-md: clamp(1rem, 0.75vw + 0.25rem, 1.25rem);
      --fz-sm: clamp(0.875rem, 0.5vw + 0.25rem, 1rem);
      --fz-xs: clamp(0.75rem, 0.5vw + 0.15rem, 0.875rem);
      --fz-en-lg: clamp(2rem, 2.5vw + 1rem, 3.5rem);
      --fz-en-md: clamp(1.25rem, 1.5vw + 0.5rem, 2rem);
      --fz-en-sm: clamp(0.875rem, 0.75vw + 0.25rem, 1.125rem);

      /* Font Families */
      --font-jp: 'Noto Sans JP', 'Hiragino Sans', 'ヒラギノ角ゴ ProN', sans-serif;
      --font-jp-serif: 'Noto Serif JP', 'Yu Mincho', serif;
      --font-en: 'ClashDisplay-Variable', -apple-system, BlinkMacSystemFont, sans-serif;

      /* Animation */
      --duration: 1s;
      --easing: cubic-bezier(.23, 1, .32, 1);
    }

    /* ========================================
       Reset & Base
    ======================================== */
    *,
    *:before,
    *:after {
      box-sizing: border-box;
    }

    * {
      margin: 0;
      padding: 0;
    }

    html {
      font-size: var(--fz-root);
      scroll-behavior: smooth;
      -webkit-text-size-adjust: 100%;
    }

    body {
      font-family: var(--font-jp);
      font-weight: 400;
      font-size: 1rem;
      line-height: 1.75;
      letter-spacing: 0.04em;
      color: var(--text-dark);
      background: var(--gradient-2);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      overflow-x: hidden;
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
      vertical-align: bottom;
    }

    a {
      color: inherit;
      text-decoration: none;
      display: inline-block;
    }

    ul, ol {
      list-style: none;
    }

    button {
      font-family: inherit;
      cursor: pointer;
      border: none;
      background: none;
    }

    /* ========================================
       SVG Icons System (BuySell Technologies方式)
    ======================================== */
    svg {
      width: 100%;
      height: auto;
    }

    .icon {
      display: inline-block;
      width: 1em;
      height: 1em;
    }

    .icon svg {
      fill: currentColor;
    }

    /* ========================================
       Utility Classes
    ======================================== */
    .container {
      max-width: min(1440px, 90rem);
      margin: 0 auto;
      padding: 0 var(--inline-space-md);
    }

    .section {
      padding: clamp(2.5rem, 5vw, 5rem) 0;
    }

    .section-white {
      background: var(--bg-white);
      border-radius: 10px;
    }

    /* ========================================
       Typography
    ======================================== */
    .heading-en {
      font-family: var(--font-en);
      font-weight: 500;
      font-variation-settings: "wght" 500;
      line-height: 1;
      letter-spacing: -0.01em;
      color: transparent;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      background-clip: text;
    }

    .heading-group {
      display: flex;
      flex-direction: column-reverse;
      gap: clamp(0.125rem, 0.25vw, 0.3125rem);
    }

    .heading-group .heading-en {
      font-size: var(--fz-en-md);
    }

    .heading-group .heading-jp {
      font-size: var(--fz-2xlg);
      font-weight: 600;
      color: var(--text-dark);
    }

    /* ========================================
       Header
    ======================================== */
    .header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: var(--bg-white);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .header-inner {
      display: grid;
      grid-template-columns: auto 1fr auto;
      align-items: center;
      gap: var(--grid-gutter);
      padding: clamp(0.625rem, 1.5vw, 1.25rem) var(--inline-space-md);
    }

    .header-logo {
      display: grid;
      gap: 4px;
    }

    .logo-main {
      font-size: clamp(1.25rem, 1.75vw, 1.75rem);
      font-weight: 700;
      color: var(--blue);
      font-family: var(--font-jp);
    }

    .logo-sub {
      font-size: clamp(0.625rem, 0.75vw, 0.75rem);
      color: var(--text-medium);
      letter-spacing: 0.05em;
    }

    .header-nav {
      display: none;
    }

    @media (min-width: 768px) {
      .header-nav {
        display: block;
      }

      .nav-list {
        display: flex;
        gap: clamp(20px, 2vw, 32px);
      }

      .nav-item a {
        font-size: var(--fz-sm);
        font-weight: 500;
        color: var(--text-dark);
        padding: 8px 12px;
        border-radius: 5px;
        transition: all calc(var(--duration) * 0.25) var(--easing);
        position: relative;
      }

      .nav-item a:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--gradient-1);
        transition: width calc(var(--duration) * 0.4) var(--easing);
      }

      .nav-item a:hover:after {
        width: 100%;
      }
    }

    .header-cta {
      display: grid;
    }

    /* ========================================
       Button Component (高度なアニメーション)
    ======================================== */
    .btn {
      position: relative;
      display: inline-grid;
      place-items: center;
      padding: clamp(12px, 1.5vw, 16px) clamp(24px, 3vw, 40px);
      font-size: var(--fz-sm);
      font-weight: 600;
      font-family: var(--font-jp);
      color: var(--white);
      background: var(--blue);
      border-radius: 10px;
      overflow: hidden;
      transition: background-color calc(var(--duration) * 0.5) var(--easing);
      cursor: pointer;
    }

    .btn:before {
      content: '';
      position: absolute;
      inset: 0;
      width: calc(100% - 10px);
      height: calc(100% - 10px);
      margin: auto;
      background: var(--gradient-6);
      border-radius: 10px;
      opacity: 0;
      scale: 1;
      transition: calc(var(--duration) * 0.5) var(--easing);
      transition-property: opacity, width, height, scale;
    }

    .btn:hover {
      background-color: transparent;
      transition-delay: 0.48s;
    }

    .btn:hover:before {
      width: 100%;
      height: 100%;
      opacity: 1;
      scale: 1.01;
    }

    .btn-text {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn:hover .btn-text {
      color: var(--dark-blue);
    }

    /* ========================================
       Hero Section
    ======================================== */
    .hero {
      position: relative;
      min-height: clamp(500px, 60vh, 700px);
      background: var(--gradient-2);
      padding: clamp(3.75rem, 8vw, 6.25rem) var(--inline-space-lg);
      display: grid;
      place-items: center;
      overflow: hidden;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: 900px;
      margin: 0 auto;
    }

    .hero-heading-en {
      font-family: var(--font-en);
      font-size: var(--fz-en-lg);
      font-weight: 600;
      font-variation-settings: "wght" 600;
      line-height: 1.2;
      letter-spacing: -0.02em;
      color: var(--white);
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
      text-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    .hero-heading-jp {
      font-size: var(--fz-2xlg);
      font-weight: 700;
      color: var(--white);
      margin-bottom: clamp(1.5rem, 3vw, 2.5rem);
      line-height: 1.6;
      text-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    .hero-description {
      font-size: var(--fz-md);
      color: rgba(255, 255, 255, 0.95);
      line-height: 1.9;
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    .hero-cta {
      display: flex;
      flex-wrap: wrap;
      gap: var(--grid-gutter);
      justify-content: center;
    }

    /* ========================================
       Card Component (高度なホバーエフェクト)
    ======================================== */
    .card {
      position: relative;
      display: grid;
      background: var(--bg-white);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      transition: background-color calc(var(--duration) * 0.5) var(--easing);
    }

    .card:before {
      content: '';
      position: absolute;
      inset: 0;
      width: calc(100% - 10px);
      height: calc(100% - 10px);
      margin: auto;
      background: var(--gradient-6);
      border-radius: 10px;
      opacity: 0;
      scale: 1;
      transition: calc(var(--duration) * 0.5) var(--easing);
      transition-property: opacity, width, height, scale;
      pointer-events: none;
    }

    .card:hover {
      background-color: transparent;
      transition-delay: 0.48s;
    }

    .card:hover:before {
      width: 100%;
      height: 100%;
      opacity: 1;
      scale: 1.01;
    }

    .card-content {
      position: relative;
      z-index: 1;
      padding: clamp(1.25rem, 2.5vw, 2rem);
    }

    .card-icon {
      width: clamp(48px, 6vw, 64px);
      height: clamp(48px, 6vw, 64px);
      display: grid;
      place-items: center;
      background: var(--gradient-3);
      border-radius: 10px;
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .card-icon svg {
      width: 60%;
      height: 60%;
      fill: var(--blue);
    }

    .card-title {
      font-size: var(--fz-lg);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
      transition: color calc(var(--duration) * 0.25) var(--easing);
    }

    .card:hover .card-title {
      color: var(--dark-blue);
    }

    .card-description {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      line-height: 1.8;
    }

    .card-image {
      position: relative;
      overflow: hidden;
      aspect-ratio: 16 / 9;
      border-radius: 10px;
    }

    .card-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform calc(var(--duration) * 0.8) var(--easing);
    }

    .card:hover .card-image img {
      transform: scale(1.1);
    }

    /* ========================================
       Services Section
    ======================================== */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
      gap: var(--grid-gutter);
    }

    /* ========================================
       Arrow Icon Animation (2つのSVGでスライド)
    ======================================== */
    .arrow-wrapper {
      position: relative;
      display: inline-grid;
      place-items: center;
      width: 1em;
      aspect-ratio: 1;
      overflow: hidden;
    }

    .arrow-wrapper svg {
      position: absolute;
      width: 100%;
      transition: translate calc(var(--duration) * 0.4) var(--easing);
    }

    .arrow-wrapper svg:first-child {
      translate: 0 0;
    }

    .arrow-wrapper svg:last-child {
      translate: calc((100% + 5px) * -1) 0;
    }

    a:hover .arrow-wrapper svg:first-child,
    button:hover .arrow-wrapper svg:first-child {
      translate: calc(100% + 5px) 0;
    }

    a:hover .arrow-wrapper svg:last-child,
    button:hover .arrow-wrapper svg:last-child {
      translate: 0 0;
    }

    /* ========================================
       Footer
    ======================================== */
    .footer {
      background: var(--text-dark);
      color: var(--bg-white);
      padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(1.5rem, 3vw, 2rem);
    }

    .footer-content {
      display: grid;
      gap: var(--grid-gutter);
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    @media (min-width: 768px) {
      .footer-content {
        grid-template-columns: 1.5fr 1fr 1fr;
      }
    }

    .footer-logo {
      font-size: clamp(1.25rem, 1.75vw, 1.5rem);
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .footer-description {
      font-size: var(--fz-sm);
      line-height: 1.8;
      opacity: 0.8;
    }

    .footer-section-title {
      font-size: var(--fz-md);
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .footer-links {
      display: grid;
      gap: 0.75rem;
    }

    .footer-links a {
      font-size: var(--fz-sm);
      opacity: 0.8;
      transition: all calc(var(--duration) * 0.25) var(--easing);
    }

    .footer-links a:hover {
      opacity: 1;
      color: var(--light-blue);
      padding-left: 4px;
    }

    .footer-bottom {
      text-align: center;
      padding-top: clamp(1.5rem, 3vw, 2rem);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: var(--fz-xs);
      opacity: 0.6;
    }

    /* ========================================
       Responsive
    ======================================== */
    @media (max-width: 767px) {
      .hero {
        padding: clamp(2.5rem, 6vw, 4rem) var(--inline-space-md);
      }

      .hero-heading-en {
        font-size: clamp(1.75rem, 8vw, 2.5rem);
      }

      .hero-heading-jp {
        font-size: clamp(1.125rem, 5vw, 1.5rem);
      }

      .hero-cta {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <!-- SVG Symbols Definition (1回だけ定義して使い回し) -->
  <svg style="display: none;" aria-hidden="true">
    <defs>
      <symbol id="icon-arrow-forward" viewBox="0 0 10 10">
        <path fill="currentColor" d="m9.425 4.612.388.389-.388.389-3.437 3.435-.777-.777L7.708 5.55H0v-1.1h7.707l-2.496-2.5.777-.778z"/>
      </symbol>
      <symbol id="icon-shield" viewBox="0 0 24 24">
        <path fill="currentColor" d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm0 18c-3.45-.89-6-4.54-6-8.83V6.3l6-2.25 6 2.25v4.87c0 4.29-2.55 7.94-6 8.83z"/>
      </symbol>
      <symbol id="icon-chart" viewBox="0 0 24 24">
        <path fill="currentColor" d="M3 13h2v7H3zm4-6h2v13H7zm4 3h2v10h-2zm4-1h2v11h-2zm4-5h2v16h-2z"/>
      </symbol>
      <symbol id="icon-people" viewBox="0 0 24 24">
        <path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
      </symbol>
      <symbol id="icon-home" viewBox="0 0 24 24">
        <path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
      </symbol>
      <symbol id="icon-trending-up" viewBox="0 0 24 24">
        <path fill="currentColor" d="m16 6 2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
      </symbol>
    </defs>
  </svg>

  <!-- Header -->
  <header class="header">
    <div class="header-inner container">
      <div class="header-logo">
        <div class="logo-main">ファイナンスブレーン</div>
        <div class="logo-sub">Finance Brain</div>
      </div>

      <nav class="header-nav">
        <ul class="nav-list">
          <li class="nav-item"><a href="#services">サービス</a></li>
          <li class="nav-item"><a href="#about">私たちについて</a></li>
          <li class="nav-item"><a href="#voice">お客様の声</a></li>
          <li class="nav-item"><a href="#contact">お問い合わせ</a></li>
        </ul>
      </nav>

      <div class="header-cta">
        <button class="btn">
          <span class="btn-text">
            無料相談予約
            <span class="arrow-wrapper">
              <svg><use href="#icon-arrow-forward"></use></svg>
              <svg><use href="#icon-arrow-forward"></use></svg>
            </span>
          </span>
        </button>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-heading-en">Financial Planning for Your Future</h1>
      <p class="hero-heading-jp">お金の不安を安心に変える、<br>大分のファイナンシャルパートナー</p>
      <p class="hero-description">
        FPや投資診断士など、大分県内の専門資格所有者が多数在籍。<br>
        保険・投資信託・資産運用・相続など、お金に関するあらゆるご相談を承ります。
      </p>
      <div class="hero-cta">
        <button class="btn">
          <span class="btn-text">
            無料相談を予約する
            <span class="arrow-wrapper">
              <svg><use href="#icon-arrow-forward"></use></svg>
              <svg><use href="#icon-arrow-forward"></use></svg>
            </span>
          </span>
        </button>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="section section-white">
    <div class="container">
      <div class="heading-group" style="text-align: center; margin-bottom: clamp(2.5rem, 5vw, 4rem);">
        <span class="heading-en">Our Services</span>
        <h2 class="heading-jp">提供サービス</h2>
      </div>

      <div class="services-grid">
        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-shield"></use></svg>
            </div>
            <h3 class="card-title">保険コンサルティング</h3>
            <p class="card-description">
              生命保険・医療保険・がん保険など、あなたとご家族に最適な保障をご提案します。複数社の商品を比較検討し、本当に必要な保険を見つけます。
            </p>
          </div>
        </a>

        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-chart"></use></svg>
            </div>
            <h3 class="card-title">資産運用・投資信託</h3>
            <p class="card-description">
              NISA・iDeCoを活用した長期資産形成から、投資信託の選び方まで。投資診断士がお客様のリスク許容度に合わせた運用プランを作成します。
            </p>
          </div>
        </a>

        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-people"></use></svg>
            </div>
            <h3 class="card-title">相続・事業承継</h3>
            <p class="card-description">
              相続税対策、遺言書作成サポート、事業承継プランニングなど。次世代へスムーズに資産を引き継ぐためのトータルサポートを提供します。
            </p>
          </div>
        </a>

        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-home"></use></svg>
            </div>
            <h3 class="card-title">住宅ローン相談</h3>
            <p class="card-description">
              マイホーム購入時の資金計画から、住宅ローンの借り換え相談まで。将来を見据えた無理のない返済プランをご提案します。
            </p>
          </div>
        </a>

        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-trending-up"></use></svg>
            </div>
            <h3 class="card-title">ライフプランニング</h3>
            <p class="card-description">
              教育資金、老後資金、住宅購入など、人生の各ステージに必要な資金を可視化。キャッシュフロー表を作成し、将来の不安を解消します。
            </p>
          </div>
        </a>

        <a href="#" class="card">
          <div class="card-content">
            <div class="card-icon">
              <svg><use href="#icon-chart"></use></svg>
            </div>
            <h3 class="card-title">税金・節税対策</h3>
            <p class="card-description">
              所得税・住民税の節税方法から、ふるさと納税の活用法まで。税理士と連携し、合法的な節税対策をアドバイスします。
            </p>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-column">
          <div class="footer-logo">ファイナンスブレーン</div>
          <p class="footer-description">
            大分のFP・投資診断士による、お金のトータルコンサルティング。保険・投資・相続など、あらゆるご相談に対応いたします。
          </p>
        </div>

        <div class="footer-column">
          <h3 class="footer-section-title">サービス</h3>
          <div class="footer-links">
            <a href="#">保険コンサルティング</a>
            <a href="#">資産運用・投資信託</a>
            <a href="#">相続・事業承継</a>
            <a href="#">住宅ローン相談</a>
            <a href="#">ライフプランニング</a>
          </div>
        </div>

        <div class="footer-column">
          <h3 class="footer-section-title">会社情報</h3>
          <div class="footer-links">
            <a href="#">私たちについて</a>
            <a href="#">お客様の声</a>
            <a href="#">よくあるご質問</a>
            <a href="#">お問い合わせ</a>
            <a href="#">プライバシーポリシー</a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        &copy; 2026 Finance Brain. All Rights Reserved.
      </div>
    </div>
  </footer>
</body>
</html>
