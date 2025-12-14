<?php
/**
 * BNI Slide System V2 - Main Presenter Test Page
 * メインプレゼン管理機能のテストページ
 */

$dbPath = __DIR__ . '/../database/bni_slide_v2.db';
$db = new SQLite3($dbPath);

echo "<!DOCTYPE html>\n";
echo "<html lang='ja'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <title>Main Presenter Test | BNI Slide System V2</title>\n";
echo "    <style>\n";
echo "        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; padding: 40px; background: #f5f5f5; }\n";
echo "        h1 { color: #C8102E; }\n";
echo "        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }\n";
echo "        .success { color: #28a745; }\n";
echo "        .error { color: #dc3545; }\n";
echo "        .info { color: #0066cc; }\n";
echo "        table { width: 100%; border-collapse: collapse; margin-top: 10px; }\n";
echo "        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }\n";
echo "        th { background: #C8102E; color: white; }\n";
echo "        .btn { display: inline-block; padding: 10px 20px; background: #C8102E; color: white; text-decoration: none; border-radius: 6px; margin: 5px; }\n";
echo "        .btn:hover { background: #a00a24; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";

echo "<h1>メインプレゼン管理機能 テスト</h1>\n";

// 1. データベース接続確認
echo "<div class='section'>\n";
echo "<h2>1. データベース接続</h2>\n";
try {
    $version = $db->querySingle('SELECT sqlite_version()');
    echo "<p class='success'>✓ 接続成功 (SQLite version: {$version})</p>\n";
} catch (Exception $e) {
    echo "<p class='error'>✗ 接続失敗: {$e->getMessage()}</p>\n";
}
echo "</div>\n";

// 2. main_presenter テーブル確認
echo "<div class='section'>\n";
echo "<h2>2. main_presenter テーブル</h2>\n";
try {
    $result = $db->query("SELECT * FROM main_presenter LIMIT 10");
    $count = $db->querySingle("SELECT COUNT(*) FROM main_presenter");

    echo "<p class='success'>✓ テーブル存在確認 ({$count}件のデータ)</p>\n";

    if ($count > 0) {
        echo "<table>\n";
        echo "<tr><th>ID</th><th>開催日</th><th>メンバーID</th><th>PDF</th><th>YouTube</th><th>作成日時</th></tr>\n";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>\n";
            echo "<td>{$row['id']}</td>\n";
            echo "<td>{$row['week_date']}</td>\n";
            echo "<td>{$row['member_id']}</td>\n";
            echo "<td>" . ($row['pdf_path'] ? '有' : '-') . "</td>\n";
            echo "<td>" . ($row['youtube_url'] ? '有' : '-') . "</td>\n";
            echo "<td>{$row['created_at']}</td>\n";
            echo "</tr>\n";
        }

        echo "</table>\n";
    } else {
        echo "<p class='info'>データがありません</p>\n";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ エラー: {$e->getMessage()}</p>\n";
}
echo "</div>\n";

// 3. メンバーデータ確認
echo "<div class='section'>\n";
echo "<h2>3. メンバーデータ（在籍中）</h2>\n";
try {
    $result = $db->query("SELECT * FROM members WHERE is_active = 1 LIMIT 10");
    $count = $db->querySingle("SELECT COUNT(*) FROM members WHERE is_active = 1");

    echo "<p class='success'>✓ {$count}名のメンバーが在籍中</p>\n";

    echo "<table>\n";
    echo "<tr><th>ID</th><th>名前</th><th>会社名</th><th>カテゴリ</th><th>写真</th></tr>\n";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>\n";
        echo "<td>{$row['id']}</td>\n";
        echo "<td>{$row['name']}</td>\n";
        echo "<td>" . ($row['company_name'] ?: '-') . "</td>\n";
        echo "<td>" . ($row['category'] ?: '-') . "</td>\n";
        echo "<td>" . ($row['photo_path'] ? '有' : '-') . "</td>\n";
        echo "</tr>\n";
    }

    echo "</table>\n";
} catch (Exception $e) {
    echo "<p class='error'>✗ エラー: {$e->getMessage()}</p>\n";
}
echo "</div>\n";

// 4. ディレクトリ確認
echo "<div class='section'>\n";
echo "<h2>4. アップロードディレクトリ</h2>\n";

$dirs = [
    'data/uploads/presentations' => __DIR__ . '/data/uploads/presentations',
    'data/uploads/members' => __DIR__ . '/data/uploads/members',
];

foreach ($dirs as $name => $path) {
    if (is_dir($path)) {
        $writable = is_writable($path) ? '書き込み可' : '書き込み不可';
        echo "<p class='success'>✓ {$name}: 存在 ({$writable})</p>\n";
    } else {
        echo "<p class='error'>✗ {$name}: 存在しません</p>\n";
    }
}
echo "</div>\n";

// 5. Python環境確認
echo "<div class='section'>\n";
echo "<h2>5. Python/PDF変換環境</h2>\n";

// Python3確認
exec('python3 --version 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "<p class='success'>✓ Python3: " . implode(' ', $output) . "</p>\n";
} else {
    echo "<p class='error'>✗ Python3が見つかりません</p>\n";
}

// PyMuPDF確認
unset($output);
exec('python3 -c "import fitz; print(fitz.__version__)" 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "<p class='success'>✓ PyMuPDF: " . implode(' ', $output) . "</p>\n";
} else {
    echo "<p class='info'>ⓘ PyMuPDF未インストール（PDF変換機能は利用できません）</p>\n";
}

// pdf2image確認
unset($output);
exec('python3 -c "from pdf2image import convert_from_path; print(\'OK\')" 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "<p class='success'>✓ pdf2image: OK</p>\n";
} else {
    echo "<p class='info'>ⓘ pdf2image未インストール（フォールバック利用不可）</p>\n";
}

echo "</div>\n";

// 6. APIエンドポイント確認
echo "<div class='section'>\n";
echo "<h2>6. APIエンドポイント</h2>\n";

$apis = [
    'メンバー管理API' => 'api/members_crud.php',
    'メインプレゼンAPI' => 'api/main_presenter_crud.php',
];

foreach ($apis as $name => $path) {
    $fullPath = __DIR__ . '/' . $path;
    if (file_exists($fullPath)) {
        echo "<p class='success'>✓ {$name}: 存在</p>\n";
    } else {
        echo "<p class='error'>✗ {$name}: 存在しません</p>\n";
    }
}
echo "</div>\n";

// 7. 管理画面リンク
echo "<div class='section'>\n";
echo "<h2>7. 管理画面</h2>\n";
echo "<a href='admin/members.php' class='btn'>メンバー管理</a>\n";
echo "<a href='admin/main_presenter.php' class='btn'>メインプレゼン管理</a>\n";
echo "<a href='slides/main_presenter.php' class='btn'>スライド表示（p.8）</a>\n";
echo "</div>\n";

// 8. データベーススキーマ
echo "<div class='section'>\n";
echo "<h2>8. データベーススキーマ</h2>\n";
echo "<h3>main_presenter テーブル</h3>\n";
try {
    $result = $db->query("PRAGMA table_info(main_presenter)");
    echo "<table>\n";
    echo "<tr><th>No</th><th>カラム名</th><th>型</th><th>NULL許可</th><th>デフォルト値</th></tr>\n";

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>\n";
        echo "<td>{$row['cid']}</td>\n";
        echo "<td>{$row['name']}</td>\n";
        echo "<td>{$row['type']}</td>\n";
        echo "<td>" . ($row['notnull'] ? 'NO' : 'YES') . "</td>\n";
        echo "<td>" . ($row['dflt_value'] ?: '-') . "</td>\n";
        echo "</tr>\n";
    }

    echo "</table>\n";
} catch (Exception $e) {
    echo "<p class='error'>✗ エラー: {$e->getMessage()}</p>\n";
}
echo "</div>\n";

$db->close();

echo "</body>\n";
echo "</html>\n";
