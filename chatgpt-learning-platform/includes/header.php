<?php
/**
 * 共通ヘッダー
 */
$user = getCurrentUser();
?>
<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <a href="<?= APP_URL ?>/dashboard.php" class="logo">
                <img src="<?= APP_URL ?>/assets/images/logo.png" alt="ChatGPT Learning">
                ChatGPT Learning
            </a>

            <nav class="main-nav">
                <?php if ($user): ?>
                    <a href="<?= APP_URL ?>/dashboard.php">ダッシュボード</a>
                    <a href="<?= APP_URL ?>/course.php">コース一覧</a>
                    <a href="<?= APP_URL ?>/my-progress.php">学習進捗</a>
                    <?php if (!hasActiveSubscription()): ?>
                        <a href="<?= APP_URL ?>/subscribe.php" class="btn-upgrade">プレミアム</a>
                    <?php endif; ?>
                    <div class="user-menu">
                        <button class="user-menu-toggle">
                            <span class="user-name"><?= h($user['name']) ?></span>
                            <span class="user-icon">👤</span>
                        </button>
                        <div class="user-dropdown">
                            <a href="<?= APP_URL ?>/profile.php">プロフィール</a>
                            <a href="<?= APP_URL ?>/logout.php">ログアウト</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/login.php">ログイン</a>
                    <a href="<?= APP_URL ?>/register.php" class="btn btn-sm btn-primary">無料登録</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>
