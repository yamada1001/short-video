<?php
require_once '../../includes/config.php';
define('PAGE_TITLE', '12/8（日）明日 - フル稼働');
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
                <p class="page-subtitle">8:30〜 | フル稼働 | 12スポット消化</p>
            </div>

            <!-- 統計情報 -->
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">今日の予定</div>
                    <div class="stat-value total-count">12</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">達成率</div>
                    <div class="stat-value">
                        <span class="checked-count">0</span>
                        <span class="total"> / 12</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">開始時刻</div>
                    <div class="stat-value" style="font-size: 20px;">8:30</div>
                </div>
            </div>

            <!-- 午前：嵐山 -->
            <section class="section" id="arashiyama">
                <h2 class="section-title">午前：嵐山エリア（8:30出発）</h2>

                <div class="transit-info">
                    <strong><i class="fas fa-train"></i> 移動：</strong>JR嵯峨野線 → 嵯峨嵐山駅（京都駅から17分）
                </div>

                <ul class="spot-list">
                    <!-- 竹林の小径 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-1" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">9:00 - 9:40</div>
                                <h3 class="spot-name">竹林の小径</h3>
                                <p class="spot-note">朝イチが空いてる！幻想的な竹林を散策</p>
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

                    <!-- 天龍寺 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-2" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">9:40 - 10:30</div>
                                <h3 class="spot-name">天龍寺</h3>
                                <p class="spot-note">世界遺産の庭園を拝観。曹源池庭園が美しい</p>
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

                    <!-- 渡月橋 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-3" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">10:30 - 11:00</div>
                                <h3 class="spot-name">渡月橋</h3>
                                <p class="spot-note">嵐山のシンボル。桂川にかかる優雅な橋</p>
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

                    <!-- キモノフォレスト -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-4" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">11:00 - 11:30</div>
                                <h3 class="spot-name">キモノフォレスト</h3>
                                <p class="spot-note">嵐電嵐山駅。友禅柱のライトアップが幻想的</p>
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
                    <strong><i class="fas fa-train"></i> 移動：</strong>嵐電 + 地下鉄で東山エリアへ（約40分）
                </div>
            </section>

            <!-- 午後：東山 -->
            <section class="section" id="higashiyama">
                <h2 class="section-title">午後：東山エリア</h2>

                <ul class="spot-list">
                    <!-- 蹴上インクライン -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-5" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">12:00 - 12:30</div>
                                <h3 class="spot-name">蹴上インクライン</h3>
                                <p class="spot-note">廃線跡のフォトスポット。線路の上を歩ける</p>
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

                    <!-- 南禅寺 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-6" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">12:30 - 13:30</div>
                                <h3 class="spot-name">南禅寺</h3>
                                <p class="spot-note">水路閣が有名。石川五右衛門の「絶景かな」の舞台</p>
                                <div class="spot-links">
                                    <a href="https://www.google.com/maps/search/?api=1&query=南禅寺+京都" target="_blank" class="spot-link">
                                        <i class="fas fa-map-marker-alt"></i> Google Maps
                                    </a>
                                    <a href="https://nanzenji.or.jp/" target="_blank" class="spot-link">
                                        <i class="fas fa-link"></i> 公式サイト
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- 永観堂 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-7" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">13:30 - 14:30</div>
                                <h3 class="spot-name">永観堂</h3>
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

                    <!-- 京都国立近代美術館 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-8" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">14:30 - 15:30</div>
                                <h3 class="spot-name">京都国立近代美術館</h3>
                                <p class="spot-note">現代アートを鑑賞。企画展をチェック</p>
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

                    <!-- 円山公園 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-9" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">15:30 - 16:00</div>
                                <h3 class="spot-name">円山公園</h3>
                                <p class="spot-note">祇園のそば。しだれ桜が有名な公園</p>
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

            <!-- 夕方：四条・河原町 -->
            <section class="section" id="shijo">
                <h2 class="section-title">夕方：四条・河原町エリア（徒歩で南下）</h2>

                <ul class="spot-list">
                    <!-- 梅園 河原町店 -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-10" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:00 - 16:45</div>
                                <h3 class="spot-name">梅園 河原町店</h3>
                                <p class="spot-note">わらび餅が絶品！休憩にぴったり</p>
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

                    <!-- 錦市場（こんなもんじゃ） -->
                    <li class="spot-item">
                        <div class="spot-header">
                            <input type="checkbox" id="spot-day2-11" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">16:45 - 17:30</div>
                                <h3 class="spot-name">錦市場（こんなもんじゃ）</h3>
                                <p class="spot-note">京の台所。食べ歩きを楽しむ</p>
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
                            <input type="checkbox" id="spot-day2-12" class="spot-checkbox">
                            <div class="spot-info">
                                <div class="spot-time">17:30 - 18:00</div>
                                <h3 class="spot-name">今西軒</h3>
                                <p class="spot-note">焼き餅が名物。創業100年以上の老舗</p>
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

            <!-- 夕食 -->
            <section class="section" id="dinner">
                <h2 class="section-title">18:30 夕食（四条周辺）</h2>
                <div class="card">
                    <p style="color: #666; margin-bottom: 12px;">四条エリアで気になるお店を選択</p>
                    <div class="spot-links">
                        <a href="https://www.google.com/maps/search/?api=1&query=四条+レストラン+京都" target="_blank" class="spot-link">
                            <i class="fas fa-search"></i> 四条レストラン検索
                        </a>
                    </div>
                </div>
            </section>

            <!-- まとめ -->
            <section class="section" id="summary">
                <h2 class="section-title">まとめ</h2>
                <div class="card">
                    <p style="margin-bottom: 16px; color: #666;">
                        <strong style="font-weight: 400; color: #333;">明日の消化スポット：</strong>12スポット
                    </p>
                    <ul style="line-height: 2; color: #666;">
                        <li>午前：嵐山エリア（4スポット）</li>
                        <li>午後：東山エリア（5スポット）</li>
                        <li>夕方：四条・河原町エリア（3スポット）</li>
                    </ul>
                    <p style="color: #999; font-size: 14px; margin-top: 16px; line-height: 1.8;">
                        ※ 各所15〜30分の余裕あり<br>
                        ※ 疲れたら適宜調整してOK
                    </p>
                </div>
            </section>
        </main>

        <!-- PC用サイドバー目次 -->
        <aside class="toc-sidebar">
            <h3 class="toc-title">目次</h3>
            <ul class="toc-list">
                <li class="toc-item"><a href="#arashiyama" class="toc-link">午前：嵐山</a></li>
                <li class="toc-item"><a href="#higashiyama" class="toc-link">午後：東山</a></li>
                <li class="toc-item"><a href="#shijo" class="toc-link">夕方：四条・河原町</a></li>
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
            <li class="toc-item"><a href="#arashiyama" class="toc-link">午前：嵐山</a></li>
            <li class="toc-item"><a href="#higashiyama" class="toc-link">午後：東山</a></li>
            <li class="toc-item"><a href="#shijo" class="toc-link">夕方：四条・河原町</a></li>
            <li class="toc-item"><a href="#dinner" class="toc-link">夕食</a></li>
            <li class="toc-item"><a href="#summary" class="toc-link">まとめ</a></li>
        </ul>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
