<?php
/**
 * ImageMagick & Ghostscript 確認スクリプト
 * Xserverで PDF→画像変換が可能かテスト
 */

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "ImageMagick & Ghostscript 確認\n";
echo "========================================\n\n";

// ImageMagick確認
echo "[1] ImageMagick (convert コマンド)\n";
exec('which convert', $convertPath, $convertReturn);
if ($convertReturn === 0) {
    echo "✓ Path: " . implode("\n", $convertPath) . "\n";

    exec('convert -version 2>&1', $versionOutput);
    echo "✓ Version:\n" . implode("\n", array_slice($versionOutput, 0, 3)) . "\n\n";
} else {
    echo "❌ ImageMagick が見つかりません\n\n";
}

// Ghostscript確認
echo "[2] Ghostscript (gs コマンド)\n";
exec('which gs', $gsPath, $gsReturn);
if ($gsReturn === 0) {
    echo "✓ Path: " . implode("\n", $gsPath) . "\n";

    exec('gs --version 2>&1', $gsVersion);
    echo "✓ Version: " . implode("\n", $gsVersion) . "\n\n";
} else {
    echo "❌ Ghostscript が見つかりません\n\n";
}

// PDF変換テスト（サンプルPDFがあれば）
echo "[3] PDF→画像変換テスト\n";

if ($convertReturn === 0 && $gsReturn === 0) {
    echo "✅ ImageMagick + Ghostscript 両方利用可能\n";
    echo "PDF→画像変換が実装可能です\n\n";

    echo "変換コマンド例:\n";
    echo "convert -density 150 input.pdf output-%03d.png\n";
} else {
    echo "⚠ PDF→画像変換には両方のツールが必要です\n";
}

echo "\n========================================\n";
echo "確認完了\n";
echo "========================================\n";
