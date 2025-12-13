<?php
/**
 * BNI Slide System - Member Introduction API
 * メンバー紹介スライド用データ取得API
 *
 * 役職ごとにグループ化してメンバーリストを返す
 */

session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// OPTIONS request handling
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // データベース接続
    $db = getDBConnection();

    // メンバー紹介データを取得（display_order順、is_active=1のみ）
    $stmt = $db->prepare('
        SELECT
            id,
            name,
            name_highlight,
            company,
            industry,
            photo_url,
            position_title,
            position_title_en,
            display_order
        FROM member_photos
        WHERE is_active = 1
        ORDER BY display_order ASC, id ASC
    ');

    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 役職ごとにグループ化
    $groupedByPosition = [];

    foreach ($members as $member) {
        $position = $member['position_title'] ?: 'その他メンバー';
        $positionEn = $member['position_title_en'] ?: 'Other Members';

        if (!isset($groupedByPosition[$position])) {
            $groupedByPosition[$position] = [
                'position_title' => $position,
                'position_title_en' => $positionEn,
                'members' => []
            ];
        }

        $groupedByPosition[$position]['members'][] = $member;
    }

    // スライドページ用にフォーマット（3-4名ずつ分割）
    $slides = [];

    foreach ($groupedByPosition as $position => $group) {
        $membersInGroup = $group['members'];
        $memberCount = count($membersInGroup);

        // 1名の場合：単独スライド
        if ($memberCount === 1) {
            $slides[] = [
                'type' => 'single',
                'position_title' => $group['position_title'],
                'position_title_en' => $group['position_title_en'],
                'members' => $membersInGroup
            ];
        }
        // 2-4名の場合：1ページに全員表示
        elseif ($memberCount <= 4) {
            $slides[] = [
                'type' => 'multi',
                'position_title' => $group['position_title'],
                'position_title_en' => $group['position_title_en'],
                'members' => $membersInGroup
            ];
        }
        // 5名以上の場合：3-4名ずつ分割
        else {
            $chunks = array_chunk($membersInGroup, 3);
            foreach ($chunks as $chunk) {
                $slides[] = [
                    'type' => 'multi',
                    'position_title' => $group['position_title'],
                    'position_title_en' => $group['position_title_en'],
                    'members' => $chunk
                ];
            }
        }
    }

    // レスポンス返却
    echo json_encode([
        'success' => true,
        'slides' => $slides,
        'total_members' => count($members),
        'total_slides' => count($slides)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
