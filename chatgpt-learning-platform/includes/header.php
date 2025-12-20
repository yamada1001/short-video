<?php
/**
 * 共通ヘッダー
 */
$user = getCurrentUser();
?>
<header class="header">
    <div class="header__container">
        <a href="<?= APP_URL ?>/dashboard.php" class="header__logo">
            <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Gemini AI Learning">
            Gemini AI Learning
        </a>

        <nav class="header__nav">
            <?php if ($user): ?>
                <a href="<?= APP_URL ?>/dashboard.php" class="header__link">ダッシュボード</a>
                <a href="<?= APP_URL ?>/course.php" class="header__link">コース一覧</a>
                <a href="<?= APP_URL ?>/my-progress.php" class="header__link">学習進捗</a>
                <?php if (!hasActiveSubscription()): ?>
                    <a href="<?= APP_URL ?>/subscribe.php" class="btn btn-primary btn-sm">プレミアム</a>
                <?php endif; ?>
                <div class="user-menu">
                    <button class="user-menu-toggle">
                        <span class="user-name"><?= h($user['name']) ?></span>
                        <span class="user-icon">👤</span>
                    </button>
                    <div class="user-dropdown">
                        <a href="<?= APP_URL ?>/profile.php">プロフィール</a>
                        <a href="<?= APP_URL ?>/my-feedbacks.php">📬 フィードバック履歴</a>
                        <a href="<?= APP_URL ?>/logout.php">ログアウト</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login.php" class="header__link">ログイン</a>
                <a href="<?= APP_URL ?>/register.php" class="btn btn-sm btn-primary">無料登録</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
