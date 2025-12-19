<?php
/**
 * レッスン編集・新規作成画面
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者チェック
requireAdmin();

$isEdit = isset($_GET['id']);
$lessonId = $isEdit ? (int)$_GET['id'] : null;
$lesson = null;

// コース一覧を取得
$coursesSql = "SELECT id, title FROM courses ORDER BY order_num, id";
$courses = db()->fetchAll($coursesSql);

// 編集モードの場合、既存データを取得
if ($isEdit) {
    $lessonSql = "SELECT * FROM lessons WHERE id = ?";
    $lesson = db()->fetchOne($lessonSql, [$lessonId]);

    if (!$lesson) {
        header('Location: ' . APP_URL . '/admin/lessons.php');
        exit;
    }

    // JSONコンテンツをデコード
    $lesson['content_decoded'] = json_decode($lesson['content'], true);
}

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token']);

    $courseId = (int)($_POST['course_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $type = $_POST['type'] ?? 'slide';
    $orderNum = (int)($_POST['order_num'] ?? 0);
    $estimatedMinutes = (int)($_POST['estimated_minutes'] ?? 5);

    // コンテンツのJSON構築（タイプ別）
    $content = [];
    switch ($type) {
        case 'slide':
            $slideCount = (int)($_POST['slide_count'] ?? 1);
            $slides = [];
            for ($i = 1; $i <= $slideCount; $i++) {
                $slides[] = [
                    'title' => $_POST["slide_{$i}_title"] ?? '',
                    'content' => $_POST["slide_{$i}_content"] ?? ''
                ];
            }
            $content = ['slides' => $slides];
            break;

        case 'editor':
            $content = [
                'instruction' => $_POST['instruction'] ?? '',
                'example_prompt' => $_POST['example_prompt'] ?? '',
                'hint' => $_POST['hint'] ?? ''
            ];
            break;

        case 'quiz':
            $questionCount = (int)($_POST['question_count'] ?? 1);
            $questions = [];
            for ($i = 1; $i <= $questionCount; $i++) {
                $questionType = $_POST["question_{$i}_type"] ?? 'multiple_choice';
                $question = [
                    'question' => $_POST["question_{$i}_text"] ?? '',
                    'type' => $questionType,
                    'explanation' => $_POST["question_{$i}_explanation"] ?? ''
                ];

                if ($questionType === 'multiple_choice') {
                    $question['options'] = array_filter([
                        $_POST["question_{$i}_option_1"] ?? '',
                        $_POST["question_{$i}_option_2"] ?? '',
                        $_POST["question_{$i}_option_3"] ?? '',
                        $_POST["question_{$i}_option_4"] ?? ''
                    ]);
                    $question['correct_answer'] = (int)($_POST["question_{$i}_correct"] ?? 1);
                } else {
                    $question['keywords'] = array_filter(array_map('trim', explode(',', $_POST["question_{$i}_keywords"] ?? '')));
                }

                $questions[] = $question;
            }
            $content = [
                'questions' => $questions,
                'pass_score' => (int)($_POST['pass_score'] ?? 80)
            ];
            break;

        case 'assignment':
            $content = [
                'instruction' => $_POST['assignment_instruction'] ?? '',
                'requirements' => array_filter(array_map('trim', explode("\n", $_POST['assignment_requirements'] ?? ''))),
                'evaluation_criteria' => $_POST['evaluation_criteria'] ?? ''
            ];
            break;
    }

    $contentJson = json_encode($content, JSON_UNESCAPED_UNICODE);

    // バリデーション
    $errors = [];
    if ($courseId === 0) {
        $errors[] = 'コースを選択してください';
    }
    if (empty($title)) {
        $errors[] = 'レッスン名を入力してください';
    }
    if (!in_array($type, ['slide', 'editor', 'quiz', 'assignment'])) {
        $errors[] = 'レッスンタイプが不正です';
    }

    if (empty($errors)) {
        if ($isEdit) {
            // 更新
            $updateSql = "UPDATE lessons SET
                          course_id = ?,
                          title = ?,
                          type = ?,
                          content = ?,
                          order_num = ?,
                          estimated_minutes = ?
                          WHERE id = ?";
            $params = [$courseId, $title, $type, $contentJson, $orderNum, $estimatedMinutes, $lessonId];

            if (db()->execute($updateSql, $params)) {
                $_SESSION['success_message'] = 'レッスンを更新しました';
                header('Location: ' . APP_URL . '/admin/lessons.php');
                exit;
            } else {
                $errors[] = 'レッスンの更新に失敗しました';
            }
        } else {
            // 新規作成
            $insertSql = "INSERT INTO lessons (course_id, title, type, content, order_num, estimated_minutes)
                          VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$courseId, $title, $type, $contentJson, $orderNum, $estimatedMinutes];

            if (db()->execute($insertSql, $params)) {
                $_SESSION['success_message'] = 'レッスンを作成しました';
                header('Location: ' . APP_URL . '/admin/lessons.php');
                exit;
            } else {
                $errors[] = 'レッスンの作成に失敗しました';
            }
        }
    }
}

// フォーム初期値
$formData = $lesson ?? [
    'course_id' => '',
    'title' => '',
    'type' => 'slide',
    'order_num' => 0,
    'estimated_minutes' => 5,
    'content_decoded' => []
];

// POSTデータがあればそちらを優先
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['course_id'] = $_POST['course_id'] ?? '';
    $formData['title'] = $_POST['title'] ?? '';
    $formData['type'] = $_POST['type'] ?? 'slide';
    $formData['order_num'] = $_POST['order_num'] ?? 0;
    $formData['estimated_minutes'] = $_POST['estimated_minutes'] ?? 5;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'レッスン編集' : 'レッスン新規作成' ?> | ChatGPT学習プラットフォーム</title>
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
        .form-container {
            max-width: 900px;
            background: white;
            padding: 40px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 15px;
            transition: var(--transition);
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91, 127, 255, 0.1);
        }
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-help {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        .content-editor {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 2px solid var(--gray-200);
        }
        .content-editor h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--text-primary);
        }
        .slide-item, .question-item {
            background: var(--bg-page);
            padding: 20px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
        }
        .slide-item h4, .question-item h4 {
            font-size: 15px;
            margin-bottom: 12px;
            color: var(--primary);
        }
        .btn-add {
            margin-top: 12px;
            padding: 8px 16px;
            background: var(--success);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 14px;
        }
        .btn-add:hover {
            background: var(--success-dark);
        }
        .hidden {
            display: none;
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
            <h1><?= $isEdit ? 'レッスン編集' : 'レッスン新規作成' ?></h1>

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
                <form method="POST" id="lessonForm">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                    <div class="form-group">
                        <label class="form-label" for="course_id">コース *</label>
                        <select id="course_id" name="course_id" class="form-select" required>
                            <option value="">選択してください</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>" <?= $formData['course_id'] == $course['id'] ? 'selected' : '' ?>>
                                    <?= h($course['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="title">レッスン名 *</label>
                        <input type="text" id="title" name="title" class="form-input" value="<?= h($formData['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="type">レッスンタイプ *</label>
                        <select id="type" name="type" class="form-select" required onchange="toggleContentEditor()">
                            <option value="slide" <?= $formData['type'] === 'slide' ? 'selected' : '' ?>>スライド</option>
                            <option value="editor" <?= $formData['type'] === 'editor' ? 'selected' : '' ?>>エディタ</option>
                            <option value="quiz" <?= $formData['type'] === 'quiz' ? 'selected' : '' ?>>クイズ</option>
                            <option value="assignment" <?= $formData['type'] === 'assignment' ? 'selected' : '' ?>>課題</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="order_num">表示順序</label>
                        <input type="number" id="order_num" name="order_num" class="form-input" value="<?= h($formData['order_num']) ?>" min="0">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="estimated_minutes">所要時間（分）</label>
                        <input type="number" id="estimated_minutes" name="estimated_minutes" class="form-input" value="<?= h($formData['estimated_minutes']) ?>" min="1">
                    </div>

                    <!-- コンテンツエディタ（タイプ別） -->
                    <div class="content-editor">
                        <h3>コンテンツ設定</h3>

                        <!-- スライド用 -->
                        <div id="editor-slide" class="type-editor hidden">
                            <input type="hidden" name="slide_count" id="slide_count" value="<?= count($formData['content_decoded']['slides'] ?? [1]) ?>">
                            <div id="slides-container">
                                <?php
                                $slides = $formData['content_decoded']['slides'] ?? [['title' => '', 'content' => '']];
                                foreach ($slides as $i => $slide):
                                    $num = $i + 1;
                                ?>
                                    <div class="slide-item">
                                        <h4>スライド <?= $num ?></h4>
                                        <div class="form-group">
                                            <label class="form-label">タイトル</label>
                                            <input type="text" name="slide_<?= $num ?>_title" class="form-input" value="<?= h($slide['title']) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">内容</label>
                                            <textarea name="slide_<?= $num ?>_content" class="form-textarea"><?= h($slide['content']) ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn-add" onclick="addSlide()">+ スライド追加</button>
                        </div>

                        <!-- エディタ用 -->
                        <div id="editor-editor" class="type-editor hidden">
                            <div class="form-group">
                                <label class="form-label">説明文</label>
                                <textarea name="instruction" class="form-textarea"><?= h($formData['content_decoded']['instruction'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">サンプルプロンプト</label>
                                <textarea name="example_prompt" class="form-textarea"><?= h($formData['content_decoded']['example_prompt'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">ヒント</label>
                                <textarea name="hint" class="form-textarea"><?= h($formData['content_decoded']['hint'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- クイズ用 -->
                        <div id="editor-quiz" class="type-editor hidden">
                            <div class="form-group">
                                <label class="form-label">合格点（%）</label>
                                <input type="number" name="pass_score" class="form-input" value="<?= h($formData['content_decoded']['pass_score'] ?? 80) ?>" min="0" max="100">
                            </div>
                            <input type="hidden" name="question_count" id="question_count" value="<?= count($formData['content_decoded']['questions'] ?? []) ?: 1 ?>">
                            <div id="questions-container">
                                <?php
                                $questions = $formData['content_decoded']['questions'] ?? [['question' => '', 'type' => 'multiple_choice']];
                                foreach ($questions as $i => $question):
                                    $num = $i + 1;
                                ?>
                                    <div class="question-item">
                                        <h4>問題 <?= $num ?></h4>
                                        <div class="form-group">
                                            <label class="form-label">問題文</label>
                                            <textarea name="question_<?= $num ?>_text" class="form-textarea"><?= h($question['question']) ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">タイプ</label>
                                            <select name="question_<?= $num ?>_type" class="form-select">
                                                <option value="multiple_choice" <?= ($question['type'] ?? 'multiple_choice') === 'multiple_choice' ? 'selected' : '' ?>>選択式</option>
                                                <option value="text" <?= ($question['type'] ?? '') === 'text' ? 'selected' : '' ?>>記述式</option>
                                            </select>
                                        </div>
                                        <?php if (($question['type'] ?? 'multiple_choice') === 'multiple_choice'): ?>
                                            <div class="form-group">
                                                <label class="form-label">選択肢1</label>
                                                <input type="text" name="question_<?= $num ?>_option_1" class="form-input" value="<?= h($question['options'][0] ?? '') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">選択肢2</label>
                                                <input type="text" name="question_<?= $num ?>_option_2" class="form-input" value="<?= h($question['options'][1] ?? '') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">選択肢3</label>
                                                <input type="text" name="question_<?= $num ?>_option_3" class="form-input" value="<?= h($question['options'][2] ?? '') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">選択肢4</label>
                                                <input type="text" name="question_<?= $num ?>_option_4" class="form-input" value="<?= h($question['options'][3] ?? '') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">正解（1〜4）</label>
                                                <input type="number" name="question_<?= $num ?>_correct" class="form-input" value="<?= h($question['correct_answer'] ?? 1) ?>" min="1" max="4">
                                            </div>
                                        <?php else: ?>
                                            <div class="form-group">
                                                <label class="form-label">キーワード（カンマ区切り）</label>
                                                <input type="text" name="question_<?= $num ?>_keywords" class="form-input" value="<?= h(implode(',', $question['keywords'] ?? [])) ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="form-label">解説</label>
                                            <textarea name="question_<?= $num ?>_explanation" class="form-textarea"><?= h($question['explanation'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn-add" onclick="addQuestion()">+ 問題追加</button>
                        </div>

                        <!-- 課題用 -->
                        <div id="editor-assignment" class="type-editor hidden">
                            <div class="form-group">
                                <label class="form-label">課題説明</label>
                                <textarea name="assignment_instruction" class="form-textarea"><?= h($formData['content_decoded']['instruction'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">要件（1行1項目）</label>
                                <textarea name="assignment_requirements" class="form-textarea"><?= h(implode("\n", $formData['content_decoded']['requirements'] ?? [])) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">評価基準</label>
                                <textarea name="evaluation_criteria" class="form-textarea"><?= h($formData['content_decoded']['evaluation_criteria'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? '更新する' : '作成する' ?>
                        </button>
                        <a href="<?= APP_URL ?>/admin/lessons.php" class="btn btn-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // レッスンタイプに応じたコンテンツエディタの切り替え
        function toggleContentEditor() {
            const type = document.getElementById('type').value;
            document.querySelectorAll('.type-editor').forEach(el => el.classList.add('hidden'));
            document.getElementById('editor-' + type).classList.remove('hidden');
        }

        // スライド追加
        function addSlide() {
            const container = document.getElementById('slides-container');
            const countInput = document.getElementById('slide_count');
            const count = parseInt(countInput.value) + 1;
            countInput.value = count;

            const slideHtml = `
                <div class="slide-item">
                    <h4>スライド ${count}</h4>
                    <div class="form-group">
                        <label class="form-label">タイトル</label>
                        <input type="text" name="slide_${count}_title" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">内容</label>
                        <textarea name="slide_${count}_content" class="form-textarea"></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', slideHtml);
        }

        // 問題追加（簡易版）
        function addQuestion() {
            const container = document.getElementById('questions-container');
            const countInput = document.getElementById('question_count');
            const count = parseInt(countInput.value) + 1;
            countInput.value = count;

            const questionHtml = `
                <div class="question-item">
                    <h4>問題 ${count}</h4>
                    <div class="form-group">
                        <label class="form-label">問題文</label>
                        <textarea name="question_${count}_text" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">タイプ</label>
                        <select name="question_${count}_type" class="form-select">
                            <option value="multiple_choice">選択式</option>
                            <option value="text">記述式</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">選択肢1</label>
                        <input type="text" name="question_${count}_option_1" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">選択肢2</label>
                        <input type="text" name="question_${count}_option_2" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">選択肢3</label>
                        <input type="text" name="question_${count}_option_3" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">選択肢4</label>
                        <input type="text" name="question_${count}_option_4" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">正解（1〜4）</label>
                        <input type="number" name="question_${count}_correct" class="form-input" value="1" min="1" max="4">
                    </div>
                    <div class="form-group">
                        <label class="form-label">解説</label>
                        <textarea name="question_${count}_explanation" class="form-textarea"></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', questionHtml);
        }

        // 初期表示
        toggleContentEditor();
    </script>
</body>
</html>
