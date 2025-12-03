<?php
/**
 * サイト基本設定
 * くるま買取ケイヴィレッジ
 */

// 会社基本情報
define('SITE_NAME', 'くるま買取ケイヴィレッジ');
define('SITE_NAME_EN', 'K Village');
define('SITE_URL', 'https://www.yojitu.com/demo/kuruma-kaitori-k-village/');
define('SITE_DESCRIPTION', '大分市中判田の車買取・販売・車検専門店。新車・中古車販売、買取、車検、整備、板金、リースまで幅広く対応。');

// 会社情報
define('COMPANY_NAME', 'くるま買取ケイヴィレッジ');
define('COMPANY_OWNER', '木村　伸嗣');
define('POSTAL_CODE', '〒870-1113');
define('ADDRESS', '大分県大分市中判田１５９２‐１');
define('ADDRESS_PREFECTURE', '大分県');
define('ADDRESS_CITY', '大分市');
define('ADDRESS_DETAIL', '中判田１５９２‐１');

// 位置情報（Google Maps用）
define('LATITUDE', '33.2181');  // 緯度（大分市中判田付近）
define('LONGITUDE', '131.6127'); // 経度（大分市中判田付近）
define('MAP_ZOOM', '15'); // ズームレベル

// 連絡先情報（※要ヒアリング）
define('PHONE', '097-XXX-XXXX'); // 電話番号（後で更新）
define('PHONE_LINK', 'tel:097XXXXXXXX'); // tel:リンク用（ハイフンなし）
define('TEL', '097XXXXXXXX'); // tel:リンク用（ハイフンなし）
define('TEL_DISPLAY', '097-XXX-XXXX'); // 表示用電話番号
define('EMAIL', 'info@k-village.example.com'); // メールアドレス（仮）
define('FAX', ''); // FAXがあれば記載

// 営業情報（※要ヒアリング）
define('BUSINESS_HOURS', '9:00〜18:00');
define('BUSINESS_DAYS', '月曜日〜土曜日');
define('HOLIDAY', '日曜日・祝日');

// 事業内容
$business_services = [
    '新車販売',
    '中古車販売',
    '買取',
    '車検',
    '整備',
    '板金',
    'オートローン',
    '新車リース'
];

// 資格・許可
define('LICENSE_NUMBER', '９４１１９０００１１１６'); // 古物商許可番号
define('LICENSE_NAME', '古物商許可番号');

// SNS（あれば）
define('SNS_FACEBOOK', ''); // Facebook URL
define('SNS_TWITTER', '');  // Twitter/X URL
define('SNS_INSTAGRAM', ''); // Instagram URL
define('SNS_LINE', '');     // LINE ID

// Google Maps埋め込みURL
define('GOOGLE_MAP_EMBED', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3348.8!2d131.6127!3d33.2181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDEzJzA1LjIiTiAxMzHCsDM2JzQ1LjciRQ!5e0!3m2!1sja!2sjp!4v1234567890');

// 構造化データ（JSON-LD）用
$structured_data = [
    '@context' => 'https://schema.org',
    '@type' => 'AutomotiveBusiness',
    'name' => COMPANY_NAME,
    'image' => SITE_URL . 'assets/images/logo.svg',
    'description' => SITE_DESCRIPTION,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => ADDRESS_DETAIL,
        'addressLocality' => ADDRESS_CITY,
        'addressRegion' => ADDRESS_PREFECTURE,
        'postalCode' => POSTAL_CODE,
        'addressCountry' => 'JP'
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => LATITUDE,
        'longitude' => LONGITUDE
    ],
    'telephone' => PHONE,
    'email' => EMAIL,
    'url' => SITE_URL,
    'openingHoursSpecification' => [
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ],
        'opens' => '09:00',
        'closes' => '18:00'
    ],
    'priceRange' => '$$',
    'currenciesAccepted' => 'JPY',
    'paymentAccepted' => '現金, クレジットカード, 銀行振込'
];

// 構造化データをJSON-LD形式で出力する関数
function get_structured_data() {
    global $structured_data;
    return '<script type="application/ld+json">' . json_encode($structured_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}

// パンくずリスト生成関数
function get_breadcrumb($items) {
    $breadcrumb_data = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => []
    ];

    foreach ($items as $index => $item) {
        $breadcrumb_data['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url'] ?? null
        ];
    }

    return '<script type="application/ld+json">' . json_encode($breadcrumb_data, JSON_UNESCAPED_UNICODE) . '</script>';
}
