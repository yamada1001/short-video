<?php
/**
 * BNI Slide System V2 - 統合テストスクリプト
 *
 * 全システムの統合テストを実行し、結果をレポートします。
 *
 * 実行方法: php test_integration.php
 */

// エラーレポート設定
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベースパス
$db_path = dirname(__DIR__) . '/database/bni_slide_v2.db';

// テスト結果を格納
$test_results = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0,
    'tests' => []
];

// テストログ
$log = [];

/**
 * テスト実行関数
 */
function runTest($name, $callback) {
    global $test_results, $log;

    $log[] = "\n[テスト開始] {$name}";

    try {
        $result = $callback();

        if ($result === true) {
            $test_results['passed']++;
            $test_results['tests'][$name] = 'PASS';
            $log[] = "✓ PASS: {$name}";
            return true;
        } elseif ($result === 'WARNING') {
            $test_results['warnings']++;
            $test_results['tests'][$name] = 'WARNING';
            $log[] = "⚠ WARNING: {$name}";
            return 'WARNING';
        } else {
            $test_results['failed']++;
            $test_results['tests'][$name] = 'FAIL';
            $log[] = "✗ FAIL: {$name} - {$result}";
            return false;
        }
    } catch (Exception $e) {
        $test_results['failed']++;
        $test_results['tests'][$name] = 'FAIL';
        $log[] = "✗ FAIL: {$name} - Exception: " . $e->getMessage();
        return false;
    }
}

echo "===========================================\n";
echo "BNI Slide System V2 - 統合テスト\n";
echo "===========================================\n";
echo "実行日時: " . date('Y-m-d H:i:s') . "\n\n";

// ==========================================
// 1. データベーステスト
// ==========================================
echo "\n■ 1. データベーステスト\n";
echo "-------------------------------------------\n";

runTest('データベースファイルの存在確認', function() use ($db_path) {
    if (!file_exists($db_path)) {
        return "データベースファイルが見つかりません: {$db_path}";
    }
    return true;
});

runTest('データベース接続テスト', function() use ($db_path) {
    try {
        $db = new PDO('sqlite:' . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
    } catch (PDOException $e) {
        return "接続エラー: " . $e->getMessage();
    }
});

// テーブル存在確認
$expected_tables = [
    'members',
    'seating_arrangement',
    'main_presenter',
    'speaker_rotation',
    'start_dash_presenter',
    'visitors',
    'substitutes',
    'new_members',
    'weekly_no1',
    'share_story',
    'networking_learning',
    'champions',
    'renewal_members',
    'member_pitch_attendance',
    'recruiting_categories',
    'statistics',
    'referral_verification',
    'qr_codes',
    'slide_visibility'
];

runTest('全テーブルの存在確認（19個）', function() use ($db_path, $expected_tables) {
    global $log;
    $db = new PDO('sqlite:' . $db_path);
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $missing = array_diff($expected_tables, $tables);
    if (!empty($missing)) {
        $log[] = "  不足テーブル: " . implode(', ', $missing);
        return "不足テーブル: " . implode(', ', $missing);
    }

    $log[] = "  検出テーブル数: " . count($tables);
    return true;
});

runTest('メンバーデータの存在確認（48名）', function() use ($db_path) {
    global $log;
    $db = new PDO('sqlite:' . $db_path);
    $stmt = $db->query("SELECT COUNT(*) FROM members");
    $count = $stmt->fetchColumn();

    $log[] = "  登録メンバー数: {$count}名";

    if ($count < 48) {
        return "メンバー数が不足: {$count}名（期待: 48名）";
    }

    return true;
});

runTest('スライド削除対応の確認（9ページ）', function() use ($db_path) {
    global $log;
    $db = new PDO('sqlite:' . $db_path);
    $stmt = $db->query("SELECT COUNT(*) FROM slide_visibility WHERE is_visible = 0");
    $count = $stmt->fetchColumn();

    $log[] = "  非表示スライド数: {$count}ページ";

    if ($count < 9) {
        return "非表示スライド数が不足: {$count}ページ（期待: 9ページ）";
    }

    return true;
});

// ==========================================
// 2. 管理画面ファイルの存在確認
// ==========================================
echo "\n■ 2. 管理画面ファイルの存在確認\n";
echo "-------------------------------------------\n";

$admin_files = [
    'admin/index.php',
    'admin/members.php',
    'admin/seating.php',
    'admin/main_presenter.php',
    'admin/speaker_rotation.php',
    'admin/start_dash.php',
    'admin/visitors.php',
    'admin/substitutes.php',
    'admin/new_members.php',
    'admin/weekly_no1.php',
    'admin/share_story.php',
    'admin/networking_pdf.php',
    'admin/champions.php',
    'admin/statistics.php',
    'admin/categories.php',
    'admin/referral_check.php',
    'admin/qr_code.php',
    'admin/slide_visibility.php'
];

runTest('管理画面ファイルの存在確認（18個）', function() use ($admin_files) {
    global $log;
    $missing = [];
    $base_dir = __DIR__;

    foreach ($admin_files as $file) {
        if (!file_exists($base_dir . '/' . $file)) {
            $missing[] = $file;
        }
    }

    if (!empty($missing)) {
        $log[] = "  不足ファイル: " . implode(', ', $missing);
        return "不足ファイル: " . count($missing) . "個";
    }

    $log[] = "  検出ファイル数: " . count($admin_files);
    return true;
});

// ==========================================
// 3. APIファイルの存在確認
// ==========================================
echo "\n■ 3. APIファイルの存在確認\n";
echo "-------------------------------------------\n";

$api_files = [
    'api/members_crud.php',
    'api/seating_crud.php',
    'api/main_presenter_crud.php',
    'api/speaker_rotation_crud.php',
    'api/start_dash_crud.php',
    'api/visitors_crud.php',
    'api/substitutes_crud.php',
    'api/new_members_crud.php',
    'api/weekly_no1_crud.php',
    'api/share_story_crud.php',
    'api/networking_pdf_crud.php',
    'api/champions_crud.php',
    'api/renewal_crud.php',
    'api/member_pitch_crud.php',
    'api/categories_crud.php',
    'api/statistics_crud.php',
    'api/referral_check_crud.php',
    'api/qr_code_crud.php',
    'api/slide_visibility_crud.php'
];

runTest('APIファイルの存在確認（19個）', function() use ($api_files) {
    global $log;
    $missing = [];
    $base_dir = __DIR__;

    foreach ($api_files as $file) {
        if (!file_exists($base_dir . '/' . $file)) {
            $missing[] = $file;
        }
    }

    if (!empty($missing)) {
        $log[] = "  不足ファイル: " . implode(', ', $missing);
        return "不足ファイル: " . count($missing) . "個";
    }

    $log[] = "  検出ファイル数: " . count($api_files);
    return true;
});

// ==========================================
// 4. スライドファイルの存在確認
// ==========================================
echo "\n■ 4. スライドファイルの存在確認\n";
echo "-------------------------------------------\n";

$slide_files = [
    'slides/main_presenter.php',
    'slides/speaker_rotation.php',
    'slides/start_dash.php',
    'slides/visitor_intro.php',
    'slides/visitor_self_intro.php',
    'slides/visitor_feedback.php',
    'slides/visitor_thanks.php',
    'slides/substitutes.php',
    'slides/new_members.php',
    'slides/weekly_no1.php',
    'slides/happy_birthday.php',
    'slides/share_story.php',
    'slides/networking_slides.php',
    'slides/referral_champion.php',
    'slides/value_champion.php',
    'slides/visitor_champion.php',
    'slides/1to1_champion.php',
    'slides/ceu_champion.php',
    'slides/all_champions.php',
    'slides/renewal.php',
    'slides/member_pitch.php',
    'slides/business_breakout.php',
    'slides/recruiting_categories.php',
    'slides/category_survey.php',
    'slides/visitor_stats.php',
    'slides/referral_stats.php',
    'slides/sales_stats.php',
    'slides/weekly_stats.php',
    'slides/referral_verification.php',
    'slides/qr_code.php'
];

runTest('スライドファイルの存在確認（30個）', function() use ($slide_files) {
    global $log;
    $missing = [];
    $base_dir = __DIR__;

    foreach ($slide_files as $file) {
        if (!file_exists($base_dir . '/' . $file)) {
            $missing[] = $file;
        }
    }

    if (!empty($missing)) {
        $log[] = "  不足ファイル: " . implode(', ', $missing);
        return "不足ファイル: " . count($missing) . "個";
    }

    $log[] = "  検出ファイル数: " . count($slide_files);
    return true;
});

// ==========================================
// 5. PHPシンタックスチェック
// ==========================================
echo "\n■ 5. PHPシンタックスチェック\n";
echo "-------------------------------------------\n";

runTest('全管理画面PHPファイルのシンタックスチェック', function() use ($admin_files) {
    global $log;
    $errors = [];
    $base_dir = __DIR__;

    foreach ($admin_files as $file) {
        $filepath = $base_dir . '/' . $file;
        if (file_exists($filepath)) {
            exec("php -l " . escapeshellarg($filepath) . " 2>&1", $output, $return_code);
            if ($return_code !== 0) {
                $errors[] = $file;
            }
        }
    }

    if (!empty($errors)) {
        $log[] = "  シンタックスエラー: " . implode(', ', $errors);
        return "シンタックスエラー: " . count($errors) . "個";
    }

    return true;
});

runTest('全APIファイルのシンタックスチェック', function() use ($api_files) {
    global $log;
    $errors = [];
    $base_dir = __DIR__;

    foreach ($api_files as $file) {
        $filepath = $base_dir . '/' . $file;
        if (file_exists($filepath)) {
            exec("php -l " . escapeshellarg($filepath) . " 2>&1", $output, $return_code);
            if ($return_code !== 0) {
                $errors[] = $file;
            }
        }
    }

    if (!empty($errors)) {
        $log[] = "  シンタックスエラー: " . implode(', ', $errors);
        return "シンタックスエラー: " . count($errors) . "個";
    }

    return true;
});

// ==========================================
// 6. 基本CRUD操作テスト
// ==========================================
echo "\n■ 6. 基本CRUD操作テスト\n";
echo "-------------------------------------------\n";

runTest('メンバーテーブル: SELECT操作', function() use ($db_path) {
    $db = new PDO('sqlite:' . $db_path);
    $stmt = $db->query("SELECT * FROM members LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return "データが取得できません";
    }

    return true;
});

runTest('座席配置テーブル: INSERT/SELECT操作', function() use ($db_path) {
    global $log;
    $db = new PDO('sqlite:' . $db_path);

    // テストデータ挿入
    $stmt = $db->prepare("INSERT INTO seating_arrangement (week_date, table_name, member_id, position) VALUES (?, ?, ?, ?)");
    $test_date = date('Y-m-d');
    $stmt->execute([$test_date, 'TEST_TABLE', 1, 1]);

    // 挿入したデータを取得
    $stmt = $db->prepare("SELECT * FROM seating_arrangement WHERE week_date = ? AND table_name = ?");
    $stmt->execute([$test_date, 'TEST_TABLE']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // テストデータ削除
    $db->exec("DELETE FROM seating_arrangement WHERE week_date = '{$test_date}' AND table_name = 'TEST_TABLE'");

    if (!$result) {
        return "データの挿入・取得に失敗";
    }

    return true;
});

runTest('ビジターテーブル: INSERT/SELECT/DELETE操作', function() use ($db_path) {
    $db = new PDO('sqlite:' . $db_path);

    // テストデータ挿入
    $stmt = $db->prepare("INSERT INTO visitors (week_date, visitor_no, name, company_name, specialty, sponsor, attend_member_id, job_description, referral_request) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $test_date = date('Y-m-d');
    $stmt->execute([$test_date, 999, 'テスト太郎', 'テスト株式会社', 'テスト業', 'テストスポンサー', 1, 'テスト業務', 'テスト紹介']);
    $insert_id = $db->lastInsertId();

    // 取得
    $stmt = $db->prepare("SELECT * FROM visitors WHERE id = ?");
    $stmt->execute([$insert_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // 削除
    $stmt = $db->prepare("DELETE FROM visitors WHERE id = ?");
    $stmt->execute([$insert_id]);

    if (!$result || $result['name'] !== 'テスト太郎') {
        return "CRUD操作に失敗";
    }

    return true;
});

// ==========================================
// 7. ディレクトリ構造チェック
// ==========================================
echo "\n■ 7. ディレクトリ構造チェック\n";
echo "-------------------------------------------\n";

runTest('必須ディレクトリの存在確認', function() {
    global $log;
    $base_dir = __DIR__;
    $required_dirs = [
        'admin',
        'api',
        'slides',
        'data',
        'data/uploads'
    ];

    $missing = [];
    foreach ($required_dirs as $dir) {
        if (!is_dir($base_dir . '/' . $dir)) {
            $missing[] = $dir;
        }
    }

    if (!empty($missing)) {
        $log[] = "  不足ディレクトリ: " . implode(', ', $missing);
        return "不足ディレクトリ: " . count($missing) . "個";
    }

    return true;
});

runTest('アップロードディレクトリの書き込み権限', function() {
    $base_dir = __DIR__;
    $upload_dirs = [
        'data/uploads',
        'data/uploads/pdfs',
        'data/uploads/images'
    ];

    $no_write = [];
    foreach ($upload_dirs as $dir) {
        $path = $base_dir . '/' . $dir;
        if (is_dir($path) && !is_writable($path)) {
            $no_write[] = $dir;
        }
    }

    if (!empty($no_write)) {
        return "書き込み権限なし: " . implode(', ', $no_write);
    }

    return true;
});

// ==========================================
// 8. Python環境チェック
// ==========================================
echo "\n■ 8. Python環境チェック\n";
echo "-------------------------------------------\n";

runTest('Python3の存在確認', function() {
    global $log;
    exec("python3 --version 2>&1", $output, $return_code);

    if ($return_code !== 0) {
        return "Python3が見つかりません";
    }

    $log[] = "  " . $output[0];
    return true;
});

runTest('PyMuPDFライブラリの確認', function() {
    global $log;
    exec("python3 -c 'import fitz; print(fitz.__doc__)' 2>&1", $output, $return_code);

    if ($return_code !== 0) {
        $log[] = "  WARNING: PyMuPDFがインストールされていません（PDF変換機能が使用不可）";
        return 'WARNING';
    }

    $log[] = "  PyMuPDF: インストール済み";
    return true;
});

// ==========================================
// テスト結果サマリー
// ==========================================
echo "\n\n===========================================\n";
echo "テスト結果サマリー\n";
echo "===========================================\n";
echo "✓ PASS: {$test_results['passed']}個\n";
echo "✗ FAIL: {$test_results['failed']}個\n";
echo "⚠ WARNING: {$test_results['warnings']}個\n";
echo "合計: " . ($test_results['passed'] + $test_results['failed'] + $test_results['warnings']) . "個\n";

$total = $test_results['passed'] + $test_results['failed'] + $test_results['warnings'];
$success_rate = $total > 0 ? round(($test_results['passed'] / $total) * 100, 1) : 0;
echo "成功率: {$success_rate}%\n";

// ログ出力
echo "\n===========================================\n";
echo "詳細ログ\n";
echo "===========================================\n";
foreach ($log as $line) {
    echo $line . "\n";
}

// ログファイル保存
$log_file = __DIR__ . '/test_integration_log_' . date('Ymd_His') . '.txt';
file_put_contents($log_file, implode("\n", $log));
echo "\nログファイル保存: {$log_file}\n";

// 終了コード
if ($test_results['failed'] > 0) {
    echo "\n❌ テストに失敗しました。\n";
    exit(1);
} elseif ($test_results['warnings'] > 0) {
    echo "\n⚠️  テストは完了しましたが、警告があります。\n";
    exit(0);
} else {
    echo "\n✅ 全てのテストが成功しました！\n";
    exit(0);
}
