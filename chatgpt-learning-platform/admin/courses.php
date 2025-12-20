<?php
/**
 * コース管理画面
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者チェック
requireAdmin();

// 削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    verifyCsrfToken($_POST['csrf_token']);

    $deleteId = (int)$_POST['delete_id'];

    // コースに紐づくレッスンを削除
    $deleteLessonsSql = "DELETE FROM lessons WHERE course_id = ?";
    db()->execute($deleteLessonsSql, [$deleteId]);

    // コースを削除
    $deleteCourseSql = "DELETE FROM courses WHERE id = ?";
    if (db()->execute($deleteCourseSql, [$deleteId])) {
        $successMessage = 'コースを削除しました';
    } else {
        $errorMessage = 'コースの削除に失敗しました';
    }
}

// コース一覧を取得
$coursesSql = "SELECT c.*,
               COUNT(l.id) as lesson_count
               FROM courses c
               LEFT JOIN lessons l ON c.id = l.course_id
               GROUP BY c.id
               ORDER BY c.order_num, c.id";
$courses = db()->fetchAll($coursesSql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コース管理 | Gemini AI学習プラットフォーム</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-v2.css">
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
        .course-table {
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
        .course-title {
            font-weight: 600;
            color: var(--text-primary);
        }
        .course-difficulty {
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
        }
        .difficulty-beginner {
            background: #e3f2fd;
            color: #1976d2;
        }
        .difficulty-intermediate {
            background: #fff3e0;
            color: #f57c00;
        }
        .difficulty-advanced {
            background: #fce4ec;
            color: #c2185b;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
        }
        .badge-free {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .badge-premium {
            background: #fff3e0;
            color: #f57c00;
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
                <li><a href="<?= APP_URL ?>/admin/courses.php" class="active">コース管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/lessons.php">レッスン管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/users.php">ユーザー管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/assignments.php">課題管理</a></li>
                <li><a href="<?= APP_URL ?>/dashboard.php">サイトに戻る</a></li>
                <li><a href="<?= APP_URL ?>/logout.php">ログアウト</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>コース管理</h1>
                <a href="<?= APP_URL ?>/admin/course-edit.php" class="btn btn-primary">+ 新規コース追加</a>
            </div>

            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?= h($successMessage) ?></div>
            <?php endif; ?>

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-error"><?= h($errorMessage) ?></div>
            <?php endif; ?>

            <div class="course-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">順序</th>
                            <th>コース名</th>
                            <th style="width: 120px;">難易度</th>
                            <th style="width: 100px;">公開範囲</th>
                            <th style="width: 100px;">レッスン数</th>
                            <th style="width: 180px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                    コースがありません。新規作成してください。
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= $course['order_num'] ?></td>
                                    <td>
                                        <div class="course-title"><?= h($course['title']) ?></div>
                                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                                            <?= h(mb_substr($course['description'], 0, 60)) ?><?= mb_strlen($course['description']) > 60 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $difficultyClass = 'difficulty-' . $course['difficulty'];
                                        $difficultyText = [
                                            'beginner' => '初級',
                                            'intermediate' => '中級',
                                            'advanced' => '上級'
                                        ][$course['difficulty']];
                                        ?>
                                        <span class="course-difficulty <?= $difficultyClass ?>">
                                            <?= $difficultyText ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($course['is_free']): ?>
                                            <span class="badge badge-free">無料</span>
                                        <?php else: ?>
                                            <span class="badge badge-premium">プレミアム</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;"><?= $course['lesson_count'] ?>個</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= APP_URL ?>/admin/course-edit.php?id=<?= $course['id'] ?>" class="btn-edit">編集</a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('このコースと関連するレッスンを削除しますか？');">
                                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                                <input type="hidden" name="delete_id" value="<?= $course['id'] ?>">
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
