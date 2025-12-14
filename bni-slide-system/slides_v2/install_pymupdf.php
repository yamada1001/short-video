<?php
/**
 * PyMuPDF インストールスクリプト
 * 本番サーバーでPDF→画像変換を有効にする
 */

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "PyMuPDF インストール\n";
echo "========================================\n\n";

// Python3のパス確認
exec('which python3', $pythonPath, $returnCode);
if ($returnCode !== 0) {
    echo "❌ Python3が見つかりません\n";
    exit(1);
}

echo "✓ Python3: " . implode("\n", $pythonPath) . "\n\n";

// PyMuPDFインストール（ユーザーディレクトリ）
echo "[Step 1] PyMuPDF (fitz) をインストール中...\n";
$command = 'python3 -m pip install --user PyMuPDF 2>&1';
exec($command, $output, $returnCode);

echo implode("\n", $output) . "\n\n";

if ($returnCode === 0) {
    echo "✅ PyMuPDFインストール成功\n\n";
} else {
    echo "⚠ インストールに失敗した可能性があります\n\n";
}

// 確認
echo "[Step 2] インストール確認中...\n";
$testCommand = 'python3 -c "import fitz; print(fitz.__version__)" 2>&1';
exec($testCommand, $versionOutput, $versionReturn);

if ($versionReturn === 0) {
    echo "✅ PyMuPDF バージョン: " . implode("\n", $versionOutput) . "\n";
    echo "\n========================================\n";
    echo "✅ インストール完了\n";
    echo "========================================\n";
} else {
    echo "❌ インストールは完了しましたが、importできませんでした\n";
    echo "エラー: " . implode("\n", $versionOutput) . "\n";
}
