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
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レッスン管理 | Gemini AI学習プラットフォーム</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-v2.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
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
