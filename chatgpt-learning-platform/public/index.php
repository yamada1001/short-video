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
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/progate-v2.css">
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
        <!-- 1. ファーストビュー -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        AIへの話しかけ方を学んで、<br>
                        毎日の仕事をラクにしよう
                    </h1>
                    <p class="hero-subtitle">
                        資料作りも、文章作成も、AIが手伝ってくれる時代。<br>
                        使い方を知れば、あなたの仕事がもっと楽しくなる！
                    </p>
                    <div class="hero-cta">
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-lg btn-primary">無料で始める</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. AIって何？どう役立つ？ -->
        <section class="ai-intro">
            <div class="container">
                <h2 class="section-title">AIって、実は身近な"賢いアシスタント"</h2>
                <div class="ai-intro-grid">
                    <div class="ai-intro-card">
                        <div class="ai-intro-icon">💬</div>
                        <h3>話しかけると答えてくれる</h3>
                        <p>ChatGPTやGemini AIは、話しかけると答えてくれる賢いアシスタント。難しい操作は一切なし！</p>
                    </div>
                    <div class="ai-intro-card">
                        <div class="ai-intro-icon">✨</div>
                        <h3>お願いするだけでOK</h3>
                        <p>「〇〇について教えて」「△△の文章を作って」とお願いするだけ。日本語で普通に話せばいいんです。</p>
                    </div>
                    <div class="ai-intro-card">
                        <div class="ai-intro-icon">⏰</div>
                        <h3>24時間いつでも</h3>
                        <p>24時間いつでも、何度でも、疲れずに手伝ってくれる。あなた専属のアシスタントです。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. 具体的にできること -->
        <section class="use-cases">
            <div class="container">
                <h2 class="section-title">こんなことが、サクッとできるようになります</h2>
                <div class="use-cases-grid">
                    <div class="use-case-card">
                        <div class="use-case-icon">📝</div>
                        <h3>会議の議事録作成</h3>
                        <p>録音した内容を書き起こして、まとめてくれる。議事録作成の時間が10分の1に！</p>
                    </div>
                    <div class="use-case-card">
                        <div class="use-case-icon">✉️</div>
                        <h3>メールの文章作成</h3>
                        <p>「〇〇さんにお礼のメールを書いて」で完成。もう文章で悩まない。</p>
                    </div>
                    <div class="use-case-card">
                        <div class="use-case-icon">📊</div>
                        <h3>プレゼン資料の構成案</h3>
                        <p>「新商品発表のスライド構成を考えて」で骨組みができる。あとは肉付けするだけ。</p>
                    </div>
                    <div class="use-case-card">
                        <div class="use-case-icon">💡</div>
                        <h3>企画書のアイデア出し</h3>
                        <p>「△△のイベント企画案を5つ出して」で選び放題。アイデアに困らない。</p>
                    </div>
                    <div class="use-case-card">
                        <div class="use-case-icon">📈</div>
                        <h3>データの分析</h3>
                        <p>数字を見せれば、傾向を教えてくれる。データ分析が苦手でも大丈夫。</p>
                    </div>
                    <div class="use-case-card">
                        <div class="use-case-icon">📱</div>
                        <h3>SNS投稿文の作成</h3>
                        <p>「〇〇の魅力を140字で」であっという間。毎日の投稿がラクラク。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. こんな人におすすめ -->
        <section class="target-users">
            <div class="container">
                <h2 class="section-title">こんなお悩み、ありませんか？</h2>
                <div class="checklist">
                    <div class="checklist-item">
                        <span class="checklist-icon">✓</span>
                        <p>毎日の資料作りに時間がかかりすぎる...</p>
                    </div>
                    <div class="checklist-item">
                        <span class="checklist-icon">✓</span>
                        <p>メールや文章を書くのが苦手...</p>
                    </div>
                    <div class="checklist-item">
                        <span class="checklist-icon">✓</span>
                        <p>アイデアが浮かばなくて困っている...</p>
                    </div>
                    <div class="checklist-item">
                        <span class="checklist-icon">✓</span>
                        <p>仕事の効率を上げたい...</p>
                    </div>
                    <div class="checklist-item">
                        <span class="checklist-icon">✓</span>
                        <p>AIを使ってみたいけど、何から始めたらいいかわからない...</p>
                    </div>
                </div>
                <p class="target-users-message">
                    そんなあなたに、ぴったりのスクールです！
                </p>
            </div>
        </section>

        <!-- 5. 無料で始められます -->
        <section class="pricing">
            <div class="container">
                <h2 class="section-title">まずは無料で試してみませんか？</h2>
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <h3>無料プラン</h3>
                        <div class="price">
                            <span class="price-amount">¥0</span>
                            <span class="price-period">/月</span>
                        </div>
                        <ul class="pricing-features">
                            <li>毎日10回まで無料で使える</li>
                            <li>基本的な使い方が学べる</li>
                            <li>いつでも辞められる</li>
                            <li>クレジットカード不要</li>
                        </ul>
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-outline btn-block">無料で始める</a>
                    </div>
                    <div class="pricing-card pricing-featured">
                        <div class="pricing-badge">おすすめ</div>
                        <h3>プレミアムプラン</h3>
                        <div class="price">
                            <span class="price-amount">¥980</span>
                            <span class="price-period">/月</span>
                        </div>
                        <ul class="pricing-features">
                            <li>毎日100回まで使える</li>
                            <li>全てのコースが学び放題</li>
                            <li>優先サポート</li>
                            <li>限定コンテンツへのアクセス</li>
                        </ul>
                        <a href="<?= APP_URL ?>/register.php" class="btn btn-primary btn-block">プレミアムで始める</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. FAQ -->
        <section class="faq">
            <div class="container">
                <h2 class="section-title">よくあるご質問</h2>
                <div class="faq-list">
                    <div class="faq-item">
                        <h3 class="faq-question">AIって難しくないですか？</h3>
                        <p class="faq-answer">日本語で話しかけるだけ。小学生でも使えるくらい簡単です。このスクールでは、基礎の基礎から丁寧に教えます。</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">仕事に使って大丈夫？</h3>
                        <p class="faq-answer">はい。実際に多くの企業で使われています。ただし、機密情報の扱いには注意が必要です。このスクールでは、安全な使い方もしっかり学べます。</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">パソコンが苦手でも使えますか？</h3>
                        <p class="faq-answer">スマホでも使えるので、誰でもOKです。操作方法も一つひとつ丁寧に説明します。</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">無料プランでどこまで学べますか？</h3>
                        <p class="faq-answer">基本的な使い方は無料プランで全て学べます。毎日10回まで実際にAIを使って練習できます。</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">途中で辞められますか？</h3>
                        <p class="faq-answer">いつでも辞められます。無料プランならクレジットカードも不要。気軽に始めてください。</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">どのくらいで使えるようになりますか？</h3>
                        <p class="faq-answer">早い人なら1日で基本を習得。1週間続ければ、仕事で使えるレベルになります。自分のペースで学べます。</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. 最終CTA -->
        <section class="cta">
            <div class="container">
                <h2 class="cta-title">1分後、AIと一緒に仕事をする未来へ</h2>
                <p class="cta-subtitle">今すぐ無料で始めて、毎日の仕事をラクにしましょう</p>
                <a href="<?= APP_URL ?>/register.php" class="btn btn-lg btn-primary">今すぐ無料で始める</a>
                <p class="cta-note">クレジットカード不要・いつでも辞められます</p>
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
