<?php
/**
 * データベース接続設定
 */

// 環境変数読み込み
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$dbType = getenv('DB_TYPE') ?: 'sqlite';

if ($dbType === 'sqlite') {
    $dbPath = getenv('DB_PATH') ?: __DIR__ . '/../database/protea.db';
    // 相対パスを絶対パスに変換
    if (!preg_match('/^\//', $dbPath)) {
        $dbPath = __DIR__ . '/../' . $dbPath;
    }

    return [
        'type' => 'sqlite',
        'dsn' => 'sqlite:' . $dbPath,
        'path' => $dbPath,
    ];
} else {
    return [
        'type' => 'mysql',
        'dsn' => sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_NAME') ?: 'protea_db',
            getenv('DB_CHARSET') ?: 'utf8mb4'
        ),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ];
}
