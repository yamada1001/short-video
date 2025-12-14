<?php
/**
 * BNI Slide System V2 - Main Presenter CRUD API
 * メインプレゼン管理API（作成・読み取り・更新・削除）
 */

header('Content-Type: application/json');

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
$uploadsDir = __DIR__ . '/../data/uploads/presentations/';

// アップロードディレクトリ作成
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'list':
        // メインプレゼン一覧取得
        $query = "
            SELECT
                mp.*,
                m.name as member_name,
                m.company_name,
                m.category,
                m.photo_path
            FROM main_presenter mp
            LEFT JOIN members m ON mp.member_id = m.id
            ORDER BY mp.week_date DESC
        ";
        $result = $db->query($query);

        $presentations = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $presentations[] = $row;
        }

        echo json_encode(['success' => true, 'presentations' => $presentations]);
        break;

    case 'get':
        // 特定日付のメインプレゼン取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                mp.*,
                m.name as member_name,
                m.company_name,
                m.category,
                m.photo_path
            FROM main_presenter mp
            LEFT JOIN members m ON mp.member_id = m.id
            WHERE mp.week_date = :week_date
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $presentation = $result->fetchArray(SQLITE3_ASSOC);

        if ($presentation) {
            echo json_encode(['success' => true, 'presentation' => $presentation]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    case 'create':
        // 新規メインプレゼン追加
        $memberId = $_POST['member_id'] ?? null;
        $weekDate = $_POST['week_date'] ?? null;
        $presentationType = $_POST['presentation_type'] ?? 'simple';
        $youtubeUrl = $_POST['youtube_url'] ?? null;

        if (!$memberId || !$weekDate) {
            echo json_encode(['success' => false, 'error' => 'メンバーIDと開催日は必須です']);
            exit;
        }

        // 既存データチェック
        $checkStmt = $db->prepare('SELECT id FROM main_presenter WHERE week_date = :week_date');
        $checkStmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $checkResult = $checkStmt->execute();
        $existing = $checkResult->fetchArray(SQLITE3_ASSOC);

        if ($existing) {
            echo json_encode(['success' => false, 'error' => 'この日付は既に登録されています']);
            exit;
        }

        // PDFアップロード処理
        $pdfPath = null;
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $filename = 'presentation_' . date('Ymd_His') . '.pdf';
            $uploadPath = $uploadsDir . $filename;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
                $pdfPath = '../data/uploads/presentations/' . $filename;

                // PDF→画像変換を実行
                $convertResult = convertPdfToImages($uploadPath, $weekDate);
                if (!$convertResult['success']) {
                    // 変換失敗してもPDFは保存（後で手動変換可能にする）
                    error_log('PDF変換エラー: ' . $convertResult['error']);
                }
            }
        }

        $stmt = $db->prepare('
            INSERT INTO main_presenter (member_id, week_date, pdf_path, youtube_url)
            VALUES (:member_id, :week_date, :pdf_path, :youtube_url)
        ');

        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':pdf_path', $pdfPath, SQLITE3_TEXT);
        $stmt->bindValue(':youtube_url', $youtubeUrl, SQLITE3_TEXT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertRowID(),
                'pdf_converted' => isset($convertResult) ? $convertResult['success'] : false
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'update':
        // メインプレゼン更新
        $id = $_POST['id'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        $weekDate = $_POST['week_date'] ?? null;
        $youtubeUrl = $_POST['youtube_url'] ?? null;

        if (!$id || !$memberId || !$weekDate) {
            echo json_encode(['success' => false, 'error' => 'ID、メンバーID、開催日は必須です']);
            exit;
        }

        // PDFアップロード処理
        $pdfPath = null;
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $filename = 'presentation_' . date('Ymd_His') . '.pdf';
            $uploadPath = $uploadsDir . $filename;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
                $pdfPath = '../data/uploads/presentations/' . $filename;

                // PDF→画像変換を実行
                $convertResult = convertPdfToImages($uploadPath, $weekDate);
            }
        }

        if ($pdfPath) {
            $stmt = $db->prepare('
                UPDATE main_presenter
                SET member_id = :member_id,
                    week_date = :week_date,
                    pdf_path = :pdf_path,
                    youtube_url = :youtube_url,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            $stmt->bindValue(':pdf_path', $pdfPath, SQLITE3_TEXT);
        } else {
            $stmt = $db->prepare('
                UPDATE main_presenter
                SET member_id = :member_id,
                    week_date = :week_date,
                    youtube_url = :youtube_url,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
        }

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':youtube_url', $youtubeUrl, SQLITE3_TEXT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'delete':
        // メインプレゼン削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        // 関連ファイルも削除
        $stmt = $db->prepare('SELECT pdf_path FROM main_presenter WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row && $row['pdf_path']) {
            $filePath = __DIR__ . '/../' . $row['pdf_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // 変換された画像も削除
            $imagesDir = dirname($filePath) . '/images_' . basename($filePath, '.pdf');
            if (is_dir($imagesDir)) {
                deleteDirectory($imagesDir);
            }
        }

        $stmt = $db->prepare('DELETE FROM main_presenter WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'get_slide_data':
        // スライド表示用データ取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                mp.*,
                m.name as member_name,
                m.company_name,
                m.category,
                m.photo_path
            FROM main_presenter mp
            LEFT JOIN members m ON mp.member_id = m.id
            WHERE mp.week_date = :week_date
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $presentation = $result->fetchArray(SQLITE3_ASSOC);

        if ($presentation) {
            // PDF画像パスを取得
            $pdfImages = [];
            if ($presentation['pdf_path']) {
                $pdfPath = __DIR__ . '/../' . $presentation['pdf_path'];
                $imagesDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
                if (is_dir($imagesDir)) {
                    $files = glob($imagesDir . '/page_*.png');
                    sort($files);
                    foreach ($files as $file) {
                        $pdfImages[] = str_replace(__DIR__ . '/../', '../', $file);
                    }
                }
            }

            $presentation['pdf_images'] = $pdfImages;

            echo json_encode(['success' => true, 'presentation' => $presentation]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}

$db->close();

/**
 * PDF→画像変換
 */
function convertPdfToImages($pdfPath, $weekDate) {
    $pythonScript = __DIR__ . '/../../scripts/pdf_to_images.py';
    $outputDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');

    // 出力ディレクトリ作成
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Pythonスクリプト実行
    $command = sprintf(
        'python3 %s %s %s 2>&1',
        escapeshellarg($pythonScript),
        escapeshellarg($pdfPath),
        escapeshellarg($outputDir)
    );

    exec($command, $output, $returnCode);

    if ($returnCode === 0) {
        return ['success' => true, 'output_dir' => $outputDir];
    } else {
        return ['success' => false, 'error' => implode("\n", $output)];
    }
}

/**
 * ディレクトリ削除
 */
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}
