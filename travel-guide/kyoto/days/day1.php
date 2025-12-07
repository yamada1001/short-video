<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/7（土）昨日 - 東寺エリア');
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
                <h1 class="page-title">12/7（土）昨日 🏯</h1>
                <p class="page-subtitle"><ruby>東寺<rt>とうじ</rt></ruby>エリア | 2スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">昨日の予定</div>
                    <div class="stat-value total-count">2</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 2</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">ステータス</div>
                    <div class="stat-value" style="font-size: 18px; color: #28a745;">✅ 完了</div>
                </div>
            </div>

            <!-- エリアマップ -->
            <div class="card" style="margin-bottom: 30px;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marked-alt"></i>
                    昨日回ったエリア
                </h3>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3268.2986870819386!2d135.74751937649917!3d34.98037207282794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x600108ae918b02ef%3A0xb61a446a7bf4a4ec!2z5p2x5a-6!5e0!3m2!1sja!2sjp!4v1701234567890!5m2!1sja!2sjp"
                    width="100%"
                    height="350"
                    style="border:0; border-radius: 8px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <p style="margin: 12px 0 16px 0; font-size: 14px; color: #666;">
                    <i class="fas fa-route"></i> <ruby>東寺<rt>とうじ</rt></ruby>（<ruby>五重塔<rt>ごじゅうのとう</rt></ruby>）→ <ruby>東寺餅<rt>とうじもち</rt></ruby> の順路
                </p>
                <a href="https://www.google.com/maps/dir/東寺+京都/御菓子司+東寺餅+京都/" target="_blank" class="btn" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: #4285F4; text-decoration: none;">
                    <i class="fas fa-map-marked-alt"></i>
                    Google Mapsでルート全体を見る（2スポット）
                </a>
            </div>

            <!-- お得な移動情報 -->
            <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-walking"></i>
                    移動方法
                </h3>
                <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">徒歩：0円</p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">京都駅から<ruby>東寺<rt>とうじ</rt></ruby>まで徒歩約15分。切符不要で経済的！</p>
            </div>

            <!-- リセットボタン -->
            <div style="margin-bottom: 30px; text-align: center;">
                <button id="reset-button" class="btn" style="background: #dc3545; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    このページのチェックをリセット
                </button>
            </div>

            <!-- スケジュール -->
            <section class="section" id="spot-1">
                <h2 class="section-title"><ruby>東寺<rt>とうじ</rt></ruby>（<ruby>五重塔<rt>ごじゅうのとう</rt></ruby>）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong>京都駅 → <ruby>東寺<rt>とうじ</rt></ruby>（徒歩15分）
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">1スポット目</div>
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
            </section>

            <section class="section" id="spot-2">
                <h2 class="section-title"><ruby>御菓子司<rt>おかしつかさ</rt></ruby> <ruby>東寺餅<rt>とうじもち</rt></ruby></h2>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>東寺<rt>とうじ</rt></ruby>のすぐ近く（徒歩2分）
                </div>

                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">2スポット目</div>
                                <h3 class="spot-name"><ruby>御菓子司<rt>おかしつかさ</rt></ruby> <ruby>東寺餅<rt>とうじもち</rt></ruby></h3>
                                <p class="spot-note"><ruby>東寺<rt>とうじ</rt></ruby>のすぐ近く。名物の<ruby>東寺餅<rt>とうじもち</rt></ruby>をお土産に</p>
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

            <!-- まとめ -->
            <section class="section" id="summary">
                <h2 class="section-title">まとめ</h2>
                <div class="card">
                    <p style="margin-bottom: 16px; color: #666;">
                        <strong style="font-weight: 400; color: #333;">昨日の消化スポット：</strong>2スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li><ruby>東寺<rt>とうじ</rt></ruby>エリア（<ruby>五重塔<rt>ごじゅうのとう</rt></ruby>、<ruby>東寺餅<rt>とうじもち</rt></ruby>）</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ✅ 徒歩のみで0円、とても経済的<br>
                        ✅ 世界遺産の<ruby>東寺<rt>とうじ</rt></ruby>を堪能<br>
                        ✅ お疲れ様でした！
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#spot-1" class="toc-link"><ruby>東寺<rt>とうじ</rt></ruby></a></li>
                <li class="toc-item"><a href="#spot-2" class="toc-link"><ruby>東寺餅<rt>とうじもち</rt></ruby></a></li>
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
            <li class="toc-item"><a href="#spot-1" class="toc-link"><ruby>東寺<rt>とうじ</rt></ruby></a></li>
            <li class="toc-item"><a href="#spot-2" class="toc-link"><ruby>東寺餅<rt>とうじもち</rt></ruby></a></li>
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
