<?php
/**
 * エリアセクション（トップページ用）
 * 大分県全18市町村への内部リンク
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
                大分県全域でホームページ制作に対応しています。<br>
                <strong>10万円〜</strong>の格安料金で高品質なWebサイトを制作します。
            </p>
        </div>

        <div class="area-section__content animate">
            <!-- 市 -->
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

            <!-- 町・村 -->
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
        </div>

        <div class="text-center mt-xl animate">
            <a href="/area/" class="btn btn-secondary">エリア一覧を見る</a>
        </div>
    </div>
</section>
