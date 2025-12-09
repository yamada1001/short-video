<?php
/**
 * ピッチ機能テストデータ作成スクリプト
 *
 * 目的：Phase 5-8のテストのため、テスト用のピッチデータを作成する
 * 実行後は必ずこのファイルを削除してください
 */

require_once __DIR__ . '/includes/db.php';

// テスト用PDFファイルの作成（シンプルなPDFを生成）
function createTestPDF($filename) {
    $content = "%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj
2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj
3 0 obj
<<
/Type /Page
/Parent 2 0 R
/Resources <<
/Font <<
/F1 <<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
>>
>>
/MediaBox [0 0 612 792]
/Contents 4 0 R
>>
endobj
4 0 obj
<<
/Length 100
>>
stream
BT
/F1 24 Tf
100 700 Td
(BNI Pitch Test Document) Tj
0 -50 Td
(This is a test PDF for pitch feature) Tj
ET
endstream
endobj
xref
0 5
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000317 00000 n
trailer
<<
/Size 5
/Root 1 0 R
>>
startxref
466
%%EOF";

    $filepath = __DIR__ . '/data/pitch/' . $filename;
    file_put_contents($filepath, $content);
    echo "✅ PDF作成完了: {$filepath}\n";
    return $filename;
}

// テスト用PowerPointファイルの作成（空のPPTXファイル）
function createTestPPTX($filename) {
    // 最小限のPPTXファイル構造を作成
    $tempDir = sys_get_temp_dir() . '/pptx_temp_' . uniqid();
    mkdir($tempDir);
    mkdir($tempDir . '/_rels');
    mkdir($tempDir . '/ppt');
    mkdir($tempDir . '/ppt/_rels');
    mkdir($tempDir . '/ppt/slides');
    mkdir($tempDir . '/ppt/slides/_rels');

    // [Content_Types].xml
    file_put_contents($tempDir . '/[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/ppt/presentation.xml" ContentType="application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml"/>
</Types>');

    // _rels/.rels
    file_put_contents($tempDir . '/_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="ppt/presentation.xml"/>
</Relationships>');

    // ppt/presentation.xml
    file_put_contents($tempDir . '/ppt/presentation.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<p:presentation xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main">
<p:sldIdLst/>
</p:presentation>');

    // ZIPに圧縮
    $filepath = __DIR__ . '/data/pitch/' . $filename;
    $zip = new ZipArchive();
    if ($zip->open($filepath, ZipArchive::CREATE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        echo "✅ PowerPoint作成完了: {$filepath}\n";
    }

    // 一時ディレクトリを削除
    function deleteDirectory($dir) {
        if (!file_exists($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = "$dir/$file";
            is_dir($path) ? deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    deleteDirectory($tempDir);

    return $filename;
}

try {
    echo "==============================================\n";
    echo "ピッチ機能テストデータ作成\n";
    echo "==============================================\n\n";

    // データベース接続
    $db = getDbConnection();

    // 1. テスト用ファイルの作成
    echo "【1】テスト用ファイルの作成\n";
    echo "------------------------------\n";

    $pdfFilename = 'test_pitch_' . date('Ymd_His') . '.pdf';
    $pptxFilename = 'test_pitch_' . date('Ymd_His') . '.pptx';

    createTestPDF($pdfFilename);
    createTestPPTX($pptxFilename);

    echo "\n";

    // 2. データベースに既存データがあるか確認
    echo "【2】既存のピッチデータ確認\n";
    echo "------------------------------\n";

    $result = dbQueryOne($db, "SELECT COUNT(*) as count FROM survey_data WHERE is_pitch_presenter = 1");
    echo "既存のピッチ担当者データ: {$result['count']}件\n\n";

    // 3. テストデータの挿入
    echo "【3】テストデータの挿入\n";
    echo "------------------------------\n";

    // 最新の週を取得
    $latestWeek = null;
    $csvFiles = glob(__DIR__ . '/data/*.csv');
    if (!empty($csvFiles)) {
        usort($csvFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $latestFile = basename($csvFiles[0], '.csv');
        $latestWeek = $latestFile;
    }

    if (!$latestWeek) {
        // データがない場合は今週の金曜日を使う
        require_once __DIR__ . '/includes/date_helper.php';
        $latestWeek = getTargetFriday();
    }

    echo "対象週: {$latestWeek}\n";

    // 既存ユーザーを取得
    $user1 = dbQueryOne($db, "SELECT id, name, email FROM users WHERE id = 3");
    $user2 = dbQueryOne($db, "SELECT id, name, email FROM users WHERE id = 4");

    if (!$user1 || !$user2) {
        throw new Exception("テスト用のユーザーが見つかりません");
    }

    // テストデータ1: PDFファイル
    dbExecute($db, "
        INSERT INTO survey_data (
            week_date, timestamp, input_date,
            user_id, user_name, user_email,
            attendance,
            thanks_slips, one_to_one,
            activities, comments,
            is_pitch_presenter, pitch_file_path, pitch_file_original_name, pitch_file_type,
            created_at
        ) VALUES (
            :week_date, datetime('now'), date('now'),
            :user_id, :user_name, :user_email,
            '出席',
            0, 0,
            '[]', 'ピッチテストデータ（PDF）',
            1, :pitch_file_path, :pitch_file_original_name, 'pdf',
            datetime('now')
        )
    ", [
        ':week_date' => $latestWeek,
        ':user_id' => $user1['id'],
        ':user_name' => $user1['name'],
        ':user_email' => $user1['email'],
        ':pitch_file_path' => 'data/pitch/' . $pdfFilename,
        ':pitch_file_original_name' => 'テストピッチ資料.pdf'
    ]);

    echo "✅ テストデータ1（PDF）を挿入しました\n";
    echo "   - 名前: テストユーザー（PDF）\n";
    echo "   - ファイル: {$pdfFilename}\n\n";

    // テストデータ2: PowerPointファイル
    dbExecute($db, "
        INSERT INTO survey_data (
            week_date, timestamp, input_date,
            user_id, user_name, user_email,
            attendance,
            thanks_slips, one_to_one,
            activities, comments,
            is_pitch_presenter, pitch_file_path, pitch_file_original_name, pitch_file_type,
            created_at
        ) VALUES (
            :week_date, datetime('now'), date('now'),
            :user_id, :user_name, :user_email,
            '出席',
            0, 0,
            '[]', 'ピッチテストデータ（PowerPoint）',
            1, :pitch_file_path, :pitch_file_original_name, 'pptx',
            datetime('now')
        )
    ", [
        ':week_date' => $latestWeek,
        ':user_id' => $user2['id'],
        ':user_name' => $user2['name'],
        ':user_email' => $user2['email'],
        ':pitch_file_path' => 'data/pitch/' . $pptxFilename,
        ':pitch_file_original_name' => 'テストピッチ資料.pptx'
    ]);

    echo "✅ テストデータ2（PowerPoint）を挿入しました\n";
    echo "   - 名前: テストユーザー（PowerPoint）\n";
    echo "   - ファイル: {$pptxFilename}\n\n";

    // 4. 確認
    echo "【4】挿入結果の確認\n";
    echo "------------------------------\n";

    $results = dbQuery($db, "
        SELECT id, week_date, user_name, pitch_file_type, pitch_file_original_name
        FROM survey_data
        WHERE is_pitch_presenter = 1
        ORDER BY created_at DESC
        LIMIT 5
    ");

    echo "ピッチ担当者データ（最新5件）:\n";
    foreach ($results as $row) {
        echo "  - ID: {$row['id']}, 週: {$row['week_date']}, 名前: {$row['user_name']}, ";
        echo "形式: {$row['pitch_file_type']}, ファイル名: {$row['pitch_file_original_name']}\n";
    }

    echo "\n";
    echo "==============================================\n";
    echo "✅ テストデータ作成完了！\n";
    echo "==============================================\n\n";

    echo "【次のステップ】\n";
    echo "1. スライド表示を確認してください:\n";
    echo "   https://yojitu.com/bni-slide-system/admin/slide.php?week={$latestWeek}\n\n";
    echo "2. セキュリティを確認してください:\n";
    echo "   https://yojitu.com/bni-slide-system/data/pitch/{$pdfFilename}\n";
    echo "   （403エラーが表示されるはずです）\n\n";
    echo "3. テスト完了後、このスクリプトを削除してください:\n";
    echo "   rm " . __FILE__ . "\n\n";

} catch (Exception $e) {
    echo "❌ エラーが発生しました:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
