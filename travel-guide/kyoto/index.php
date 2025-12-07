<?php
require_once '../includes/config.php';
define('PAGE_TITLE', '京都旅行 | 全16スポット制覇プラン');
require_once '../includes/header.php';
?>

<div class="container">
    <div class="guide-container">
        <main class="guide-main">
            <div class="page-header">
                <h1 class="page-title">京都旅行 🏯</h1>
                <p class="page-subtitle">2025年12月7日〜9日 | 2泊3日 | 全16スポット制覇プラン</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">訪問予定</div>
                    <div class="stat-value total-count">16</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 16</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">残り日数</div>
                    <div class="stat-value">2日</div>
                </div>
            </div>

            <!-- 全日程リセットボタン -->
            <div style="margin-bottom: 30px; text-align: center;">
                <button id="reset-all-button" class="btn" style="background: #dc3545; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    全日程のチェックをリセット
                </button>
            </div>

            <!-- 日程リンク -->
            <section class="section" id="schedule">
                <h2 class="section-title">日程</h2>
                <div style="display: grid; gap: 16px;">
                    <a href="days/day1.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #28a745;"></i>
                            12/7（土）昨日 - <ruby>東寺<rt>とうじ</rt></ruby>エリア
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">2スポット | ✅ 完了</p>
                        <p style="color: #999; font-size: 14px;"><ruby>東寺<rt>とうじ</rt></ruby>（<ruby>五重塔<rt>ごじゅうのとう</rt></ruby>） → <ruby>御菓子司<rt>おかしつかさ</rt></ruby> <ruby>東寺餅<rt>とうじもち</rt></ruby></p>
                    </a>

                    <a href="days/day2.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #4A90E2;"></i>
                            12/8（日）今日 - <ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>＋東山＋<ruby>四条<rt>しじょう</rt></ruby>
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">8:00〜 | 7スポット</p>
                        <p style="color: #999; font-size: 14px;"><ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>（朝1人）→ 東山エリア（13:00〜中山さんと合流）→ <ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby></p>
                    </a>

                    <a href="days/day3.php" class="card" style="text-decoration: none;">
                        <h3 style="font-size: 20px; font-weight: 400; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-day" style="color: #4A90E2;"></i>
                            12/9（月）明後日 - <ruby>嵐山<rt>あらしやま</rt></ruby>フル稼働
                        </h3>
                        <p style="color: #666; margin-bottom: 8px;">7:00〜 | 7スポット</p>
                        <p style="color: #999; font-size: 14px;"><ruby>嵐山<rt>あらしやま</rt></ruby>エリア（早朝）→ <ruby>東寺<rt>とうじ</rt></ruby> → レバノン料理 → おみやげ小路</p>
                    </a>
                </div>
            </section>

            <!-- エリア別スポット -->
            <section class="section" id="areas">
                <h2 class="section-title">エリア別スポット</h2>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-torii-gate"></i> <ruby>伏見<rt>ふしみ</rt></ruby>・京都駅周辺（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ <ruby>伏見稲荷大社<rt>ふしみいなりたいしゃ</rt></ruby></li>
                        <li>✓ <ruby>五重塔<rt>ごじゅうのとう</rt></ruby>（<ruby>東寺<rt>とうじ</rt></ruby>）</li>
                        <li>✓ <ruby>御菓子司<rt>おかしつかさ</rt></ruby> <ruby>東寺餅<rt>とうじもち</rt></ruby></li>
                        <li>✓ おみやげ小路 京小町</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-mountain"></i> <ruby>嵐山<rt>あらしやま</rt></ruby>エリア（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ <ruby>竹林<rt>ちくりん</rt></ruby>の<ruby>小径<rt>こみち</rt></ruby></li>
                        <li>✓ <ruby>天龍寺<rt>てんりゅうじ</rt></ruby></li>
                        <li>✓ <ruby>渡月橋<rt>とげつきょう</rt></ruby></li>
                        <li>✓ キモノフォレスト</li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-temple"></i> 東山エリア（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ <ruby>蹴上<rt>けあげ</rt></ruby>インクライン</li>
                        <li>✓ <ruby>南禅寺<rt>なんぜんじ</rt></ruby></li>
                        <li>✓ <ruby>永観堂<rt>えいかんどう</rt></ruby></li>
                        <li>✓ <ruby>円山公園<rt>まるやまこうえん</rt></ruby></li>
                    </ul>
                </div>

                <div class="card">
                    <h3 style="font-size: 18px; font-weight: 400; margin-bottom: 12px; color: #4A90E2;">
                        <i class="fas fa-shopping-bag"></i> <ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby>エリア（4スポット）
                    </h3>
                    <ul style="list-style: none; color: #666;">
                        <li>✓ レバノン料理 <ruby>汽<rt>き</rt></ruby></li>
                        <li>✓ <ruby>梅園<rt>うめぞの</rt></ruby> <ruby>河原町店<rt>かわらまちてん</rt></ruby></li>
                        <li>✓ <ruby>錦市場<rt>にしきいちば</rt></ruby>（こんなもんじゃ）</li>
                        <li>✓ 今西軒</li>
                    </ul>
                </div>
            </section>

            <!-- 注意事項 -->
            <section class="section" id="notes">
                <h2 class="section-title">注意事項</h2>
                <div class="card">
                    <ul style="line-height: 2; color: #666;">
                        <li><strong style="font-weight: 400; color: #333;">移動手段：</strong>JR、地下鉄、市バス、徒歩を組み合わせ</li>
                        <li><strong style="font-weight: 400; color: #333;">おすすめ切符：</strong>地下鉄・バス1日券（1,100円）がお得</li>
                        <li><strong style="font-weight: 400; color: #333;">朝イチ：</strong><ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>（8:00）、<ruby>嵐山<rt>あらしやま</rt></ruby>（7:00）は早朝が空いている</li>
                        <li><strong style="font-weight: 400; color: #333;">12/8：</strong>午前は1人で<ruby>伏見稲荷<rt>ふしみいなり</rt></ruby>、13:00〜中山さんと合流</li>
                        <li><strong style="font-weight: 400; color: #333;">12/9：</strong>早朝7:00出発で<ruby>嵐山<rt>あらしやま</rt></ruby>の朝イチを満喫</li>
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
<script>
// 全日程のlocalStorageキーを定義
const dayKeys = [
    'checked_spots_/travel-guide/kyoto/days/day1.php',
    'checked_spots_/travel-guide/kyoto/days/day2.php',
    'checked_spots_/travel-guide/kyoto/days/day3.php'
];

// 京都トップページ専用のupdateStats関数（guide.jsをオーバーライド）
window.updateKyotoStats = () => {
    let totalChecked = 0;
    dayKeys.forEach(key => {
        const savedData = localStorage.getItem(key);
        if (savedData) {
            try {
                const checkedIds = JSON.parse(savedData);
                totalChecked += checkedIds.length;
            } catch (e) {
                console.error('localStorage parse error:', e);
            }
        }
    });

    // 統計情報を更新
    const checkedElement = document.querySelector('.checked-count');
    if (checkedElement) {
        checkedElement.textContent = totalChecked;
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // 初期表示時に達成率を更新
    updateKyotoStats();

    // guide.jsのupdateStatsを無効化して、updateKyotoStatsに置き換え
    // これでguide.jsが0で上書きするのを防ぐ
    setTimeout(() => {
        updateKyotoStats();
    }, 100);

    // 全日程リセットボタンのイベント
    const resetAllButton = document.getElementById('reset-all-button');
    if (resetAllButton) {
        resetAllButton.addEventListener('click', () => {
            if (confirm('全日程（day1〜day3）のチェックをすべてリセットしますか？')) {
                // 全日程のlocalStorageキーを削除
                dayKeys.forEach(key => {
                    localStorage.removeItem(key);
                });

                // ページをリロードして表示を更新
                location.reload();
            }
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
