<?php
/**
 * スピーカーローテーションAPI動作確認スクリプト
 */

echo "=== スピーカーローテーションAPI動作確認 ===\n\n";

// GET actionシミュレート
$_GET['action'] = 'get_six_weeks';
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
include __DIR__ . '/slides_v2/api/speaker_rotation_crud.php';
$output = ob_get_clean();

echo "1. get_six_weeks アクション:\n";
$data = json_decode($output, true);
if ($data && $data['success']) {
    echo "   ✅ 成功: " . count($data['weeks']) . "週分のデータ取得\n";
    foreach ($data['weeks'] as $index => $week) {
        echo "     週" . ($index + 1) . ": {$week['rotation_date']}\n";
    }
} else {
    echo "   ❌ 失敗: " . ($data['error'] ?? '不明なエラー') . "\n";
}

echo "\n";

// get_slide_data actionシミュレート
$_GET['action'] = 'get_slide_data';
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
include __DIR__ . '/slides_v2/api/speaker_rotation_crud.php';
$output = ob_get_clean();

echo "2. get_slide_data アクション:\n";
$data = json_decode($output, true);
if ($data && $data['success']) {
    echo "   ✅ 成功: " . count($data['weeks']) . "週分のデータ取得（メンバー名含む）\n";
    foreach ($data['weeks'] as $index => $week) {
        $memberName = $week['member_name'] ?? '-';
        echo "     週" . ($index + 1) . ": {$week['rotation_date']} - {$memberName}\n";
    }
} else {
    echo "   ❌ 失敗: " . ($data['error'] ?? '不明なエラー') . "\n";
}

echo "\n=== テスト完了 ===\n";
