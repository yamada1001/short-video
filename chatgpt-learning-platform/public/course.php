<?php
/**
 * コース詳細ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();
$courseId = $_GET['id'] ?? null;

if (!$courseId) {
    redirect(APP_URL . '/dashboard.php');
}

// コース情報を取得
$sql = "SELECT * FROM courses WHERE id = ?";
$course = db()->fetchOne($sql, [$courseId]);

if (!$course) {
    redirect(APP_URL . '/dashboard.php');
}

// レッスン一覧を取得
$lessonsSql = "SELECT l.*,
               COALESCE(up.status, 'not_started') as progress_status,
               up.completed_at
               FROM lessons l
               LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
               WHERE l.course_id = ?
               ORDER BY l.order_num";
$lessons = db()->fetchAll($lessonsSql, [$user['id'], $courseId]);

// 進捗率を計算
$totalLessons = count($lessons);
$completedLessons = 0;
foreach ($lessons as $lesson) {
    if ($lesson['progress_status'] === 'completed') {
        $completedLessons++;
    }
}
$progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

// コースごとのベネフィット情報（将来的にはDBから取得）
$courseBenefits = [
    1 => [ // 初めてのプロンプトエンジニアリング
        'skills' => [
            '効果的なプロンプトの書き方',
            'AI との対話の基本原則',
            '具体的な指示の出し方',
            'コンテキストの与え方',
            '結果の改善方法'
        ],
        'realworld' => [
            [
                'title' => '業務メールの下書き作成',
                'description' => '「顧客への謝罪メールを書いてください」といった具体的な指示で、適切なビジネスメールを自動生成'
            ],
            [
                'title' => 'ブログ記事のアイデア出し',
                'description' => 'テーマとターゲット読者を指定することで、魅力的な記事タイトルと構成案を取得'
            ],
            [
                'title' => '翻訳・要約タスク',
                'description' => '「この文章を英語に翻訳してください」「200文字で要約してください」など、日常業務で即活用'
            ]
        ]
    ],
    // 他のコースのベネフィットも追加可能
];

// 現在のコースのベネフィットを取得（デフォルト値を設定）
$benefits = $courseBenefits[$courseId] ?? [
    'skills' => [
        'AIツールの基本的な使い方',
        '効率的な業務の進め方',
        'プロンプト作成の基礎知識'
    ],
    'realworld' => [
        [
            'title' => '業務効率化',
            'description' => 'AIを活用して日々の業務を効率化できます'
        ],
        [
            'title' => '生産性向上',
            'description' => '繰り返し作業を自動化し、クリエイティブな仕事に集中できます'
        ]
    ]
];
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
    <title><?= h($course['title']) ?> | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="course-detail">
        <div class="container">
            <!-- コースヘッダー -->
            <div class="course-header">
                <div class="breadcrumb">
                    <a href="<?= APP_URL ?>/dashboard.php">ダッシュボード</a>
                    <span class="separator">›</span>
                    <span><?= h($course['title']) ?></span>
                </div>

                <div class="course-header-content">
                    <div class="course-header-left">
                        <h1><?= h($course['title']) ?></h1>
                        <p class="course-description"><?= h($course['description']) ?></p>

                        <div class="course-meta">
                            <span class="difficulty difficulty-<?= h($course['difficulty']) ?>">
                                <?php
                                $difficultyLabel = [
                                    'beginner' => '初級',
                                    'intermediate' => '中級',
                                    'advanced' => '上級'
                                ];
                                echo $difficultyLabel[$course['difficulty']] ?? '不明';
                                ?>
                            </span>
                            <span class="lesson-count"><?= $totalLessons ?> レッスン</span>
                            <span class="badge badge-free">無料</span>
                        </div>
                    </div>

                    <div class="course-header-right">
                        <div class="progress-circle" data-progress="<?= $progressPercent ?>">
                            <svg width="120" height="120">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#e0e0e0" stroke-width="8"/>
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#4CAF50" stroke-width="8"
                                        stroke-dasharray="<?= 2 * pi() * 54 ?>"
                                        stroke-dashoffset="<?= 2 * pi() * 54 * (1 - $progressPercent / 100) ?>"
                                        transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="progress-text"><?= $progressPercent ?>%</div>
                        </div>
                        <p class="progress-label"><?= $completedLessons ?> / <?= $totalLessons ?> 完了</p>
                    </div>
                </div>
            </div>

            <!-- コースベネフィット -->
            <section class="course-benefits">
                <div class="benefits-grid">
                    <!-- 得られるスキル -->
                    <div class="benefit-card benefit-skills">
                        <div class="benefit-header">
                            <div class="benefit-icon"><i class="fas fa-lightbulb"></i></div>
                            <h3>このコースで得られるスキル</h3>
                        </div>
                        <ul class="skills-list">
                            <?php foreach ($benefits['skills'] as $skill): ?>
                                <li>
                                    <span class="skill-check">✓</span>
                                    <span><?= h($skill) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- 実務活用例 -->
                    <div class="benefit-card benefit-realworld">
                        <div class="benefit-header">
                            <div class="benefit-icon">🚀</div>
                            <h3>実務での活用例</h3>
                        </div>
                        <div class="realworld-examples">
                            <?php foreach ($benefits['realworld'] as $example): ?>
                                <div class="example-item">
                                    <h4 class="example-title"><?= h($example['title']) ?></h4>
                                    <p class="example-description"><?= h($example['description']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- レッスン一覧 -->
            <div class="lessons-list">
                <h2>レッスン一覧</h2>

                <?php foreach ($lessons as $index => $lesson): ?>
                    <?php
                    $isLocked = false; // 全てのレッスンがアクセス可能
                    $isCompleted = $lesson['progress_status'] === 'completed';
                    $isInProgress = $lesson['progress_status'] === 'in_progress';

                    // レッスンタイプのアイコン
                    $typeIcons = [
                        'slide' => '📖',
                        'editor' => '💻',
                        'quiz' => '✏️',
                        'assignment' => '📝'
                    ];
                    $typeLabels = [
                        'slide' => 'スライド',
                        'editor' => 'エディタ',
                        'quiz' => 'クイズ',
                        'assignment' => '課題'
                    ];
                    ?>
                    <div class="lesson-item <?= $isCompleted ? 'completed' : '' ?> <?= $isInProgress ? 'in-progress' : '' ?> <?= $isLocked ? 'locked' : '' ?>">
                        <div class="lesson-number"><?= $index + 1 ?></div>

                        <div class="lesson-content">
                            <div class="lesson-header">
                                <h3><?= h($lesson['title']) ?></h3>
                                <span class="lesson-type">
                                    <?= $typeIcons[$lesson['lesson_type']] ?? '📄' ?>
                                    <?= $typeLabels[$lesson['lesson_type']] ?? 'レッスン' ?>
                                </span>
                            </div>

                            <?php if ($lesson['description']): ?>
                                <p class="lesson-description"><?= h($lesson['description']) ?></p>
                            <?php endif; ?>

                            <div class="lesson-footer">
                                <?php if ($isCompleted): ?>
                                    <span class="status-badge status-completed">✓ 完了</span>
                                    <span class="completed-date">
                                        <?= date('Y/m/d H:i', strtotime($lesson['completed_at'])) ?>
                                    </span>
                                <?php elseif ($isInProgress): ?>
                                    <span class="status-badge status-in-progress">進行中</span>
                                <?php endif; ?>

                                <?php if (!$isLocked): ?>
                                    <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-sm btn-primary">
                                        <?= $isCompleted ? '復習する' : ($isInProgress ? '続きから' : '始める') ?>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-disabled" disabled>🔒 ロック</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
