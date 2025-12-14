<?php
/**
 * BNI Slide System V2 - Speaker Rotation CRUD API
 * スピーカーローテーション管理API（作成・読み取り・更新・削除）
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

// POSTリクエストの場合、JSON入力を解析
$input = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? null;
}

switch ($action) {
    case 'get_six_weeks':
        // 6週分のデータ取得（過去3週 + 今週 + 未来2週）
        $weeks = getSixWeeks($db);
        echo json_encode(['success' => true, 'weeks' => $weeks]);
        break;

    case 'save_six_weeks':
        // 6週分のデータ一括保存
        $weeks = $input['weeks'] ?? null;

        if (!$weeks || !is_array($weeks)) {
            echo json_encode(['success' => false, 'error' => 'データが不正です']);
            exit;
        }

        $result = saveSixWeeks($db, $weeks);
        echo json_encode($result);
        break;

    case 'get_slide_data':
        // スライド表示用データ取得（6週分）
        $weeks = getSixWeeks($db);

        // メンバー情報を含める
        foreach ($weeks as &$week) {
            if ($week['main_presenter_id']) {
                $stmt = $db->prepare('SELECT name, company_name FROM members WHERE id = :id');
                $stmt->bindValue(':id', $week['main_presenter_id'], SQLITE3_INTEGER);
                $result = $stmt->execute();
                $member = $result->fetchArray(SQLITE3_ASSOC);

                if ($member) {
                    $week['member_name'] = $member['name'];
                    $week['company_name'] = $member['company_name'];
                }
            }
        }

        echo json_encode(['success' => true, 'weeks' => $weeks]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}

$db->close();

/**
 * 6週分のデータ取得（過去3週 + 今週 + 未来2週）
 */
function getSixWeeks($db) {
    $weeks = [];

    // 6週分の金曜日を計算
    $fridays = calculateSixFridays();

    foreach ($fridays as $friday) {
        // データベースから該当日のデータを取得
        $stmt = $db->prepare('
            SELECT * FROM speaker_rotation
            WHERE rotation_date = :rotation_date
            ORDER BY id DESC
            LIMIT 1
        ');
        $stmt->bindValue(':rotation_date', $friday, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row) {
            $weeks[] = $row;
        } else {
            // データがない場合は空データ
            $weeks[] = [
                'id' => null,
                'rotation_date' => $friday,
                'main_presenter_id' => null,
                'referral_target' => null
            ];
        }
    }

    return $weeks;
}

/**
 * 6週分のデータ一括保存（トランザクション処理）
 */
function saveSixWeeks($db, $weeks) {
    try {
        $db->exec('BEGIN TRANSACTION');

        foreach ($weeks as $week) {
            $rotationDate = $week['rotation_date'] ?? null;
            $mainPresenterId = $week['main_presenter_id'] ?? null;
            $referralTarget = $week['referral_target'] ?? null;

            if (!$rotationDate) {
                continue;
            }

            // 既存データチェック
            $checkStmt = $db->prepare('SELECT id FROM speaker_rotation WHERE rotation_date = :rotation_date');
            $checkStmt->bindValue(':rotation_date', $rotationDate, SQLITE3_TEXT);
            $checkResult = $checkStmt->execute();
            $existing = $checkResult->fetchArray(SQLITE3_ASSOC);

            if ($existing) {
                // 更新
                $stmt = $db->prepare('
                    UPDATE speaker_rotation
                    SET main_presenter_id = :main_presenter_id,
                        referral_target = :referral_target,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE rotation_date = :rotation_date
                ');
            } else {
                // 新規作成
                $stmt = $db->prepare('
                    INSERT INTO speaker_rotation (rotation_date, main_presenter_id, referral_target)
                    VALUES (:rotation_date, :main_presenter_id, :referral_target)
                ');
            }

            $stmt->bindValue(':rotation_date', $rotationDate, SQLITE3_TEXT);
            $stmt->bindValue(':main_presenter_id', $mainPresenterId, SQLITE3_INTEGER);
            $stmt->bindValue(':referral_target', $referralTarget, SQLITE3_TEXT);
            $stmt->execute();
        }

        $db->exec('COMMIT');
        return ['success' => true];
    } catch (Exception $e) {
        $db->exec('ROLLBACK');
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * 6週分の金曜日を計算（過去3週 + 今週 + 未来2週）
 */
function calculateSixFridays() {
    $fridays = [];

    // 今週の金曜日を基準とする
    $today = new DateTime();
    $dayOfWeek = (int)$today->format('w'); // 0 (日曜) ～ 6 (土曜)

    // 今週の金曜日を計算
    $daysUntilFriday = (5 - $dayOfWeek + 7) % 7;
    if ($daysUntilFriday > 0 || ($daysUntilFriday === 0 && $dayOfWeek !== 5)) {
        // まだ今週の金曜日が来ていない場合
        $thisFriday = clone $today;
        $thisFriday->modify("+{$daysUntilFriday} days");
    } else {
        // 今日が金曜日、または金曜日を過ぎた場合
        $thisFriday = clone $today;
        if ($dayOfWeek !== 5) {
            $daysUntilNextFriday = 5 + (7 - $dayOfWeek);
            $thisFriday->modify("+{$daysUntilNextFriday} days");
        }
    }

    // 過去3週
    for ($i = 3; $i >= 1; $i--) {
        $pastFriday = clone $thisFriday;
        $pastFriday->modify("-{$i} weeks");
        $fridays[] = $pastFriday->format('Y-m-d');
    }

    // 今週
    $fridays[] = $thisFriday->format('Y-m-d');

    // 未来2週
    for ($i = 1; $i <= 2; $i++) {
        $futureFriday = clone $thisFriday;
        $futureFriday->modify("+{$i} weeks");
        $fridays[] = $futureFriday->format('Y-m-d');
    }

    return $fridays;
}
