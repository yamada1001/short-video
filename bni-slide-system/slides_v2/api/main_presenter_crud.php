<?php
/**
 * BNI Slide System V2 - Main Presenter CRUD API
 * メインプレゼン管理API（作成・読み取り・更新・削除）
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$uploadsDir = __DIR__ . '/../data/uploads/presentations/';

// アップロードディレクトリ作成
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $stmt = $db->query($query);

        $presentations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $presentations[] = $row;
        }

        echo json_encode(['success' => true, 'presentations' => $presentations]);
        break;

    case 'get':
    case 'read':
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $presentation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($presentation) {
            echo json_encode(['success' => true, 'data' => $presentation]);
        } else {
            echo json_encode(['success' => false, 'data' => null]);
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
        $checkStmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $checkStmt->execute();
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

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
            INSERT INTO main_presenter (member_id, week_date, presentation_type, pdf_path, youtube_url)
            VALUES (:member_id, :week_date, :presentation_type, :pdf_path, :youtube_url)
        ');

        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':presentation_type', $presentationType, PDO::PARAM_STR);
        $stmt->bindValue(':pdf_path', $pdfPath, PDO::PARAM_STR);
        $stmt->bindValue(':youtube_url', $youtubeUrl, PDO::PARAM_STR);

        $stmt->execute();

        // 保存成功後、スライド画像を生成（p.8とp.204）
        generateSlideImage('main_presenter.php', 8, $weekDate);
        generateSlideImage('main_presenter.php', 204, $weekDate);

        echo json_encode([
            'success' => true,
            'id' => $db->lastInsertId(),
            'pdf_converted' => isset($convertResult) ? $convertResult['success'] : false
        ]);
        break;

    case 'update':
        // メインプレゼン更新
        $memberId = $_POST['member_id'] ?? null;
        $weekDate = $_POST['week_date'] ?? null;
        $presentationType = $_POST['presentation_type'] ?? 'simple';
        $youtubeUrl = $_POST['youtube_url'] ?? null;

        if (!$memberId || !$weekDate) {
            echo json_encode(['success' => false, 'error' => 'メンバーID、開催日は必須です']);
            exit;
        }

        // week_dateからIDを取得
        $checkStmt = $db->prepare('SELECT id FROM main_presenter WHERE week_date = :week_date');
        $checkStmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $checkStmt->execute();
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing) {
            echo json_encode(['success' => false, 'error' => '更新対象のデータが見つかりません']);
            exit;
        }

        $id = $existing['id'];

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
                    presentation_type = :presentation_type,
                    pdf_path = :pdf_path,
                    youtube_url = :youtube_url,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            $stmt->bindValue(':pdf_path', $pdfPath, PDO::PARAM_STR);
        } else {
            $stmt = $db->prepare('
                UPDATE main_presenter
                SET member_id = :member_id,
                    week_date = :week_date,
                    presentation_type = :presentation_type,
                    youtube_url = :youtube_url,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
        }

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':presentation_type', $presentationType, PDO::PARAM_STR);
        $stmt->bindValue(':youtube_url', $youtubeUrl, PDO::PARAM_STR);

        $stmt->execute();

        // 保存成功後、スライド画像を生成（p.8とp.204）
        generateSlideImage('main_presenter.php', 8, $weekDate);
        generateSlideImage('main_presenter.php', 204, $weekDate);

        echo json_encode(['success' => true]);
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
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $presentation = $stmt->fetch(PDO::FETCH_ASSOC);

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
