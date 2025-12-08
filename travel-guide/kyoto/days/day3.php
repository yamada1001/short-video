<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/9（月）明後日 - 嵐山フル稼働');
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
                <p class="page-subtitle">7:00〜 | <ruby>嵐山<rt>あらしやま</rt></ruby>＋<ruby>四条<rt>しじょう</rt></ruby> | 7スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">今日の予定</div>
                    <div class="stat-value total-count">7</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 7</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">開始時刻</div>
                    <div class="stat-value" style="font-size: 20px;">7:00</div>
                </div>
            </div>

            <!-- エリアマップ -->
            <div class="card" style="margin-bottom: 30px;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marked-alt"></i>
                    今日回るエリア
                </h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6537.373785545632!2d135.66547807649917!3d35.00941007282794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6001075f3e3d7d0d%3A0x8e1f1e88b4f4e4e4!2z5auQ5bGx!5e0!3m2!1sja!2sjp!4v1701234567890!5m2!1sja!2sjp"
                    width="100%"
                    height="400"
                    style="border:0; border-radius: 8px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <p style="margin: 12px 0 16px 0; font-size: 14px; color: #666;">
                    <i class="fas fa-route"></i> TUNE STAY KYOTO → <ruby>竹林<rt>ちくりん</rt></ruby>の<ruby>小径<rt>こみち</rt></ruby> → <ruby>天龍寺<rt>てんりゅうじ</rt></ruby> → <ruby>渡月橋<rt>とげつきょう</rt></ruby> → キモノフォレスト → <ruby>東寺<rt>とうじ</rt></ruby> → レバノン料理 <ruby>汽<rt>き</rt></ruby> → おみやげ小路 の順路
                </p>
                <a href="https://www.google.com/maps/dir/TUNE+STAY+KYOTO+京都/竹林の小径+嵐山/天龍寺+嵐山/渡月橋+嵐山/キモノフォレスト+嵐電嵐山駅/東寺+京都/レバノン料理+汽+京都/おみやげ小路+京小町+京都駅/" target="_blank" class="btn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: #4285F4; text-decoration: none;">
                    <i class="fas fa-map-marked-alt"></i>
                    Google Mapsでルート全体を見る（ホテル→7スポット）
                </a>
            </div>

            <!-- お得な移動情報 -->
            <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-ticket-alt"></i>
                    今日のおすすめ切符
                </h3>
                <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">JR＋地下鉄個別払い：計920円</p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">JR<ruby>嵯峨野線<rt>さがのせん</rt></ruby>往復480円 + 地下鉄往復440円。<ruby>嵐山<rt>あらしやま</rt></ruby>と<ruby>東寺<rt>とうじ</rt></ruby>は徒歩で回れます。</p>
            </div>

            <!-- リセットボタン -->
            <div style="margin-bottom: 30px; text-align: center;">
                <button id="reset-button" class="btn" style="background: #dc3545; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    このページのチェックをリセット
                </button>
            </div>

            <!-- 早朝：嵐山エリア -->
            <section class="section" id="arashiyama">
                <h2 class="section-title">早朝：<ruby>嵐山<rt>あらしやま</rt></ruby>エリア（7:00出発）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>京都駅 → JR<ruby>嵯峨嵐山駅<rt>さがあらしやまえき</rt></ruby>（JR<ruby>嵯峨野線<rt>さがのせん</rt></ruby>）<br>
                    <span style="color: #666; font-size: 14px;">💰 240円 | ⏱️ 約17分</span>
                </div>

                <ul class="spot-list">
                    <!-- 竹林の小径 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">7:45 - 8:25</div>
                                <h3 class="spot-name"><ruby>竹林<rt>ちくりん</rt></ruby>の<ruby>小径<rt>こみち</rt></ruby></h3>
                                <p class="spot-note">朝イチが空いてる！幻想的な<ruby>竹林<rt>ちくりん</rt></ruby>を散策。朝の光が美しい</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=竹林の小径+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=竹林の小径+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>竹林<rt>ちくりん</rt></ruby>の<ruby>小径<rt>こみち</rt></ruby> → <ruby>天龍寺<rt>てんりゅうじ</rt></ruby>（徒歩2分）
                </div>

                <ul class="spot-list">
                    <!-- 天龍寺 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">8:25 - 9:15</div>
                                <h3 class="spot-name"><ruby>天龍寺<rt>てんりゅうじ</rt></ruby></h3>
                                <p class="spot-note">世界遺産の庭園を拝観。<ruby>曹源池庭園<rt>そうげんちていえん</rt></ruby>が美しい</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=天龍寺+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.tenryuji.com/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>天龍寺<rt>てんりゅうじ</rt></ruby> → <ruby>渡月橋<rt>とげつきょう</rt></ruby>（徒歩5分）
                </div>

                <ul class="spot-list">
                    <!-- 渡月橋 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">9:15 - 9:45</div>
                                <h3 class="spot-name"><ruby>渡月橋<rt>とげつきょう</rt></ruby></h3>
                                <p class="spot-note"><ruby>嵐山<rt>あらしやま</rt></ruby>のシンボル。<ruby>桂川<rt>かつらがわ</rt></ruby>にかかる優雅な橋</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=渡月橋+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=渡月橋+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>渡月橋<rt>とげつきょう</rt></ruby> → キモノフォレスト（徒歩20分 or <ruby>嵐電<rt>らんでん</rt></ruby>160円）
                </div>

                <ul class="spot-list">
                    <!-- キモノフォレスト -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">9:45 - 10:15</div>
                                <h3 class="spot-name">キモノフォレスト</h3>
                                <p class="spot-note"><ruby>嵐電嵐山駅<rt>らんでんあらしやまえき</rt></ruby>。<ruby>友禅柱<rt>ゆうぜんばしら</rt></ruby>のライトアップが幻想的</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=キモノフォレスト+嵐電嵐山駅" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=キモノフォレスト+嵐山" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong><ruby>嵐山<rt>あらしやま</rt></ruby> → 京都駅（JR<ruby>嵯峨野線<rt>さがのせん</rt></ruby> 17分、240円）
                </div>
            </section>

            <!-- 午前：東寺・お土産 -->
            <section class="section" id="toji">
                <h2 class="section-title">午前：<ruby>東寺<rt>とうじ</rt></ruby>・お土産</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong>京都駅 → <ruby>東寺<rt>とうじ</rt></ruby>（徒歩15分）
                </div>

                <ul class="spot-list">
                    <!-- 東寺 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-5" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">11:15 - 11:45</div>
                                <h3 class="spot-name"><ruby>東寺<rt>とうじ</rt></ruby>（<ruby>五重塔<rt>ごじゅうのとう</rt></ruby>）</h3>
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

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>東寺<rt>とうじ</rt></ruby> → 京都駅（徒歩15分）→ 地下鉄<ruby>烏丸線<rt>からすません</rt></ruby>「<ruby>四条<rt>しじょう</rt></ruby>」（3分）<br>
                    <span style="color: #666; font-size: 14px;">💰 220円 | ⏱️ 約20分</span>
                </div>

                <ul class="spot-list">
                    <!-- レバノン料理 汽 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-6" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">12:00 - 13:00</div>
                                <h3 class="spot-name">レバノン料理 <ruby>汽<rt>き</rt></ruby></h3>
                                <p class="spot-note">本格レバノン料理のランチ。ヴィーガン・ベジタリアン対応も充実</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=レバノン料理+汽+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=レバノン料理+汽+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>四条<rt>しじょう</rt></ruby> → 京都駅（地下鉄<ruby>烏丸線<rt>からすません</rt></ruby> 3分）<br>
                    <span style="color: #666; font-size: 14px;">💰 220円 | ⏱️ 約10分</span>
                </div>

                <ul class="spot-list">
                    <!-- おみやげ小路 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day3-7" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">13:15 -</div>
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
                        <strong style="font-weight: 400; color: #333;">最終日の消化スポット：</strong>7スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li>早朝：<ruby>嵐山<rt>あらしやま</rt></ruby>エリア（4スポット）</li>
                        <li>午前：<ruby>東寺<rt>とうじ</rt></ruby>（1スポット）</li>
                        <li>昼：レバノン料理 <ruby>汽<rt>き</rt></ruby>（1スポット）</li>
                        <li>午後：お土産（1スポット）</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ✅ JR＋地下鉄で920円、コスパ良好<br>
                        ✅ 早朝7:00出発で<ruby>嵐山<rt>あらしやま</rt></ruby>の朝イチを満喫<br>
                        ✅ レバノン料理でエキゾチックなランチ<br>
                        ✅ 全16スポット制覇お疲れ様でした！
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#arashiyama" class="toc-link">早朝：<ruby>嵐山<rt>あらしやま</rt></ruby></a></li>
                <li class="toc-item"><a href="#toji" class="toc-link">午前：<ruby>東寺<rt>とうじ</rt></ruby>・お土産</a></li>
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
            <li class="toc-item"><a href="#arashiyama" class="toc-link">早朝：<ruby>嵐山<rt>あらしやま</rt></ruby></a></li>
            <li class="toc-item"><a href="#toji" class="toc-link">午前：<ruby>東寺<rt>とうじ</rt></ruby>・お土産</a></li>
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
