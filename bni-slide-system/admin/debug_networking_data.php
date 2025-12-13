<?php
/**
 * Debug Networking Learning Data
 * ネットワーキング学習コーナーデータのデバッグ
 */

require_once __DIR__ . '/../includes/session_auth.php';
require_once __DIR__ . '/../includes/db.php';

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
  <title>ネットワーキング学習コーナー デバッグ</title>
  <style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    pre { background: white; padding: 20px; border-radius: 8px; overflow-x: auto; }
    h2 { color: #CF2030; }
    table { border-collapse: collapse; width: 100%; background: white; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: #CF2030; color: white; }
  </style>
</head>
<body>
  <h1>🔍 ネットワーキング学習コーナー データデバッグ</h1>

<?php
try {
    $db = getDbConnection();

    echo "<h2>📊 全データ一覧</h2>";
    $allData = dbQuery($db, "SELECT * FROM networking_learning_presenters ORDER BY created_at DESC");

    if ($allData && count($allData) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>週日付</th><th>担当者名</th><th>PDF</th><th>作成日時</th></tr>";
        foreach ($allData as $row) {
            $pdfStatus = $row['pdf_file_path'] ? '✅ あり' : '❌ なし';
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td><strong>{$row['week_date']}</strong></td>";
            echo "<td>{$row['presenter_name']}</td>";
            echo "<td>{$pdfStatus}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ データが存在しません</p>";
    }

    echo "<h2>📅 現在の週情報</h2>";
    echo "<pre>";
    echo "今日: " . date('Y-m-d') . "\n";
    echo "曜日: " . date('l (w)') . "\n";

    // getTargetFriday関数をシミュレート
    require_once __DIR__ . '/../includes/date_helper.php';
    $targetFriday = getTargetFriday(date('Y-m-d'));
    echo "対象金曜日（getTargetFriday）: {$targetFriday}\n";
    echo "</pre>";

    echo "<h2>🔍 APIが取得しようとしている週のデータ</h2>";
    $apiData = dbQueryOne($db, "SELECT * FROM networking_learning_presenters WHERE week_date = ?", [$targetFriday]);

    if ($apiData) {
        echo "<pre>";
        print_r($apiData);
        echo "</pre>";
    } else {
        echo "<p>❌ 週日付 <strong>{$targetFriday}</strong> のデータは存在しません</p>";
    }

    dbClose($db);

} catch (Exception $e) {
    echo "<p style='color:red;'>❌ エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

</body>
</html>
