<?php
/**
 * BNI Slide System V2 - Start Dash Presenter CRUD API
 * スタートダッシュプレゼン管理API（作成・読み取り・更新・削除）
 */

header('Content-Type: application/json');

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';

try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'list':
        // スタートダッシュプレゼン一覧取得
        $query = "
            SELECT
                sd.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM start_dash_presenter sd
            LEFT JOIN members m ON sd.member_id = m.id
            ORDER BY sd.week_date DESC, sd.page_number ASC
        ";
        $result = $db->query($query);

        $presentations = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $presentations[] = $row;
        }

        echo json_encode(['success' => true, 'presentations' => $presentations]);
        break;

    case 'get':
        // 特定日付のスタートダッシュプレゼン取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                sd.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM start_dash_presenter sd
            LEFT JOIN members m ON sd.member_id = m.id
            WHERE sd.week_date = :week_date
            ORDER BY sd.page_number ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $presenters = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $presenters[] = $row;
        }

        if (count($presenters) > 0) {
            echo json_encode(['success' => true, 'presenters' => $presenters]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    case 'get_by_page':
        // 特定日付・特定ページのスタートダッシュプレゼン取得
        $weekDate = $_GET['week_date'] ?? null;
        $pageNumber = $_GET['page_number'] ?? null;

        if (!$weekDate || !$pageNumber) {
            echo json_encode(['success' => false, 'error' => '日付とページ番号が必要です']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                sd.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM start_dash_presenter sd
            LEFT JOIN members m ON sd.member_id = m.id
            WHERE sd.week_date = :week_date AND sd.page_number = :page_number
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':page_number', $pageNumber, SQLITE3_INTEGER);
        $result = $stmt->execute();

        $presenter = $result->fetchArray(SQLITE3_ASSOC);

        if ($presenter) {
            echo json_encode(['success' => true, 'presenter' => $presenter]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    case 'create':
        // 新規スタートダッシュプレゼン追加（p.15とp.107を同時登録）
        $weekDate = $_POST['week_date'] ?? null;
        $memberId15 = $_POST['member_id_15'] ?? null;
        $memberId107 = $_POST['member_id_107'] ?? null;

        if (!$weekDate || !$memberId15 || !$memberId107) {
            echo json_encode(['success' => false, 'error' => '開催日と両方のメンバーIDは必須です']);
            exit;
        }

        // トランザクション開始
        $db->exec('BEGIN TRANSACTION');

        try {
            // 既存データ削除（同じ日付のデータがあれば）
            $deleteStmt = $db->prepare('DELETE FROM start_dash_presenter WHERE week_date = :week_date');
            $deleteStmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
            $deleteStmt->execute();

            // p.15のデータを登録
            $stmt15 = $db->prepare('
                INSERT INTO start_dash_presenter (week_date, member_id, page_number)
                VALUES (:week_date, :member_id, :page_number)
            ');
            $stmt15->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
            $stmt15->bindValue(':member_id', $memberId15, SQLITE3_INTEGER);
            $stmt15->bindValue(':page_number', 15, SQLITE3_INTEGER);
            $result15 = $stmt15->execute();

            // p.107のデータを登録
            $stmt107 = $db->prepare('
                INSERT INTO start_dash_presenter (week_date, member_id, page_number)
                VALUES (:week_date, :member_id, :page_number)
            ');
            $stmt107->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
            $stmt107->bindValue(':member_id', $memberId107, SQLITE3_INTEGER);
            $stmt107->bindValue(':page_number', 107, SQLITE3_INTEGER);
            $result107 = $stmt107->execute();

            if ($result15 && $result107) {
                $db->exec('COMMIT');
                echo json_encode([
                    'success' => true,
                    'id_15' => $db->lastInsertRowID() - 1,
                    'id_107' => $db->lastInsertRowID()
                ]);
            } else {
                $db->exec('ROLLBACK');
                echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
            }
        } catch (Exception $e) {
            $db->exec('ROLLBACK');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'update':
        // スタートダッシュプレゼン更新
        $id = $_POST['id'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        $weekDate = $_POST['week_date'] ?? null;
        $pageNumber = $_POST['page_number'] ?? null;

        if (!$id || !$memberId || !$weekDate || !$pageNumber) {
            echo json_encode(['success' => false, 'error' => 'すべての項目は必須です']);
            exit;
        }

        $stmt = $db->prepare('
            UPDATE start_dash_presenter
            SET member_id = :member_id,
                week_date = :week_date,
                page_number = :page_number,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':page_number', $pageNumber, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'delete':
        // スタートダッシュプレゼン削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM start_dash_presenter WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'delete_by_date':
        // 特定日付のスタートダッシュプレゼン全削除
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM start_dash_presenter WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'get_slide_data':
        // スライド表示用データ取得
        $weekDate = $_GET['week_date'] ?? null;
        $pageNumber = $_GET['page_number'] ?? null;

        if (!$weekDate || !$pageNumber) {
            echo json_encode(['success' => false, 'error' => '日付とページ番号が必要です']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                sd.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM start_dash_presenter sd
            LEFT JOIN members m ON sd.member_id = m.id
            WHERE sd.week_date = :week_date AND sd.page_number = :page_number
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':page_number', $pageNumber, SQLITE3_INTEGER);
        $result = $stmt->execute();

        $presenter = $result->fetchArray(SQLITE3_ASSOC);

        if ($presenter) {
            echo json_encode(['success' => true, 'presenter' => $presenter]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}

$db->close();
