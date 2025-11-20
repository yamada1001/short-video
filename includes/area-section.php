<?php
/**
 * エリアセクション（トップページ用）
 * 大分県全18市町村への内部リンク（Web制作・動画制作）
 */

// エリアデータ読み込み
$areas_json_path = __DIR__ . '/../area/data/areas.json';
if (file_exists($areas_json_path)) {
    $areas_json = file_get_contents($areas_json_path);
    $areas_data = json_decode($areas_json, true);
    $areas = $areas_data['areas'] ?? [];
} else {
    $areas = [];
}

// 市と町村を分ける
$cities = array_filter($areas, function($area) {
    return $area['type'] === '市';
});
$towns = array_filter($areas, function($area) {
    return $area['type'] === '町' || $area['type'] === '村';
});
?>

<!-- エリアセクション -->
<section class="section area-section" id="area">
    <div class="container">
        <div class="section-header">
            <span class="section-header__label animate">Service Area</span>
            <h2 class="section__title" data-split-text>対応エリア</h2>
            <p class="section__description animate">
                大分県全域でサービスを提供しています。<br>
                各エリアへの出張費は<strong>無料</strong>です。
            </p>
        </div>

        <!-- サービス切り替えタブ -->
        <div class="area-service-tabs animate">
            <button class="area-service-tab active" data-service="web" onclick="switchAreaService('web')">
                <i class="fas fa-laptop-code"></i> Web制作
            </button>
            <button class="area-service-tab" data-service="video" onclick="switchAreaService('video')">
                <i class="fas fa-video"></i> ショート動画制作
            </button>
        </div>

        <!-- Web制作エリア -->
        <div class="area-section__content animate area-service-content active" data-service="web">
            <div class="area-section__group">
                <h3 class="area-section__group-title"><i class="fas fa-city"></i> 市（14市）</h3>
                <div class="area-section__links">
                    <?php foreach ($cities as $area): ?>
                    <a href="/area/?area=<?php echo urlencode($area['slug']); ?>" class="area-section__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="area-section__group">
                <h3 class="area-section__group-title"><i class="fas fa-map-marker-alt"></i> 町・村（4町村）</h3>
                <div class="area-section__links">
                    <?php foreach ($towns as $area): ?>
                    <a href="/area/?area=<?php echo urlencode($area['slug']); ?>" class="area-section__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="text-center mt-lg">
                <a href="/area/" class="btn btn-secondary">Web制作エリア一覧</a>
            </div>
        </div>

        <!-- 動画制作エリア -->
        <div class="area-section__content animate area-service-content" data-service="video">
            <div class="area-section__group">
                <h3 class="area-section__group-title"><i class="fas fa-city"></i> 市（14市）</h3>
                <div class="area-section__links">
                    <?php foreach ($cities as $area): ?>
                    <a href="/area/video/?area=<?php echo urlencode($area['slug']); ?>" class="area-section__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="area-section__group">
                <h3 class="area-section__group-title"><i class="fas fa-map-marker-alt"></i> 町・村（4町村）</h3>
                <div class="area-section__links">
                    <?php foreach ($towns as $area): ?>
                    <a href="/area/video/?area=<?php echo urlencode($area['slug']); ?>" class="area-section__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="text-center mt-lg">
                <a href="/area/video/" class="btn btn-secondary">動画制作エリア一覧</a>
            </div>
        </div>
    </div>
</section>

<style>
.area-service-tabs {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: var(--spacing-xl);
}

.area-service-tab {
    padding: 12px 24px;
    border: 1px solid var(--color-natural-brown);
    background: transparent;
    color: var(--color-natural-brown);
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.area-service-tab.active {
    background: var(--color-natural-brown);
    color: #fff;
}

.area-service-tab:hover:not(.active) {
    background: rgba(139, 115, 85, 0.1);
}

.area-service-content {
    display: none;
}

.area-service-content.active {
    display: block;
}

@media (max-width: 480px) {
    .area-service-tabs {
        flex-direction: column;
        align-items: center;
    }

    .area-service-tab {
        width: 100%;
        max-width: 200px;
        justify-content: center;
    }
}
</style>

<script>
function switchAreaService(serviceId) {
    // タブの切り替え
    document.querySelectorAll('.area-service-tab').forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.service === serviceId) {
            tab.classList.add('active');
        }
    });
    // コンテンツの切り替え
    document.querySelectorAll('.area-service-content').forEach(content => {
        content.classList.remove('active');
        if (content.dataset.service === serviceId) {
            content.classList.add('active');
        }
    });
}
</script>
