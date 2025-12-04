<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>BNI週次レポート | BNI Slide System</title>

  <!-- Reveal.js CSS -->
  <link rel="stylesheet" href="../assets/lib/reveal.js/dist/reveal.css">
  <link rel="stylesheet" href="../assets/lib/reveal.js/dist/theme/white.css">

  <!-- Custom Slide CSS -->
  <link rel="stylesheet" href="../assets/css/slide.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="spinner"></div>
    <div class="loading-text">データを読み込んでいます...</div>
  </div>

  <!-- Week Selector -->
  <div style="position: fixed; top: 10px; left: 10px; z-index: 2000; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 12px 16px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.15);">
    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #CF2030; font-size: 12px;">表示する週:</label>
    <select id="weekSelector" style="padding: 6px 10px; border: 2px solid #CF2030; border-radius: 4px; font-size: 13px; min-width: 180px; cursor: pointer;">
      <option value="">読み込み中...</option>
    </select>
  </div>

  <!-- Reveal.js Presentation -->
  <div class="reveal">
    <div class="slides" id="slideContainer">
      <!-- Slides will be generated dynamically by JavaScript -->
    </div>
  </div>

  <!-- Edit Button -->
  <a href="edit.php" class="edit-button">編集モード</a>

  <!-- External Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/countup@1.8.2/dist/countUp.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

  <!-- Reveal.js Scripts -->
  <script src="../assets/lib/reveal.js/dist/reveal.js"></script>

  <!-- Custom Slide Script -->
  <script src="../assets/js/slide.js"></script>

</body>
</html>
