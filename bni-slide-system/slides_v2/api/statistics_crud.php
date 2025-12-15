<?php
/**
 * BNI Slide System V2 - Statistics CRUD API
 * 統計情報管理API
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) {
    $action = $postData['action'] ?? $action;
}

switch ($action) {
    case 'get':
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が必要です']);
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM statistics WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $statistics = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $statistics[] = $row;
        }

        echo json_encode(['success' => true, 'statistics' => $statistics]);
        break;

    case 'get_by_type':
        $weekDate = $_GET['week_date'] ?? null;
        $statType = $_GET['stat_type'] ?? null;

        if (!$weekDate || !$statType) {
            echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']);
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM statistics WHERE week_date = :week_date AND type = :type LIMIT 1");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $statType, PDO::PARAM_STR);
        $stmt->execute();

        $stat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stat) {
            echo json_encode(['success' => true, 'statistic' => $stat]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
        }
        break;

    case 'get_latest':
        // 最新のweek_dateの統計データ取得
        $stmt = $db->query("
            SELECT * FROM statistics
            WHERE week_date = (
                SELECT MAX(week_date) FROM statistics
            )
            ORDER BY id
        ");

        $statistics = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $statistics[] = $row;
        }

        echo json_encode(['success' => true, 'statistics' => $statistics]);
        break;

    case 'save':
        $weekDate = $postData['week_date'] ?? null;
        $statType = $postData['stat_type'] ?? null;
        $value = $postData['value'] ?? null;

        if (!$weekDate || !$statType || !$value) {
            echo json_encode(['success' => false, 'error' => '必要なデータが不足しています']);
            exit;
        }

        // 既存データをチェック
        $stmt = $db->prepare("SELECT id FROM statistics WHERE week_date = :week_date AND type = :type");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $statType, PDO::PARAM_STR);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare("
                UPDATE statistics
                SET data_json = :data_json, updated_at = CURRENT_TIMESTAMP
                WHERE week_date = :week_date AND type = :type
            ");
        } else {
            // 新規作成
            $stmt = $db->prepare("
                INSERT INTO statistics (week_date, type, data_json)
                VALUES (:week_date, :type, :data_json)
            ");
        }

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $statType, PDO::PARAM_STR);
        $stmt->bindValue(':data_json', $value, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // 保存成功後、スライド画像を生成
            switch ($statType) {
                case 'visitor':
                    generateSlideImage('visitor_stats.php', 188, $weekDate);
                    break;
                case 'referral':
                    generateSlideImage('referral_stats.php', 189, $weekDate);
                    break;
                case 'sales':
                    generateSlideImage('sales_stats.php', 190, $weekDate);
                    break;
                case 'weekly':
                    generateSlideImage('weekly_stats.php', 302, $weekDate);
                    break;
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベースエラー']);
        }
        break;

    case 'delete':
        $weekDate = $postData['week_date'] ?? $_POST['week_date'] ?? null;
        $statType = $postData['stat_type'] ?? $_POST['stat_type'] ?? null;

        if (!$weekDate || !$statType) {
            echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']);
            exit;
        }

        $stmt = $db->prepare("DELETE FROM statistics WHERE week_date = :week_date AND type = :type");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $statType, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベース削除エラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}
