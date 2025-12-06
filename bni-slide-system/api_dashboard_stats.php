<?php
/**
 * BNI Slide System - Dashboard Statistics API (SQLite Version)
 * ログインユーザーのダッシュボード統計データを返す
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/date_helper.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    echo json_encode(['success' => false, 'message' => 'ユーザー情報が見つかりません']);
    exit;
}

$userEmail = $currentUser['email'];
$userName = $currentUser['name'];

try {
    $db = getDbConnection();

    // 今週の金曜日を取得
    $thisFriday = getTargetFriday(time());
    $thisFridayStr = $thisFriday->format('Y-m-d');

    // 先週の金曜日を取得
    $lastFriday = clone $thisFriday;
    $lastFriday->modify('-7 days');
    $lastFridayStr = $lastFriday->format('Y-m-d');

    // 今月の範囲を取得
    $now = new DateTime();
    $firstDayOfMonth = $now->format('Y-m-01');
    $lastDayOfMonth = $now->format('Y-m-t');

    // 今週のユーザーデータ
    $userThisWeek = getUserStats($db, $thisFridayStr, $userEmail);

    // 先週のユーザーデータ
    $userLastWeek = getUserStats($db, $lastFridayStr, $userEmail);

    // 今週のチーム全体データ
    $teamThisWeek = getTeamStats($db, $thisFridayStr);

    // 今月のユーザーデータ
    $monthlyUserStats = getMonthlyUserStats($db, $userEmail, $firstDayOfMonth, $lastDayOfMonth);

    dbClose($db);

    // レスポンスデータ
    $response = [
        'success' => true,
        'this_week' => [
            'user' => $userThisWeek,
            'team' => $teamThisWeek
        ],
        'last_week' => [
            'user' => [
                'submitted' => $userLastWeek['submitted'],
                'visitor_count' => $userLastWeek['visitor_count'],
                'referral_amount' => $userLastWeek['referral_amount'],
                'referral_count' => $userLastWeek['referral_count']
            ]
        ],
        'this_month' => [
            'user' => $monthlyUserStats
        ],
        'week_dates' => [
            'this_friday' => $thisFridayStr,
            'last_friday' => $lastFridayStr,
            'month' => $now->format('Y年n月')
        ]
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log('[API DASHBOARD STATS] Error: ' . $e->getMessage());
    if (isset($db)) {
        dbClose($db);
    }
    echo json_encode([
        'success' => false,
        'message' => 'ダッシュボード統計の取得中にエラーが発生しました'
    ]);
}

/**
 * 指定週のユーザー統計を取得
 */
function getUserStats($db, $weekDate, $userEmail) {
    // Check if user submitted
    $submittedQuery = "SELECT COUNT(*) as count FROM survey_data
                       WHERE week_date = :week_date AND user_email = :email";
    $result = dbQueryOne($db, $submittedQuery, [
        ':week_date' => $weekDate,
        ':email' => $userEmail
    ]);
    $submitted = $result && intval($result['count']) > 0;

    if (!$submitted) {
        return [
            'submitted' => false,
            'attendance' => '',
            'visitor_count' => 0,
            'referral_amount' => 0,
            'referral_count' => 0,
            'thanks_slips' => 0,
            'one_to_one' => 0
        ];
    }

    // Get attendance and counts
    $query = "SELECT attendance, thanks_slips, one_to_one
              FROM survey_data
              WHERE week_date = :week_date AND user_email = :email
              LIMIT 1";
    $data = dbQueryOne($db, $query, [
        ':week_date' => $weekDate,
        ':email' => $userEmail
    ]);

    // Get visitor count
    $visitorQuery = "SELECT COUNT(*) as count
                     FROM visitors v
                     JOIN survey_data s ON v.survey_data_id = s.id
                     WHERE s.week_date = :week_date
                     AND s.user_email = :email
                     AND v.visitor_name IS NOT NULL
                     AND v.visitor_name != ''";
    $visitorResult = dbQueryOne($db, $visitorQuery, [
        ':week_date' => $weekDate,
        ':email' => $userEmail
    ]);
    $visitorCount = $visitorResult ? intval($visitorResult['count']) : 0;

    // Get referral stats
    $referralQuery = "SELECT
                        COUNT(CASE WHEN referral_name IS NOT NULL AND referral_name != '-' AND referral_amount > 0 THEN 1 END) as count,
                        COALESCE(SUM(referral_amount), 0) as amount
                      FROM referrals r
                      JOIN survey_data s ON r.survey_data_id = s.id
                      WHERE s.week_date = :week_date AND s.user_email = :email";
    $referralResult = dbQueryOne($db, $referralQuery, [
        ':week_date' => $weekDate,
        ':email' => $userEmail
    ]);

    return [
        'submitted' => true,
        'attendance' => $data['attendance'] ?? '',
        'visitor_count' => $visitorCount,
        'referral_amount' => $referralResult ? intval($referralResult['amount']) : 0,
        'referral_count' => $referralResult ? intval($referralResult['count']) : 0,
        'thanks_slips' => $data['thanks_slips'] ?? 0,
        'one_to_one' => $data['one_to_one'] ?? 0
    ];
}

/**
 * 指定週のチーム統計を取得
 */
function getTeamStats($db, $weekDate) {
    // Total unique members
    $membersQuery = "SELECT COUNT(DISTINCT user_email) as count
                     FROM survey_data
                     WHERE week_date = :week_date";
    $membersResult = dbQueryOne($db, $membersQuery, [':week_date' => $weekDate]);
    $totalMembers = $membersResult ? intval($membersResult['count']) : 0;

    // Total visitors
    $visitorsQuery = "SELECT COUNT(*) as count
                      FROM visitors v
                      JOIN survey_data s ON v.survey_data_id = s.id
                      WHERE s.week_date = :week_date
                      AND v.visitor_name IS NOT NULL
                      AND v.visitor_name != ''";
    $visitorsResult = dbQueryOne($db, $visitorsQuery, [':week_date' => $weekDate]);
    $visitorCount = $visitorsResult ? intval($visitorsResult['count']) : 0;

    // Total referral stats
    $referralQuery = "SELECT
                        COUNT(CASE WHEN referral_name IS NOT NULL AND referral_name != '-' AND referral_amount > 0 THEN 1 END) as count,
                        COALESCE(SUM(referral_amount), 0) as amount
                      FROM referrals r
                      JOIN survey_data s ON r.survey_data_id = s.id
                      WHERE s.week_date = :week_date";
    $referralResult = dbQueryOne($db, $referralQuery, [':week_date' => $weekDate]);

    return [
        'total_members' => $totalMembers,
        'visitor_count' => $visitorCount,
        'referral_amount' => $referralResult ? intval($referralResult['amount']) : 0,
        'referral_count' => $referralResult ? intval($referralResult['count']) : 0
    ];
}

/**
 * 今月のユーザー統計を取得
 */
function getMonthlyUserStats($db, $userEmail, $firstDay, $lastDay) {
    // Visitor count
    $visitorQuery = "SELECT COUNT(*) as count
                     FROM visitors v
                     JOIN survey_data s ON v.survey_data_id = s.id
                     WHERE s.user_email = :email
                     AND s.week_date BETWEEN :first_day AND :last_day
                     AND v.visitor_name IS NOT NULL
                     AND v.visitor_name != ''";
    $visitorResult = dbQueryOne($db, $visitorQuery, [
        ':email' => $userEmail,
        ':first_day' => $firstDay,
        ':last_day' => $lastDay
    ]);
    $visitorCount = $visitorResult ? intval($visitorResult['count']) : 0;

    // Referral stats
    $referralQuery = "SELECT
                        COUNT(CASE WHEN referral_name IS NOT NULL AND referral_name != '-' AND referral_amount > 0 THEN 1 END) as count,
                        COALESCE(SUM(referral_amount), 0) as amount
                      FROM referrals r
                      JOIN survey_data s ON r.survey_data_id = s.id
                      WHERE s.user_email = :email
                      AND s.week_date BETWEEN :first_day AND :last_day";
    $referralResult = dbQueryOne($db, $referralQuery, [
        ':email' => $userEmail,
        ':first_day' => $firstDay,
        ':last_day' => $lastDay
    ]);

    // Thanks slips and one-to-one (sum of distinct records)
    $countsQuery = "SELECT
                      COALESCE(SUM(thanks_slips), 0) as thanks_slips,
                      COALESCE(SUM(one_to_one), 0) as one_to_one,
                      COUNT(DISTINCT week_date) as attendance_count
                    FROM (
                      SELECT DISTINCT week_date, thanks_slips, one_to_one
                      FROM survey_data
                      WHERE user_email = :email
                      AND week_date BETWEEN :first_day AND :last_day
                    )";
    $countsResult = dbQueryOne($db, $countsQuery, [
        ':email' => $userEmail,
        ':first_day' => $firstDay,
        ':last_day' => $lastDay
    ]);

    return [
        'visitor_count' => $visitorCount,
        'referral_amount' => $referralResult ? intval($referralResult['amount']) : 0,
        'referral_count' => $referralResult ? intval($referralResult['count']) : 0,
        'thanks_slips' => $countsResult ? intval($countsResult['thanks_slips']) : 0,
        'one_to_one' => $countsResult ? intval($countsResult['one_to_one']) : 0,
        'attendance_count' => $countsResult ? intval($countsResult['attendance_count']) : 0
    ];
}

// getTargetFriday() はincludes/date_helper.phpで定義されています
