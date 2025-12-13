<?php
/**
 * BNI Slide System - Load My Data API (SQLite Version)
 * ログインユーザー自身のアンケートデータを読み込む
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/db.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

$userName = $currentUser['name'];
$userEmail = $currentUser['email'];

try {
    $db = getDbConnection();

    // Load user's data from all weeks (only user-editable fields)
    $query = "
        SELECT
            s.week_date,
            s.timestamp,
            s.input_date,
            s.user_name,
            s.user_email,
            s.is_pitch_presenter,
            s.pitch_file_path,
            s.pitch_file_original_name,
            s.pitch_file_type,
            s.youtube_url,
            v.visitor_name,
            v.visitor_company,
            v.visitor_industry
        FROM survey_data s
        LEFT JOIN visitors v ON s.id = v.survey_data_id
        WHERE s.user_email = :email
        ORDER BY s.week_date DESC, s.timestamp DESC
    ";

    $rows = dbQuery($db, $query, [':email' => $userEmail]);

    $myData = [];

    // Convert to structured format (only user-editable fields)
    foreach ($rows as $row) {
        $weekDate = $row['week_date'];
        $weekLabel = getWeekLabel($weekDate);

        $myData[] = [
            'タイムスタンプ' => $row['timestamp'],
            '入力日' => $row['input_date'],
            '紹介者名' => $row['user_name'],
            'メールアドレス' => $row['user_email'],
            'ビジター名' => $row['visitor_name'] ?: '',
            'ビジター会社名' => $row['visitor_company'] ?: '',
            'ビジター業種' => $row['visitor_industry'] ?: '',
            'ピッチ担当' => $row['is_pitch_presenter'] ? 'はい' : 'いいえ',
            'ピッチファイル' => $row['pitch_file_original_name'] ?: '',
            'ピッチファイルパス' => $row['pitch_file_path'] ?: '',
            'ピッチファイル種類' => $row['pitch_file_type'] ?: '',
            'YouTube URL' => $row['youtube_url'] ?: '',
            '週' => $weekLabel,
            'CSVファイル' => $weekDate
        ];
    }

    dbClose($db);

    echo json_encode([
        'success' => true,
        'data' => $myData,
        'user' => [
            'name' => $userName,
            'email' => $userEmail
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('[API LOAD MY DATA] Error: ' . $e->getMessage());
    if (isset($db)) {
        dbClose($db);
    }
    echo json_encode([
        'success' => false,
        'message' => 'データの読み込み中にエラーが発生しました'
    ]);
}

/**
 * Format week_date to Japanese label
 */
function getWeekLabel($weekDate) {
    try {
        $dt = new DateTime($weekDate);
        $dayOfWeek = $dt->format('w');
        $dayLabels = ['日', '月', '火', '水', '木', '金', '土'];
        $dayLabel = $dayLabels[$dayOfWeek];
        return $dt->format('Y年n月j日') . '（' . $dayLabel . '）';
    } catch (Exception $e) {
        return $weekDate;
    }
}
