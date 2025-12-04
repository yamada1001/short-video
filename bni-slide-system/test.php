<?php
/**
 * Test Script - Absolute Path Checker
 *
 * このファイルをブラウザで開いて、絶対パスを確認してください。
 * 確認後は、セキュリティのため必ず削除してください。
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Path Checker - BNI Slide System</title>
  <style>
    body {
      font-family: 'Courier New', monospace;
      background-color: #f5f5f5;
      padding: 40px;
      line-height: 1.6;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h1 {
      color: #CF2030;
      border-bottom: 3px solid #CF2030;
      padding-bottom: 10px;
    }
    .path-box {
      background-color: #2d2d2d;
      color: #00ff00;
      padding: 20px;
      border-radius: 4px;
      margin: 20px 0;
      font-size: 14px;
      word-break: break-all;
    }
    .instruction {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 15px;
      margin: 20px 0;
    }
    .warning {
      background-color: #f8d7da;
      border-left: 4px solid #dc3545;
      padding: 15px;
      margin: 20px 0;
      color: #721c24;
    }
    .info {
      background-color: #d1ecf1;
      border-left: 4px solid #17a2b8;
      padding: 15px;
      margin: 20px 0;
      color: #0c5460;
    }
    code {
      background-color: #f4f4f4;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: 'Courier New', monospace;
    }
    .delete-btn {
      display: inline-block;
      background-color: #dc3545;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 20px;
    }
    .delete-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🔍 Absolute Path Checker</h1>

    <div class="info">
      <strong>このスクリプトの目的:</strong><br>
      Xserverの絶対パスを確認して、<code>.htaccess</code> の <code>AuthUserFile</code> に設定するためのツールです。
    </div>

    <h2>📍 検出された絶対パス</h2>

    <div class="path-box">
      <?php echo __DIR__; ?>
    </div>

    <h2>📝 .htaccess に設定するパス</h2>

    <div class="instruction">
      <strong>以下のパスをコピーして、.htaccess の28行目に貼り付けてください:</strong>
    </div>

    <div class="path-box">
      AuthUserFile <?php echo __DIR__; ?>/.htpasswd
    </div>

    <h2>✅ 設定手順</h2>

    <ol>
      <li>上記の「AuthUserFile」で始まる行をコピー</li>
      <li><code>bni-slide-system/.htaccess</code> を開く</li>
      <li>28行目（AuthUserFile の行）を探す</li>
      <li>コピーした内容に置き換える</li>
      <li>ファイルを保存</li>
      <li><strong>このtest.phpを削除する（セキュリティのため必須）</strong></li>
    </ol>

    <div class="warning">
      <strong>⚠️ 重要:</strong><br>
      絶対パスを確認したら、このファイル（test.php）は必ず削除してください。<br>
      サーバー情報が外部に漏れるリスクがあります。
    </div>

    <h2>🛠 その他の情報</h2>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
      <tr style="background-color: #f8f9fa;">
        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">項目</td>
        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">値</td>
      </tr>
      <tr>
        <td style="padding: 10px; border: 1px solid #ddd;">サーバーOS</td>
        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo PHP_OS; ?></td>
      </tr>
      <tr>
        <td style="padding: 10px; border: 1px solid #ddd;">PHPバージョン</td>
        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo phpversion(); ?></td>
      </tr>
      <tr>
        <td style="padding: 10px; border: 1px solid #ddd;">ドキュメントルート</td>
        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td>
      </tr>
      <tr>
        <td style="padding: 10px; border: 1px solid #ddd;">サーバー名</td>
        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?></td>
      </tr>
      <tr>
        <td style="padding: 10px; border: 1px solid #ddd;">このファイルのパス</td>
        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo __FILE__; ?></td>
      </tr>
    </table>

    <h2>🔐 パーミッション確認</h2>

    <div class="info">
      <strong>設定すべきパーミッション:</strong>
      <ul>
        <li><code>bni-slide-system/data/</code> → 707</li>
        <li><code>bni-slide-system/.htpasswd</code> → 604</li>
        <li><code>bni-slide-system/.htaccess</code> → 644</li>
      </ul>
    </div>

    <?php
    // Check if data directory exists and is writable
    $dataDir = __DIR__ . '/data';
    $htpasswdFile = __DIR__ . '/.htpasswd';
    $htaccessFile = __DIR__ . '/.htaccess';
    ?>

    <h3>現在の状態:</h3>
    <ul>
      <li>data/ ディレクトリ:
        <?php
        if (is_dir($dataDir)) {
          echo is_writable($dataDir) ? '✅ 書き込み可能' : '❌ 書き込み不可（パーミッション要確認）';
        } else {
          echo '❌ ディレクトリが存在しません';
        }
        ?>
      </li>
      <li>.htpasswd ファイル:
        <?php
        if (file_exists($htpasswdFile)) {
          echo '✅ 存在する';
        } else {
          echo '❌ ファイルが存在しません';
        }
        ?>
      </li>
      <li>.htaccess ファイル:
        <?php
        if (file_exists($htaccessFile)) {
          echo '✅ 存在する';
        } else {
          echo '❌ ファイルが存在しません';
        }
        ?>
      </li>
    </ul>

    <div class="warning">
      <strong>🗑 確認後は必ず削除:</strong><br>
      <code>rm test.php</code> または FTP で削除してください。
    </div>

    <p style="text-align: center; margin-top: 40px; color: #999;">
      <small>BNI Slide System - Test Script</small>
    </p>
  </div>
</body>
</html>
