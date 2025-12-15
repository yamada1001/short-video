<?php
namespace BNI;

use PDO;
use PDOException;

/**
 * データベース接続クラス
 * Singletonパターンで実装
 */
class Database {
    private static $instance = null;
    private $pdo;

    /**
     * コンストラクタ（private）
     */
    private function __construct() {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            env('DB_HOST'),
            env('DB_NAME'),
            env('DB_CHARSET', 'utf8mb4')
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, env('DB_USER'), env('DB_PASSWORD'), $options);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * インスタンス取得
     *
     * @return self
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDO接続取得
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /**
     * クローン禁止
     */
    private function __clone() {}

    /**
     * アンシリアライズ禁止
     */
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
