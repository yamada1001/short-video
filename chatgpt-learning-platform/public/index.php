<?php
/**
 * トップページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログイン済みならダッシュボードへ
if (isset($_SESSION['user_id'])) {
    redirect(APP_URL . '/dashboard.php');
}

// 無料コース数を取得
$freeCourseSql = "SELECT COUNT(*) as count FROM courses WHERE is_free = 1";
$freeCourseResult = db()->fetchOne($freeCourseSql);
$freeCourseCount = $freeCourseResult['count'] ?? 0;

// 全コース数を取得
$totalCourseSql = "SELECT COUNT(*) as count FROM courses";
$totalCourseResult = db()->fetchOne($totalCourseSql);
$totalCourseCount = $totalCourseResult['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI活用スクール | 楽しく学べるAI使い方講座</title>
    <meta name="description" content="楽しく体験しながらAIアシスタントの使い方を学べるスクール。AIへの話しかけ方をマスターしましょう。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/custom-style.css">
</head>
<body class="landing-page">
    <header class="landing-header">
        <div class="container">
            <div class="header-inner">
                <div class="logo">AI活用スクール</div>
                <nav class="header-nav">
                    <a href="<?= APP_URL ?>/login.php">ログイン</a>
                    <a href="<?= APP_URL ?>/register.php" class="btn btn-sm btn-primary">無料登録</a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <!-- ヒーローセクション -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        AIへの話しかけ方を学んで<br>
                        毎日の仕事をラクにしよう
                    </h1>
                    <p class="hero-subtitle">
                        実際に試しながら学べる、楽しいAI活用スクール。<br>
                        今すぐ無料で始められます。
                    </p>
                    <div class="hero-cta">
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-lg btn-primary">無料で始める</a>
                        <a href="#features" class="btn btn-lg btn-outline">詳しく見る</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?= $freeCourseCount ?></div>
                            <div class="stat-label">無料コース</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $totalCourseCount ?></div>
                            <div class="stat-label">総コース数</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">980円</div>
                            <div class="stat-label">月額料金</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 特徴セクション -->
        <section id="features" class="features">
            <div class="container">
                <h2 class="section-title">このスクールの特徴</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">💻</div>
                        <h3>見やすい画面</h3>
                        <p>パッと見てわかる、使いやすい画面。ゲーム感覚で楽しくAIの使い方を学べます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">✍️</div>
                        <h3>実際に体験できる</h3>
                        <p>実際にAIへお願いを出して、反応を確認。その場でどんどん学べます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>がんばった記録が見える</h3>
                        <p>どこまで進んだか一目でわかる。やる気を保ちながら、楽しく続けられます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3>クイズとチャレンジ</h3>
                        <p>わかったかな？を確認できるクイズと、やってみようチャレンジで、確実にレベルアップ。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>月額980円</h3>
                        <p>プレミアムプランでも月額980円。全コースにアクセスして学び放題。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🚀</div>
                        <h3>すぐに始められる</h3>
                        <p>メールアドレスまたはGoogleアカウントで即座に登録。今すぐ学習をスタート。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 料金プラン -->
        <section class="pricing">
            <div class="container">
                <h2 class="section-title">料金プラン</h2>
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <h3>無料プラン</h3>
                        <div class="price">
                            <span class="price-amount">¥0</span>
                            <span class="price-period">/月</span>
                        </div>
                        <ul class="pricing-features">
                            <li>✓ 基礎コースへのアクセス</li>
                            <li>✓ 毎日10回まで無料で使える</li>
                            <li>✓ がんばり記録</li>
                            <li>✓ クイズ機能</li>
                        </ul>
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-outline btn-block">今すぐ始める</a>
                    </div>
                    <div class="pricing-card pricing-featured">
                        <div class="pricing-badge">おすすめ</div>
                        <h3>プレミアムプラン</h3>
                        <div class="price">
                            <span class="price-amount">¥980</span>
                            <span class="price-period">/月</span>
                        </div>
                        <ul class="pricing-features">
                            <li>✓ 全コースへのアクセス</li>
                            <li>✓ 毎日100回まで使える</li>
                            <li>✓ がんばり記録</li>
                            <li>✓ クイズ・チャレンジ</li>
                            <li>✓ 優先サポート</li>
                        </ul>
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-primary btn-block">プレミアムで始める</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="container">
                <h2>今すぐAIを使いこなそう</h2>
                <p>無料で始めて、AIへのお願い上手になろう</p>
                <a href="<?= APP_URL ?>/register.php" class="btn btn-lg btn-primary">無料で始める</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> AI活用スクール. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
