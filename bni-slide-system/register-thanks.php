<?php
/**
 * BNI Slide System - Registration Thanks Page
 * 新規登録完了ページ
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
  <!-- End Google Tag Manager -->

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>登録完了 | BNI Slide System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/form.css">

  <style>
    .thanks-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--bni-red) 0%, #a01828 100%);
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    .thanks-container::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
    }

    .thanks-container::after {
      content: '';
      position: absolute;
      bottom: -50%;
      left: -50%;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
    }

    .thanks-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 600px;
      width: 100%;
      padding: 60px 40px;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .thanks-logo {
      width: 120px;
      height: auto;
      margin: 0 auto 30px;
      display: block;
    }

    .success-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #28a745, #20c997);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      color: white;
      font-size: 50px;
      animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
      0% {
        transform: scale(0);
        opacity: 0;
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .thanks-title {
      font-size: 32px;
      color: var(--bni-red);
      margin-bottom: 20px;
      font-weight: 700;
    }

    .thanks-message {
      font-size: 16px;
      color: #666;
      line-height: 1.8;
      margin-bottom: 30px;
    }

    .info-box {
      background: #f9f9f9;
      border-left: 4px solid var(--bni-red);
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
      text-align: left;
    }

    .info-box h3 {
      color: var(--bni-red);
      font-size: 18px;
      margin-bottom: 15px;
    }

    .info-box p {
      color: #666;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 10px;
    }

    .info-box strong {
      color: #333;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 15px 40px;
      background: var(--bni-red);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: #e01020;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(207, 32, 48, 0.3);
    }
  </style>
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <div class="thanks-container">
    <div class="thanks-card">
      <img src="assets/images/bni-logo.svg" alt="BNI Logo" class="thanks-logo">

      <div class="success-icon">
        <i class="fas fa-check"></i>
      </div>

      <h1 class="thanks-title">登録完了！</h1>

      <p class="thanks-message">
        BNI Slide Systemへの登録が完了しました。<br>
        ありがとうございます！
      </p>

      <div class="info-box">
        <h3><i class="fas fa-envelope"></i> メールをご確認ください</h3>
        <p>
          ご登録いただいたメールアドレス宛に、ログイン情報を記載したメールを送信しました。
        </p>
        <p>
          <strong>メールが届かない場合:</strong><br>
          迷惑メールフォルダをご確認いただくか、管理者までお問い合わせください。
        </p>
      </div>

      <div class="info-box">
        <h3><i class="fas fa-info-circle"></i> 次のステップ</h3>
        <p>
          1. メールに記載されているログインID（メールアドレス）とパスワードを確認<br>
          2. ログインページからログイン<br>
          3. セキュリティのため、初回ログイン後にパスワードを変更することをお勧めします
        </p>
      </div>

      <a href="login.php" class="btn-primary">
        <i class="fas fa-sign-in-alt"></i>
        ログインページへ
      </a>
    </div>
  </div>

</body>
</html>
