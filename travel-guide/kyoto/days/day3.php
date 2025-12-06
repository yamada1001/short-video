<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/9（月）明後日 - 午前のみ');
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="guide-container">
        <main class="guide-main">
            <!-- 戻るリンク -->
            <div style="margin-bottom: 20px;">
                <a href="../index.php" style="display: inline-flex; align-items: center; gap: 6px; color: #4A90E2; text-decoration: none; font-size: 14px;">
                    <i class="fas fa-arrow-left"></i>
                    京都旅行トップに戻る
                </a>
            </div>

            <div class="page-header">
                <h1 class="page-title">12/9（月）明後日 🏯</h1>
                <p class="page-subtitle">8:30〜10:15 | 午前のみ | 3スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">今日の予定</div>
                    <div class="stat-value total-count">3</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 3</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">開始時刻</div>
                    <div class="stat-value" style="font-size: 20px;">8:30</div>
                </div>
            </div>

            <!-- スケジュール -->
            <section class="section" id="spot-1">
                <h2 class="section-title">8:30 ホテル出発</h2>
                <div class="card">
                    <p style="color: #666;">京都駅周辺のホテルから出発</p>
                </div>
            </section>

            <section class="section" id="spot-2">
                <h2 class="section-title">8:45 五重塔（東寺）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong>徒歩 or バス（京都駅から約15分）
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">8:45 - 9:30</div>
                                <h3 class="spot-name">五重塔（東寺）</h3>
                                <p class="spot-note">日本一高い木造塔（55m）。世界遺産の古刹</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=東寺+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://toji.or.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                    <a href="https://www.google.com/search?q=東寺+五重塔" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="section" id="spot-3">
                <h2 class="section-title">9:30 御菓子司 東寺餅</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">9:30 - 10:00</div>
                                <h3 class="spot-name">御菓子司 東寺餅</h3>
                                <p class="spot-note">東寺のすぐ近く。名物の東寺餅をお土産に</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=御菓子司+東寺餅" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=東寺餅" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="section" id="spot-4">
                <h2 class="section-title">10:00 京都駅へ戻る</h2>
                <div class="card">
                    <p style="color: #666;">徒歩 or バスで京都駅へ</p>
                </div>
            </section>

            <section class="section" id="spot-5">
                <h2 class="section-title">10:15 おみやげ小路 京小町</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">10:15 -</div>
                                <h3 class="spot-name">おみやげ小路 京小町</h3>
                                <p class="spot-note">京都駅構内。お土産を購入して旅の締めくくり</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=おみやげ小路+京小町+京都駅" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=おみやげ小路+京小町" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- まとめ -->
            <section class="section" id="summary">
                <h2 class="section-title">まとめ</h2>
                <div class="card">
                    <p style="margin-bottom: 16px; color: #666;">
                        <strong style="font-weight: 400; color: #333;">最終日の消化スポット：</strong>3スポット
                    </p>
                    <p style="color: #999; font-size: 14px; line-height: 1.8;">
                        ※ 午前で余裕を持って終了<br>
                        ※ お土産購入の時間も確保<br>
                        ※ 全19スポット制覇お疲れ様でした！
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#spot-1" class="toc-link">8:30 ホテル出発</a></li>
                <li class="toc-item"><a href="#spot-2" class="toc-link">8:45 五重塔（東寺）</a></li>
                <li class="toc-item"><a href="#spot-3" class="toc-link">9:30 東寺餅</a></li>
                <li class="toc-item"><a href="#spot-4" class="toc-link">10:00 京都駅へ</a></li>
                <li class="toc-item"><a href="#spot-5" class="toc-link">10:15 おみやげ小路</a></li>
                <li class="toc-item"><a href="#summary" class="toc-link">まとめ</a></li>
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
            <li class="toc-item"><a href="#spot-1" class="toc-link">8:30 ホテル出発</a></li>
            <li class="toc-item"><a href="#spot-2" class="toc-link">8:45 五重塔（東寺）</a></li>
            <li class="toc-item"><a href="#spot-3" class="toc-link">9:30 東寺餅</a></li>
            <li class="toc-item"><a href="#spot-4" class="toc-link">10:00 京都駅へ</a></li>
            <li class="toc-item"><a href="#spot-5" class="toc-link">10:15 おみやげ小路</a></li>
            <li class="toc-item"><a href="#summary" class="toc-link">まとめ</a></li>
        </ul>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
