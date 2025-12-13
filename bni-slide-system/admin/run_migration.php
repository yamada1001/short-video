<?php
/**
 * Migration Runner for Production
 * 本番環境用マイグレーション実行ページ
 */

require_once __DIR__ . '/../includes/session_auth.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: ../login.php');
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    die('<h1>アクセス拒否</h1><p>このページは管理者のみアクセス可能です。</p>');
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイグレーション実行 | BNI Slide System</title>
  <style>
    body {
      font-family: 'Noto Sans JP', sans-serif;
      max-width: 800px;
      margin: 50px auto;
      padding: 20px;
      background: #f5f5f5;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h1 {
      color: #CF2030;
      margin-bottom: 20px;
    }
    .migration-btn {
      background: #CF2030;
      color: white;
      padding: 15px 30px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin: 10px 0;
      display: block;
      width: 100%;
    }
    .migration-btn:hover {
      background: #a01828;
    }
    .result {
      margin-top: 20px;
      padding: 15px;
      border-radius: 8px;
      white-space: pre-wrap;
      font-family: monospace;
    }
    .result.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .result.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔧 データベースマイグレーション実行</h1>
    <p>以下のボタンをクリックして、必要なマイグレーションを実行してください。</p>

    <button class="migration-btn" onclick="runMigration('networking_pdf')">
      ネットワーキング学習コーナーPDFカラム追加
    </button>

    <div id="result"></div>
  </div>

  <script>
    async function runMigration(type) {
      const resultDiv = document.getElementById('result');
      resultDiv.innerHTML = '<p>マイグレーション実行中...</p>';

      try {
        const response = await fetch('../database/migrate_add_networking_pdf.php');
        const text = await response.text();

        if (response.ok) {
          resultDiv.className = 'result success';
          resultDiv.textContent = text;
        } else {
          resultDiv.className = 'result error';
          resultDiv.textContent = 'エラー:\n' + text;
        }
      } catch (error) {
        resultDiv.className = 'result error';
        resultDiv.textContent = 'エラー: ' + error.message;
      }
    }
  </script>
</body>
</html>
