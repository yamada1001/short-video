<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/7（土）今日 - 動ける場合');
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
                <h1 class="page-title">12/7（土）今日 🌆</h1>
                <p class="page-subtitle">16:00〜 | 動ける場合 | 3〜4スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">今日の予定</div>
                    <div class="stat-value total-count">4</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 4</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">開始時刻</div>
                    <div class="stat-value" style="font-size: 20px;">16:00</div>
                </div>
            </div>

            <!-- スケジュール -->
            <section class="section" id="spot-1">
                <h2 class="section-title">16:00 ホテル出発</h2>
                <div class="card">
                    <p style="color: #666;">京都駅周辺のホテルから出発を想定</p>
                </div>
            </section>

            <section class="section" id="spot-2">
                <h2 class="section-title">16:15 イノダコーヒ</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:15</div>
                                <h3 class="spot-name">イノダコーヒ</h3>
                                <p class="spot-note">軽く休憩。京都の老舗カフェでリラックス</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=イノダコーヒ+京都駅" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.inoda-coffee.co.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="section" id="spot-3">
                <h2 class="section-title">17:00 伏見稲荷大社</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>JR稲荷駅（京都駅から5分）
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">17:00 - 18:30</div>
                                <h3 class="spot-name">伏見稲荷大社</h3>
                                <p class="spot-note">夕暮れ〜ライトアップが美しい。千本鳥居をくぐる</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=伏見稲荷大社" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="http://inari.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                    <a href="https://www.google.com/search?q=伏見稲荷大社+千本鳥居" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="section" id="spot-4">
                <h2 class="section-title">19:00 鴨川沿い散歩</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>JRで京都駅へ戻り、四条方面へ
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">19:00 - 19:30</div>
                                <h3 class="spot-name">鴨川沿い散歩</h3>
                                <p class="spot-note">四条方面へ。夜の鴨川は雰囲気抜群</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=鴨川+四条大橋" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="section" id="spot-5">
                <h2 class="section-title">19:30 夕食</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">19:30 -</div>
                                <h3 class="spot-name">レバノン料理 汽 or 四条で夕食</h3>
                                <p class="spot-note">レバノン料理 汽 or 四条エリアで気になるお店を選択</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=レバノン料理+汽+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> レバノン料理 汽
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=四条+レストラン+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 四条レストラン検索
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
                        <strong style="font-weight: 400; color: #333;">今日の消化スポット：</strong>3〜4スポット
                    </p>
                    <p style="color: #999; font-size: 14px; line-height: 1.8;">
                        ※ 動けなければ明日（12/8）に回してもOK<br>
                        ※ 伏見稲荷大社の夕暮れ〜ライトアップは特におすすめ
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#spot-1" class="toc-link">16:00 ホテル出発</a></li>
                <li class="toc-item"><a href="#spot-2" class="toc-link">16:15 イノダコーヒ</a></li>
                <li class="toc-item"><a href="#spot-3" class="toc-link">17:00 伏見稲荷大社</a></li>
                <li class="toc-item"><a href="#spot-4" class="toc-link">19:00 鴨川沿い散歩</a></li>
                <li class="toc-item"><a href="#spot-5" class="toc-link">19:30 夕食</a></li>
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
            <li class="toc-item"><a href="#spot-1" class="toc-link">16:00 ホテル出発</a></li>
            <li class="toc-item"><a href="#spot-2" class="toc-link">16:15 イノダコーヒ</a></li>
            <li class="toc-item"><a href="#spot-3" class="toc-link">17:00 伏見稲荷大社</a></li>
            <li class="toc-item"><a href="#spot-4" class="toc-link">19:00 鴨川沿い散歩</a></li>
            <li class="toc-item"><a href="#spot-5" class="toc-link">19:30 夕食</a></li>
            <li class="toc-item"><a href="#summary" class="toc-link">まとめ</a></li>
        </ul>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
