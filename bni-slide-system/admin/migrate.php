<?php
/**
 * BNI Slide System - Database Migration Tool
 * ワンクリックでデータベースマイグレーション実行
 */

require_once __DIR__ . '/../includes/session_auth.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    header('Location: ../login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    die('<h1>アクセス拒否</h1><p>このページは管理者のみアクセス可能です。</p><a href="../index.php">ホームに戻る</a>');
}

$message = '';
$error = '';
$migrationResults = [];

// マイグレーション実行
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_migration'])) {

    // CSRF保護
    require_once __DIR__ . '/../includes/csrf.php';
    try {
        requireCSRFToken();
    } catch (Exception $e) {
        $error = 'CSRFトークンエラー: ' . $e->getMessage();
    }

    if (!$error) {
        $dbPath = __DIR__ . '/../database/bni_slide.db';

        // マイグレーションSQLファイル
        $migrations = [
            'schema_phase6_update.sql' => 'VP統計情報テーブル更新',
            'schema_member_photos.sql' => 'メンバー写真テーブル作成',
            'test_data_member_photos.sql' => 'メンバー写真テストデータ'
        ];

        foreach ($migrations as $file => $description) {
            $sqlPath = __DIR__ . '/../database/' . $file;

            if (!file_exists($sqlPath)) {
                $migrationResults[] = [
                    'file' => $file,
                    'description' => $description,
                    'status' => 'skip',
                    'message' => 'ファイルが存在しません'
                ];
                continue;
            }

            try {
                $db = new SQLite3($dbPath);

                // 特別処理: schema_phase6_update.sql
                if ($file === 'schema_phase6_update.sql') {
                    // weekly_presentersテーブルにreferral_targetカラムが存在するかチェック
                    $result = $db->query("PRAGMA table_info(weekly_presenters)");
                    $columnExists = false;

                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        if ($row['name'] === 'referral_target') {
                            $columnExists = true;
                            break;
                        }
                    }

                    if ($columnExists) {
                        $db->close();
                        $migrationResults[] = [
                            'file' => $file,
                            'description' => $description,
                            'status' => 'skip',
                            'message' => 'referral_targetカラムは既に存在します（スキップ）'
                        ];
                        continue;
                    }

                    // カラムが存在しない場合のみ追加
                    $db->exec('BEGIN TRANSACTION');
                    $db->exec('ALTER TABLE weekly_presenters ADD COLUMN referral_target TEXT');
                    $db->exec('COMMIT');
                    $db->close();

                    $migrationResults[] = [
                        'file' => $file,
                        'description' => $description,
                        'status' => 'success',
                        'message' => 'referral_targetカラムを追加しました'
                    ];
                    continue;
                }

                // その他のマイグレーション
                $sql = file_get_contents($sqlPath);
                $db->exec('BEGIN TRANSACTION');

                // SQLを実行
                $result = $db->exec($sql);

                if ($result === false) {
                    throw new Exception($db->lastErrorMsg());
                }

                $db->exec('COMMIT');
                $db->close();

                $migrationResults[] = [
                    'file' => $file,
                    'description' => $description,
                    'status' => 'success',
                    'message' => '実行成功'
                ];

            } catch (Exception $e) {
                if (isset($db)) {
                    $db->exec('ROLLBACK');
                    $db->close();
                }

                // 既に存在するエラーはスキップとして扱う
                if (strpos($e->getMessage(), 'already exists') !== false ||
                    strpos($e->getMessage(), 'duplicate') !== false) {
                    $migrationResults[] = [
                        'file' => $file,
                        'description' => $description,
                        'status' => 'skip',
                        'message' => '既に存在します（スキップ）'
                    ];
                } else {
                    $migrationResults[] = [
                        'file' => $file,
                        'description' => $description,
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }

        $message = 'マイグレーション実行完了';
    }
}

// CSRF トークン生成
require_once __DIR__ . '/../includes/csrf.php';
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>データベースマイグレーション | BNI Slide System</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Common CSS -->
    <link rel="stylesheet" href="../assets/css/common.css">

    <style>
        .migration-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .migration-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .migration-header h1 {
            color: #C8102E;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .migration-description {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .migration-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .migration-list li {
            padding: 10px;
            margin: 5px 0;
            background: #fafafa;
            border-left: 4px solid #C8102E;
        }

        .btn-migrate {
            background: #C8102E;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 30px auto;
        }

        .btn-migrate:hover {
            background: #a00a24;
        }

        .btn-migrate:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .results-container {
            margin-top: 30px;
        }

        .result-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 4px solid;
        }

        .result-item.success {
            background: #d4edda;
            border-color: #28a745;
        }

        .result-item.error {
            background: #f8d7da;
            border-color: #dc3545;
        }

        .result-item.skip {
            background: #fff3cd;
            border-color: #ffc107;
        }

        .result-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .result-message {
            font-size: 14px;
            color: #666;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #C8102E;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .warning-box strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="migration-container">
        <div class="migration-header">
            <h1><i class="fas fa-database"></i> データベースマイグレーション</h1>
            <p>Phase 10実装で追加された新機能に必要なデータベース更新を実行します</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="migration-description">
            <h3>実行されるマイグレーション</h3>
            <ul class="migration-list">
                <li><strong>VP統計情報テーブル更新</strong>: スピーカーローテーションの「ご紹介してほしい方」フィールド追加</li>
                <li><strong>メンバー写真テーブル作成</strong>: メンバー紹介スライド用の写真管理テーブル作成</li>
                <li><strong>テストデータ投入</strong>: 14名のメンバー写真サンプルデータ投入</li>
            </ul>
        </div>

        <div class="warning-box">
            <strong><i class="fas fa-exclamation-triangle"></i> 注意:</strong>
            このマイグレーションは複数回実行しても安全ですが、既にデータが存在する場合はエラーが表示される場合があります。
        </div>

        <?php if (!empty($migrationResults)): ?>
            <div class="results-container">
                <h3>実行結果</h3>
                <?php foreach ($migrationResults as $result): ?>
                    <div class="result-item <?= $result['status'] ?>">
                        <div class="result-title">
                            <?php if ($result['status'] === 'success'): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php elseif ($result['status'] === 'error'): ?>
                                <i class="fas fa-times-circle"></i>
                            <?php else: ?>
                                <i class="fas fa-info-circle"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($result['description']) ?>
                        </div>
                        <div class="result-message">
                            <?= htmlspecialchars($result['message']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button type="submit" name="execute_migration" class="btn-migrate">
                <i class="fas fa-play-circle"></i> マイグレーションを実行
            </button>
        </form>

        <div class="back-link">
            <a href="slide.php"><i class="fas fa-arrow-left"></i> スライド画面に戻る</a> |
            <a href="sitemap.php"><i class="fas fa-sitemap"></i> サイトマップ</a>
        </div>
    </div>
</body>
</html>
