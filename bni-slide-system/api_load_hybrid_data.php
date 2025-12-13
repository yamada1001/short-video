<?php
/**
 * BNI Slide System - Load Hybrid Data API
 * ハイブリッドモード用の動的データ取得API
 * 特定ページのみ差し替えるためのデータを返す
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

try {
    $weekDate = trim($_GET['week_date'] ?? '');

    if (empty($weekDate)) {
        // デフォルトで今週の金曜日を取得
        $weekDate = date('Y-m-d', strtotime('friday this week'));
    }

    $db = getDbConnection();

    // 動的データを収集
    $data = [
        'presenter' => null,
        'rotation' => [],
        'visitors' => [],
        'substitutes' => [],
        'newMembers' => [],
        'weeklyStats' => null,
        'champions' => []
    ];

    // 1. メインプレゼンター情報 (Page 8)
    $presenterQuery = "
        SELECT
            wp.*,
            u.name as user_name,
            u.company as user_company,
            u.category as user_category
        FROM weekly_presenters wp
        LEFT JOIN users u ON wp.member_id = u.id
        WHERE wp.week_date = :week_date
        LIMIT 1
    ";
    $presenterData = dbQueryOne($db, $presenterQuery, [':week_date' => $weekDate]);
    if ($presenterData) {
        // 名前の強調文字を自動検出
        $highlightChar = '';
        if (!empty($presenterData['member_name'])) {
            $name = $presenterData['member_name'];
            if (mb_strlen($name) > 0) {
                $highlightChar = mb_substr($name, mb_strlen($name) > 2 ? 1 : 0, 1);
            }
        }
        $presenterData['highlight_char'] = $highlightChar;
        $data['presenter'] = $presenterData;
    }

    // 2. スピーカーローテーション (Pages 9-13: 5週分)
    $weeks = [];
    $currentDate = new DateTime($weekDate);

    // 前1週
    $prevWeek = clone $currentDate;
    $prevWeek->modify('-7 days');
    $weeks[] = ['date' => $prevWeek->format('Y-m-d'), 'offset' => 1];

    // 当週
    $weeks[] = ['date' => $currentDate->format('Y-m-d'), 'offset' => 2];

    // 後3週
    for ($i = 1; $i <= 3; $i++) {
        $nextWeek = clone $currentDate;
        $nextWeek->modify('+' . ($i * 7) . ' days');
        $weeks[] = ['date' => $nextWeek->format('Y-m-d'), 'offset' => $i + 2];
    }

    // VP統計データからローテーション情報を取得
    foreach ($weeks as $week) {
        $vpStatsQuery = "SELECT stats_data FROM vp_statistics WHERE week_date = :week_date LIMIT 1";
        $vpStats = dbQueryOne($db, $vpStatsQuery, [':week_date' => $week['date']]);

        $rotationItem = [
            'week_date' => date('n/j', strtotime($week['date'])),
            'week_offset' => $week['offset'],
            'main_presentation' => '',
            'networking_learning' => '',
            'secretary' => ''
        ];

        if ($vpStats && !empty($vpStats['stats_data'])) {
            $statsData = json_decode($vpStats['stats_data'], true);
            if ($statsData) {
                $rotationItem['main_presentation'] = $statsData['main_presentation'] ?? '';
                $rotationItem['networking_learning'] = $statsData['networking_learning'] ?? '';
                $rotationItem['secretary'] = $statsData['secretary'] ?? '';
            }
        }

        $data['rotation'][] = $rotationItem;
    }

    // 3. ビジター紹介 (Page 19)
    $visitorsQuery = "
        SELECT
            visitor_name,
            company,
            specialty,
            sponsor,
            attendant
        FROM visitor_introductions
        WHERE week_date = :week_date
        ORDER BY display_order ASC, created_at ASC
    ";
    $visitors = dbQuery($db, $visitorsQuery, [':week_date' => $weekDate]);
    if ($visitors) {
        $data['visitors'] = $visitors;
    }

    // 4. 代理出席者 (Pages 22-24: 最大9名)
    // Note: データベースに代理出席者テーブルがない場合は空配列
    // 必要に応じてテーブル作成またはVP統計から取得
    // 今回はサンプルとして空配列を返す
    $data['substitutes'] = [];

    // 5. 新メンバー (Pages 25-27: 最大6名)
    // renewal_membersテーブルから取得
    $renewalQuery = "SELECT member_ids FROM renewal_members WHERE week_date = :week_date LIMIT 1";
    $renewal = dbQueryOne($db, $renewalQuery, [':week_date' => $weekDate]);
    if ($renewal && !empty($renewal['member_ids'])) {
        $memberIds = json_decode($renewal['member_ids'], true);
        if ($memberIds && is_array($memberIds)) {
            $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
            $membersQuery = "
                SELECT
                    name as member_name,
                    company,
                    category
                FROM users
                WHERE id IN ($placeholders)
                ORDER BY id ASC
            ";
            $newMembers = dbQuery($db, $membersQuery, $memberIds);
            if ($newMembers) {
                $data['newMembers'] = $newMembers;
            }
        }
    }

    // 6. 週間NO.1 (Page 28)
    // Note: 実際のPALMSデータがない場合はダミーデータ
    $data['weeklyStats'] = [
        'date' => date('Y年n月j日', strtotime($weekDate)),
        'referral' => [
            'count' => 0,
            'name' => '未定'
        ],
        'visitor' => [
            'count' => 0,
            'name' => '未定'
        ],
        'one_to_one' => [
            'count' => 0,
            'name' => '未定'
        ]
    ];

    // 7. 月間チャンピオン (Pages 29-30)
    $yearMonth = date('Y-m', strtotime($weekDate));
    $rankingQuery = "
        SELECT ranking_data
        FROM monthly_ranking
        WHERE year_month = :year_month
        AND display_in_slide = 1
        LIMIT 1
    ";
    $ranking = dbQueryOne($db, $rankingQuery, [':year_month' => $yearMonth]);
    if ($ranking && !empty($ranking['ranking_data'])) {
        $rankingData = json_decode($ranking['ranking_data'], true);
        if ($rankingData) {
            $data['champions'] = $rankingData;
        }
    }

    dbClose($db);

    echo json_encode([
        'success' => true,
        'data' => $data,
        'week_date' => $weekDate
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('[API LOAD HYBRID DATA] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
