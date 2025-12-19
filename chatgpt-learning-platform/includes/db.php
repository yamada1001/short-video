<?php
/**
 * ChatGPT学習プラットフォーム - データベース接続
 *
 * PDOを使用したデータベース接続とクエリ実行関数
 */

// 設定ファイルを読み込み
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $pdo;

    /**
     * コンストラクタ（シングルトンパターン）
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            die('データベース接続に失敗しました。');
        }
    }

    /**
     * インスタンス取得
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * PDOオブジェクト取得
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * SELECT クエリ実行（単一行）
     */
    public function fetchOne($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * SELECT クエリ実行（複数行）
     */
    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * INSERT/UPDATE/DELETE クエリ実行
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 最後に挿入されたIDを取得
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * トランザクション開始
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     * コミット
     */
    public function commit() {
        return $this->pdo->commit();
    }

    /**
     * ロールバック
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }
}

// グローバル関数としてDB接続を提供
function db() {
    return Database::getInstance();
}
