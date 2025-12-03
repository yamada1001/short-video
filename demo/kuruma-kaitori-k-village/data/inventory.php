<?php
/**
 * 在庫データ
 * くるま買取ケイヴィレッジ
 */

// 在庫車両データ
$inventory = [
    [
        'id' => 1,
        'name' => 'トヨタ プリウス S',
        'image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800&h=600&fit=crop',
        'price_total' => 760000,
        'price_vehicle' => 690000,
        'price_fees' => 70000,
        'year' => 2014,
        'year_jp' => 'H26',
        'mileage' => 5.7,
        'repair_history' => false,
        'maintenance' => '付き',
        'inspection' => '車検整備付',
        'location' => '大分県',
        'warranty' => '付き / 3ヵ月 / 距離無制限',
        'status' => '販売中'
    ],
    [
        'id' => 2,
        'name' => 'ホンダ フィット G',
        'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800&h=600&fit=crop',
        'price_total' => 580000,
        'price_vehicle' => 520000,
        'price_fees' => 60000,
        'year' => 2013,
        'year_jp' => 'H25',
        'mileage' => 6.2,
        'repair_history' => false,
        'maintenance' => '付き',
        'inspection' => '2025年9月',
        'location' => '大分県',
        'warranty' => '付き / 3ヵ月 / 距離無制限',
        'status' => '販売中'
    ],
    [
        'id' => 3,
        'name' => '日産 ノート X',
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
        'price_total' => 650000,
        'price_vehicle' => 590000,
        'price_fees' => 60000,
        'year' => 2015,
        'year_jp' => 'H27',
        'mileage' => 4.8,
        'repair_history' => false,
        'maintenance' => '付き',
        'inspection' => '車検整備付',
        'location' => '大分県',
        'warranty' => '付き / 3ヵ月 / 距離無制限',
        'status' => '販売中'
    ],
    [
        'id' => 4,
        'name' => 'スズキ ワゴンR FX',
        'image' => 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=800&h=600&fit=crop',
        'price_total' => 480000,
        'price_vehicle' => 430000,
        'price_fees' => 50000,
        'year' => 2014,
        'year_jp' => 'H26',
        'mileage' => 7.1,
        'repair_history' => false,
        'maintenance' => '付き',
        'inspection' => '2025年8月',
        'location' => '大分県',
        'warranty' => '付き / 3ヵ月 / 距離無制限',
        'status' => '販売中'
    ],
];

/**
 * 在庫データを取得
 */
function get_inventory($limit = null, $status = '販売中') {
    global $inventory;

    $filtered = array_filter($inventory, function($item) use ($status) {
        return $item['status'] === $status;
    });

    if ($limit) {
        return array_slice($filtered, 0, $limit);
    }

    return $filtered;
}

/**
 * 価格をフォーマット
 */
function format_price($price) {
    return number_format($price) . '円';
}
