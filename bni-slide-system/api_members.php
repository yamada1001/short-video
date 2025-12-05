<?php
/**
 * Members API
 * メンバーリストをJSON形式で返す
 * 新構造に対応（後方互換性あり）
 */

header('Content-Type: application/json; charset=utf-8');

$membersFile = __DIR__ . '/data/members.json';

if (!file_exists($membersFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Members file not found']);
    exit;
}

$content = file_get_contents($membersFile);
if ($content === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to read members file']);
    exit;
}

// JSONとして出力（新しい構造でもmembersフィールドがあるので互換性あり）
echo $content;
