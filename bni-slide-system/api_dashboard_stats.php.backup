<?php
/**
 * BNI Slide System - Dashboard Statistics API
 * ログインユーザーのダッシュボード統計データを返す
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/date_helper.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    echo json_encode(['success' => false, 'message' => 'ユーザー情報が見つかりません']);
    exit;
}

$userEmail = $currentUser['email'];
$userName = $currentUser['name'];

// データディレクトリ
$dataDir = __DIR__ . '/data';

// 今週の金曜日を取得
$thisFriday = getTargetFriday(time());
$thisFridayStr = $thisFriday->format('Y-m-d');

// 先週の金曜日を取得
$lastFriday = clone $thisFriday;
$lastFriday->modify('-7 days');
$lastFridayStr = $lastFriday->format('Y-m-d');

// 今月の範囲を取得
$now = new DateTime();
$firstDayOfMonth = new DateTime($now->format('Y-m-01'));
$lastDayOfMonth = new DateTime($now->format('Y-m-t'));

/**
 * CSVファイルからユーザーのデータを読み込む
 */
function loadUserDataFromCSV($csvFile, $userEmail, $userName) {
    if (!file_exists($csvFile)) {
        return [];
    }

    $records = [];
    $handle = fopen($csvFile, 'r');

    if ($handle === false) {
        return [];
    }

    // ヘッダー行を読み込み
    $headers = fgetcsv($handle);
    if (!$headers || count($headers) < 10) {
        fclose($handle);
        return [];
    }

    // データ行を読み込み
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) !== count($headers)) {
            continue;
        }

        $record = array_combine($headers, $row);
        if ($record === false) {
            continue;
        }

        // ユーザーのデータのみ抽出（メールアドレスまたは名前で照合）
        if ($record['メールアドレス'] === $userEmail || $record['紹介者名'] === $userName) {
            $records[] = $record;
        }
    }

    fclose($handle);
    return $records;
}

/**
 * 全体の統計データを読み込む
 */
function loadTeamDataFromCSV($csvFile) {
    if (!file_exists($csvFile)) {
        return [];
    }

    $records = [];
    $handle = fopen($csvFile, 'r');

    if ($handle === false) {
        return [];
    }

    // ヘッダー行を読み込み
    $headers = fgetcsv($handle);
    if (!$headers || count($headers) < 10) {
        fclose($handle);
        return [];
    }

    // データ行を読み込み
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) !== count($headers)) {
            continue;
        }

        $record = array_combine($headers, $row);
        if ($record === false) {
            continue;
        }

        $records[] = $record;
    }

    fclose($handle);
    return $records;
}

/**
 * 統計を計算
 */
function calculateStats($records) {
    $stats = [
        'visitor_count' => 0,
        'referral_amount' => 0,
        'referral_count' => 0,
        'thanks_slips' => 0,
        'one_to_one' => 0,
        'submitted' => false,
        'attendance' => '',
        'unique_members' => []
    ];

    foreach ($records as $record) {
        // ビジターカウント（名前が空でない場合のみ）
        if (!empty($record['ビジター名']) && trim($record['ビジター名']) !== '-') {
            $stats['visitor_count']++;
        }

        // リファーラル金額とカウント
        if (!empty($record['案件名']) && trim($record['案件名']) !== '-') {
            $amount = intval($record['リファーラル金額'] ?? 0);
            if ($amount > 0) {
                $stats['referral_amount'] += $amount;
                $stats['referral_count']++;
            }
        }

        // サンクスリップとワンツーワン
        $stats['thanks_slips'] += intval($record['サンクスリップ数'] ?? 0);
        $stats['one_to_one'] += intval($record['ワンツーワン数'] ?? 0);

        // 出席状況
        if (!empty($record['出席状況'])) {
            $stats['attendance'] = $record['出席状況'];
        }

        // ユニークメンバー数
        $email = $record['メールアドレス'] ?? '';
        if ($email && !in_array($email, $stats['unique_members'])) {
            $stats['unique_members'][] = $email;
        }

        $stats['submitted'] = true;
    }

    return $stats;
}

// 今週のユーザーデータ
$thisWeekCSV = $dataDir . '/' . $thisFridayStr . '.csv';
$userThisWeek = loadUserDataFromCSV($thisWeekCSV, $userEmail, $userName);
$userStatsThisWeek = calculateStats($userThisWeek);

// 先週のユーザーデータ
$lastWeekCSV = $dataDir . '/' . $lastFridayStr . '.csv';
$userLastWeek = loadUserDataFromCSV($lastWeekCSV, $userEmail, $userName);
$userStatsLastWeek = calculateStats($userLastWeek);

// 今週のチーム全体データ
$teamThisWeek = loadTeamDataFromCSV($thisWeekCSV);
$teamStatsThisWeek = calculateStats($teamThisWeek);

// 今月のユーザーデータ（全CSVファイルから読み込み）
$monthlyUserStats = [
    'visitor_count' => 0,
    'referral_amount' => 0,
    'referral_count' => 0,
    'thanks_slips' => 0,
    'one_to_one' => 0,
    'attendance_count' => 0
];

$csvFiles = glob($dataDir . '/*.csv');
foreach ($csvFiles as $csvFile) {
    $basename = basename($csvFile, '.csv');

    // 日付形式のCSVファイルのみ処理
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $basename)) {
        try {
            $fileDate = new DateTime($basename);

            // 今月の範囲内かチェック
            if ($fileDate >= $firstDayOfMonth && $fileDate <= $lastDayOfMonth) {
                $userData = loadUserDataFromCSV($csvFile, $userEmail, $userName);
                $stats = calculateStats($userData);

                $monthlyUserStats['visitor_count'] += $stats['visitor_count'];
                $monthlyUserStats['referral_amount'] += $stats['referral_amount'];
                $monthlyUserStats['referral_count'] += $stats['referral_count'];
                $monthlyUserStats['thanks_slips'] += $stats['thanks_slips'];
                $monthlyUserStats['one_to_one'] += $stats['one_to_one'];

                if ($stats['submitted']) {
                    $monthlyUserStats['attendance_count']++;
                }
            }
        } catch (Exception $e) {
            // 日付パースエラーはスキップ
            continue;
        }
    }
}

// レスポンスデータ
$response = [
    'success' => true,
    'this_week' => [
        'user' => [
            'submitted' => $userStatsThisWeek['submitted'],
            'attendance' => $userStatsThisWeek['attendance'],
            'visitor_count' => $userStatsThisWeek['visitor_count'],
            'referral_amount' => $userStatsThisWeek['referral_amount'],
            'referral_count' => $userStatsThisWeek['referral_count'],
            'thanks_slips' => $userStatsThisWeek['thanks_slips'],
            'one_to_one' => $userStatsThisWeek['one_to_one']
        ],
        'team' => [
            'total_members' => count($teamStatsThisWeek['unique_members']),
            'visitor_count' => $teamStatsThisWeek['visitor_count'],
            'referral_amount' => $teamStatsThisWeek['referral_amount'],
            'referral_count' => $teamStatsThisWeek['referral_count']
        ]
    ],
    'last_week' => [
        'user' => [
            'submitted' => $userStatsLastWeek['submitted'],
            'visitor_count' => $userStatsLastWeek['visitor_count'],
            'referral_amount' => $userStatsLastWeek['referral_amount'],
            'referral_count' => $userStatsLastWeek['referral_count']
        ]
    ],
    'this_month' => [
        'user' => $monthlyUserStats
    ],
    'week_dates' => [
        'this_friday' => $thisFridayStr,
        'last_friday' => $lastFridayStr,
        'month' => $now->format('Y年m月')
    ]
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
