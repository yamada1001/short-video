<?php
/**
 * Members API (SQLite Version)
 * メンバーリストをJSON形式で返す
 * Updated: 2025-12-06 - SQLite対応版
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/db.php';

try {
    $db = getDbConnection();

    // アクティブなメンバーを取得
    $query = "
        SELECT
            id,
            email,
            name,
            phone,
            company,
            category,
            industry,
            role,
            is_active,
            created_at
        FROM users
        WHERE is_active = 1
        ORDER BY name
    ";

    $members = dbQuery($db, $query);

    dbClose($db);

    // JSON形式で出力（後方互換性のため配列形式）
    $response = [
        'success' => true,
        'members' => $members,
        'count' => count($members)
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    error_log('[API MEMBERS] Error: ' . $e->getMessage());
    if (isset($db)) {
        dbClose($db);
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch members',
        'message' => 'メンバー情報の取得中にエラーが発生しました'
    ], JSON_UNESCAPED_UNICODE);
}
?>
