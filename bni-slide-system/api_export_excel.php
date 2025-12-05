<?php
/**
 * BNI Slide System - Excel Export API
 * 指定した週のデータをExcel形式でエクスポート
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

// PhpSpreadsheetが使用可能か確認
$usePhpSpreadsheet = false;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        $usePhpSpreadsheet = true;
    }
}

if ($usePhpSpreadsheet) {
    // PhpSpreadsheetを使用したExcel生成
    exportWithPhpSpreadsheet($csvFile, $week);
} else {
    // フォールバック: CSVファイルを読み込んで簡易Excel（TSV）として出力
    exportAsEnhancedCSV($csvFile, $week);
}

/**
 * PhpSpreadsheetを使用したExcelエクスポート
 */
function exportWithPhpSpreadsheet($csvFile, $week) {
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('BNI週次データ');

    // CSVファイルを読み込み
    $csvData = [];
    $handle = fopen($csvFile, 'r');
    while (($row = fgetcsv($handle)) !== false) {
        $csvData[] = $row;
    }
    fclose($handle);

    // データをExcelシートに書き込み
    $rowNum = 1;
    foreach ($csvData as $rowData) {
        $colNum = 1;
        foreach ($rowData as $cellData) {
            $sheet->setCellValueByColumnAndRow($colNum, $rowNum, $cellData);
            $colNum++;
        }
        $rowNum++;
    }

    // ヘッダー行のスタイル設定
    $headerStyle = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'CF2030']
        ],
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 12
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ];

    $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);

    // 列幅を自動調整
    foreach (range('A', $sheet->getHighestColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ファイル名を設定
    $filename = 'BNI_Weekly_Data_' . $week . '.xlsx';

    // ヘッダー設定
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // 出力
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

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
