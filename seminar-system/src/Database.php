<?php
namespace Seminar;

use PDO;
use PDOException;

/**
 * データベース接続クラス（Singleton）
 */
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    /**
     * コンストラクタ（private）
     */
    private function __construct() {
        $host = env('DB_HOST', 'localhost');
        $dbname = env('DB_NAME');
        $user = env('DB_USER');
        $password = env('DB_PASSWORD');
        $charset = env('DB_CHARSET', 'utf8mb4');

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('データベース接続に失敗しました');
        }
    }

    /**
     * インスタンス取得
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDOインスタンス取得
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /**
     * クエリ実行（SELECT）
     */
    public function query(string $sql, array $params = []): array {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error('Query execution failed', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * クエリ実行（INSERT/UPDATE/DELETE）
     */
    public function execute(string $sql, array $params = []): int {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Logger::error('Execute failed', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * 単一行取得
     */
    public function fetch(string $sql, array $params = []): ?array {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            Logger::error('Fetch failed', [
                'sql' => $sql,
                'params' => $params,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * 最後に挿入されたID取得
     */
    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    /**
     * トランザクション開始
     */
    public function beginTransaction(): bool {
        return $this->pdo->beginTransaction();
    }

    /**
     * コミット
     */
    public function commit(): bool {
        return $this->pdo->commit();
    }

    /**
     * ロールバック
     */
    public function rollBack(): bool {
        return $this->pdo->rollBack();
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
