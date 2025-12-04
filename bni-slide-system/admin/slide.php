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
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="spinner"></div>
    <div class="loading-text">データを読み込んでいます...</div>
  </div>

  <!-- Control Icon Button -->
  <button id="controlButton" class="control-icon-button" title="コントロール">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="3"></circle>
      <path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m-2.83 2.83l-4.24 4.24m13.66-4.24l-4.24-4.24m-2.83-2.83l-4.24-4.24"></path>
    </svg>
  </button>

  <!-- Control Panel Modal -->
  <div id="controlPanel" class="control-panel hidden">
    <div class="control-panel-content">
      <div class="control-panel-header">
        <h3>コントロールパネル</h3>
        <button id="closeControlPanel" class="close-button">×</button>
      </div>
      <div class="control-panel-body">
        <div class="control-group">
          <label>表示する週:</label>
          <select id="weekSelector">
            <option value="">読み込み中...</option>
          </select>
        </div>
        <div class="control-group">
          <a href="edit.php" class="edit-link">📝 編集モード</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Reveal.js Presentation -->
  <div class="reveal">
    <div class="slides" id="slideContainer">
      <!-- Slides will be generated dynamically by JavaScript -->
    </div>
  </div>

  <!-- Reveal.js Scripts -->
  <script src="../assets/lib/reveal.js/dist/reveal.js"></script>

  <!-- D3.js Library -->
  <script src="https://d3js.org/d3.v7.min.js"></script>

  <!-- SVG Slide Generator -->
  <script src="../assets/js/svg-slide-generator.js"></script>

  <!-- Custom Slide Script -->
  <script src="../assets/js/slide.js"></script>

</body>
</html>
