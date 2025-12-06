<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/7（土）今日 - 東山エリア先行消化');
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
                <p class="page-subtitle">16:00〜 | 東山エリア | 6スポット消化</p>
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
                    <div class="stat-value" style="font-size: 20px;">16:00</div>
                </div>
            </div>

            <!-- お得な移動情報 -->
            <div class="card" style="margin-bottom: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h3 style="margin: 0 0 12px 0; font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-ticket-alt"></i>
                    今日のおすすめ切符
                </h3>
                <p style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold;">地下鉄・バス1日券：1,100円</p>
                <p style="margin: 0; font-size: 14px; opacity: 0.9;">地下鉄+市バス乗り放題。地下鉄で<ruby>蹴上<rt>けあげ</rt></ruby>へ、帰りはバスで自由に移動できます。</p>
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
                <h2 class="section-title">16:00 ホテル出発</h2>
                <div class="card">
                    <p style="color: #666; margin-bottom: 12px;">京都駅周辺のホテルから出発を想定</p>
                    <div class="transit-info">
                        <strong><i class="fas fa-subway"></i> 移動：</strong>地下鉄<ruby>烏丸<rt>からすま</rt></ruby>線「京都駅」→「<ruby>烏丸御池<rt>からすまおいけ</rt></ruby>」（乗換）→ 東西線「<ruby>蹴上<rt>けあげ</rt></ruby>駅」<br>
                        <span style="color: #666; font-size: 14px;">💰 260円 | ⏱️ 約15分 | 🎫 1日券利用可</span>
                    </div>
                </div>
            </section>

            <section class="section" id="spot-2">
                <h2 class="section-title">16:30 <ruby>蹴上<rt>けあげ</rt></ruby>インクライン</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:30 - 17:00</div>
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
            </section>

            <section class="section" id="spot-3">
                <h2 class="section-title">17:00 <ruby>南禅寺<rt>なんぜんじ</rt></ruby></h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">17:00 - 18:00</div>
                                <h3 class="spot-name"><ruby>南禅寺<rt>なんぜんじ</rt></ruby></h3>
                                <p class="spot-note">水路閣が有名。石川五右衛門の「絶景かな」の舞台。夕暮れ時の雰囲気が最高</p>
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
            </section>

            <section class="section" id="spot-4">
                <h2 class="section-title">18:00 <ruby>永観堂<rt>えいかんどう</rt></ruby></h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">18:00 - 18:45</div>
                                <h3 class="spot-name"><ruby>永観堂<rt>えいかんどう</rt></ruby></h3>
                                <p class="spot-note">紅葉の名所。みかえり阿弥陀が有名。ライトアップがあれば最高</p>
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
            </section>

            <section class="section" id="spot-5">
                <h2 class="section-title">18:45 <ruby>円山公園<rt>まるやまこうえん</rt></ruby></h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">18:45 - 19:15</div>
                                <h3 class="spot-name"><ruby>円山公園<rt>まるやまこうえん</rt></ruby></h3>
                                <p class="spot-note"><ruby>祇園<rt>ぎおん</rt></ruby>のそば。しだれ桜が有名な公園。夜のライトアップも美しい</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=円山公園+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="transit-info">
                    <strong><i class="fas fa-walking"></i> 移動：</strong><ruby>円山公園<rt>まるやまこうえん</rt></ruby> → <ruby>鴨川<rt>かもがわ</rt></ruby>・<ruby>四条<rt>しじょう</rt></ruby>（徒歩10分）
                </div>
            </section>

            <section class="section" id="spot-6">
                <h2 class="section-title">19:15 <ruby>鴨川<rt>かもがわ</rt></ruby>沿い散歩</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-5" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">19:15 - 19:45</div>
                                <h3 class="spot-name"><ruby>鴨川<rt>かもがわ</rt></ruby>沿い散歩</h3>
                                <p class="spot-note"><ruby>四条大橋<rt>しじょうおおはし</rt></ruby>周辺。夜の<ruby>鴨川<rt>かもがわ</rt></ruby>は雰囲気抜群。カップルの聖地</p>
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

            <section class="section" id="spot-7">
                <h2 class="section-title">19:45 夕食</h2>
                <ul class="spot-list">
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day1-6" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">19:45 -</div>
                                <h3 class="spot-name"><ruby>四条<rt>しじょう</rt></ruby>エリアで夕食</h3>
                                <p class="spot-note"><ruby>四条<rt>しじょう</rt></ruby>・<ruby>河原町<rt>かわらまち</rt></ruby>エリアで気になるお店を選択。京料理や居酒屋など選択肢豊富</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=四条+レストラン+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 四条レストラン検索
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query=河原町+居酒屋+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-search"></i> 河原町居酒屋検索
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
                        <strong style="font-weight: 400; color: #333;">今日の消化スポット：</strong>6スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li>東山エリア（<ruby>蹴上<rt>けあげ</rt></ruby>・<ruby>南禅寺<rt>なんぜんじ</rt></ruby>・<ruby>永観堂<rt>えいかんどう</rt></ruby>・<ruby>円山公園<rt>まるやまこうえん</rt></ruby>）</li>
                        <li><ruby>鴨川<rt>かもがわ</rt></ruby>・<ruby>四条<rt>しじょう</rt></ruby>エリア</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ✅ 地下鉄・バス1日券（1,100円）がおすすめ<br>
                        ✅ 明日のMTG後に備えて東山エリアを先行消化<br>
                        ✅ 夕暮れ〜夜の雰囲気が楽しめる時間帯
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#spot-1" class="toc-link">16:00 ホテル出発</a></li>
                <li class="toc-item"><a href="#spot-2" class="toc-link">16:30 <ruby>蹴上<rt>けあげ</rt></ruby>インクライン</a></li>
                <li class="toc-item"><a href="#spot-3" class="toc-link">17:00 <ruby>南禅寺<rt>なんぜんじ</rt></ruby></a></li>
                <li class="toc-item"><a href="#spot-4" class="toc-link">18:00 <ruby>永観堂<rt>えいかんどう</rt></ruby></a></li>
                <li class="toc-item"><a href="#spot-5" class="toc-link">18:45 <ruby>円山公園<rt>まるやまこうえん</rt></ruby></a></li>
                <li class="toc-item"><a href="#spot-6" class="toc-link">19:15 <ruby>鴨川<rt>かもがわ</rt></ruby>沿い散歩</a></li>
                <li class="toc-item"><a href="#spot-7" class="toc-link">19:45 夕食</a></li>
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
            <li class="toc-item"><a href="#spot-2" class="toc-link">16:30 <ruby>蹴上<rt>けあげ</rt></ruby>インクライン</a></li>
            <li class="toc-item"><a href="#spot-3" class="toc-link">17:00 <ruby>南禅寺<rt>なんぜんじ</rt></ruby></a></li>
            <li class="toc-item"><a href="#spot-4" class="toc-link">18:00 <ruby>永観堂<rt>えいかんどう</rt></ruby></a></li>
            <li class="toc-item"><a href="#spot-5" class="toc-link">18:45 <ruby>円山公園<rt>まるやまこうえん</rt></ruby></a></li>
            <li class="toc-item"><a href="#spot-6" class="toc-link">19:15 <ruby>鴨川<rt>かもがわ</rt></ruby>沿い散歩</a></li>
            <li class="toc-item"><a href="#spot-7" class="toc-link">19:45 夕食</a></li>
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
