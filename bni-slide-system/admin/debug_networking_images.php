<?php
/**
 * Debug Networking Learning Images
 * ネットワーキング学習コーナーの画像データをデバッグ
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/pdf_helper.php';

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

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ネットワーキング学習コーナー 画像デバッグ</title>
  <style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    pre { background: white; padding: 20px; border-radius: 8px; overflow-x: auto; margin: 20px 0; }
    h2 { color: #CF2030; }
    table { border-collapse: collapse; width: 100%; background: white; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: #CF2030; color: white; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    img { max-width: 300px; border: 2px solid #ddd; margin: 10px; }
  </style>
</head>
<body>
  <h1>🔍 ネットワーキング学習コーナー 画像データデバッグ</h1>

<?php
try {
    $db = getDbConnection();

    echo "<h2>📊 全データ一覧</h2>";
    $allData = dbQuery($db, "SELECT * FROM networking_learning_presenters ORDER BY created_at DESC");

    if ($allData && count($allData) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>週日付</th><th>担当者名</th><th>PDF</th><th>ページ数</th><th>画像パス</th><th>作成日時</th></tr>";
        foreach ($allData as $row) {
            $pdfStatus = $row['pdf_file_path'] ? '✅ あり' : '❌ なし';
            $pageCount = $row['pdf_page_count'] ?? 0;
            $imagePaths = $row['pdf_image_paths'] ?? '';

            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td><strong>{$row['week_date']}</strong></td>";
            echo "<td>{$row['presenter_name']}</td>";
            echo "<td>{$pdfStatus}</td>";
            echo "<td>{$pageCount}</td>";
            echo "<td style='max-width: 300px; overflow: auto; font-size: 11px;'>" . htmlspecialchars(substr($imagePaths, 0, 100)) . "...</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ データが存在しません</p>";
    }

    echo "<h2>📷 最新データの詳細</h2>";
    $latestData = dbQueryOne($db, "SELECT * FROM networking_learning_presenters ORDER BY created_at DESC LIMIT 1");

    if ($latestData) {
        echo "<pre>";
        echo "ID: " . $latestData['id'] . "\n";
        echo "週日付: " . $latestData['week_date'] . "\n";
        echo "担当者名: " . $latestData['presenter_name'] . "\n";
        echo "PDFファイルパス: " . ($latestData['pdf_file_path'] ?? 'なし') . "\n";
        echo "PDFオリジナル名: " . ($latestData['pdf_file_original_name'] ?? 'なし') . "\n";
        echo "ページ数: " . ($latestData['pdf_page_count'] ?? 0) . "\n";
        echo "\n--- PDF画像パス（JSON） ---\n";
        echo $latestData['pdf_image_paths'] ?? 'なし';
        echo "\n\n--- デコード後の配列 ---\n";

        if (!empty($latestData['pdf_image_paths'])) {
            $imagePathsArray = decodeImagePaths($latestData['pdf_image_paths']);
            print_r($imagePathsArray);

            echo "\n--- 画像ファイルの存在確認 ---\n";
            foreach ($imagePathsArray as $index => $imagePath) {
                $fullPath = __DIR__ . '/../' . $imagePath;
                $exists = file_exists($fullPath);
                $statusIcon = $exists ? '✅' : '❌';
                echo "{$statusIcon} [{$index}] {$imagePath}";
                if ($exists) {
                    $fileSize = filesize($fullPath);
                    echo " (" . round($fileSize / 1024, 2) . " KB)";
                } else {
                    echo " (ファイルが存在しません)";
                }
                echo "\n";
            }
        } else {
            echo "画像パスが保存されていません\n";
        }
        echo "</pre>";

        // 画像のプレビュー
        if (!empty($latestData['pdf_image_paths'])) {
            $imagePathsArray = decodeImagePaths($latestData['pdf_image_paths']);

            echo "<h2>🖼️ 画像プレビュー</h2>";
            echo "<div style='background: white; padding: 20px; border-radius: 8px;'>";

            foreach ($imagePathsArray as $index => $imagePath) {
                $fullPath = __DIR__ . '/../' . $imagePath;
                if (file_exists($fullPath)) {
                    $webPath = '../' . $imagePath;
                    echo "<div style='display: inline-block; margin: 10px; text-align: center;'>";
                    echo "<img src='{$webPath}' alt='Page " . ($index + 1) . "' />";
                    echo "<div>ページ " . ($index + 1) . "</div>";
                    echo "</div>";
                }
            }

            echo "</div>";
        }

    } else {
        echo "<p>❌ データが存在しません</p>";
    }

    echo "<h2>🔧 Imagick 環境確認</h2>";
    echo "<pre>";
    if (extension_loaded('imagick')) {
        echo "✅ Imagick 拡張がロードされています\n";
        $imagick = new Imagick();
        $version = $imagick->getVersion();
        echo "バージョン: " . $version['versionString'] . "\n";
    } else {
        echo "❌ Imagick 拡張がロードされていません\n";
    }
    echo "</pre>";

    dbClose($db);

} catch (Exception $e) {
    echo "<p style='color:red;'>❌ エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<div style="margin-top: 40px; padding: 20px; background: white; border-radius: 8px;">
  <h3>📝 確認ポイント</h3>
  <ul>
    <li><strong>ページ数:</strong> 0より大きいか？</li>
    <li><strong>画像パス（JSON）:</strong> 空でないか？</li>
    <li><strong>画像ファイルの存在:</strong> 全て✅か？</li>
    <li><strong>画像プレビュー:</strong> 正しく表示されているか？</li>
  </ul>

  <h3>🔗 関連リンク</h3>
  <ul>
    <li><a href="networking_learning.php">ネットワーキング学習コーナー管理画面</a></li>
    <li><a href="slide.php">スライド表示</a></li>
    <li><a href="../database/migrate_add_pdf_page_count.php">マイグレーション実行</a></li>
  </ul>
</div>

</body>
</html>
