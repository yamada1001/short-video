<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/8（日）今日 - 伏見稲荷＋東山＋四条');
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="guide-container">
        <main class="guide-main">
            <!-- 戻るリンク -->
            <div style="margin-bottom: 20px;">
                <a href="../index.php" style="display: inline-flex; align-items: center; gap: 8px; color: #4A90E2; text-decoration: none; font-size: 14px;">
                    <i class="fas fa-arrow-left"></i>
                    京都旅行トップに戻る
                </a>
            </div>

            <div class="page-header">
                <h1 class="page-title">12/8（日）今日 ⛩️</h1>
                <p class="page-subtitle">午前：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby> | 午後：東山・<ruby>四条<rt>しじょう</rt></ruby> | 7スポット消化</p>
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
                    <div class="stat-value" style="font-size: 20px;">8:00</div>
                </div>
            </div>

            <!-- エリアマップ -->
            <div class="card" style="margin-bottom: 30px;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marked-alt"></i>
                    今日回るエリア
                </h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52296.93425395343!2d135.7406!3d34.9944!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x600108d6c5f1d36f%3A0xe4cf9ba87e3fe55c!2z5Lqs6YO95biC!5e0!3m2!1sja!2sjp!4v1701234567890!5m2!1sja!2sjp"
                    width="100%"
                    height="400"
                    style="border:0; border-radius: 8px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <p style="margin: 12px 0 16px 0; font-size: 14px; color: #666;">
                    <i class="fas fa-route"></i> <ruby>伏見稲荷<rt>ふしみいなり</rt></ruby> → <ruby>蹴上<rt>けあげ</rt></ruby> → <ruby>南禅寺<rt>なんぜんじ</rt></ruby> → <ruby>永観堂<rt>えいかんどう</rt></ruby> → <ruby>円山公園<rt>まるやまこうえん</rt></ruby> → <ruby>梅園<rt>うめぞの</rt></ruby> → <ruby>錦市場<rt>にしきいちば</rt></ruby> の順路
                </p>
                <a href="https://www.google.com/maps/dir/伏見稲荷大社/蹴上インクライン+京都/南禅寺+京都/永観堂+京都/円山公園+京都/梅園+河原町店+京都/錦市場+京都/" target="_blank" class="btn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: #4285F4; text-decoration: none;">
                    <i class="fas fa-map-marked-alt"></i>
                    Google Mapsでルート全体を見る（7スポット）
                </a>
            </div>

            <!-- お得な移動情報 -->
            <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-ticket-alt"></i>
                    今日のおすすめ切符
                </h3>
                <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">地下鉄・バス1日券：1,100円 + JR個別払い150円</p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;"><ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>へはJR<ruby>奈良線<rt>ならせん</rt></ruby>、東山エリアは地下鉄、<ruby>四条<rt>しじょう</rt></ruby>はバスで移動。</p>
            </div>

            <!-- リセットボタン -->
            <div style="margin-bottom: 30px; text-align: center;">
                <button id="reset-button" class="btn" style="background: #dc3545; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    このページのチェックをリセット
                </button>
            </div>

            <!-- 午前：伏見稲荷（1人） -->
            <section class="section" id="fushimi">
                <h2 class="section-title">午前：<ruby>伏見稲荷大社<rt>ふしみいなりたいしゃ</rt></ruby>（1人）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>京都駅 → JR<ruby>稲荷駅<rt>いなりえき</rt></ruby>（JR<ruby>奈良線<rt>ならせん</rt></ruby> 5分）<br>
                    <span style="color: #666; font-size: 14px;">💰 150円 | ⏱️ 5分 | 🚉 1駅</span>
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">8:00 - 10:00</div>
                                <h3 class="spot-name"><ruby>伏見稲荷大社<rt>ふしみいなりたいしゃ</rt></ruby></h3>
                                <p class="spot-note">千本鳥居が有名。朝イチが空いてる！外国人観光客に人気No.1</p>
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
                    <strong><i class="fas fa-train"></i> 移動：</strong>JR<ruby>稲荷駅<rt>いなりえき</rt></ruby> → 京都駅 → 地下鉄<ruby>烏丸線<rt>からすません</rt></ruby>「<ruby>烏丸御池<rt>からすまおいけ</rt></ruby>」→ 東西線「<ruby>蹴上駅<rt>けあげえき</rt></ruby>」<br>
                    <span style="color: #666; font-size: 14px;">💰 JR 150円 + 地下鉄 260円 | ⏱️ 約25分 | 🎫 1日券利用可（地下鉄のみ）</span>
                </div>
            </section>

            <!-- 午後：東山エリア（中山さんと合流） -->
            <section class="section" id="higashiyama">
                <h2 class="section-title">午後：東山エリア（中山さんと合流 13:00〜）</h2>

                <ul class="spot-list">
                    <!-- 蹴上インクライン -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">13:30 - 14:00</div>
                                <h3 class="spot-name"><ruby>蹴上<rt>けあげ</rt></ruby>インクライン</h3>
                                <p class="spot-note">廃線跡のフォトスポット。線路の上を歩けるインスタ映えスポット</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=蹴上インクライン" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://www.google.com/search?q=蹴上インクライン" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>蹴上<rt>けあげ</rt></ruby>インクライン → <ruby>南禅寺<rt>なんぜんじ</rt></ruby>（徒歩5分）
                </div>

                <ul class="spot-list">
                    <!-- 南禅寺 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">14:00 - 15:00</div>
                                <h3 class="spot-name"><ruby>南禅寺<rt>なんぜんじ</rt></ruby></h3>
                                <p class="spot-note">水路閣が有名。石川五右衛門の「絶景かな」の舞台</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=南禅寺+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://nanzenji.or.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                    <a href="https://www.google.com/search?q=南禅寺+水路閣" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 画像検索
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>南禅寺<rt>なんぜんじ</rt></ruby> → <ruby>永観堂<rt>えいかんどう</rt></ruby>（徒歩10分）
                </div>

                <ul class="spot-list">
                    <!-- 永観堂 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">15:00 - 15:45</div>
                                <h3 class="spot-name"><ruby>永観堂<rt>えいかんどう</rt></ruby></h3>
                                <p class="spot-note">紅葉の名所。みかえり阿弥陀が有名</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=永観堂+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="http://www.eikando.or.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>永観堂<rt>えいかんどう</rt></ruby> → <ruby>円山公園<rt>まるやまこうえん</rt></ruby>（徒歩15分）
                </div>

                <ul class="spot-list">
                    <!-- 円山公園 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-5" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:00 - 16:30</div>
                                <h3 class="spot-name"><ruby>円山公園<rt>まるやまこうえん</rt></ruby></h3>
                                <p class="spot-note"><ruby>祇園<rt>ぎおん</rt></ruby>のそば。しだれ桜が有名な公園</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=円山公園+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- 夕方：四条・河原町エリア -->
            <section class="section" id="shijo">
                <h2 class="section-title">夕方：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby>エリア</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-bus"></i> 移動：</strong><ruby>円山公園<rt>まるやまこうえん</rt></ruby> → <ruby>河原町<rt>かわらまち</rt></ruby>（市バス 約10分）<br>
                    <span style="color: #666; font-size: 14px;">🎫 1日券利用可</span>
                </div>

                <ul class="spot-list">
                    <!-- 梅園 河原町店 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-6" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:45 - 17:30</div>
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
                    <!-- 錦市場・今西軒 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-7" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">17:30 - 18:15</div>
                                <h3 class="spot-name"><ruby>錦市場<rt>にしきいちば</rt></ruby>・今西軒</h3>
                                <p class="spot-note">京の台所。食べ歩きを楽しむ。豆乳ドーナツの「こんなもんじゃ」＋焼き餅の「今西軒」</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=錦市場+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> 錦市場
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=今西軒+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> 今西軒
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-bus"></i> 帰路：</strong><ruby>四条<rt>しじょう</rt></ruby> → 京都駅（市バス 約15分）<br>
                    <span style="color: #666; font-size: 14px;">🎫 1日券利用可</span>
                </div>
            </section>

            <!-- まとめ -->
            <section class="section" id="summary">
                <h2 class="section-title">まとめ</h2>
                <div class="card">
                    <p style="margin-bottom: 16px; color: #666;">
                        <strong style="font-weight: 400; color: #333;">今日の消化スポット：</strong>7スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li>午前：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>（1人、1スポット）</li>
                        <li>午後：東山エリア（2人、4スポット）</li>
                        <li>夕方：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby>エリア（2人、2スポット）</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ✅ 地下鉄・バス1日券（1,100円）+ JR個別払い（300円）= 計1,400円<br>
                        ✅ 午前は1人で<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>、午後から中山さんと合流<br>
                        ✅ 広範囲を効率的に回れるプラン
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#fushimi" class="toc-link">午前：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby></a></li>
                <li class="toc-item"><a href="#higashiyama" class="toc-link">午後：東山</a></li>
                <li class="toc-item"><a href="#shijo" class="toc-link">夕方：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby></a></li>
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
            <li class="toc-item"><a href="#fushimi" class="toc-link">午前：<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby></a></li>
            <li class="toc-item"><a href="#higashiyama" class="toc-link">午後：東山</a></li>
            <li class="toc-item"><a href="#shijo" class="toc-link">夕方：<ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby></a></li>
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
