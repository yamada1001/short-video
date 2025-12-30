<?php
namespace Protea;

use PDO;
use PDOException;

/**
 * データベース接続クラス
 */
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        try {
            if ($config['type'] === 'sqlite') {
                // SQLiteの場合
                $dbPath = $config['path'];
                $dbDir = dirname($dbPath);

                // ディレクトリが存在しない場合は作成
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0755, true);
                }

                $this->pdo = new PDO($config['dsn']);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                // 外部キー制約を有効化
                $this->pdo->exec('PRAGMA foreign_keys = ON');

                // データベースが空の場合、スキーマを作成
                $this->initializeSchema($dbPath);

            } else {
                // MySQLの場合
                $this->pdo = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            }
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \Exception("データベース接続に失敗しました: " . $e->getMessage());
        }
    }

    /**
     * スキーマ初期化（SQLiteが空の場合のみ）
     */
    private function initializeSchema($dbPath)
    {
        // データベースファイルが新規作成された場合のみスキーマを実行
        $schemaFile = __DIR__ . '/../database/schema.sql';

        if (!file_exists($schemaFile)) {
            return;
        }

        // テーブルが存在するか確認
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='keywords'");
        $exists = $stmt->fetch();

        if (!$exists) {
            // スキーマを実行
            $schema = file_get_contents($schemaFile);
            $this->pdo->exec($schema);
        }
    }

    /**
     * シングルトンインスタンス取得
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDOインスタンス取得
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * クローン防止
     */
    private function __clone()
    {
    }

    /**
     * アンシリアライズ防止
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
