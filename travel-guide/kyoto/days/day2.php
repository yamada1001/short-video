<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/8（日）明日 - MTG後に四条・伏見');
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
                <h1 class="page-title">12/8（日）明日 ⛩️</h1>
                <p class="page-subtitle">13:00〜 | MTG後スタート | 6スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">今日の予定</div>
                    <div class="stat-value total-count">6</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 6</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">開始時刻</div>
                    <div class="stat-value" style="font-size: 20px;">13:00</div>
                </div>
            </div>

            <!-- お得な移動情報 -->
            <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-ticket-alt"></i>
                    今日のおすすめ切符
                </h3>
                <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">バス1日券：700円 + JR個別払い</p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">市バス乗り放題。<ruby>四条<rt>しじょう</rt></ruby>エリアはバスで移動、<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>へはJR<ruby>奈良線<rt>ならせん</rt></ruby>を利用。</p>
            </div>

            <!-- リセットボタン -->
            <div style="margin-bottom: 30px; text-align: center;">
                <button id="reset-button" class="btn" style="background: #dc3545; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    このページのチェックをリセット
                </button>
            </div>

            <!-- 午前：MTG -->
            <section class="section" id="mtg">
                <h2 class="section-title">午前：MTG</h2>
                <div class="card" style="background: #fff3cd; border-left: 4px solid #ffc107;">
                    <p style="color: #856404; margin: 0;">
                        <strong>🗓️ 午前中はMTG</strong><br>
                        MTG終了後、13:00頃からホテル出発を想定
                    </p>
                </div>
            </section>

            <!-- 午後：四条・祇園エリア -->
            <section class="section" id="shijo">
                <h2 class="section-title">午後：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>祇園<rt>ぎおん</rt></ruby>エリア（13:00出発）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-bus"></i> 移動：</strong>京都駅 → <ruby>四条<rt>しじょう</rt></ruby>（市バス 約15分）<br>
                    <span style="color: #666; font-size: 14px;">💰 230円 | 🎫 1日券利用可</span>
                </div>

                <ul class="spot-list">
                    <!-- 京都国立近代美術館 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">13:30 - 14:30</div>
                                <h3 class="spot-name">京都国立近代美術館</h3>
                                <p class="spot-note">現代アートを鑑賞。企画展をチェック。ゆったり文化的な時間を</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=京都国立近代美術館" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.momak.go.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-bus"></i> 移動：</strong>美術館 → <ruby>河原町<rt>かわらまち</rt></ruby>（市バス 約10分）<br>
                    <span style="color: #666; font-size: 14px;">🎫 1日券利用可</span>
                </div>

                <ul class="spot-list">
                    <!-- 梅園 河原町店 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">14:30 - 15:15</div>
                                <h3 class="spot-name"><ruby>梅園<rt>うめぞの</rt></ruby> <ruby>河原町店<rt>かわらまちてん</rt></ruby></h3>
                                <p class="spot-note">わらび餅が絶品！京都の老舗和菓子店でひと休み</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=梅園+河原町店" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=梅園+河原町店+わらび餅" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>梅園<rt>うめぞの</rt></ruby> → <ruby>錦市場<rt>にしきいちば</rt></ruby>（徒歩5分）
                </div>

                <ul class="spot-list">
                    <!-- 錦市場 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">15:15 - 16:00</div>
                                <h3 class="spot-name"><ruby>錦市場<rt>にしきいちば</rt></ruby>（こんなもんじゃ）</h3>
                                <p class="spot-note">京の台所。食べ歩きを楽しむ。豆乳ドーナツの「こんなもんじゃ」は必食</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=錦市場+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> 錦市場
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=こんなもんじゃ+錦市場" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> こんなもんじゃ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- 今西軒 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:00 - 16:30</div>
                                <h3 class="spot-name">今西軒</h3>
                                <p class="spot-note">焼き餅が名物。創業100年以上の老舗。お土産にもおすすめ</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=今西軒+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- 夕方：伏見稲荷 -->
            <section class="section" id="fushimi">
                <h2 class="section-title">夕方：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>エリア</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong><ruby>四条<rt>しじょう</rt></ruby> → 京都駅 → JR<ruby>稲荷駅<rt>いなりえき</rt></ruby><br>
                    <span style="color: #666; font-size: 14px;">💰 JR奈良線 150円 | ⏱️ 京都駅から5分</span><br>
                    <span style="color: #999; font-size: 13px;">※京阪 <ruby>祇園四条<rt>ぎおんしじょう</rt></ruby>→<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>（260円、直通15分）も可</span>
                </div>

                <ul class="spot-list">
                    <!-- 伏見稲荷大社 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-5" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:30 - 18:00</div>
                                <h3 class="spot-name"><ruby>伏見稲荷大社<rt>ふしみいなりたいしゃ</rt></ruby></h3>
                                <p class="spot-note">千本鳥居が有名。夕暮れ〜ライトアップが幻想的。外国人観光客に人気No.1</p>
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

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>JR<ruby>稲荷駅<rt>いなりえき</rt></ruby> → 京都駅（JR<ruby>奈良線<rt>ならせん</rt></ruby> 5分、150円）
                </div>
            </section>

            <!-- 夕食 -->
            <section class="section" id="dinner">
                <h2 class="section-title">18:30 夕食</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-6" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">18:30 -</div>
                                <h3 class="spot-name">京都駅周辺で夕食</h3>
                                <p class="spot-note">京都駅ビルや京都タワー周辺で気になるお店を選択。ラーメン小路、伊勢丹なども</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=京都駅+レストラン" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 京都駅レストラン検索
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=京都ラーメン小路" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> ラーメン小路
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
                        <strong style="font-weight: 400; color: #333;">明日の消化スポット：</strong>6スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li>午後：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>祇園<rt>ぎおん</rt></ruby>エリア（4スポット）</li>
                        <li>夕方：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>（1スポット）</li>
                        <li>夕食：京都駅周辺</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ✅ バス1日券（700円）+ JR個別払い（300円）= 計1,000円<br>
                        ✅ MTG後の午後スタートでも6スポット消化可能<br>
                        ✅ <ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>の夕暮れは特におすすめ
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#mtg" class="toc-link">午前：MTG</a></li>
                <li class="toc-item"><a href="#shijo" class="toc-link">午後：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>祇園<rt>ぎおん</rt></ruby></a></li>
                <li class="toc-item"><a href="#fushimi" class="toc-link">夕方：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby></a></li>
                <li class="toc-item"><a href="#dinner" class="toc-link">夕食</a></li>
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
            <li class="toc-item"><a href="#mtg" class="toc-link">午前：MTG</a></li>
            <li class="toc-item"><a href="#shijo" class="toc-link">午後：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>祇園<rt>ぎおん</rt></ruby></a></li>
            <li class="toc-item"><a href="#fushimi" class="toc-link">夕方：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby></a></li>
            <li class="toc-item"><a href="#dinner" class="toc-link">夕食</a></li>
            <li class="toc-item"><a href="#summary" class="toc-link">まとめ</a></li>
        </ul>
    </div>
</div>

<!-- リセットボタン用スクリプト -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const resetButton = document.getElementById('reset-button');

    if (resetButton) {
        resetButton.addEventListener('click', () => {
            if (confirm('このページのチェックをすべてリセットしますか？')) {
                // このページのlocalStorageキーを削除
                const storageKey = 'checked_spots_' + window.location.pathname;
                localStorage.removeItem(storageKey);

                // ページをリロードして表示を更新
                location.reload();
            }
        });
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
