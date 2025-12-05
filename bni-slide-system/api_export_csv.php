<?php
/**
 * BNI Slide System - Excel Export API
 * 指定した週のデータをExcel形式でエクスポート
 * Updated: 2025-12-05 14:30 (Cache Clear)
 * シンプルなCSV出力（UTF-8 BOM）でExcel互換性を確保
 */

require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/date_helper.php';

// 管理者チェック
$currentUser = getCurrentUserInfo();
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';

if (!$isAdmin) {
    http_response_code(403);
    die('管理者権限が必要です');
}

// 週の指定を取得
$week = $_GET['week'] ?? '';

if (empty($week)) {
    http_response_code(400);
    die('週の指定が必要です');
}

// CSVファイルパス
$csvFile = __DIR__ . '/data/' . $week . '.csv';

if (!file_exists($csvFile)) {
    http_response_code(404);
    die('指定された週のデータが見つかりません');
}

// シンプルなCSV出力を使用（Excelで開ける形式）
exportAsEnhancedCSV($csvFile, $week);

/**
 * フォールバック: 拡張CSV（Excel互換）としてエクスポート
 */
function exportAsEnhancedCSV($csvFile, $week) {
    // CSVファイルを読み込み
    $csvData = [];
    $handle = fopen($csvFile, 'r');
    while (($row = fgetcsv($handle)) !== false) {
        $csvData[] = $row;
    }
    fclose($handle);

    // ファイル名を設定
    $filename = 'BNI_Weekly_Data_' . $week . '.csv';

    // ヘッダー設定（UTF-8 BOM付きでExcelで正しく開けるようにする）
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // UTF-8 BOMを出力（Excelで文字化けを防ぐ）
    echo "\xEF\xBB\xBF";

    // CSV出力
    $output = fopen('php://output', 'w');

    foreach ($csvData as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
