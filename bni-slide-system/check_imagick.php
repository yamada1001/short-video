<?php
/**
 * Check if Imagick is available on server
 * Xserver環境でのImagick確認用
 */

header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>Imagick チェック</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}pre{background:white;padding:20px;border-radius:8px;}</style>";
echo "</head><body><h1>🔍 Imagick 確認</h1><pre>";

// Check if Imagick extension is loaded
if (extension_loaded('imagick')) {
    echo "✅ Imagick 拡張がロードされています\n\n";

    // Get Imagick version
    $imagick = new Imagick();
    $version = $imagick->getVersion();
    echo "バージョン情報:\n";
    print_r($version);
    echo "\n\n";

    // Check supported formats
    echo "サポートされているフォーマット:\n";
    $formats = Imagick::queryFormats();

    // Check if PDF is supported
    if (in_array('PDF', $formats)) {
        echo "✅ PDF フォーマットがサポートされています\n";
    } else {
        echo "❌ PDF フォーマットはサポートされていません\n";
    }

    // Check if PNG is supported
    if (in_array('PNG', $formats)) {
        echo "✅ PNG フォーマットがサポートされています\n";
    } else {
        echo "❌ PNG フォーマットはサポートされていません\n";
    }

    echo "\n主要フォーマット:\n";
    $mainFormats = array_intersect($formats, ['PDF', 'PNG', 'JPEG', 'JPG', 'GIF', 'WEBP']);
    foreach ($mainFormats as $format) {
        echo "  - {$format}\n";
    }

} else {
    echo "❌ Imagick 拡張がロードされていません\n\n";
    echo "代替案: PDF.js を使用してブラウザ側で処理する必要があります。\n";
}

echo "</pre></body></html>";
?>
