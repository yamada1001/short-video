<?php
require_once '../includes/config.php';
define('PAGE_TITLE', '京都旅行 | 全19スポット制覇プラン');
require_once '../includes/header.php';
?>

<div class="container">
    <div class="guide-container">
        <main class="guide-main">
            <div class="page-header">
                <h1 class="page-title">京都旅行 🏯</h1>
                <p class="page-subtitle">2025年12月7日〜9日 | 2泊3日 | 全19スポット制覇プラン</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">訪問予定</div>
                    <div class="stat-value total-count">19</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 19</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">残り日数</div>
                    <div class="stat-value">3日</div>
                </div>
            </div>

            <!-- 日程リンク -->
            <section class="section" id="schedule">
                <h2 class="section-title">日程</h2>
                <div style="display: grid; gap: 16px;">
                    <a href="days/day1.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #4A90E2;"></i>
                            12/7（土）今日 - 動ける場合
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">16:00〜 | 3〜4スポット</p>
                        <p style="color: #999; font-size: 14px;">イノダコーヒ → 伏見稲荷大社 → 鴨川沿い散歩 → 夕食</p>
                    </a>

                    <a href="days/day2.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #4A90E2;"></i>
                            12/8（日）明日 - フル稼働
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">8:30〜 | 12スポット</p>
                        <p style="color: #999; font-size: 14px;">嵐山エリア → 東山エリア → 四条・河原町エリア</p>
                    </a>

                    <a href="days/day3.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #4A90E2;"></i>
                            12/9（月）明後日 - 午前のみ
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">8:30〜10:15 | 3スポット</p>
                        <p style="color: #999; font-size: 14px;">五重塔（東寺） → 東寺餅 → おみやげ小路</p>
                    </a>
                </div>
            </section>

            <!-- エリア別スポット -->
            <section class="section" id="areas">
                <h2 class="section-title">エリア別スポット</h2>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-torii-gate"></i> 伏見・京都駅周辺（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ イノダコーヒ</li>
                        <li>✓ 伏見稲荷大社</li>
                        <li>✓ 五重塔（東寺）</li>
                        <li>✓ 御菓子司 東寺餅</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-mountain"></i> 嵐山エリア（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ 竹林の小径</li>
                        <li>✓ 天龍寺</li>
                        <li>✓ 渡月橋</li>
                        <li>✓ キモノフォレスト</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-temple"></i> 東山エリア（5スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ 蹴上インクライン</li>
                        <li>✓ 南禅寺</li>
                        <li>✓ 永観堂</li>
                        <li>✓ 京都国立近代美術館</li>
                        <li>✓ 円山公園</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-shopping-bag"></i> 四条・河原町エリア（6スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ 鴨川沿い散歩</li>
                        <li>✓ レバノン料理 汽</li>
                        <li>✓ 梅園 河原町店</li>
                        <li>✓ 錦市場（こんなもんじゃ）</li>
                        <li>✓ 今西軒</li>
                        <li>✓ おみやげ小路 京小町</li>
                    </ul>
                </div>
            </section>

            <!-- 注意事項 -->
            <section class="section" id="notes">
                <h2 class="section-title">注意事項</h2>
                <div class="card">
                    <ul style="line-height: 2; color: #666;">
                        <li><strong style="font-weight: 400; color: #333;">移動手段：</strong>JR、地下鉄、嵐電、徒歩を組み合わせ</li>
                        <li><strong style="font-weight: 400; color: #333;">所要時間：</strong>各スポット15〜30分を想定</li>
                        <li><strong style="font-weight: 400; color: #333;">朝イチ：</strong>竹林の小径は8:30出発推奨（空いている）</li>
                        <li><strong style="font-weight: 400; color: #333;">夕暮れ：</strong>伏見稲荷大社はライトアップがおすすめ</li>
                        <li><strong style="font-weight: 400; color: #333;">バッファ：</strong>12/8は余裕あり、疲れたら調整可能</li>
                    </ul>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#schedule" class="toc-link">日程</a></li>
                <li class="toc-item"><a href="#areas" class="toc-link">エリア別スポット</a></li>
                <li class="toc-item"><a href="#notes" class="toc-link">注意事項</a></li>
            </ul>
        </aside>
    </div>
</div>

<!-- SP用フローティング目次ボタン -->
<button class="toc-toggle" aria-label="目次を開く">
    <i class="fas fa-list"></i>
</button>

<!-- SP用目次モーダル -->
<div class="toc-modal">
    <div class="toc-modal-content">
        <div class="toc-modal-header">
            <h3 class="toc-title">目次</h3>
            <button class="toc-modal-close" aria-label="閉じる">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="toc-list">
            <li class="toc-item"><a href="#schedule" class="toc-link">日程</a></li>
            <li class="toc-item"><a href="#areas" class="toc-link">エリア別スポット</a></li>
            <li class="toc-item"><a href="#notes" class="toc-link">注意事項</a></li>
        </ul>
    </div>
</div>

<!-- 京都トップページ専用スクリプト（全日程の達成率を合算） -->
<script src="<?php echo BASE_URL; ?>/assets/js/kyoto-index.js"></script>

<?php require_once '../includes/footer.php'; ?>
