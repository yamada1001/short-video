<?php
/**
 * Article 99のキャッシュをクリア
 * アクセス後は削除してください
 */

$cacheDir = __DIR__ . '/cache/translations';
$deleted = [];

if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*99*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted[] = basename($file);
        }
    }

    // content_99 で始まるファイルも削除
    $files = glob($cacheDir . '/content_99_*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted[] = basename($file);
        }
    }

    // post_99 で始まるファイルも削除
    $files = glob($cacheDir . '/post_99_*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted[] = basename($file);
        }
    }
}

echo "Deleted cache files:\n";
if (empty($deleted)) {
    echo "None found\n";
} else {
    foreach ($deleted as $f) {
        echo "- $f\n";
    }
}

echo "\nDone. Please delete this file (clear-cache-99.php) after use.";
