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
    <title>Gemini AI学習プラットフォーム | Progate風ハンズオン学習</title>
    <meta name="description" content="Progate風のハンズオン形式でGemini AIを学べるプラットフォーム。実践的なプロンプトエンジニアリングを習得しましょう。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-style.css">
</head>
<body class="landing-page">
    <header class="landing-header">
        <div class="container">
            <div class="header-inner">
                <div class="logo">Gemini AI Learning</div>
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
                        Gemini AIを<br>
                        実践的に学ぼう
                    </h1>
                    <p class="hero-subtitle">
                        Progate風のハンズオン形式で、プロンプトエンジニアリングを習得。<br>
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
                <h2 class="section-title">このプラットフォームの特徴</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">💻</div>
                        <h3>Progate風UI</h3>
                        <p>直感的で使いやすいインターフェース。プログラミング学習のように楽しくGemini AIを学べます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">✍️</div>
                        <h3>実践エディタ</h3>
                        <p>実際にプロンプトを入力してGemini AIの反応を確認。リアルタイムで学習できます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>進捗管理</h3>
                        <p>学習の進捗をビジュアルで確認。モチベーションを維持しながら学習を進められます。</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3>クイズ・課題</h3>
                        <p>理解度をチェックできるクイズと実践的な課題で、確実にスキルアップ。</p>
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
                            <li>✓ 1日10回のAPI実行</li>
                            <li>✓ 進捗管理機能</li>
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
                            <li>✓ 1日100回のAPI実行</li>
                            <li>✓ 進捗管理機能</li>
                            <li>✓ クイズ・課題機能</li>
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
                <h2>今すぐGemini AIをマスターしよう</h2>
                <p>無料で始めて、プロンプトエンジニアリングのスキルを習得</p>
                <a href="<?= APP_URL ?>/register.php" class="btn btn-lg btn-primary">無料で始める</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Gemini AI Learning Platform. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
