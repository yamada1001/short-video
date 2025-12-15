<?php
require_once __DIR__ . '/../config.php';

// phpqrcodeライブラリのDeprecated警告を抑制
error_reporting(E_ERROR | E_PARSE);

header('Content-Type: application/json');
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

switch ($action) {
    case 'get':
        $weekDate = $_GET['week_date'] ?? null;
        if (!$weekDate) { echo json_encode(['success' => false, 'error' => '日付が必要です']); exit; }
        
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $qr = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($qr) {
            echo json_encode(['success' => true, 'qr' => $qr]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
        }
        break;

    case 'get_latest':
        // 最新のQRコードデータ取得
        $stmt = $db->query("
            SELECT * FROM qr_codes
            ORDER BY week_date DESC, id DESC
            LIMIT 1
        ");

        $qr = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($qr) {
            echo json_encode(['success' => true, 'qr' => $qr]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
        }
        break;

    case 'create':
        $weekDate = $postData['week_date'] ?? null;
        $url = $postData['url'] ?? null;
        if (!$weekDate || !$url) { echo json_encode(['success' => false, 'error' => '必要なデータが不足しています']); exit; }

        // QRコード生成（phpqrcodeライブラリを使用）
        require_once __DIR__ . '/../lib/phpqrcode/phpqrcode.php';

        $qrImageDir = __DIR__ . '/../data/uploads/qr_codes/';
        if (!is_dir($qrImageDir)) { mkdir($qrImageDir, 0755, true); }

        $fileName = time() . '_qr.png';
        $qrImagePath = $qrImageDir . $fileName;
        $relativeQrPath = 'data/uploads/qr_codes/' . $fileName;

        // phpqrcodeライブラリでQRコード画像を生成
        // パラメータ: (データ, 出力ファイル, エラー訂正レベル, サイズ, マージン)
        // エラー訂正レベル: L=7%, M=15%, Q=25%, H=30%
        try {
            QRcode::png($url, $qrImagePath, QR_ECLEVEL_M, 10, 2);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'QRコード生成に失敗しました: ' . $e->getMessage()]);
            exit;
        }

        if (!file_exists($qrImagePath)) {
            echo json_encode(['success' => false, 'error' => 'QRコード画像の保存に失敗しました']);
            exit;
        }

        // 既存データを確認
        $stmt = $db->prepare("SELECT id FROM qr_codes WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare("UPDATE qr_codes SET url = :url, qr_code_path = :qr_code_path, updated_at = CURRENT_TIMESTAMP WHERE week_date = :week_date");
        } else {
            // 新規作成
            $stmt = $db->prepare("INSERT INTO qr_codes (week_date, url, qr_code_path) VALUES (:week_date, :url, :qr_code_path)");
        }

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':url', $url, PDO::PARAM_STR);
        $stmt->bindValue(':qr_code_path', $relativeQrPath, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // 保存成功後、スライド画像を生成
            generateSlideImage('qr_code.php', 242, $weekDate);

            echo json_encode(['success' => true, 'qr_code_path' => $relativeQrPath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベースエラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}
