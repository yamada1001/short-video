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
</head>
                </p>
                <div class="hero__cta">
                    <a href="register.php" class="btn-primary">無料で始める</a>
                </div>
                <div class="hero__stats">
                    <div class="hero__stat">
                        <div class="hero__stat-number">5+</div>
                        <div class="hero__stat-label">無料コース</div>
                    </div>
                    <div class="hero__stat">
                        <div class="hero__stat-number">15+</div>
                        <div class="hero__stat-label">総コース数</div>
                    </div>
                    <div class="hero__stat">
                        <div class="hero__stat-number">980円</div>
                        <div class="hero__stat-label">月額料金</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 特徴セクション -->
        <section class="features">
            <div class="container">
                <h2 class="features__title">AIを味方につけて、仕事の相棒に</h2>
                <div class="features__grid">
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">見やすい画面</h3>
                            <p class="feature-card__description">パッと見てわかる、使いやすい画面。ゲーム感覚で楽しくAIの使い方を学べます。</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">実際に体験できる</h3>
                            <p class="feature-card__description">実際にAIへお願いを出して、反応を確認。その場でどんどん学べます。</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">がんばった記録が見える</h3>
                            <p class="feature-card__description">どこまで進んだか一目でわかる。やる気を保ちながら、楽しく続けられます。</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">クイズとチャレンジ</h3>
                            <p class="feature-card__description">わかったかな？を確認できるクイズと、やってみようチャレンジで、確実にレベルアップ。</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">月額980円</h3>
                            <p class="feature-card__description">プレミアムプランでも月額980円。全コースにアクセスして学び放題。</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-card__icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="feature-card__content">
                            <h3 class="feature-card__title">すぐに始められる</h3>
                            <p class="feature-card__description">メールアドレスまたはGoogleアカウントで即座に登録。今すぐ学習をスタート。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- コース一覧セクション -->
        <section class="courses">
            <div class="container">
                <h2 class="courses__title">
                    実際にAIに触れながら学べる<br>
                    15以上のコースを用意
                </h2>
                <div class="courses__grid">
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #FFF5F2;">
                            <i class="fas fa-comment-dots" style="color: #FF6B6B; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">基本の話しかけ方</h3>
                            <p class="course-card__description">AIに指示を出す基本を学ぶコース。初心者におすすめ。</p>
                            <div class="course-card__meta">
                                <span>全5レッスン</span>
                            </div>
                        </div>
                    </div>
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #FFFBEB;">
                            <i class="fas fa-file-alt" style="color: #F59E0B; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">文章作成</h3>
                            <p class="course-card__description">メールやレポートをAIに書いてもらうテクニック。</p>
                            <div class="course-card__meta">
                                <span>全7レッスン</span>
                            </div>
                        </div>
                    </div>
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #EFF6FF;">
                            <i class="fas fa-table" style="color: #3B82F6; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">データ整理</h3>
                            <p class="course-card__description">表データを効率よく整理・分析する方法を学ぶ。</p>
                            <div class="course-card__meta">
                                <span>全6レッスン</span>
                            </div>
                        </div>
                    </div>
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #FEF2F2;">
                            <i class="fas fa-search" style="color: #EF4444; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">情報収集</h3>
                            <p class="course-card__description">AIを使った効率的な情報収集・要約テクニック。</p>
                            <div class="course-card__meta">
                                <span>全5レッスン</span>
                            </div>
                        </div>
                    </div>
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #F5F3FF;">
                            <i class="fas fa-lightbulb" style="color: #8B5CF6; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">アイデア出し</h3>
                            <p class="course-card__description">AIと一緒にブレストして新しいアイデアを生み出す。</p>
                            <div class="course-card__meta">
                                <span>全6レッスン</span>
                            </div>
                        </div>
                    </div>
                    <div class="course-card">
                        <div class="course-card__icon" style="background-color: #ECFDF5;">
                            <i class="fas fa-code" style="color: #10B981; font-size: 24px;"></i>
                        </div>
                        <div class="course-card__content">
                            <h3 class="course-card__title">簡単なコード生成</h3>
                            <p class="course-card__description">プログラミング初心者でもAIでコードを作れる。</p>
                            <div class="course-card__meta">
                                <span>全8レッスン</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 料金プランセクション -->
        <section class="pricing">
            <div class="container">
                <h2 class="pricing__title">料金プラン</h2>
                <div class="pricing__grid">
                    <div class="plan-card">
                        <h3 class="plan-card__name">無料プラン</h3>
                        <div class="plan-card__price">
                            <span class="plan-card__price-amount">0</span>
                            <span class="plan-card__price-unit">円/月</span>
                        </div>
                        <ul class="plan-card__features">
                            <li><i class="fas fa-check"></i>基礎コースへのアクセス</li>
                            <li><i class="fas fa-check"></i>毎日10回まで無料で使える</li>
                            <li><i class="fas fa-check"></i>がんばり記録</li>
                            <li><i class="fas fa-check"></i>クイズ機能</li>
                        </ul>
                        <a href="register.php" class="btn-secondary btn-block">今すぐ始める</a>
                    </div>
                    <div class="plan-card plan-card--featured">
                        <div class="plan-card__badge">おすすめ</div>
                        <h3 class="plan-card__name">プレミアムプラン</h3>
                        <div class="plan-card__price">
                            <span class="plan-card__price-amount">980</span>
                            <span class="plan-card__price-unit">円/月</span>
                        </div>
                        <ul class="plan-card__features">
                            <li><i class="fas fa-check"></i>全コースへのアクセス</li>
                            <li><i class="fas fa-check"></i>毎日100回まで使える</li>
                            <li><i class="fas fa-check"></i>がんばり記録</li>
                            <li><i class="fas fa-check"></i>クイズ・チャレンジ</li>
                            <li><i class="fas fa-check"></i>優先サポート</li>
                        </ul>
                        <a href="register.php" class="btn-primary btn-block">プレミアムで始める</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 最終CTAセクション -->
        <section class="final-cta">
            <div class="container">
                <h2 class="final-cta__title">1分後、AIを味方にした新しい毎日が始まります</h2>
                <p class="final-cta__subtitle">まずは無料で始めて、AIへのお願い上手になろう</p>
                <a href="register.php" class="btn-primary">無料で始める</a>
            </div>
        </section>
    </main>

    <!-- フッター -->
    <footer class="footer">
        <div class="footer__container">
            <div class="footer__logo">AI活用スクール</div>
            <nav class="footer__nav">
                <div class="footer__nav-section">
                    <h4 class="footer__nav-title">サービス</h4>
                    <ul class="footer__nav-list">
                        <li><a href="#features">特徴</a></li>
                        <li><a href="#courses">コース一覧</a></li>
                        <li><a href="#pricing">料金プラン</a></li>
                    </ul>
                </div>
                <div class="footer__nav-section">
                    <h4 class="footer__nav-title">サポート</h4>
                    <ul class="footer__nav-list">
                        <li><a href="#">よくある質問</a></li>
                        <li><a href="#">お問い合わせ</a></li>
                        <li><a href="#">使い方ガイド</a></li>
                    </ul>
                </div>
                <div class="footer__nav-section">
                    <h4 class="footer__nav-title">会社情報</h4>
                    <ul class="footer__nav-list">
                        <li><a href="#">運営会社</a></li>
                        <li><a href="#">プライバシーポリシー</a></li>
                        <li><a href="#">利用規約</a></li>
                    </ul>
                </div>
            </nav>
            <div class="footer__bottom">
                &copy; 2025 AI活用スクール. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
