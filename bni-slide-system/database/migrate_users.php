<?php
/**
 * BNI Slide System - User Data Migration Script
 * members.jsonからusersテーブルへデータを移行する
 *
 * 使い方:
 * php database/migrate_users.php
 */

require_once __DIR__ . '/../includes/db.php';

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

echo "==============================================\n";
echo "BNI Slide System - ユーザーデータ移行\n";
echo "==============================================\n\n";

// members.jsonを読み込み
$membersFile = __DIR__ . '/../data/members.json';

if (!file_exists($membersFile)) {
    echo "エラー: members.jsonが見つかりません: {$membersFile}\n";
    exit(1);
}

echo "members.jsonを読み込み中...\n";
$content = file_get_contents($membersFile);
$data = json_decode($content, true);

if (!$data || !isset($data['users'])) {
    echo "エラー: members.jsonの読み込みに失敗しました\n";
    exit(1);
}

$users = $data['users'];
$userCount = count($users);

echo "✓ {$userCount}人のユーザーを検出\n\n";

try {
    // データベース接続
    echo "データベースに接続中...\n";
    $db = getDbConnection();
    echo "✓ データベース接続成功\n\n";

    // トランザクション開始
    dbBeginTransaction($db);

    echo "ユーザーデータを移行中...\n";

    $insertedCount = 0;
    $skippedCount = 0;
    $errorCount = 0;

    foreach ($users as $email => $user) {
        try {
            // 既に存在するかチェック
            $existing = dbQueryOne($db,
                "SELECT id FROM users WHERE email = :email",
                [':email' => $email]
            );

            if ($existing) {
                echo "  - スキップ: {$email} (既に存在)\n";
                $skippedCount++;
                continue;
            }

            // ユーザーを挿入
            $query = "INSERT INTO users (
                email,
                name,
                password_hash,
                phone,
                company,
                category,
                role,
                htpasswd_user,
                created_at,
                updated_at
            ) VALUES (
                :email,
                :name,
                :password_hash,
                :phone,
                :company,
                :category,
                :role,
                :htpasswd_user,
                :created_at,
                :updated_at
            )";

            $params = [
                ':email' => $email,
                ':name' => $user['name'] ?? '',
                ':password_hash' => $user['password_hash'] ?? $user['password'] ?? null,
                ':phone' => $user['phone'] ?? null,
                ':company' => $user['company'] ?? null,
                ':category' => $user['category'] ?? null,
                ':role' => $user['role'] ?? 'member',
                ':htpasswd_user' => $user['htpasswd_user'] ?? null,
                ':created_at' => $user['created_at'] ?? date('Y-m-d H:i:s'),
                ':updated_at' => $user['updated_at'] ?? date('Y-m-d H:i:s')
            ];

            $userId = dbExecute($db, $query, $params);

            echo "  ✓ 挿入: {$email} (ID: {$userId})\n";
            $insertedCount++;

        } catch (Exception $e) {
            echo "  ✗ エラー: {$email} - " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }

    // トランザクションをコミット
    dbCommit($db);

    echo "\n";
    echo "==============================================\n";
    echo "ユーザーデータ移行が完了しました\n";
    echo "==============================================\n";
    echo "挿入: {$insertedCount}人\n";
    echo "スキップ: {$skippedCount}人\n";
    echo "エラー: {$errorCount}人\n";
    echo "==============================================\n";

    // データベース接続を閉じる
    dbClose($db);

} catch (Exception $e) {
    // エラーが発生した場合はロールバック
    if (isset($db)) {
        dbRollback($db);
        dbClose($db);
    }

    echo "\n";
    echo "エラー: " . $e->getMessage() . "\n";
    echo "トランザクションをロールバックしました\n";
    echo "\n";
    exit(1);
}
