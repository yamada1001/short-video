<?php
/**
 * PDF Helper Functions
 * PDFを画像化するヘルパー関数
 */

/**
 * PDFを各ページごとにPNG画像に変換
 *
 * @param string $pdfPath PDFファイルのフルパス
 * @param string $outputDir 出力ディレクトリ（最後にスラッシュなし）
 * @param string $baseFilename 出力ファイル名のベース（例: "20251213_abc123"）
 * @return array ['success' => bool, 'page_count' => int, 'image_paths' => array, 'error' => string]
 */
function convertPdfToImages($pdfPath, $outputDir, $baseFilename) {
    // Check if Imagick is available
    if (!extension_loaded('imagick')) {
        return [
            'success' => false,
            'error' => 'Imagick extension is not loaded'
        ];
    }

    // Check if PDF file exists
    if (!file_exists($pdfPath)) {
        return [
            'success' => false,
            'error' => 'PDF file not found: ' . $pdfPath
        ];
    }

    // Create output directory if it doesn't exist
    if (!is_dir($outputDir)) {
        if (!mkdir($outputDir, 0755, true)) {
            return [
                'success' => false,
                'error' => 'Failed to create output directory: ' . $outputDir
            ];
        }
    }

    try {
        $imagick = new Imagick();

        // Set resolution for better quality (150 DPI is good for presentations)
        $imagick->setResolution(150, 150);

        // Read PDF
        $imagick->readImage($pdfPath);

        // Get number of pages
        $pageCount = $imagick->getNumberImages();

        $imagePaths = [];

        // Process each page
        foreach ($imagick as $pageNumber => $page) {
            // Set image format to PNG
            $page->setImageFormat('png');

            // Set white background (PDFs might have transparent backgrounds)
            $page->setImageBackgroundColor('white');
            $page = $page->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

            // Resize if too large (max width: 1920px for Full HD displays)
            $width = $page->getImageWidth();
            if ($width > 1920) {
                $page->scaleImage(1920, 0); // 0 = maintain aspect ratio
            }

            // Generate filename: baseFilename_page1.png, baseFilename_page2.png, etc.
            $outputFilename = sprintf('%s_page%d.png', $baseFilename, $pageNumber + 1);
            $outputPath = $outputDir . '/' . $outputFilename;

            // Save image
            if (!$page->writeImage($outputPath)) {
                throw new Exception('Failed to write image: ' . $outputPath);
            }

            // Store relative path (from project root)
            $imagePaths[] = str_replace(__DIR__ . '/../', '', $outputPath);
        }

        // Clean up
        $imagick->clear();
        $imagick->destroy();

        return [
            'success' => true,
            'page_count' => $pageCount,
            'image_paths' => $imagePaths
        ];

    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Imagick error: ' . $e->getMessage()
        ];
    }
}

/**
 * 画像ファイルを削除
 *
 * @param array $imagePaths 画像パスの配列
 * @return bool 成功したかどうか
 */
function deleteImages($imagePaths) {
    $allDeleted = true;

    foreach ($imagePaths as $imagePath) {
        $fullPath = __DIR__ . '/../' . $imagePath;
        if (file_exists($fullPath)) {
            if (!unlink($fullPath)) {
                $allDeleted = false;
                error_log('[PDF Helper] Failed to delete image: ' . $fullPath);
            }
        }
    }

    return $allDeleted;
}

/**
 * PDF画像のパス配列をJSON文字列に変換
 *
 * @param array $imagePaths 画像パスの配列
 * @return string JSON文字列
 */
function encodeImagePaths($imagePaths) {
    return json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
}

/**
 * JSON文字列をPDF画像のパス配列に変換
 *
 * @param string $jsonString JSON文字列
 * @return array 画像パスの配列
 */
function decodeImagePaths($jsonString) {
    if (empty($jsonString)) {
        return [];
    }

    $paths = json_decode($jsonString, true);
    return is_array($paths) ? $paths : [];
}
