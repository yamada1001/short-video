<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Members Debug</title>
</head>
<body>
  <h1>Members.json Debug</h1>

  <h2>1. ファイル存在確認</h2>
  <p>
    <?php
    $file = __DIR__ . '/data/members.json';
    echo file_exists($file) ? '✅ ファイルが存在します' : '❌ ファイルが存在しません';
    ?>
  </p>

  <h2>2. ファイル内容</h2>
  <pre><?php
  if (file_exists($file)) {
    echo htmlspecialchars(file_get_contents($file));
  }
  ?></pre>

  <h2>3. JSON解析</h2>
  <pre><?php
  if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    print_r($data);
  }
  ?></pre>

  <h2>4. JavaScriptでのFetch確認（直接アクセス）</h2>
  <button onclick="testFetch()">data/members.json</button>
  <pre id="fetchResult"></pre>

  <h2>5. JavaScriptでのFetch確認（API経由）</h2>
  <button onclick="testFetchAPI()">api_members.php</button>
  <pre id="fetchResultAPI"></pre>

  <script>
  async function testFetch() {
    try {
      const response = await fetch('data/members.json');
      const data = await response.json();
      document.getElementById('fetchResult').textContent = JSON.stringify(data, null, 2);
    } catch (error) {
      document.getElementById('fetchResult').textContent = 'Error: ' + error.message;
    }
  }

  async function testFetchAPI() {
    try {
      const response = await fetch('api_members.php');
      const data = await response.json();
      document.getElementById('fetchResultAPI').textContent = JSON.stringify(data, null, 2);
    } catch (error) {
      document.getElementById('fetchResultAPI').textContent = 'Error: ' + error.message;
    }
  }
  </script>

  <p><a href="index.php">アンケートフォームに戻る</a></p>
</body>
</html>
