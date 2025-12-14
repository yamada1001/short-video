<?php
/**
 * BNI Slide System V2 - Networking PDF CRUD API
 * ネットワーキング学習PDF管理API
 */

header('Content-Type: application/json');

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';

try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'list':
        // PDFリスト取得
        $query = "SELECT * FROM networking_learning ORDER BY week_date DESC";
        $result = $db->query($query);

        $pdfs = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $pdfs[] = $row;
        }

        echo json_encode(['success' => true, 'pdfs' => $pdfs]);
        break;

    case 'get':
        // 特定のPDF取得
        $id = $_GET['id'] ?? null;
        $weekDate = $_GET['week_date'] ?? null;

        if ($id) {
            $stmt = $db->prepare("SELECT * FROM networking_learning WHERE id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        } elseif ($weekDate) {
            $stmt = $db->prepare("SELECT * FROM networking_learning WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
            $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        } else {
            echo json_encode(['success' => false, 'error' => 'IDまたは日付が必要です']);
            exit;
        }

        $result = $stmt->execute();
        $pdf = $result->fetchArray(SQLITE3_ASSOC);

        if ($pdf) {
            echo json_encode(['success' => true, 'pdf' => $pdf]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
        }
        break;

    case 'create':
        // 新規PDF追加
        $weekDate = $_POST['week_date'] ?? '';

        if (empty($weekDate)) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        // PDFファイルアップロード処理
        if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'PDFファイルのアップロードに失敗しました']);
            exit;
        }

        $uploadDir = __DIR__ . '/../data/uploads/networking_pdf/';
        $imageDir = __DIR__ . '/../data/uploads/networking_images/';

        // ディレクトリ作成
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $fileName = time() . '_' . basename($_FILES['pdf_file']['name']);
        $pdfPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $pdfPath)) {
            echo json_encode(['success' => false, 'error' => 'ファイルの保存に失敗しました']);
            exit;
        }

        // PDFを画像に変換
        $pythonScript = __DIR__ . '/../../pdf_to_images.py';
        $outputDir = $imageDir . pathinfo($fileName, PATHINFO_FILENAME) . '/';

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Pythonスクリプトを実行
        $command = sprintf(
            'python3 %s %s %s 2>&1',
            escapeshellarg($pythonScript),
            escapeshellarg($pdfPath),
            escapeshellarg($outputDir)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            echo json_encode([
                'success' => false,
                'error' => 'PDF変換に失敗しました: ' . implode("\n", $output)
            ]);
            exit;
        }

        // 生成された画像パスを取得
        $imageFiles = glob($outputDir . '*.png');
        sort($imageFiles);

        // 相対パスに変換
        $relativeImagePaths = array_map(function($path) use ($imageDir) {
            return 'data/uploads/networking_images/' . basename(dirname($path)) . '/' . basename($path);
        }, $imageFiles);

        // データベースに保存
        $relativePdfPath = 'data/uploads/networking_pdf/' . $fileName;
        $imagePathsJson = json_encode($relativeImagePaths);

        $stmt = $db->prepare("
            INSERT INTO networking_learning (week_date, pdf_path, image_paths)
            VALUES (:week_date, :pdf_path, :image_paths)
        ");

        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':pdf_path', $relativePdfPath, SQLITE3_TEXT);
        $stmt->bindValue(':image_paths', $imagePathsJson, SQLITE3_TEXT);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertRowID(),
                'image_count' => count($relativeImagePaths)
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベース保存エラー']);
        }
        break;

    case 'delete':
        // PDF削除
        $id = $_POST['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDが必要です']);
            exit;
        }

        // 削除前にファイルパスを取得
        $stmt = $db->prepare("SELECT pdf_path, image_paths FROM networking_learning WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if (!$row) {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
            exit;
        }

        // ファイル削除
        $pdfFullPath = __DIR__ . '/../' . $row['pdf_path'];
        if (file_exists($pdfFullPath)) {
            unlink($pdfFullPath);
        }

        // 画像ファイル削除
        if ($row['image_paths']) {
            $imagePaths = json_decode($row['image_paths'], true);
            foreach ($imagePaths as $imagePath) {
                $imageFullPath = __DIR__ . '/../' . $imagePath;
                if (file_exists($imageFullPath)) {
                    unlink($imageFullPath);
                }
            }

            // ディレクトリも削除
            $imageDir = dirname(__DIR__ . '/../' . $imagePaths[0]);
            if (is_dir($imageDir)) {
                rmdir($imageDir);
            }
        }

        // データベースから削除
        $stmt = $db->prepare("DELETE FROM networking_learning WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベース削除エラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}

$db->close();
