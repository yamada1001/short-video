<?php
header('Content-Type: application/json');
$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
try { $db = new SQLite3($dbPath); } catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

switch ($action) {
    case 'get':
        $weekDate = $_GET['week_date'] ?? null;
        if (!$weekDate) { echo json_encode(['success' => false, 'error' => '日付が必要です']); exit; }
        
        $stmt = $db->prepare("SELECT * FROM qr_codes WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();
        $qr = $result->fetchArray(SQLITE3_ASSOC);
        
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

        // QRコード生成（Google Charts APIを使用）
        $qrImageDir = __DIR__ . '/../data/uploads/qr_codes/';
        if (!is_dir($qrImageDir)) { mkdir($qrImageDir, 0755, true); }

        $fileName = time() . '_qr.png';
        $qrImagePath = $qrImageDir . $fileName;
        $relativeQrPath = 'data/uploads/qr_codes/' . $fileName;

        // Google Charts API経由でQRコード画像を生成
        $qrUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . urlencode($url);
        $imageData = file_get_contents($qrUrl);
        
        if ($imageData === false) {
            echo json_encode(['success' => false, 'error' => 'QRコード生成に失敗しました']);
            exit;
        }

        file_put_contents($qrImagePath, $imageData);

        // 既存データを確認
        $stmt = $db->prepare("SELECT id FROM qr_codes WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();
        $existing = $result->fetchArray(SQLITE3_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare("UPDATE qr_codes SET url = :url, qr_image_path = :qr_image_path, updated_at = CURRENT_TIMESTAMP WHERE week_date = :week_date");
        } else {
            // 新規作成
            $stmt = $db->prepare("INSERT INTO qr_codes (week_date, url, qr_image_path) VALUES (:week_date, :url, :qr_image_path)");
        }

        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':url', $url, SQLITE3_TEXT);
        $stmt->bindValue(':qr_image_path', $relativeQrPath, SQLITE3_TEXT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'qr_image_path' => $relativeQrPath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベースエラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}

$db->close();
