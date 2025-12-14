<?php
/**
 * QRコード生成テスト
 * phpqrcodeライブラリを使用した新しいQRコード生成機能のテスト
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/phpqrcode/phpqrcode.php';

echo "=== QRコード生成テスト ===\n\n";

// テスト1: ライブラリの読み込み確認
echo "1. phpqrcodeライブラリの読み込み確認\n";
if (class_exists('QRcode')) {
    echo "   ✓ QRcodeクラスが正常に読み込まれました\n\n";
} else {
    echo "   ✗ QRcodeクラスの読み込みに失敗しました\n\n";
    exit(1);
}

// テスト2: QRコード画像の生成
echo "2. QRコード画像の生成テスト\n";
$testUrl = 'https://www.example.com/test';
$testDir = __DIR__ . '/data/uploads/qr_codes/';
if (!is_dir($testDir)) {
    mkdir($testDir, 0755, true);
}

$testFileName = 'test_' . time() . '_qr.png';
$testFilePath = $testDir . $testFileName;

try {
    // QRコード生成
    // パラメータ: (データ, 出力ファイル, エラー訂正レベル, サイズ, マージン)
    QRcode::png($testUrl, $testFilePath, QR_ECLEVEL_M, 10, 2);

    if (file_exists($testFilePath)) {
        $fileSize = filesize($testFilePath);
        echo "   ✓ QRコード画像が生成されました\n";
        echo "   　ファイルパス: {$testFilePath}\n";
        echo "   　ファイルサイズ: {$fileSize} bytes\n";
        echo "   　URL: {$testUrl}\n\n";
    } else {
        echo "   ✗ QRコード画像ファイルが見つかりません\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ✗ QRコード生成中にエラーが発生しました: " . $e->getMessage() . "\n\n";
    exit(1);
}

// テスト3: データベース接続確認
echo "3. データベース接続確認\n";
try {
    $db = getDbConnection();
    echo "   ✓ データベースに接続しました\n\n";
} catch (Exception $e) {
    echo "   ✗ データベース接続エラー: " . $e->getMessage() . "\n\n";
    exit(1);
}

// テスト4: qr_codesテーブル確認
echo "4. qr_codesテーブル確認\n";
try {
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='qr_codes'");
    $table = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($table) {
        echo "   ✓ qr_codesテーブルが存在します\n";

        // テーブル構造確認
        $stmt = $db->query("PRAGMA table_info(qr_codes)");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   　カラム:\n";
        foreach ($columns as $col) {
            echo "   　　- {$col['name']} ({$col['type']})\n";
        }
        echo "\n";
    } else {
        echo "   ✗ qr_codesテーブルが見つかりません\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ✗ テーブル確認エラー: " . $e->getMessage() . "\n\n";
    exit(1);
}

// テスト5: APIエンドポイントテスト（シミュレーション）
echo "5. QRコード保存テスト（データベース）\n";
try {
    $testWeekDate = date('Y-m-d', strtotime('next Friday'));
    $relativeQrPath = 'data/uploads/qr_codes/' . $testFileName;

    // 既存データを確認
    $stmt = $db->prepare("SELECT id FROM qr_codes WHERE week_date = :week_date");
    $stmt->bindValue(':week_date', $testWeekDate, PDO::PARAM_STR);
    $stmt->execute();
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // 更新
        $stmt = $db->prepare("UPDATE qr_codes SET url = :url, qr_code_path = :qr_code_path, updated_at = CURRENT_TIMESTAMP WHERE week_date = :week_date");
        echo "   　既存データを更新します\n";
    } else {
        // 新規作成
        $stmt = $db->prepare("INSERT INTO qr_codes (week_date, url, qr_code_path) VALUES (:week_date, :url, :qr_code_path)");
        echo "   　新規データを作成します\n";
    }

    $stmt->bindValue(':week_date', $testWeekDate, PDO::PARAM_STR);
    $stmt->bindValue(':url', $testUrl, PDO::PARAM_STR);
    $stmt->bindValue(':qr_code_path', $relativeQrPath, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "   ✓ データベースに保存しました\n";
        echo "   　対象週: {$testWeekDate}\n";
        echo "   　URL: {$testUrl}\n";
        echo "   　画像パス: {$relativeQrPath}\n\n";
    } else {
        echo "   ✗ データベース保存に失敗しました\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ✗ データベース保存エラー: " . $e->getMessage() . "\n\n";
    exit(1);
}

// テスト6: 保存したデータの読み取り確認
echo "6. 保存データの読み取り確認\n";
try {
    $stmt = $db->prepare("SELECT * FROM qr_codes WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
    $stmt->bindValue(':week_date', $testWeekDate, PDO::PARAM_STR);
    $stmt->execute();
    $qr = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qr) {
        echo "   ✓ データを読み取りました\n";
        echo "   　ID: {$qr['id']}\n";
        echo "   　対象週: {$qr['week_date']}\n";
        echo "   　URL: {$qr['url']}\n";
        echo "   　画像パス: {$qr['qr_code_path']}\n";
        echo "   　作成日時: {$qr['created_at']}\n";
        echo "   　更新日時: {$qr['updated_at']}\n\n";
    } else {
        echo "   ✗ データが見つかりません\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ✗ データ読み取りエラー: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "=== すべてのテストが完了しました ===\n\n";
echo "結果:\n";
echo "✓ phpqrcodeライブラリが正常に動作しています\n";
echo "✓ QRコード画像を生成できます\n";
echo "✓ データベースに保存できます\n";
echo "✓ データベースから読み取れます\n\n";

echo "次のステップ:\n";
echo "1. ブラウザで /slides_v2/admin/qr_code.php にアクセス\n";
echo "2. URLを入力してQRコードを生成\n";
echo "3. 生成されたQRコードが表示されることを確認\n\n";

echo "生成されたテストファイル: {$testFilePath}\n";
echo "このファイルは手動で削除してください。\n";
