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

    // Load user's data from all weeks
    $query = "
        SELECT
            s.week_date,
            s.timestamp,
            s.input_date,
            s.user_name,
            s.user_email,
            s.attendance,
            s.thanks_slips,
            s.one_to_one,
            s.activities,
            s.comments,
            v.visitor_name,
            v.visitor_company,
            v.visitor_industry,
            r.referral_name,
            r.referral_amount,
            r.referral_category,
            r.referral_provider
        FROM survey_data s
        LEFT JOIN visitors v ON s.id = v.survey_data_id
        LEFT JOIN referrals r ON s.id = r.survey_data_id
        WHERE s.user_email = :email
        ORDER BY s.week_date DESC, s.timestamp DESC
    ";

    $rows = dbQuery($db, $query, [':email' => $userEmail]);

    $myData = [];

    // Convert to CSV-like format
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
            '案件名' => $row['referral_name'] ?: '',
            'リファーラル金額' => $row['referral_amount'] ?: 0,
            'カテゴリ' => $row['referral_category'] ?: '',
            'リファーラル提供者' => $row['referral_provider'] ?: '',
            '出席状況' => $row['attendance'],
            'サンクスリップ数' => $row['thanks_slips'],
            'ワンツーワン数' => $row['one_to_one'],
            'アクティビティ' => $row['activities'] ?: '',
            'コメント' => $row['comments'] ?: '',
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
