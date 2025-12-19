<?php
/**
 * レッスン管理画面
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者チェック
requireAdmin();

// コース一覧を取得（フィルター用）
$coursesSql = "SELECT id, title FROM courses ORDER BY order_num, id";
$courses = db()->fetchAll($coursesSql);

// フィルター処理
$filterCourseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    verifyCsrfToken($_POST['csrf_token']);

    $deleteId = (int)$_POST['delete_id'];

    // レッスンを削除
    $deleteLessonSql = "DELETE FROM lessons WHERE id = ?";
    if (db()->execute($deleteLessonSql, [$deleteId])) {
        $successMessage = 'レッスンを削除しました';
    } else {
        $errorMessage = 'レッスンの削除に失敗しました';
    }
}

// レッスン一覧を取得
if ($filterCourseId) {
    $lessonsSql = "SELECT l.*, c.title as course_title
                   FROM lessons l
                   INNER JOIN courses c ON l.course_id = c.id
                   WHERE l.course_id = ?
                   ORDER BY l.order_num, l.id";
    $lessons = db()->fetchAll($lessonsSql, [$filterCourseId]);
} else {
    $lessonsSql = "SELECT l.*, c.title as course_title
                   FROM lessons l
                   INNER JOIN courses c ON l.course_id = c.id
                   ORDER BY c.order_num, l.order_num, l.id";
    $lessons = db()->fetchAll($lessonsSql);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レッスン管理 | Gemini AI学習プラットフォーム</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            background: var(--gray-900);
            color: white;
            padding: 30px 20px;
        }
        .admin-sidebar h2 {
            font-size: 18px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--gray-700);
        }
        .admin-nav {
            list-style: none;
        }
        .admin-nav li {
            margin-bottom: 8px;
        }
        .admin-nav a {
            display: block;
            padding: 12px 16px;
            color: var(--gray-400);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }
        .admin-nav a:hover, .admin-nav a.active {
            background: var(--gray-800);
            color: white;
        }
        .admin-main {
            flex: 1;
            padding: 40px;
            background: var(--bg-page);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .filter-bar label {
            font-weight: 600;
        }
        .filter-bar select {
            padding: 8px 12px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
        }
        .lesson-table {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        th {
            background: var(--bg-page);
            font-weight: 600;
            font-size: 14px;
        }
        .lesson-title {
            font-weight: 600;
            color: var(--text-primary);
        }
        .lesson-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
        }
        .type-slide {
            background: #e3f2fd;
            color: #1976d2;
        }
        .type-editor {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        .type-quiz {
            background: #fff3e0;
            color: #f57c00;
        }
        .type-assignment {
            background: #fce4ec;
            color: #c2185b;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-edit {
            padding: 6px 12px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius-sm);
            font-size: 14px;
            transition: var(--transition);
        }
        .btn-edit:hover {
            background: var(--primary-dark);
        }
        .btn-delete {
            padding: 6px 12px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-delete:hover {
            background: var(--danger-dark);
        }
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>管理メニュー</h2>
            <ul class="admin-nav">
                <li><a href="<?= APP_URL ?>/admin/index.php">ダッシュボード</a></li>
                <li><a href="<?= APP_URL ?>/admin/courses.php">コース管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/lessons.php" class="active">レッスン管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/users.php">ユーザー管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/assignments.php">課題管理</a></li>
                <li><a href="<?= APP_URL ?>/dashboard.php">サイトに戻る</a></li>
                <li><a href="<?= APP_URL ?>/logout.php">ログアウト</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>レッスン管理</h1>
                <a href="<?= APP_URL ?>/admin/lesson-edit.php" class="btn btn-primary">+ 新規レッスン追加</a>
            </div>

            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?= h($successMessage) ?></div>
            <?php endif; ?>

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error"><?= h($errorMessage) ?></div>
            <?php endif; ?>

            <div class="filter-bar">
                <label for="course_filter">コース絞り込み:</label>
                <select id="course_filter" onchange="location.href='?course_id=' + this.value">
                    <option value="">すべてのコース</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id'] ?>" <?= $filterCourseId === $course['id'] ? 'selected' : '' ?>>
                            <?= h($course['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="lesson-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">順序</th>
                            <th style="width: 200px;">コース</th>
                            <th>レッスン名</th>
                            <th style="width: 100px;">タイプ</th>
                            <th style="width: 80px;">時間</th>
                            <th style="width: 180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lessons)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                    レッスンがありません。新規作成してください。
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lessons as $lesson): ?>
                                <tr>
                                    <td><?= $lesson['order_num'] ?></td>
                                    <td>
                                        <div style="font-size: 13px; color: var(--text-muted);">
                                            <?= h($lesson['course_title']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="lesson-title"><?= h($lesson['title']) ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $typeClass = 'type-' . $lesson['type'];
                                        $typeText = [
                                            'slide' => 'スライド',
                                            'editor' => 'エディタ',
                                            'quiz' => 'クイズ',
                                            'assignment' => '課題'
                                        ][$lesson['type']];
                                        ?>
                                        <span class="lesson-type <?= $typeClass ?>">
                                            <?= $typeText ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;"><?= $lesson['estimated_minutes'] ?>分</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= APP_URL ?>/admin/lesson-edit.php?id=<?= $lesson['id'] ?>" class="btn-edit">編集</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('このレッスンを削除しますか？');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                                <input type="hidden" name="delete_id" value="<?= $lesson['id'] ?>">
                                                <button type="submit" class="btn-delete">削除</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
