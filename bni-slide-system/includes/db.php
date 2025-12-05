<?php
/**
 * BNI Slide System - Database Connection Library
 * SQLite3データベース接続とヘルパー関数
 */

// データベースファイルのパス
define('DB_PATH', __DIR__ . '/../data/bni_system.db');

/**
 * データベース接続を取得
 *
 * @return SQLite3 データベース接続オブジェクト
 * @throws Exception データベース接続に失敗した場合
 */
function getDbConnection() {
    try {
        $db = new SQLite3(DB_PATH);

        // 外部キー制約を有効化
        $db->exec('PRAGMA foreign_keys = ON');

        // WALモードを有効化（並行アクセス改善）
        $db->exec('PRAGMA journal_mode = WAL');

        return $db;
    } catch (Exception $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("データベース接続に失敗しました");
    }
}

/**
 * SELECT クエリを実行して結果を配列で取得
 *
 * @param SQLite3 $db データベース接続
 * @param string $query SQLクエリ
 * @param array $params バインドパラメータ（オプション）
 * @return array 結果の配列
 */
function dbQuery($db, $query, $params = []) {
    try {
        $stmt = $db->prepare($query);

        if ($stmt === false) {
            throw new Exception("Query preparation failed: " . $db->lastErrorMsg());
        }

        // パラメータをバインド
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                // 位置パラメータ（1始まり）
                $stmt->bindValue($key + 1, $value);
            } else {
                // 名前付きパラメータ
                $stmt->bindValue($key, $value);
            }
        }

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Query execution failed: " . $db->lastErrorMsg());
        }

        // 結果を配列に変換
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        $stmt->close();

        return $rows;
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage() . " Query: " . $query);
        throw $e;
    }
}

/**
 * SELECT クエリを実行して最初の行を取得
 *
 * @param SQLite3 $db データベース接続
 * @param string $query SQLクエリ
 * @param array $params バインドパラメータ（オプション）
 * @return array|null 結果の行、または null
 */
function dbQueryOne($db, $query, $params = []) {
    $rows = dbQuery($db, $query, $params);
    return count($rows) > 0 ? $rows[0] : null;
}

/**
 * INSERT/UPDATE/DELETE クエリを実行
 *
 * @param SQLite3 $db データベース接続
 * @param string $query SQLクエリ
 * @param array $params バインドパラメータ（オプション）
 * @return int 影響を受けた行数、またはINSERTの場合は挿入されたID
 */
function dbExecute($db, $query, $params = []) {
    try {
        $stmt = $db->prepare($query);

        if ($stmt === false) {
            throw new Exception("Query preparation failed: " . $db->lastErrorMsg());
        }

        // パラメータをバインド
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                // 位置パラメータ（1始まり）
                $stmt->bindValue($key + 1, $value);
            } else {
                // 名前付きパラメータ
                $stmt->bindValue($key, $value);
            }
        }

        $result = $stmt->execute();

        if ($result === false) {
            throw new Exception("Query execution failed: " . $db->lastErrorMsg());
        }

        $stmt->close();

        // INSERTの場合は挿入されたIDを返す
        if (stripos(trim($query), 'INSERT') === 0) {
            return $db->lastInsertRowID();
        }

        // それ以外は影響を受けた行数
        return $db->changes();
    } catch (Exception $e) {
        error_log("Database execution failed: " . $e->getMessage() . " Query: " . $query);
        throw $e;
    }
}

/**
 * トランザクションを開始
 *
 * @param SQLite3 $db データベース接続
 */
function dbBeginTransaction($db) {
    $db->exec('BEGIN TRANSACTION');
}

/**
 * トランザクションをコミット
 *
 * @param SQLite3 $db データベース接続
 */
function dbCommit($db) {
    $db->exec('COMMIT');
}

/**
 * トランザクションをロールバック
 *
 * @param SQLite3 $db データベース接続
 */
function dbRollback($db) {
    $db->exec('ROLLBACK');
}

/**
 * データベース接続を閉じる
 *
 * @param SQLite3 $db データベース接続
 */
function dbClose($db) {
    if ($db) {
        $db->close();
    }
}

/**
 * 値をSQLiteのBOOLEAN（0/1）に変換
 *
 * @param mixed $value 変換する値
 * @return int 0 または 1
 */
function dbBool($value) {
    return $value ? 1 : 0;
}

/**
 * SQLiteのBOOLEAN（0/1）をPHPのboolに変換
 *
 * @param int $value SQLiteの値
 * @return bool
 */
function dbToBool($value) {
    return (bool)$value;
}

/**
 * 配列をJSON文字列に変換してデータベースに保存
 *
 * @param array $data 配列データ
 * @return string JSON文字列
 */
function dbJsonEncode($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

/**
 * データベースのJSON文字列を配列に変換
 *
 * @param string $json JSON文字列
 * @return array|null 配列、またはnull
 */
function dbJsonDecode($json) {
    if (empty($json)) {
        return null;
    }
    return json_decode($json, true);
}

/**
 * エスケープ処理（Like検索用）
 *
 * @param string $value エスケープする値
 * @return string エスケープされた値
 */
function dbEscapeLike($value) {
    return str_replace(['%', '_'], ['\%', '\_'], $value);
}
