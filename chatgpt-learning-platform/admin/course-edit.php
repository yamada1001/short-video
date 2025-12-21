<?php
/**
 * コース編集・新規作成画面
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者チェック
requireAdmin();

$isEdit = isset($_GET['id']);
$courseId = $isEdit ? (int)$_GET['id'] : null;
$course = null;

// 編集モードの場合、既存データを取得
if ($isEdit) {
    $courseSql = "SELECT * FROM courses WHERE id = ?";
    $course = db()->fetchOne($courseSql, [$courseId]);

    if (!$course) {
        header('Location: ' . APP_URL . '/admin/courses.php');
        exit;
    }
}

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token']);

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $difficulty = $_POST['difficulty'] ?? 'beginner';
    $isFree = isset($_POST['is_free']) ? 1 : 0;
    $orderNum = (int)($_POST['order_num'] ?? 0);
    $thumbnailUrl = trim($_POST['thumbnail_url'] ?? '');

    // バリデーション
    $errors = [];
    if (empty($title)) {
        $errors[] = 'コース名を入力してください';
    }
    if (empty($description)) {
        $errors[] = '説明を入力してください';
    }
    if (!in_array($difficulty, ['beginner', 'intermediate', 'advanced'])) {
        $errors[] = '難易度が不正です';
    }

    if (empty($errors)) {
        if ($isEdit) {
            // 更新
            $updateSql = "UPDATE courses SET
                          title = ?,
                          description = ?,
                          difficulty = ?,
                          is_free = ?,
                          order_num = ?,
                          thumbnail_url = ?
                          WHERE id = ?";
            $params = [$title, $description, $difficulty, $isFree, $orderNum, $thumbnailUrl, $courseId];

            if (db()->execute($updateSql, $params)) {
                $_SESSION['success_message'] = 'コースを更新しました';
                header('Location: ' . APP_URL . '/admin/courses.php');
                exit;
            } else {
                $errors[] = 'コースの更新に失敗しました';
            }
        } else {
            // 新規作成
            $insertSql = "INSERT INTO courses (title, description, difficulty, is_free, order_num, thumbnail_url)
                          VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$title, $description, $difficulty, $isFree, $orderNum, $thumbnailUrl];

            if (db()->execute($insertSql, $params)) {
                $_SESSION['success_message'] = 'コースを作成しました';
                header('Location: ' . APP_URL . '/admin/courses.php');
                exit;
            } else {
                $errors[] = 'コースの作成に失敗しました';
            }
        }
    }
}

// フォーム初期値
$formData = $course ?? [
    'title' => '',
    'description' => '',
    'difficulty' => 'beginner',
    'is_free' => 1,
    'order_num' => 0,
    'thumbnail_url' => ''
];

// POSTデータがあればそちらを優先
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'difficulty' => $_POST['difficulty'] ?? 'beginner',
        'is_free' => isset($_POST['is_free']) ? 1 : 0,
        'order_num' => $_POST['order_num'] ?? 0,
        'thumbnail_url' => $_POST['thumbnail_url'] ?? ''
    ];
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
    <title><?= $isEdit ? 'コース編集' : 'コース新規作成' ?> | Gemini AI学習プラットフォーム</title>
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
                <li><a href="<?= APP_URL ?>/admin/courses.php" class="active">コース管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/lessons.php">レッスン管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/users.php">ユーザー管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/assignments.php">課題管理</a></li>
                <li><a href="<?= APP_URL ?>/dashboard.php">サイトに戻る</a></li>
                <li><a href="<?= APP_URL ?>/logout.php">ログアウト</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <h1><?= $isEdit ? 'コース編集' : 'コース新規作成' ?></h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                    <div class="form-group">
                        <label class="form-label" for="title">コース名 *</label>
                        <input type="text" id="title" name="title" class="form-input" value="<?= h($formData['title']) ?>" required>
                        <div class="form-help">例: Gemini AI入門、プロンプトエンジニアリング基礎</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">説明 *</label>
                        <textarea id="description" name="description" class="form-textarea" required><?= h($formData['description']) ?></textarea>
                        <div class="form-help">コースの内容や学習目標を説明してください</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="difficulty">難易度 *</label>
                        <select id="difficulty" name="difficulty" class="form-select" required>
                            <option value="beginner" <?= $formData['difficulty'] === 'beginner' ? 'selected' : '' ?>>初級</option>
                            <option value="intermediate" <?= $formData['difficulty'] === 'intermediate' ? 'selected' : '' ?>>中級</option>
                            <option value="advanced" <?= $formData['difficulty'] === 'advanced' ? 'selected' : '' ?>>上級</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="order_num">表示順序</label>
                        <input type="number" id="order_num" name="order_num" class="form-input" value="<?= h($formData['order_num']) ?>" min="0">
                        <div class="form-help">小さい数字ほど上に表示されます（0が最上位）</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="thumbnail_url">サムネイル画像URL</label>
                        <input type="url" id="thumbnail_url" name="thumbnail_url" class="form-input" value="<?= h($formData['thumbnail_url']) ?>" placeholder="https://example.com/image.jpg">
                        <div class="form-help">コースカードに表示される画像のURL（省略可）</div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_free" name="is_free" value="1" <?= $formData['is_free'] ? 'checked' : '' ?>>
                            <label for="is_free">無料コース（チェックを外すとプレミアム限定）</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? '更新する' : '作成する' ?>
                        </button>
                        <a href="<?= APP_URL ?>/admin/courses.php" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
