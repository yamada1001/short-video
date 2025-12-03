<?php
/**
 * サービス情報
 * 6つのサービス（買取、新車販売、中古車販売、車検・整備、板金、リース）
 */

$services = [
    [
        'id' => 'kaitori',
        'name' => '車買取',
        'short_name' => '買取',
        'name_en' => 'Car Purchase',
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'color' => '#2563eb', // ブルー
        'description' => '大切なお車を高価買取いたします。国産車・輸入車問わず、どんな車でもまずはご相談ください。',
        'features' => [
            '無料査定実施中',
            '出張査定対応',
            '即日現金買取可能',
            '他社より高価買取'
        ],
        'detail' => '長年乗った愛車、買い替えを検討している車、不要になった車など、どんな車でもお気軽にご相談ください。経験豊富なスタッフが適正価格で査定いたします。他社の査定額と比較してください。',
        'image' => 'assets/images/services/kaitori.jpg'
    ],

    [
        'id' => 'shinsha',
        'name' => '新車販売',
        'short_name' => '新車',
        'name_en' => 'New Car Sales',
        'icon' => 'fa-solid fa-car',
        'color' => '#10b981', // グリーン
        'description' => '国産車・輸入車の新車をお取り扱い。お客様のニーズに合った最適な1台をご提案します。',
        'features' => [
            '全メーカー取扱い可能',
            '低金利ローン対応',
            '下取り・買取同時対応',
            '納車後のアフターサポート'
        ],
        'detail' => 'トヨタ、ホンダ、日産、スズキ、ダイハツなど、国産車全メーカーの新車を取り扱っています。お客様のライフスタイルやご予算に合わせて、最適な車をご提案いたします。',
        'image' => 'assets/images/services/shinsha.jpg'
    ],

    [
        'id' => 'chuko',
        'name' => '中古車販売',
        'short_name' => '中古',
        'name_en' => 'Used Car Sales',
        'icon' => 'fa-solid fa-car-side',
        'color' => '#f59e0b', // オレンジ
        'description' => '厳選された良質な中古車を取り揃えています。初めての車購入も安心してお任せください。',
        'features' => [
            '整備済み車両のみ販売',
            '保証付きで安心',
            '低価格・高品質',
            '各種ローン対応'
        ],
        'detail' => '当店が厳選した良質な中古車のみを販売しています。すべての車両は納車前に点検・整備を実施。安心してお乗りいただけます。',
        'image' => 'assets/images/services/chuko.jpg'
    ],

    [
        'id' => 'shaken',
        'name' => '車検・整備',
        'short_name' => '車検',
        'name_en' => 'Vehicle Inspection & Maintenance',
        'icon' => 'fa-solid fa-wrench',
        'color' => '#6366f1', // インディゴ
        'description' => '車検、定期点検、一般整備まで幅広く対応。大切な愛車を長く安全に乗るためのサポートをいたします。',
        'features' => [
            'スピード車検対応',
            '代車無料貸出',
            '整備士常駐',
            '見積もり無料'
        ],
        'detail' => '国家資格を持つ整備士が、お客様の愛車を丁寧に点検・整備いたします。車検はもちろん、オイル交換、タイヤ交換、バッテリー交換など、日常のメンテナンスもお任せください。',
        'image' => 'assets/images/services/shaken.jpg'
    ],

    [
        'id' => 'bankin',
        'name' => '板金・塗装',
        'short_name' => '板金',
        'name_en' => 'Body Repair & Painting',
        'icon' => 'fa-solid fa-spray-can',
        'color' => '#ef4444', // レッド
        'description' => '小さなキズ・ヘコミから事故修理まで、板金・塗装のプロが対応いたします。',
        'features' => [
            '小さなキズから事故修理まで',
            '保険修理対応',
            '無料見積もり',
            '代車無料貸出'
        ],
        'detail' => 'ドアのへこみ、バンパーのキズ、事故による大きな損傷まで、どんな修理もお任せください。保険を使った修理も対応いたします。',
        'image' => 'assets/images/services/bankin.jpg'
    ],

    [
        'id' => 'lease',
        'name' => '新車リース',
        'short_name' => 'リース',
        'name_en' => 'Car Leasing',
        'icon' => 'fa-solid fa-file-contract',
        'color' => '#8b5cf6', // パープル
        'description' => '頭金不要、月々定額で新車に乗れるリースサービス。車検・メンテナンス込みで安心です。',
        'features' => [
            '頭金0円',
            '月々定額払い',
            '車検・メンテナンス込み',
            '全メーカー対応'
        ],
        'detail' => '初期費用を抑えて新車に乗りたい方におすすめのリースサービス。車検、税金、メンテナンス費用がすべて含まれているので、急な出費の心配がありません。',
        'image' => 'assets/images/services/lease.jpg'
    ]
];

/**
 * サービスIDからサービス情報を取得
 * @param string $service_id サービスID
 * @return array|null サービス情報
 */
function get_service_by_id($service_id) {
    global $services;
    foreach ($services as $service) {
        if ($service['id'] === $service_id) {
            return $service;
        }
    }
    return null;
}

/**
 * すべてのサービスを取得
 * @return array サービス情報の配列
 */
function get_all_services() {
    global $services;
    return $services;
}
