<?php
/**
 * CSVエクスポート機能
 * 週ごとの支払い状況をCSV形式でダウンロード
 */
require_once __DIR__ . '/../../config/config.php';

use BNI\Payment;
use BNI\Logger;

// 週の指定
$weekOf = $_GET['week'] ?? getCurrentWeek();
$weekLabel = getWeekLabel($weekOf);

// CSVデータ取得
$data = Payment::getForExport($weekOf);

// ファイル名生成
$filename = sprintf('bni_payments_%s.csv', str_replace('-', '', $weekOf));

// HTTPヘッダー設定
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// BOM付きUTF-8（Excel対応）
echo "\xEF\xBB\xBF";

// 出力バッファ
$output = fopen('php://output', 'w');

// ヘッダー行
fputcsv($output, [
    '名前',
    'メールアドレス',
    'ステータス',
    '金額',
    '支払い日時',
    'Square決済ID'
]);

// データ行
foreach ($data as $row) {
    $isPaid = !is_null($row['paid_at']);

    fputcsv($output, [
        $row['name'],
        $row['email'],
        $isPaid ? '支払い済み' : '未払い',
        $isPaid ? number_format($row['amount']) : '',
        $isPaid ? date('Y-m-d H:i:s', strtotime($row['paid_at'])) : '',
        $isPaid ? $row['square_payment_id'] : ''
    ]);
}

fclose($output);

Logger::info('CSV exported', [
    'week_of' => $weekOf,
    'filename' => $filename,
    'rows' => count($data)
]);

exit;
