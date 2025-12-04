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
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="spinner"></div>
    <div class="loading-text">データを読み込んでいます...</div>
  </div>

  <!-- Week Selector -->
  <div style="position: fixed; top: 20px; left: 20px; z-index: 1000; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #CF2030;">表示する週:</label>
    <select id="weekSelector" style="padding: 8px 12px; border: 2px solid #CF2030; border-radius: 4px; font-size: 14px; min-width: 200px;">
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

  <!-- Reveal.js Scripts -->
  <script src="../assets/lib/reveal.js/dist/reveal.js"></script>

  <!-- Custom Slide Script -->
  <script src="../assets/js/slide.js"></script>

</body>
</html>
