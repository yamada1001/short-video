<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイナンスブレーン | 大分の【保険・投資信託・資産運用】のコンサルタント</title>
  <meta name="description" content="FPや投資診断士など、大分県内の専門資格所有者が多数在籍し、お金に関するご相談を幅広く承っています。お金の貯め方・増やし方・残し方に迷うことなく、安心して将来に備えたい方は他にいませんか？">
  <link rel="icon" type="image/png" href="assets/images/favicon.png">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;900&family=Noto+Serif+JP:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- ClashDisplay Variable Font -->
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
       CSS Custom Properties (BuySell Technologies完全準拠)
    ======================================== */
    :root {
      /* Viewport計算 */
      --viewport-width: 100vw;
      --window-width: tan(atan2(var(--viewport-width), 1px));
      --mw: max(1440px, 90rem);
      --max: tan(atan2(var(--mw), 1px));
      --scale: max(1, var(--window-width) / var(--max));
      --px: calc(1px * var(--scale));
      --rem: calc(1rem * var(--scale));

      /* Colors - Finance Brain (青ベース) */
      --white: #fff;
      --black: #222;
      --blue: #5767bf;
      --dark-blue: #4a5ab3;
      --darker-blue: #3a4a8f;
      --light-blue: #cfe2ff;
      --orange: #ff8c42;
      --dark-orange: #e67e22;

      /* Gradients (6種類 - BuySell Technologies方式をFinance Brain色に適用) */
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
      --inline-space-container: calc(var(--inline-space-md) + var(--inline-space-xl));

      /* Font Sizes (clamp()で流動的) */
      --fz-root: clamp(0.9375rem, 1vw, 1rem);
      --fz-hgroup-en-lg: clamp(3.375rem, 2.5vw + 2rem, 5rem);
      --fz-hgroup-en-md: clamp(2.5rem, 1.5vw + 1.5rem, 3.75rem);
      --fz-hgroup-en-sm: clamp(2rem, 1vw + 1.5rem, 2.5rem);
      --fz-3xlg: clamp(1.5rem, 1.5vw + 0.75rem, 2rem);
      --fz-2xlg: clamp(1.375rem, 1.25vw + 0.5rem, 1.75rem);
      --fz-xlg: clamp(1.25rem, 1vw + 0.5rem, 1.5rem);
      --fz-lg: clamp(1.125rem, 0.75vw + 0.5rem, 1.25rem);
      --fz-md: clamp(1rem, 0.5vw + 0.5rem, 1.125rem);
      --fz-sm: clamp(0.875rem, 0.25vw + 0.5rem, 1rem);
      --fz-xs: clamp(0.75rem, 0.25vw + 0.4rem, 0.875rem);
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
       Reset & Base (BuySell Technologies準拠)
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
      border-radius: calc(10 * var(--px));
    }

    /* ========================================
       Typography (BuySell Technologies完全準拠)
    ======================================== */
    [data-hgroup] {
      display: flex;
      flex-direction: column-reverse;
      justify-content: flex-end;
      row-gap: clamp(0.125rem, 0.25vw, 0.3125rem);
    }

    .hgroup-heading {
      width: fit-content;
      font-family: var(--font-jp);
      font-style: normal;
      font-weight: 600;
      font-optical-sizing: auto;
      font-feature-settings: "palt" on;
      font-size: var(--fz-2xlg);
      line-height: 1.6;
      letter-spacing: 0.04em;
      color: var(--text-dark);
    }

    .hgroup-text {
      width: fit-content;
      color: transparent;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      background-clip: text;
      line-height: 1;
      letter-spacing: -0.01em;
      font-family: var(--font-en);
      font-style: normal;
      font-weight: 500;
      font-variation-settings: "wght" 500;
      font-size: var(--fz-en-md);
    }

    /* ========================================
       Header (BuySell Technologies準拠)
    ======================================== */
    .header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: var(--bg-white);
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
    }

    .header-inner {
      display: grid;
      grid-template-columns: auto 1fr auto;
      align-items: center;
      gap: var(--grid-gutter);
      padding: clamp(0.625rem, 1.5vw, 1.25rem) var(--inline-space-md);
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: calc(4 * var(--px));
    }

    .header-logo img {
      height: clamp(40px, 5vw, 60px);
      width: auto;
      display: block;
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
        padding: calc(8 * var(--px)) calc(12 * var(--px));
        border-radius: calc(5 * var(--px));
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
        height: calc(2 * var(--px));
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
       Button Component (高度なアニメーション - BuySell Technologies完全準拠)
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
      border-radius: calc(10 * var(--px));
      overflow: hidden;
      transition: background-color calc(var(--duration) * 0.5) var(--easing);
      cursor: pointer;
    }

    .btn:before {
      content: '';
      position: absolute;
      inset: 0;
      width: calc(100% - calc(10 * var(--px)));
      height: calc(100% - calc(10 * var(--px)));
      margin: auto;
      background: var(--gradient-6);
      border-radius: calc(10 * var(--px));
      opacity: 0;
      scale: 1;
      transition: calc(var(--duration) * 0.5) var(--easing);
      transition-property: opacity, width, height, scale;
    }

    @media (hover: hover) {
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

      .btn:hover .btn-text {
        color: var(--dark-blue);
      }
    }

    .btn-text {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      gap: calc(8 * var(--px));
    }

    /* ========================================
       Hero Section
    ======================================== */
    .hero {
      position: relative;
      min-height: clamp(500px, 60vh, 700px);
      background:
        linear-gradient(90deg, rgba(87, 103, 191, 0.85) 0%, rgba(74, 90, 179, 0.85) 100%),
        url('assets/images/hero-desktop.jpg') center/cover no-repeat;
      padding: clamp(3.75rem, 8vw, 6.25rem) var(--inline-space-lg);
      display: grid;
      place-items: center;
      overflow: hidden;
    }

    @media (max-width: 767px) {
      .hero {
        background:
          linear-gradient(90deg, rgba(87, 103, 191, 0.85) 0%, rgba(74, 90, 179, 0.85) 100%),
          url('assets/images/hero-mobile.jpg') center/cover no-repeat;
      }
    }

    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      max-width: calc(900 * var(--px));
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
      text-shadow: 0 calc(2 * var(--px)) calc(20 * var(--px)) rgba(0, 0, 0, 0.1);
    }

    .hero-heading-jp {
      font-size: var(--fz-2xlg);
      font-weight: 700;
      color: var(--white);
      margin-bottom: clamp(1.5rem, 3vw, 2.5rem);
      line-height: 1.6;
      text-shadow: 0 calc(2 * var(--px)) calc(20 * var(--px)) rgba(0, 0, 0, 0.1);
    }

    .hero-lead {
      font-size: var(--fz-md);
      color: rgba(255, 255, 255, 0.95);
      line-height: 1.9;
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    .hero-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: var(--grid-gutter);
      justify-content: center;
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    .btn-outline {
      background: transparent;
      border: calc(2 * var(--px)) solid var(--white);
      color: var(--white);
    }

    .btn-outline:hover {
      background: var(--white);
      color: var(--blue);
    }

    .hero-features {
      display: flex;
      flex-wrap: wrap;
      gap: clamp(1rem, 2vw, 1.5rem);
      justify-content: center;
    }

    .feature-badge {
      display: flex;
      align-items: center;
      gap: calc(8 * var(--px));
      padding: calc(8 * var(--px)) calc(16 * var(--px));
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(calc(10 * var(--px)));
      border-radius: calc(20 * var(--px));
      font-size: var(--fz-sm);
      color: var(--white);
      font-weight: 500;
    }

    /* ========================================
       Card Component (高度なホバーエフェクト - BuySell Technologies完全準拠)
    ======================================== */
    .card {
      position: relative;
      display: grid;
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      overflow: hidden;
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
      transition: background-color calc(var(--duration) * 0.5) var(--easing);
    }

    .card:before {
      content: '';
      position: absolute;
      inset: 0;
      width: calc(100% - calc(10 * var(--px)));
      height: calc(100% - calc(10 * var(--px)));
      margin: auto;
      background: var(--gradient-6);
      border-radius: calc(10 * var(--px));
      opacity: 0;
      scale: 1;
      transition: calc(var(--duration) * 0.5) var(--easing);
      transition-property: opacity, width, height, scale;
      pointer-events: none;
    }

    @media (hover: hover) {
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

      .card:hover .card-title {
        color: var(--dark-blue);
      }
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
      border-radius: calc(10 * var(--px));
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

    .card-description {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      line-height: 1.8;
    }

    /* ========================================
       About Section
    ======================================== */
    .about-content {
      display: grid;
      gap: var(--grid-gutter);
    }

    @media (min-width: 1024px) {
      .about-content {
        grid-template-columns: 2fr 1fr;
      }
    }

    .about-text {
      font-size: var(--fz-md);
      line-height: 1.9;
    }

    .about-text p {
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .about-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: var(--inline-space-md);
    }

    .stat-card {
      display: grid;
      place-items: center;
      padding: clamp(1.5rem, 3vw, 2rem);
      background: var(--gradient-3);
      border-radius: calc(10 * var(--px));
      text-align: center;
    }

    .stat-number {
      font-family: var(--font-en);
      font-size: var(--fz-3xlg);
      font-weight: 600;
      font-variation-settings: "wght" 600;
      color: var(--blue);
      line-height: 1;
      margin-bottom: calc(8 * var(--px));
    }

    .stat-label {
      font-size: var(--fz-sm);
      color: var(--text-dark);
      font-weight: 500;
    }

    /* ========================================
       Services Section
    ======================================== */
    .services-tabs {
      display: flex;
      gap: var(--inline-space-md);
      margin-bottom: clamp(2rem, 4vw, 3rem);
      justify-content: center;
      flex-wrap: wrap;
    }

    .tab-button {
      padding: clamp(12px, 1.5vw, 16px) clamp(24px, 3vw, 40px);
      font-size: var(--fz-sm);
      font-weight: 600;
      font-family: var(--font-jp);
      color: var(--text-medium);
      background: var(--bg-lighter);
      border-radius: calc(10 * var(--px));
      cursor: pointer;
      transition: all calc(var(--duration) * 0.3) var(--easing);
    }

    .tab-button.active {
      color: var(--white);
      background: var(--blue);
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
      gap: var(--grid-gutter);
    }

    .service-card {
      position: relative;
      display: grid;
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      padding: clamp(1.5rem, 3vw, 2rem);
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
      transition: all calc(var(--duration) * 0.3) var(--easing);
    }

    @media (hover: hover) {
      .service-card:hover {
        transform: translateY(calc(-4 * var(--px)));
        box-shadow: 0 calc(4 * var(--px)) calc(16 * var(--px)) rgba(0, 0, 0, 0.1);
      }

      .service-card:hover .service-title {
        color: var(--blue);
      }
    }

    .service-title {
      font-size: var(--fz-lg);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
      transition: color calc(var(--duration) * 0.25) var(--easing);
    }

    .service-description {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      line-height: 1.8;
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .service-link {
      display: inline-flex;
      align-items: center;
      gap: calc(8 * var(--px));
      font-size: var(--fz-sm);
      color: var(--blue);
      font-weight: 500;
      transition: gap calc(var(--duration) * 0.3) var(--easing);
    }

    .service-link:hover {
      gap: calc(12 * var(--px));
    }

    /* ========================================
       Why Us Section
    ======================================== */
    .reasons-grid {
      display: grid;
      gap: var(--grid-gutter);
    }

    @media (min-width: 768px) {
      .reasons-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .reason-card {
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      padding: clamp(1.5rem, 3vw, 2rem);
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
    }

    .reason-number {
      display: inline-grid;
      place-items: center;
      width: clamp(48px, 6vw, 64px);
      height: clamp(48px, 6vw, 64px);
      background: var(--gradient-1);
      color: var(--white);
      font-family: var(--font-en);
      font-size: var(--fz-en-md);
      font-weight: 600;
      font-variation-settings: "wght" 600;
      border-radius: calc(10 * var(--px));
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .reason-title {
      font-size: var(--fz-md);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
      line-height: 1.6;
    }

    .reason-description {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      line-height: 1.8;
    }

    /* ========================================
       Voice Section
    ======================================== */
    .voice-grid {
      display: grid;
      gap: var(--grid-gutter);
    }

    @media (min-width: 768px) {
      .voice-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (min-width: 1024px) {
      .voice-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .voice-card {
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      padding: clamp(1.5rem, 3vw, 2rem);
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
    }

    .voice-header {
      display: flex;
      align-items: center;
      gap: clamp(12px, 1.5vw, 16px);
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .voice-avatar {
      width: clamp(48px, 6vw, 56px);
      height: clamp(48px, 6vw, 56px);
      background: var(--gradient-3);
      border-radius: 50%;
      display: grid;
      place-items: center;
      font-size: clamp(24px, 3vw, 28px);
    }

    .voice-name {
      font-size: var(--fz-sm);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: calc(4 * var(--px));
    }

    .voice-category {
      font-size: var(--fz-xs);
      color: var(--blue);
      font-weight: 500;
    }

    .voice-text {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      line-height: 1.8;
    }

    /* ========================================
       Company Section
    ======================================== */
    .company-content {
      display: grid;
      gap: var(--grid-gutter);
    }

    @media (min-width: 1024px) {
      .company-content {
        grid-template-columns: 1.5fr 1fr;
      }
    }

    .company-table {
      width: 100%;
      font-size: var(--fz-sm);
      border-collapse: collapse;
    }

    .company-table th,
    .company-table td {
      padding: clamp(12px, 1.5vw, 16px);
      border-bottom: calc(1 * var(--px)) solid var(--border-01);
      text-align: left;
      line-height: 1.8;
    }

    .company-table th {
      font-weight: 600;
      color: var(--text-dark);
      width: clamp(100px, 20%, 150px);
      background: var(--bg-lighter);
    }

    .company-table td {
      color: var(--text-medium);
    }

    .map-placeholder {
      background: var(--bg-lighter);
      border-radius: calc(10 * var(--px));
      padding: clamp(2rem, 4vw, 3rem);
      text-align: center;
      min-height: clamp(250px, 30vw, 350px);
      display: grid;
      place-items: center;
    }

    .map-note {
      margin-top: clamp(1rem, 2vw, 1.5rem);
      font-size: var(--fz-sm);
      color: var(--text-medium);
    }

    /* ========================================
       Contact Section
    ======================================== */
    .contact-methods {
      display: grid;
      gap: var(--grid-gutter);
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    @media (min-width: 768px) {
      .contact-methods {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .contact-method {
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      padding: clamp(1.5rem, 3vw, 2rem);
      text-align: center;
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
    }

    .contact-method-title {
      font-size: var(--fz-lg);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .contact-method-tel {
      font-size: var(--fz-2xlg);
      font-weight: 700;
      color: var(--blue);
      margin-bottom: calc(8 * var(--px));
    }

    .contact-method-tel a {
      color: var(--blue);
    }

    .contact-method-time {
      font-size: var(--fz-sm);
      color: var(--text-medium);
    }

    .contact-method-description {
      font-size: var(--fz-sm);
      color: var(--text-medium);
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .btn-line {
      background: #06c755;
    }

    .btn-line:before {
      background: linear-gradient(93deg, #7ee2a8 20.13%, #00b900 81.55%), #06c755;
    }

    .contact-form-area {
      background: var(--bg-white);
      border-radius: calc(10 * var(--px));
      padding: clamp(2rem, 4vw, 3rem);
      box-shadow: 0 calc(2 * var(--px)) calc(8 * var(--px)) rgba(0, 0, 0, 0.05);
    }

    .form-title {
      font-size: var(--fz-xlg);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: clamp(1.5rem, 3vw, 2rem);
      text-align: center;
    }

    .form-group {
      margin-bottom: clamp(1.25rem, 2.5vw, 1.75rem);
    }

    .form-row {
      display: grid;
      gap: var(--grid-gutter);
    }

    @media (min-width: 768px) {
      .form-row {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .form-label {
      display: block;
      font-size: var(--fz-sm);
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: calc(8 * var(--px));
    }

    .required {
      display: inline-block;
      margin-left: calc(8 * var(--px));
      padding: calc(2 * var(--px)) calc(8 * var(--px));
      background: #e74c3c;
      color: var(--white);
      font-size: var(--fz-xs);
      font-weight: 500;
      border-radius: calc(3 * var(--px));
    }

    .form-control {
      width: 100%;
      padding: clamp(12px, 1.5vw, 14px);
      font-size: var(--fz-sm);
      font-family: var(--font-jp);
      color: var(--text-dark);
      background: var(--bg-lighter);
      border: calc(2 * var(--px)) solid var(--border-01);
      border-radius: calc(5 * var(--px));
      transition: all calc(var(--duration) * 0.3) var(--easing);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--blue);
      background: var(--white);
    }

    textarea.form-control {
      resize: vertical;
      min-height: 150px;
    }

    .form-privacy {
      margin-bottom: clamp(1.5rem, 3vw, 2rem);
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: calc(8 * var(--px));
      font-size: var(--fz-sm);
      color: var(--text-dark);
      cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
      width: calc(20 * var(--px));
      height: calc(20 * var(--px));
      cursor: pointer;
    }

    .form-submit {
      text-align: center;
    }

    .btn-large {
      padding: clamp(16px, 2vw, 20px) clamp(40px, 5vw, 60px);
      font-size: var(--fz-md);
    }

    /* ========================================
       Arrow Icon Animation (2つのSVGでスライド - BuySell Technologies完全準拠)
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
      translate: calc((100% + calc(5 * var(--px))) * -1) 0;
    }

    a:hover .arrow-wrapper svg:first-child,
    button:hover .arrow-wrapper svg:first-child {
      translate: calc(100% + calc(5 * var(--px))) 0;
    }

    a:hover .arrow-wrapper svg:last-child,
    button:hover .arrow-wrapper svg:last-child {
      translate: 0 0;
    }

    /* ========================================
       Section Header
    ======================================== */
    .section-header {
      text-align: center;
      margin-bottom: clamp(2.5rem, 5vw, 4rem);
    }

    .section-title {
      font-size: var(--fz-2xlg);
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
      line-height: 1.4;
    }

    .section-lead {
      font-size: var(--fz-md);
      color: var(--text-medium);
      line-height: 1.7;
    }

    /* ========================================
       Footer (BuySell Technologies準拠)
    ======================================== */
    .footer {
      background: var(--text-dark);
      color: var(--bg-white);
      padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(1.5rem, 3vw, 2rem);
      border-radius: calc(10 * var(--px)) calc(10 * var(--px)) 0 0;
    }

    .footer-content {
      display: grid;
      gap: var(--grid-gutter);
      margin-bottom: clamp(2rem, 4vw, 3rem);
    }

    @media (min-width: 768px) {
      .footer-content {
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
      }
    }

    .footer-brand {
      display: grid;
      gap: clamp(0.75rem, 1.5vw, 1rem);
    }

    .footer-logo {
      margin-bottom: clamp(1rem, 2vw, 1.5rem);
    }

    .footer-logo img {
      height: clamp(45px, 5vw, 55px);
      width: auto;
      display: block;
    }

    .footer-tagline {
      font-size: var(--fz-sm);
      line-height: 1.8;
      opacity: 0.9;
    }

    .footer-address {
      font-size: var(--fz-sm);
      line-height: 1.8;
      opacity: 0.8;
    }

    .footer-address a {
      color: var(--white);
      text-decoration: underline;
    }

    .footer-column h4 {
      font-size: var(--fz-md);
      font-weight: 600;
      margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
    }

    .footer-column ul {
      display: grid;
      gap: calc(8 * var(--px));
    }

    .footer-column a {
      font-size: var(--fz-sm);
      opacity: 0.8;
      transition: all calc(var(--duration) * 0.25) var(--easing);
    }

    .footer-column a:hover {
      opacity: 1;
      color: var(--light-blue);
      padding-left: calc(4 * var(--px));
    }

    .footer-bottom {
      text-align: center;
      padding-top: clamp(1.5rem, 3vw, 2rem);
      border-top: calc(1 * var(--px)) solid rgba(255, 255, 255, 0.1);
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

      .hero-buttons {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }
    }

    /* ========================================
       JavaScript Tab Functionality
    ======================================== */
  </style>
</head>
<body>
  <!-- SVG Symbols Definition (BuySell Technologies方式 - 1回だけ定義して使い回し) -->
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
      <symbol id="icon-phone" viewBox="0 0 20 20">
        <path d="M18.3 14.4c-1.1-.2-2.2-.4-3.3-.4-.6 0-1.2.1-1.7.4l-1.3 1.3c-3.1-1.6-5.6-4.1-7.2-7.2l1.3-1.3c.3-.5.4-1.1.4-1.7 0-1.1-.2-2.2-.4-3.3C5.9 1.7 5.4 1.3 4.8 1.3H2.3C1.6 1.3 1 1.9 1 2.6 1 11.9 8.1 19 17.4 19c.7 0 1.3-.6 1.3-1.3v-2.5c0-.6-.4-1.1-1-.8z" stroke="currentColor" stroke-width="1.5" fill="none"/>
      </symbol>
      <symbol id="icon-star" viewBox="0 0 24 24">
        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#FFD700"/>
      </symbol>
    </defs>
  </svg>

  <!-- Header -->
  <header class="header">
    <div class="header-inner container">
      <a href="/" class="header-logo">
        <img src="assets/images/logo.jpg" alt="ファイナンスブレーン Finance Brain">
      </a>

      <nav class="header-nav">
        <ul class="nav-list">
          <li class="nav-item"><a href="#about">ファイナンスブレーンとは</a></li>
          <li class="nav-item"><a href="#services">サービス</a></li>
          <li class="nav-item"><a href="#why-us">選ばれる理由</a></li>
          <li class="nav-item"><a href="#voice">お客様の声</a></li>
          <li class="nav-item"><a href="#company">会社概要</a></li>
        </ul>
      </nav>

      <div class="header-cta">
        <a href="#contact" class="btn">
          <span class="btn-text">
            お問い合わせ
            <span class="arrow-wrapper">
              <svg><use href="#icon-arrow-forward"></use></svg>
              <svg><use href="#icon-arrow-forward"></use></svg>
            </span>
          </span>
        </a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1 class="hero-heading-en">Financial Planning for Your Future</h1>
      <p class="hero-heading-jp">お金のこと、<br>安心して相談できる場所。</p>
      <p class="hero-lead">
        保険、投資、住宅ローン、相続——人生には、お金について考えるべき場面がたくさん。<br>
        ファイナンシャルプランナーをはじめとする専門家が、<br>
        お客様一人ひとりに寄り添い、最適なプランをご提案。
      </p>
      <div class="hero-buttons">
        <a href="#contact" class="btn">
          <span class="btn-text">
            無料相談予約
            <span class="arrow-wrapper">
              <svg><use href="#icon-arrow-forward"></use></svg>
              <svg><use href="#icon-arrow-forward"></use></svg>
            </span>
          </span>
        </a>
        <a href="tel:097-574-8212" class="btn btn-outline">
          <span class="btn-text">
            <svg width="20" height="20"><use href="#icon-phone"></use></svg>
            097-574-8212
          </span>
        </a>
      </div>
      <div class="hero-features">
        <div class="feature-badge">
          <svg width="24" height="24"><use href="#icon-star"></use></svg>
          <span>2006年創業・大分で19年の実績</span>
        </div>
        <div class="feature-badge">
          <svg width="24" height="24"><use href="#icon-star"></use></svg>
          <span>FP・投資診断士など専門資格所有者在籍</span>
        </div>
        <div class="feature-badge">
          <svg width="24" height="24"><use href="#icon-star"></use></svg>
          <span>初回相談無料</span>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="section section-white" id="about">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">About Us</span>
          <h2 class="hgroup-heading">ファイナンスブレーンとは</h2>
        </div>
        <p class="section-lead">あなたの人生に寄り添う、お金の相談パートナーです</p>
      </div>
      <div class="about-content">
        <div class="about-text">
          <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark);">私たちが大切にしていること</h3>
          <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-dark);">
            お客様の人生に寄り添い、本当に必要なサポートを、中立的な立場で提供すること。
          </p>
          <p>
            2006年の創業以来、大分の地で1,000組以上のご家族・企業様と向き合ってきました。
            「子どもの教育費が心配」「老後が不安」「会社を守りたい」——お金の悩みは人それぞれ。
            正解は一つではありません。
          </p>
          <p>
            だからこそ私たちは、お客様の話にじっくり耳を傾けることから始めます。
            ファイナンシャルプランナー（FP）や投資診断士などの専門知識を持ったスタッフが、
            あなたの「本当に大切なこと」を一緒に考え、実現するお手伝いをします。
          </p>
          <p>
            保険、投資、住宅ローン、相続——専門的な知識が必要な分野だからこそ、
            難しい言葉ではなく、わかりやすく。押し付けではなく、一緒に。
            それが、ファイナンスブレーンのスタイルです。
          </p>
        </div>
        <div class="about-stats">
          <div class="stat-card">
            <div class="stat-number">19</div>
            <div class="stat-label">年の実績</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">16</div>
            <div class="stat-label">取扱保険会社</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">1,000+</div>
            <div class="stat-label">相談実績</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="section section-white" id="services">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">Our Services</span>
          <h2 class="hgroup-heading">サービス</h2>
        </div>
        <p class="section-lead">個人の方も、法人の方も——あなたの「困った」「不安」「知りたい」に、お応えします</p>
      </div>

      <!-- タブ切り替え -->
      <div class="services-tabs">
        <button class="tab-button active" data-tab="personal">個人向けサービス</button>
        <button class="tab-button" data-tab="corporate">法人向けサービス</button>
      </div>

      <!-- 個人向けサービス -->
      <div class="tab-content active" id="personal">
        <div class="services-grid">
          <a href="services/personal/life-planning/index.html" class="service-card">
            <h3 class="service-title">ライフプランニング</h3>
            <p class="service-description">
              将来の夢や目標を実現するための資金計画を作成します。住宅購入、教育資金、老後資金など、ライフステージに合わせたプランをご提案。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/personal/insurance/index.html" class="service-card">
            <h3 class="service-title">保険の見直し・ご相談</h3>
            <p class="service-description">
              生命保険・損害保険の見直しから新規加入まで。生命保険11社・損害保険5社の取扱保険会社から、お客様に最適な保険をご提案します。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/personal/housing-loan/index.html" class="service-card">
            <h3 class="service-title">住宅ローンのご相談</h3>
            <p class="service-description">
              住宅ローンの選び方から借り換えまで。金利タイプの比較や返済計画のアドバイスを行います。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/personal/inheritance/index.html" class="service-card">
            <h3 class="service-title">相続に関するご相談</h3>
            <p class="service-description">
              相続対策の基本から相続税対策、生前贈与、遺言書作成サポートまで。専門家と連携してトータルサポート。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/personal/investment/index.html" class="service-card">
            <h3 class="service-title">投資信託・資産運用</h3>
            <p class="service-description">
              NISA・iDeCoを活用した資産形成プランをご提案。リスク管理も含めた長期的な資産運用をサポートします。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/personal/tax/index.html" class="service-card">
            <h3 class="service-title">税金・節税対策</h3>
            <p class="service-description">
              所得税・住民税の節税方法から、ふるさと納税の活用法まで。税理士と連携し、合法的な節税対策をアドバイスします。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>
        </div>
      </div>

      <!-- 法人向けサービス -->
      <div class="tab-content" id="corporate">
        <div class="services-grid">
          <a href="services/corporate/financial-consulting/index.html" class="service-card">
            <h3 class="service-title">財務コンサルティング</h3>
            <p class="service-description">
              売上は伸びているのに、なぜかお金が残らない——そんな悩みを抱える経営者様へ。財務の専門家が、資金繰り改善・銀行融資・事業計画策定をサポートします。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/corporate/retirement/index.html" class="service-card">
            <h3 class="service-title">退職金コンサルティング</h3>
            <p class="service-description">
              優秀な人材の定着と節税を両立したい。企業型確定拠出年金（DC）、中小企業退職金共済（中退共）など、御社に最適な退職金制度をご提案します。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/corporate/succession/index.html" class="service-card">
            <h3 class="service-title">事業承継対策</h3>
            <p class="service-description">
              後継者問題、相続税対策——事業承継は複雑です。税理士・弁護士とも連携し、円滑な事業承継と相続税対策をトータルサポートします。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>

          <a href="services/corporate/stock/index.html" class="service-card">
            <h3 class="service-title">自社株対策</h3>
            <p class="service-description">
              自社株の評価額が高すぎて、相続税が払えない——そんな不安を解消。10年計画で評価額を削減し、相続税負担を大幅に軽減します。
            </p>
            <span class="service-link">詳しく見る →</span>
          </a>
        </div>
      </div>

    </div>
  </section>

  <!-- Why Us Section -->
  <section class="section section-white" id="why-us">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">Why Choose Us</span>
          <h2 class="hgroup-heading">選ばれる理由</h2>
        </div>
        <p class="section-lead">なぜ、多くの方が私たちに相談してくださるのか</p>
      </div>
      <div class="reasons-grid">
        <div class="reason-card">
          <div class="reason-number">01</div>
          <h3 class="reason-title">大分で19年、地域の皆様と歩んできた実績</h3>
          <p class="reason-description">
            2006年の創業以来、1,000組以上のご家族・企業様のお金の悩みに向き合ってきました。「顔の見える関係」を大切に、何度でも気軽にご相談いただける雰囲気づくりを心がけています。地元だからこそできる、きめ細やかなサポートを提供します。
          </p>
        </div>
        <div class="reason-card">
          <div class="reason-number">02</div>
          <h3 class="reason-title">専門知識を持ったスタッフが、わかりやすく説明</h3>
          <p class="reason-description">
            CFP®、1級FP技能士、投資診断士®、相続診断士など、各分野の専門資格を持つスタッフが在籍。でも、難しい専門用語は使いません。「わかりやすく、丁寧に」をモットーに、あなたに本当に必要な情報だけをお伝えします。
          </p>
        </div>
        <div class="reason-card">
          <div class="reason-number">03</div>
          <h3 class="reason-title">税理士・弁護士とも連携、複雑な相談もお任せください</h3>
          <p class="reason-description">
            相続や事業承継など、税務や法務の知識が必要な相談も、公認会計士・税理士・弁護士・社労士などの専門家ネットワークを活用して対応。窓口は一つ、でもサポートは総合的に。複雑なお悩みもワンストップで解決します。
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Voice Section -->
  <section class="section section-white" id="voice">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">Customer Voice</span>
          <h2 class="hgroup-heading">お客様の声</h2>
        </div>
        <p class="section-lead">「相談して良かった」――そんな声が、私たちの誇りです</p>
      </div>
      <div class="voice-grid">
        <div class="voice-card">
          <div class="voice-header">
            <div class="voice-avatar">👨</div>
            <div class="voice-info">
              <div class="voice-name">40代男性 / 会社員</div>
              <div class="voice-category">ライフプランニング</div>
            </div>
          </div>
          <p class="voice-text">
            将来の資金計画について漠然とした不安がありましたが、FPの方に丁寧に相談に乗っていただき、明確な目標と計画を立てることができました。住宅購入や子供の教育費についても具体的なアドバイスをいただけて安心しました。
          </p>
        </div>

        <div class="voice-card">
          <div class="voice-header">
            <div class="voice-avatar">👩</div>
            <div class="voice-info">
              <div class="voice-name">30代女性 / 自営業</div>
              <div class="voice-category">保険の見直し</div>
            </div>
          </div>
          <p class="voice-text">
            複数の保険会社を比較検討していただき、自分に最適な保険を見つけることができました。無理な営業は一切なく、中立的な立場でアドバイスいただけたのが良かったです。保険料も以前より安くなり満足しています。
          </p>
        </div>

        <div class="voice-card">
          <div class="voice-header">
            <div class="voice-avatar">👴</div>
            <div class="voice-info">
              <div class="voice-name">60代男性 / 経営者</div>
              <div class="voice-category">事業承継対策</div>
            </div>
          </div>
          <p class="voice-text">
            事業承継について悩んでいましたが、税理士とも連携しながら総合的なサポートをしていただきました。自社株対策や後継者への引き継ぎ計画など、専門的なアドバイスをいただけて大変助かりました。
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Company Section -->
  <section class="section section-white" id="company">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">Company</span>
          <h2 class="hgroup-heading">会社概要・アクセス</h2>
        </div>
        <p class="section-lead">株式会社ファイナンスブレーン</p>
      </div>
      <div class="company-content">
        <div class="company-info">
          <table class="company-table">
            <tr>
              <th>会社名</th>
              <td>株式会社ファイナンスブレーン</td>
            </tr>
            <tr>
              <th>代表取締役</th>
              <td>高橋 英一郎</td>
            </tr>
            <tr>
              <th>設立</th>
              <td>2006年8月</td>
            </tr>
            <tr>
              <th>所在地</th>
              <td>
                〒870-0934<br>
                大分県大分市東津留1-6-11<br>
                大分ハイデンス1F
              </td>
            </tr>
            <tr>
              <th>TEL</th>
              <td><a href="tel:097-574-8212">097-574-8212</a></td>
            </tr>
            <tr>
              <th>FAX</th>
              <td>097-574-8213</td>
            </tr>
            <tr>
              <th>営業時間</th>
              <td>平日 9:00〜18:00（土日祝日は予約制）</td>
            </tr>
            <tr>
              <th>取扱保険会社</th>
              <td>
                生命保険11社、損害保険5社<br>
                <small>※詳細はお問い合わせください</small>
              </td>
            </tr>
          </table>
        </div>
        <div class="company-map">
          <div class="map-placeholder">
            <p style="font-size: var(--fz-lg); color: var(--text-dark); margin-bottom: 1rem;">📍 Google Mapを表示</p>
            <p class="map-note">大分駅から車で約10分<br>駐車場完備</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="section section-white" id="contact">
    <div class="container">
      <div class="section-header">
        <div data-hgroup>
          <span class="hgroup-text">Contact</span>
          <h2 class="hgroup-heading">お問い合わせ</h2>
        </div>
        <p class="section-lead">お気軽にご相談ください。初回相談は無料です。</p>
      </div>
      <div class="contact-methods">
        <div class="contact-method">
          <h3 class="contact-method-title">お電話でのご相談</h3>
          <p class="contact-method-tel"><a href="tel:097-574-8212">097-574-8212</a></p>
          <p class="contact-method-time">平日 9:00〜18:00</p>
        </div>
        <div class="contact-method">
          <h3 class="contact-method-title">LINEで気軽に相談</h3>
          <p class="contact-method-description">LINEアプリから簡単にご相談いただけます</p>
          <a href="https://lin.ee/149antcn" class="btn btn-line" target="_blank" rel="noopener">
            <span class="btn-text">
              LINE友だち追加
              <span class="arrow-wrapper">
                <svg><use href="#icon-arrow-forward"></use></svg>
                <svg><use href="#icon-arrow-forward"></use></svg>
              </span>
            </span>
          </a>
        </div>
      </div>
      <div class="contact-form-area">
        <h3 class="form-title">お問い合わせフォーム</h3>
        <form class="contact-form" id="contactForm">
          <div class="form-group">
            <label for="inquiry-type" class="form-label">お問い合わせ種別<span class="required">必須</span></label>
            <select id="inquiry-type" name="inquiry-type" class="form-control" required>
              <option value="">選択してください</option>
              <option value="consultation">無料相談予約</option>
              <option value="inquiry">一般問い合わせ</option>
              <option value="materials">資料請求</option>
              <option value="other">その他</option>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="name" class="form-label">お名前<span class="required">必須</span></label>
              <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="name-kana" class="form-label">フリガナ<span class="required">必須</span></label>
              <input type="text" id="name-kana" name="name-kana" class="form-control" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="email" class="form-label">メールアドレス<span class="required">必須</span></label>
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="tel" class="form-label">電話番号<span class="required">必須</span></label>
              <input type="tel" id="tel" name="tel" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label for="message" class="form-label">お問い合わせ内容<span class="required">必須</span></label>
            <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
          </div>
          <div class="form-privacy">
            <label class="checkbox-label">
              <input type="checkbox" name="privacy" required>
              <span><a href="#" target="_blank">個人情報保護方針</a>に同意する</span>
            </label>
          </div>
          <div class="form-submit">
            <button type="submit" class="btn btn-large">
              <span class="btn-text">
                送信する
                <span class="arrow-wrapper">
                  <svg><use href="#icon-arrow-forward"></use></svg>
                  <svg><use href="#icon-arrow-forward"></use></svg>
                </span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <div class="footer-logo">
            <img src="assets/images/logo.jpg" alt="ファイナンスブレーン Finance Brain">
          </div>
          <p class="footer-tagline">大分の【保険・投資信託・資産運用】のコンサルタント</p>
          <p class="footer-address">
            〒870-0934<br>
            大分県大分市東津留1-6-11 大分ハイデンス1F<br>
            TEL: <a href="tel:097-574-8212">097-574-8212</a>
          </p>
        </div>

        <div class="footer-column">
          <h4>個人向けサービス</h4>
          <ul>
            <li><a href="services/personal/life-planning/index.html">ライフプランニング</a></li>
            <li><a href="services/personal/insurance/index.html">保険の見直し・ご相談</a></li>
            <li><a href="services/personal/housing-loan/index.html">住宅ローンのご相談</a></li>
            <li><a href="services/personal/inheritance/index.html">相続に関するご相談</a></li>
            <li><a href="services/personal/investment/index.html">投資信託・資産運用</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>法人向けサービス</h4>
          <ul>
            <li><a href="services/corporate/financial-consulting/index.html">財務コンサルティング</a></li>
            <li><a href="services/corporate/retirement/index.html">退職金コンサルティング</a></li>
            <li><a href="services/corporate/succession/index.html">事業承継対策</a></li>
            <li><a href="services/corporate/stock/index.html">自社株対策</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h4>企業情報</h4>
          <ul>
            <li><a href="company/index.html">会社概要</a></li>
            <li><a href="#voice">お客様の声</a></li>
            <li><a href="#contact">お問い合わせ</a></li>
            <li><a href="news/staff-blog/index.html">スタッフブログ</a></li>
            <li><a href="#">個人情報保護方針</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2006-2026 株式会社ファイナンスブレーン All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript -->
  <script>
    // Tab切り替え機能
    document.querySelectorAll('.tab-button').forEach(button => {
      button.addEventListener('click', () => {
        const tab = button.dataset.tab;

        // すべてのタブボタンとコンテンツからactiveクラスを削除
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        // クリックされたボタンと対応するコンテンツにactiveクラスを追加
        button.classList.add('active');
        document.getElementById(tab).classList.add('active');
      });
    });

    // フォーム送信処理（デモ用）
    document.getElementById('contactForm').addEventListener('submit', (e) => {
      e.preventDefault();
      alert('お問い合わせありがとうございます。\n内容を確認の上、担当者よりご連絡させていただきます。');
    });
  </script>
</body>
</html>
