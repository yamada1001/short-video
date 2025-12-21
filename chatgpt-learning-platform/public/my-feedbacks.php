<?php
/**
 * フィードバック履歴ページ
 *
 * ユーザーが送信したフィードバックと運営からの返信を一覧表示
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();

// フィードバック一覧を取得
$sql = "SELECT
            uf.id,
            uf.lesson_id,
            l.title as lesson_title,
            c.title as course_title,
            uf.feedback_type,
            uf.message,
            uf.reply_message,
            uf.status,
            uf.created_at,
            uf.replied_at
        FROM user_feedback uf
        LEFT JOIN lessons l ON uf.lesson_id = l.id
        LEFT JOIN courses c ON l.course_id = c.id
        WHERE uf.user_id = ?
        ORDER BY uf.created_at DESC";

$feedbacks = db()->fetchAll($sql, [$user['id']]);

// フィードバックタイプのラベル
$typeLabels = [
    'question' => '質問',
    'bug' => 'バグ報告',
    'request' => '要望',
    'other' => 'その他'
];

// ステータスラベル
$statusLabels = [
    'pending' => '未対応',
    'in_progress' => '対応中',
    'completed' => '完了'
];

// アイコン
$typeIcons = [
    'question' => '<i class="fas fa-question"></i>',
    'bug' => '<i class="fas fa-bug"></i>',
    'request' => '<i class="fas fa-lightbulb"></i>',
    'other' => '<i class="fas fa-edit"></i>'
];

$statusIcons = [
    'pending' => '<i class="fas fa-hourglass-half"></i>',
    'in_progress' => '<i class="fas fa-sync"></i>',
    'completed' => '<i class="fas fa-check-circle"></i>'
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
    <title>フィードバック履歴 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="feedbacks-page">
        <div class="container">
            <div class="page-header">
                <h1><i class="fas fa-inbox"></i> フィードバック履歴</h1>
                <p class="page-subtitle">あなたが送信したフィードバックと運営からの返信を確認できます</p>
            </div>

            <?php if (empty($feedbacks)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="far fa-inbox"></i></div>
                    <h3>まだフィードバックがありません</h3>
                    <p>学習中にわからないことがあれば、各レッスンページの「<i class="fas fa-question-circle"></i> ヘルプ」ボタンからお気軽にご質問ください！</p>
                    <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-primary">ダッシュボードへ戻る</a>
                </div>
            <?php else: ?>
                <div class="feedbacks-list">
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-card <?= $feedback['status'] ?>">
                            <div class="feedback-header">
                                <div class="feedback-meta">
                                    <span class="feedback-type">
                                        <?= $typeIcons[$feedback['feedback_type']] ?>
                                        <?= h($typeLabels[$feedback['feedback_type']] ?? $feedback['feedback_type']) ?>
                                    </span>
                                    <span class="feedback-status status-<?= h($feedback['status']) ?>">
                                        <?= $statusIcons[$feedback['status']] ?>
                                        <?= h($statusLabels[$feedback['status']] ?? $feedback['status']) ?>
                                    </span>
                                </div>
                                <div class="feedback-date">
                                    送信日: <?= date('Y/m/d H:i', strtotime($feedback['created_at'])) ?>
                                </div>
                            </div>

                            <?php if ($feedback['lesson_title']): ?>
                                <div class="feedback-lesson">
                                    <span class="lesson-icon"><i class="fas fa-book"></i></span>
                                    <?= h($feedback['course_title']) ?> - <?= h($feedback['lesson_title']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="feedback-message">
                                <h4>あなたのメッセージ:</h4>
                                <p><?= nl2br(h($feedback['message'])) ?></p>
                            </div>

                            <?php if ($feedback['reply_message']): ?>
                                <div class="feedback-reply">
                                    <div class="reply-header">
                                        <span class="reply-icon"><i class="fas fa-comment"></i></span>
                                        <span class="reply-label">運営からの返信</span>
                                        <?php if ($feedback['replied_at']): ?>
                                            <span class="reply-date">
                                                <?= date('Y/m/d H:i', strtotime($feedback['replied_at'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="reply-message">
                                        <?= nl2br(h($feedback['reply_message'])) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="feedback-waiting">
                                    <span class="waiting-icon"><i class="fas fa-hourglass-half"></i></span>
                                    返信をお待ちください。通常1〜3営業日以内に返信いたします。
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="feedbacks-summary">
                    <p>総フィードバック数: <strong><?= count($feedbacks) ?></strong>件</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
