<?php
/**
 * BNI Slide System - Web-based Production Setup
 * ブラウザから本番環境のデータベースを初期化
 *
 * ⚠️ セキュリティ警告:
 * このファイルは初回セットアップ後は削除してください
 */

// セキュリティトークン（変更してください）
define('SETUP_TOKEN', 'bni-setup-2025-secure-token-change-this');

// リクエストトークンチェック
$requestToken = $_GET['token'] ?? '';
if ($requestToken !== SETUP_TOKEN) {
    http_response_code(403);
    die('❌ 不正なアクセスです。URLに正しいトークンを指定してください。<br><br>使用方法: setup_production.php?token=' . SETUP_TOKEN);
}

echo "<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BNI Slide System - 本番環境セットアップ</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .log { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #333; }
        pre { background: #000; color: #0f0; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🚀 BNI Slide System - 本番環境セットアップ</h1>
";

// データベースファイルのパス
$dbPath = __DIR__ . '/data/bni_system.db';
$schemaPath = __DIR__ . '/database/schema.sql';
$membersJsonPath = __DIR__ . '/data/members.json';

echo "<div class='log'>";
echo "<h2>📊 セットアップ開始</h2>";
echo "<p>データベースパス: <code>$dbPath</code></p>";

// データベース接続
try {
    $db = new SQLite3($dbPath);
    echo "<p class='success'>✅ データベース接続成功</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ データベース接続失敗: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div></body></html>";
    exit(1);
}

// 既存のテーブルをチェック
$tables = [];
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $tables[] = $row['name'];
}

echo "<p>既存のテーブル: " . (empty($tables) ? 'なし' : implode(', ', $tables)) . "</p>";

// usersテーブルが存在するかチェック
if (in_array('users', $tables)) {
    echo "<p class='warning'>⚠️ usersテーブルは既に存在します。既存データを保持します。</p>";
} else {
    echo "<h3>📋 スキーマファイルを実行中...</h3>";

    // スキーマファイルを読み込み
    $schema = file_get_contents($schemaPath);
    if ($schema === false) {
        echo "<p class='error'>❌ スキーマファイルの読み込みに失敗: $schemaPath</p>";
        echo "</div></body></html>";
        exit(1);
    }

    // スキーマを実行
    try {
        $db->exec($schema);
        echo "<p class='success'>✅ スキーマファイルの実行に成功しました</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ スキーマ実行エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div></body></html>";
        exit(1);
    }
}

// members.jsonからユーザーをインポート
if (!file_exists($membersJsonPath)) {
    echo "<p class='error'>❌ members.json が見つかりません: $membersJsonPath</p>";
    echo "<p>data/members.json.sample を data/members.json にコピーして、パスワードを設定してください。</p>";
    echo "</div></body></html>";
    exit(1);
}

echo "<h3>📥 members.json からユーザーをインポート中...</h3>";

$membersData = json_decode(file_get_contents($membersJsonPath), true);
if ($membersData === null) {
    echo "<p class='error'>❌ members.json の読み込みに失敗しました（JSON形式が不正です）</p>";
    echo "</div></body></html>";
    exit(1);
}

$importCount = 0;
$skipCount = 0;
$errors = [];

foreach ($membersData['users'] as $email => $userData) {
    // 既に存在するかチェック
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $exists = $result->fetchArray(SQLITE3_ASSOC);

    if ($exists) {
        echo "<p>⏭ スキップ: " . htmlspecialchars($email) . " (既に存在)</p>";
        $skipCount++;
        continue;
    }

    // ユーザーを挿入
    $stmt = $db->prepare("
        INSERT INTO users (
            email, name, password_hash, phone, company, category, role,
            htpasswd_user, created_at, updated_at
        ) VALUES (
            :email, :name, :password_hash, :phone, :company, :category, :role,
            :htpasswd_user, :created_at, :updated_at
        )
    ");

    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $stmt->bindValue(':name', $userData['name'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':password_hash', $userData['password_hash'] ?? null, SQLITE3_TEXT);
    $stmt->bindValue(':phone', $userData['phone'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':company', $userData['company'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':category', $userData['category'] ?? '', SQLITE3_TEXT);
    $stmt->bindValue(':role', $userData['role'] ?? 'member', SQLITE3_TEXT);
    $stmt->bindValue(':htpasswd_user', $userData['htpasswd_user'] ?? null, SQLITE3_TEXT);
    $stmt->bindValue(':created_at', $userData['created_at'] ?? date('Y-m-d H:i:s'), SQLITE3_TEXT);
    $stmt->bindValue(':updated_at', $userData['updated_at'] ?? date('Y-m-d H:i:s'), SQLITE3_TEXT);

    try {
        $stmt->execute();
        echo "<p class='success'>✅ インポート: " . htmlspecialchars($email) . " (" . htmlspecialchars($userData['name']) . ")</p>";
        $importCount++;
    } catch (Exception $e) {
        $errorMsg = htmlspecialchars($email) . " - " . htmlspecialchars($e->getMessage());
        echo "<p class='error'>❌ エラー: $errorMsg</p>";
        $errors[] = $errorMsg;
    }
}

echo "<h3>📊 インポート結果</h3>";
echo "<ul>";
echo "<li>新規追加: <strong>$importCount</strong> ユーザー</li>";
echo "<li>スキップ: <strong>$skipCount</strong> ユーザー</li>";
if (!empty($errors)) {
    echo "<li class='error'>エラー: <strong>" . count($errors) . "</strong> 件</li>";
}
echo "</ul>";

// 最終確認
$result = $db->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetchArray(SQLITE3_ASSOC);
echo "<p class='success'>✅ 完了！ usersテーブルのユーザー数: <strong>{$row['count']}</strong></p>";

$db->close();

echo "</div>";

echo "<div class='log'>";
echo "<h2>🎉 セットアップ完了</h2>";
echo "<p>次のステップ:</p>";
echo "<ol>";
echo "<li>✅ ログインページにアクセス: <a href='login.php'>login.php</a></li>";
echo "<li>✅ アンケートフォームにアクセス: <a href='index.php'>index.php</a></li>";
echo "<li>⚠️ <strong>重要</strong>: セキュリティのため、このファイル（setup_production.php）を削除してください</li>";
echo "</ol>";
echo "<pre>rm " . __FILE__ . "</pre>";
echo "</div>";

echo "</body></html>";
