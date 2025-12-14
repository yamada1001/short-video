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
    case 'get_latest':
        // 最新のメインプレゼン取得
        $stmt = $db->query("
            SELECT
                mp.*,
                m.name as member_name,
                m.company_name,
                m.category,
                m.photo_path
            FROM main_presenter mp
            LEFT JOIN members m ON mp.member_id = m.id
            ORDER BY mp.created_at DESC
            LIMIT 1
        ");

        $presentation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($presentation) {
            echo json_encode(['success' => true, 'data' => $presentation]);
        } else {
            echo json_encode(['success' => false, 'data' => null]);
        }
        break;

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
        $weekDate = date('Y-m-d');  // 本日の日付を設定
        $presentationType = $_POST['presentation_type'] ?? 'simple';
        $youtubeUrl = $_POST['youtube_url'] ?? null;

        if (!$memberId) {
            echo json_encode(['success' => false, 'error' => 'メンバーIDは必須です']);
            exit;
        }

        // PDFアップロード処理
        $pdfPath = null;
        $convertResult = null;

        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $filename = 'presentation_' . date('Ymd_His') . '.pdf';
            $uploadPath = $uploadsDir . $filename;

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadPath)) {
                $pdfPath = 'data/uploads/presentations/' . $filename;

                // PDF→画像変換を実行
                $convertResult = convertPdfToImages($uploadPath, $weekDate);
                if (!$convertResult['success']) {
                    // 変換失敗してもPDFは保存（後で手動変換可能にする）
                    error_log('PDF変換エラー: ' . $convertResult['error']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'PDFファイルのアップロードに失敗しました']);
                exit;
            }
        }

        try {
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
            generateSlideImage('main_presenter.php', 8);
            generateSlideImage('main_presenter.php', 204);

            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId(),
                'pdf_converted' => $convertResult ? $convertResult['success'] : false
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'データベースエラー: ' . $e->getMessage()
            ]);
        }
        break;

    case 'update':
        // メインプレゼン更新（最新レコードを更新）
        $memberId = $_POST['member_id'] ?? null;
        $weekDate = date('Y-m-d');  // 本日の日付を設定
        $presentationType = $_POST['presentation_type'] ?? 'simple';
        $youtubeUrl = $_POST['youtube_url'] ?? null;

        if (!$memberId) {
            echo json_encode(['success' => false, 'error' => 'メンバーIDは必須です']);
            exit;
        }

        // 最新のレコードを取得
        $checkStmt = $db->query('SELECT id FROM main_presenter ORDER BY created_at DESC LIMIT 1');
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
        generateSlideImage('main_presenter.php', 8);
        generateSlideImage('main_presenter.php', 204);

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
    $outputDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');

    // 出力ディレクトリ作成
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // ImageMagick を使用してPDF→画像変換（Xserver対応）
    // -density: 解像度（150dpiで十分）
    // -quality: 画質（85%）
    $outputPattern = $outputDir . '/page-%03d.png';

    $command = sprintf(
        'convert -density 150 -quality 85 %s %s 2>&1',
        escapeshellarg($pdfPath),
        escapeshellarg($outputPattern)
    );

    exec($command, $output, $returnCode);

    if ($returnCode === 0) {
        // 生成された画像ファイルを確認
        $images = glob($outputDir . '/page-*.png');
        return [
            'success' => true,
            'output_dir' => $outputDir,
            'image_count' => count($images),
            'images' => $images
        ];
    } else {
        return [
            'success' => false,
            'error' => implode("\n", $output),
            'command' => $command
        ];
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
