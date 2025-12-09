<?php
/**
 * BNI Slide System - Production Database Initialization
 * 本番環境のデータベースを初期化し、members.jsonからユーザーをインポート
 */

echo "=== BNI Slide System - Production Database Initialization ===\n\n";

// データベースファイルのパス
$dbPath = __DIR__ . '/../data/bni_system.db';
$schemaPath = __DIR__ . '/schema.sql';
$membersJsonPath = __DIR__ . '/../data/members.json';

// データベース接続
try {
    $db = new SQLite3($dbPath);
    echo "✅ データベース接続成功: $dbPath\n\n";
} catch (Exception $e) {
    echo "❌ データベース接続失敗: " . $e->getMessage() . "\n";
    exit(1);
}

// 既存のテーブルをチェック
$tables = [];
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $tables[] = $row['name'];
}

echo "既存のテーブル: " . implode(', ', $tables) . "\n\n";

// usersテーブルが存在するかチェック
if (in_array('users', $tables)) {
    echo "⚠️  usersテーブルは既に存在します。\n";
    echo "データを保持したまま処理を続けますか？ (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) !== 'y') {
        echo "中止しました。\n";
        exit(0);
    }
    fclose($handle);
} else {
    echo "📋 スキーマファイルを実行します...\n";

    // スキーマファイルを読み込み
    $schema = file_get_contents($schemaPath);
    if ($schema === false) {
        echo "❌ スキーマファイルの読み込みに失敗しました: $schemaPath\n";
        exit(1);
    }

    // スキーマを実行
    try {
        $db->exec($schema);
        echo "✅ スキーマファイルの実行に成功しました\n\n";
    } catch (Exception $e) {
        echo "❌ スキーマ実行エラー: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// members.jsonからユーザーをインポート
if (file_exists($membersJsonPath)) {
    echo "📥 members.json からユーザーをインポートします...\n";

    $membersData = json_decode(file_get_contents($membersJsonPath), true);
    if ($membersData === null) {
        echo "❌ members.json の読み込みに失敗しました\n";
        exit(1);
    }

    $importCount = 0;
    $skipCount = 0;

    foreach ($membersData['users'] as $email => $userData) {
        // 既に存在するかチェック
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        $exists = $result->fetchArray(SQLITE3_ASSOC);

        if ($exists) {
            echo "  ⏭  スキップ: $email (既に存在)\n";
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
            echo "  ✅ インポート: $email ({$userData['name']})\n";
            $importCount++;
        } catch (Exception $e) {
            echo "  ❌ エラー: $email - " . $e->getMessage() . "\n";
        }
    }

    echo "\n📊 インポート結果:\n";
    echo "  - 新規追加: $importCount ユーザー\n";
    echo "  - スキップ: $skipCount ユーザー\n";
} else {
    echo "⚠️  members.json が見つかりません: $membersJsonPath\n";
}

// 最終確認
$result = $db->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetchArray(SQLITE3_ASSOC);
echo "\n✅ 完了！ usersテーブルのユーザー数: {$row['count']}\n";

$db->close();
echo "\n=== 初期化完了 ===\n";
