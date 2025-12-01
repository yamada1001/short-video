<?php
// A/Bテスト用パターンをPHPで管理
$patterns = [
    1 => ['catch' => 1, 'cta' => 'A'],
    2 => ['catch' => 1, 'cta' => 'B'],
    3 => ['catch' => 1, 'cta' => 'E'],
    4 => ['catch' => 2, 'cta' => 'A'],
    5 => ['catch' => 2, 'cta' => 'B'],
    6 => ['catch' => 2, 'cta' => 'E'],
    7 => ['catch' => 6, 'cta' => 'A'],
    8 => ['catch' => 6, 'cta' => 'B'],
    9 => ['catch' => 6, 'cta' => 'E'],
];

// Cookie確認、なければランダム割り当て
if (!isset($_COOKIE['lp_pattern'])) {
    $patternId = rand(1, 9);
    setcookie('lp_pattern', $patternId, time() + (86400 * 30), '/'); // 30日間有効
} else {
    $patternId = (int)$_COOKIE['lp_pattern'];
}

$currentPattern = $patterns[$patternId];

// キャッチコピー定義
$catchCopies = [
    1 => 'あなたの商品、ちゃんと伝わっていますか？<br><br>「もっと知ってほしい」を形にする、<br>特別な1ページ。5万円〜、最短3日。',
    2 => '伝えたいことがある。<br>でも、どう届けたらいいかわからない。<br><br>この想いを、1枚のページで届けませんか？<br>5万円〜、最短3日。',
    6 => 'もっと多くの人に、この良さを知ってもらいたい。<br><br>そんな想いを、形にします。<br>5万円〜、最短3日。'
];

$currentCatchCopy = $catchCopies[$currentPattern['catch']];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LP制作サービス。5万円〜、最短3営業日で納品。AI活用で高品質・スピーディーなランディングページ制作を実現します。">
    <title>LP制作サービス - 5万円〜、最短3営業日 | YOJITU.COM</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+JP:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <!-- フローティングオーブ背景 -->
    <div class="floating-orbs" aria-hidden="true">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- ヘッダー -->
    <header class="header" role="banner">
        <div class="header-inner container">
            <div class="header-logo">
                <a href="/">YOJITU.COM</a>
            </div>
            <nav class="header-nav" role="navigation">
                <ul>
                    <li><a href="#features">特徴</a></li>
                    <li><a href="#timing">タイミング</a></li>
                    <li><a href="#pricing">料金</a></li>
                    <li><a href="#portfolio">実績</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </nav>
            <button class="btn-primary header-cta" onclick="openModal()">無料相談する</button>
            <button class="hamburger" aria-label="メニューを開く" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- モバイルメニュー -->
    <div class="mobile-menu" id="mobileMenu">
        <nav role="navigation">
            <ul>
                <li><a href="#features" onclick="toggleMenu()">特徴</a></li>
                <li><a href="#timing" onclick="toggleMenu()">タイミング</a></li>
                <li><a href="#pricing" onclick="toggleMenu()">料金</a></li>
                <li><a href="#portfolio" onclick="toggleMenu()">実績</a></li>
                <li><a href="#faq" onclick="toggleMenu()">FAQ</a></li>
            </ul>
            <button class="btn-primary" onclick="openModal(); toggleMenu();">無料相談する</button>
        </nav>
    </div>

    <main role="main">
        <!-- ファーストビュー -->
        <section class="hero" id="hero">
            <div class="container hero-content">
                <h1 class="hero-title"><?php echo $currentCatchCopy; ?></h1>
                <div class="hero-features">
                    <div class="hero-feature">💰 5万円〜10万円</div>
                    <div class="hero-feature">⚡ 最短3営業日納品</div>
                    <div class="hero-feature">🤖 AI活用で高品質</div>
                </div>
                <div class="hero-cta">
                    <button class="btn-primary btn-large" onclick="openModal()">今すぐ相談する</button>
                </div>
            </div>
        </section>

        <!-- 問題提起セクション -->
        <section class="section-problems" id="features">
            <div class="container">
                <h2 class="section-title">この商品・サービスのこと、もっと知ってほしい。<br>そう思ったことはありませんか？</h2>
                <div class="problem-grid">
                    <div class="card problem-card" data-anim>
                        <div class="problem-icon">💡</div>
                        <h3>新商品・サービスを広めたい</h3>
                        <p>せっかく作った新しい商品。でも、既存のホームページでは埋もれてしまう…</p>
                    </div>
                    <div class="card problem-card" data-anim>
                        <div class="problem-icon">📢</div>
                        <h3>広告を出したいけど、受け皿がない</h3>
                        <p>Google広告やSNS広告を検討しているが、クリック後のページが整っていない…</p>
                    </div>
                    <div class="card problem-card" data-anim>
                        <div class="problem-icon">🎯</div>
                        <h3>イベント・キャンペーン告知したい</h3>
                        <p>期間限定の企画。専用ページを作りたいけど、時間もコストもかけられない…</p>
                    </div>
                    <div class="card problem-card" data-anim>
                        <div class="problem-icon">📈</div>
                        <h3>問い合わせを増やしたい</h3>
                        <p>ホームページはあるけれど、なかなか問い合わせにつながらない…</p>
                    </div>
                    <div class="card problem-card" data-anim>
                        <div class="problem-icon">💸</div>
                        <h3>見積もりが100万円…高すぎる</h3>
                        <p>LP制作の見積もりを取ったら予算オーバー。もっと手軽に始めたい…</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- LP説明セクション -->
        <section class="section-explanation">
            <div class="container">
                <h2 class="section-title gradient-text">LPとは、</h2>
                <p class="section-subtitle">1つの目的に特化した、1枚のページです。</p>
                <div class="comparison-table-wrapper" data-anim>
                    <table class="comparison-table">
                        <thead>
                            <tr>
                                <th>種類</th>
                                <th>目的</th>
                                <th>ページ数</th>
                                <th>向いているケース</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>ホームページ</strong></td>
                                <td>会社・サービス全体の紹介</td>
                                <td>複数ページ</td>
                                <td>企業情報を網羅的に伝えたい</td>
                            </tr>
                            <tr>
                                <td><strong>LP（ランディングページ）</strong></td>
                                <td>特定の商品・サービス訴求</td>
                                <td>1ページ完結</td>
                                <td>この商品を買ってほしい、問い合わせしてほしい</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- タイミングセクション -->
        <section class="section-timing" id="timing">
            <div class="container">
                <h2 class="section-title">こんな時に、LPが必要です</h2>
                <div class="timing-grid">
                    <div class="card timing-card" data-anim>
                        <h3>🚀 新商品・サービス</h3>
                        <ul>
                            <li>新商品・新サービスのリリース</li>
                            <li>新規事業の立ち上げ</li>
                            <li>サービスリニューアル告知</li>
                        </ul>
                    </div>
                    <div class="card timing-card" data-anim>
                        <h3>📢 広告・プロモーション</h3>
                        <ul>
                            <li>Google/SNS広告の出稿</li>
                            <li>チラシ・DM配布時のリンク先</li>
                            <li>キャンペーン・セール告知</li>
                        </ul>
                    </div>
                    <div class="card timing-card" data-anim>
                        <h3>🎯 集客強化</h3>
                        <ul>
                            <li>問い合わせ数を増やしたい</li>
                            <li>資料請求・申し込みを獲得したい</li>
                            <li>セミナー・イベント集客</li>
                        </ul>
                    </div>
                    <div class="card timing-card" data-anim>
                        <h3>🌐 地域・ターゲット特化</h3>
                        <ul>
                            <li>地域限定サービスの訴求</li>
                            <li>特定の業界・職種向けPR</li>
                            <li>ローカルSEO対策</li>
                        </ul>
                    </div>
                    <div class="card timing-card" data-anim>
                        <h3>💼 営業・商談支援</h3>
                        <ul>
                            <li>営業先に送るURL</li>
                            <li>提案資料の補完</li>
                            <li>BtoB向け詳細説明ページ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- 価格・スピードの理由セクション -->
        <section class="section-reason">
            <div class="container">
                <h2 class="section-title">なぜ、<span class="gradient-text">5万円〜・最短3営業日</span>で<br>可能なのか？</h2>
                <div class="reason-comparison" data-anim>
                    <div class="reason-box">
                        <h3>一般的な制作会社</h3>
                        <div class="reason-process">
                            <div class="process-step">
                                <span class="step-number">1</span>
                                <div>
                                    <strong>ディレクターがヒアリング</strong>
                                    <span class="step-time">1〜2日</span>
                                </div>
                            </div>
                            <div class="process-step">
                                <span class="step-number">2</span>
                                <div>
                                    <strong>デザイナーがワイヤーフレーム作成</strong>
                                    <span class="step-time">3〜5日</span>
                                </div>
                            </div>
                            <div class="process-step">
                                <span class="step-number">3</span>
                                <div>
                                    <strong>デザイナーがデザイン作成</strong>
                                    <span class="step-time">4〜7日</span>
                                </div>
                            </div>
                            <div class="process-step">
                                <span class="step-number">4</span>
                                <div>
                                    <strong>コーダーがコーディング</strong>
                                    <span class="step-time">5〜7日</span>
                                </div>
                            </div>
                        </div>
                        <div class="reason-result reason-result-bad">
                            <strong>合計:</strong> 1〜2ヶ月 / 30万円〜100万円
                        </div>
                    </div>

                    <div class="reason-box reason-box-highlight">
                        <h3>YOJITU.COM</h3>
                        <div class="reason-badge">AI活用</div>
                        <div class="reason-process">
                            <div class="process-step">
                                <span class="step-number">1</span>
                                <div>
                                    <strong>ヒアリング</strong>
                                    <span class="step-time">1日</span>
                                </div>
                            </div>
                            <div class="process-step">
                                <span class="step-number">2</span>
                                <div>
                                    <strong>ワイヤー・デザイン・コーディングを同時進行</strong>
                                    <span class="step-time">1〜3日</span>
                                    <span class="step-note">※AIを活用して制作スピードを加速</span>
                                </div>
                            </div>
                        </div>
                        <div class="reason-result reason-result-good">
                            <strong>合計:</strong> 最短3営業日 / 5万円〜10万円
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 品質保証セクション -->
        <section class="section-quality" id="portfolio">
            <div class="container">
                <h2 class="section-title"><span class="gradient-text">品質</span>は、<br>どうなのでしょうか？</h2>
                <div class="quality-proof" data-anim>
                    <p class="quality-text">このサイト（yojitu.com）も、今ご覧いただいているこのページも、<br>AIで制作しています。</p>
                    <div class="portfolio-grid">
                        <div class="card portfolio-card">
                            <h4>大分IT移住プロジェクト</h4>
                            <p>「このままでいいのかな」から抜け出した人たちが選んだ、次の一歩。</p>
                            <a href="https://migration.oita-creative.jp/lp2/" target="_blank" class="btn-outline">実績を見る →</a>
                        </div>
                        <div class="card portfolio-card">
                            <h4>大分IT移住プロジェクト</h4>
                            <p>同じ働き方で、もっと豊かに</p>
                            <a href="https://migration.oita-creative.jp/2025/lp/" target="_blank" class="btn-outline">実績を見る →</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 料金プランセクション -->
        <section class="section-pricing" id="pricing">
            <div class="container">
                <h2 class="section-title"><span class="gradient-text">料金プラン</span></h2>
                <p class="section-subtitle">シンプルでわかりやすい2つのプラン</p>
                <div class="pricing-grid">
                    <div class="card pricing-card" data-anim>
                        <h3>ベーシックプラン</h3>
                        <div class="price">¥50,000<span class="price-suffix">〜</span></div>
                        <p class="price-note">税込 55,000円〜</p>
                        <ul class="pricing-features">
                            <li>LP制作（1ページ）</li>
                            <li>レスポンシブデザイン</li>
                            <li>最短3営業日納品</li>
                            <li>2回まで修正対応</li>
                        </ul>
                        <button class="btn-primary btn-block" onclick="openModal()">今すぐ相談する</button>
                    </div>
                    <div class="card pricing-card pricing-card-featured" data-anim>
                        <div class="pricing-badge">人気</div>
                        <h3>フォーム付きプラン</h3>
                        <div class="price">¥100,000</div>
                        <p class="price-note">税込 110,000円</p>
                        <ul class="pricing-features">
                            <li>LP制作（1ページ）</li>
                            <li>お問い合わせフォーム</li>
                            <li>サンクスページ</li>
                            <li>レスポンシブデザイン</li>
                            <li>最短5営業日納品</li>
                            <li>3回まで修正対応</li>
                        </ul>
                        <button class="btn-primary btn-block" onclick="openModal()">今すぐ相談する</button>
                    </div>
                </div>
                <p class="pricing-note">※ フォームは、お名前・メールアドレス・お問い合わせ内容などの基本項目を含みます。<br>※ 送信されたデータは、ご指定のメールアドレスに自動転送されます。</p>
            </div>
        </section>

        <!-- 制作の流れセクション -->
        <section class="section-flow">
            <div class="container">
                <h2 class="section-title">制作の流れ</h2>
                <div class="flow-timeline">
                    <div class="flow-item" data-anim>
                        <div class="flow-number">1</div>
                        <div class="flow-content">
                            <h4>ご相談（即日〜1日）</h4>
                            <p>お問い合わせフォームでご相談ください。「こんなことできる？」という質問だけでも大歓迎です。</p>
                        </div>
                    </div>
                    <div class="flow-item" data-anim>
                        <div class="flow-number">2</div>
                        <div class="flow-content">
                            <h4>ヒアリング（1日）</h4>
                            <p>どんな商品・サービスを、誰に、どう伝えたいのか。目的やターゲットをお聞かせください。</p>
                        </div>
                    </div>
                    <div class="flow-item" data-anim>
                        <div class="flow-number">3</div>
                        <div class="flow-content">
                            <h4>制作開始（1〜3日）</h4>
                            <p>ワイヤー・デザイン・コーディングを同時並行で進めます。途中経過もご確認いただけます。</p>
                        </div>
                    </div>
                    <div class="flow-item" data-anim>
                        <div class="flow-number">4</div>
                        <div class="flow-content">
                            <h4>納品（即日）</h4>
                            <p>修正対応後、納品・公開いたします。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQセクション -->
        <section class="section-faq" id="faq">
            <div class="container">
                <h2 class="section-title">よくある質問</h2>
                <div class="faq-list">
                    <div class="card faq-item" onclick="toggleFAQ(this)" data-anim>
                        <div class="faq-question">
                            <span>本当に3営業日で納品できるんですか？</span>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p>はい、可能です。ただし、素材のご提供や修正対応のタイミングによっては若干延びる場合があります。お急ぎの場合は、ご相談時にお知らせください。</p>
                        </div>
                    </div>
                    <div class="card faq-item" onclick="toggleFAQ(this)" data-anim>
                        <div class="faq-question">
                            <span>AIで作って、品質は大丈夫ですか？</span>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p>このサイト（yojitu.com）や、今ご覧いただいているこのページもAIで制作しています。ぜひ実際の品質をご確認ください。</p>
                        </div>
                    </div>
                    <div class="card faq-item" onclick="toggleFAQ(this)" data-anim>
                        <div class="faq-question">
                            <span>広告を出さないのですが、LPは必要ですか？</span>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p>はい、必要です。特に地方では、紹介や口コミの際に「このページを見てください」と使えるページが一つ増えるだけで、成約率が大きく変わります。ブランディングや、特定商品の訴求、メディア掲載時の受け皿としても有効です。</p>
                        </div>
                    </div>
                    <div class="card faq-item" onclick="toggleFAQ(this)" data-anim>
                        <div class="faq-question">
                            <span>修正は何回までできますか？</span>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p>基本プランは2回、フォーム付きプランは3回まで対応いたします。それ以上の修正は別途お見積もりとなります。</p>
                        </div>
                    </div>
                    <div class="card faq-item" onclick="toggleFAQ(this)" data-anim>
                        <div class="faq-question">
                            <span>公開後のサポートはありますか？</span>
                            <div class="faq-icon">+</div>
                        </div>
                        <div class="faq-answer">
                            <p>公開後の軽微な修正（誤字修正など）は対応可能です。大幅な修正や追加機能については別途お見積もりとなります。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 最終CTAセクション -->
        <section class="section-final-cta">
            <div class="container">
                <h2 class="section-title text-white">LP制作、5万円〜。最短3日。</h2>
                <p class="final-cta-text">「新商品のローンチに間に合わせたい」<br>「キャンペーンのLPが急遽必要になった」<br>「まずは話を聞いてみたい」<br><br>どんなご相談でも、お気軽にどうぞ。</p>
                <button class="btn-primary btn-large" onclick="openModal()">今すぐ無料相談する</button>
            </div>
        </section>
    </main>

    <!-- フッター -->
    <footer class="footer" role="contentinfo">
        <div class="container footer-content">
            <div class="footer-brand">
                <div class="footer-logo">YOJITU.COM</div>
                <p class="footer-tagline">LP制作サービス<br>5万円〜、最短3営業日</p>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h4>サービス</h4>
                    <ul>
                        <li><a href="#features">特徴</a></li>
                        <li><a href="#timing">タイミング</a></li>
                        <li><a href="#pricing">料金</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>実績・サポート</h4>
                    <ul>
                        <li><a href="#portfolio">実績</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#" onclick="openModal(); return false;">お問い合わせ</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>会社情報</h4>
                    <ul>
                        <li><a href="/about.php">会社概要</a></li>
                        <li><a href="/privacy.php">プライバシーポリシー</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; 2025 YOJITU.COM All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- モーダル -->
    <div class="modal" id="contactModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-overlay" onclick="closeModal()"></div>
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()" aria-label="閉じる">&times;</button>
            <h2 id="modalTitle">お問い合わせフォーム</h2>
            <form action="/contact/submit.php" method="POST">
                <div class="form-group">
                    <label for="company">会社名・屋号</label>
                    <input type="text" id="company" name="company" placeholder="例）株式会社サンプル">
                </div>
                <div class="form-group">
                    <label for="name">お名前<span class="required">*</span></label>
                    <input type="text" id="name" name="name" required placeholder="例）山田太郎">
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス<span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="例）example@example.com">
                </div>
                <div class="form-group">
                    <label for="phone">電話番号</label>
                    <input type="tel" id="phone" name="phone" placeholder="例）090-1234-5678">
                </div>
                <div class="form-group">
                    <label for="message">お問い合わせ内容<span class="required">*</span></label>
                    <textarea id="message" name="message" required placeholder="LPで訴求したい商品・サービス、目的などをお聞かせください。"></textarea>
                </div>
                <button type="submit" class="btn-primary btn-block">送信する</button>
                <p class="form-note">※2営業日以内にご返信いたします</p>
            </form>
        </div>
    </div>

    <!-- パターン表示（開発用・本番では削除） -->
    <div class="pattern-indicator">
        表示中: キャッチ<?php echo $currentPattern['catch']; ?> × CTA <?php echo $currentPattern['cta']; ?>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
