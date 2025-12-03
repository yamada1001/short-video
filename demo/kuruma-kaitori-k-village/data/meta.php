<?php
/**
 * 各ページのmeta情報
 * SEO対策用（title, description, keywords）
 */

$meta = [
    'index' => [
        'title' => '大分の車買取・販売・車検ならくるま買取ケイヴィレッジ',
        'description' => '大分市中判田の車買取・販売・車検専門店。新車・中古車販売、買取、車検、整備、板金、リースまで幅広く対応。誠実対応で地域の皆様に選ばれています。無料査定実施中。',
        'keywords' => '大分,車買取,中古車販売,車検,整備,板金,中判田,ケイヴィレッジ,新車,リース',
        'og_image' => 'assets/images/og-image.jpg'
    ],

    'about' => [
        'title' => '会社概要 | くるま買取ケイヴィレッジ',
        'description' => 'くるま買取ケイヴィレッジの会社概要。大分市中判田で車買取・販売・車検を行っています。古物商許可番号：９４１１９０００１１１６',
        'keywords' => '会社概要,大分,中判田,古物商,車買取',
        'og_image' => 'assets/images/shop/exterior.jpg'
    ],

    'contact' => [
        'title' => 'お問い合わせ・無料査定 | くるま買取ケイヴィレッジ',
        'description' => '車の買取、販売、車検のお問い合わせはこちら。無料査定も受付中。お電話またはフォームからお気軽にご連絡ください。',
        'keywords' => 'お問い合わせ,無料査定,買取査定,大分,車',
        'og_image' => 'assets/images/og-image.jpg'
    ],

    'news' => [
        'title' => 'お知らせ一覧 | くるま買取ケイヴィレッジ',
        'description' => 'くるま買取ケイヴィレッジの最新情報、キャンペーン、営業情報をお知らせします。',
        'keywords' => 'お知らせ,ニュース,キャンペーン,大分,車',
        'og_image' => 'assets/images/og-image.jpg'
    ],

    'privacy' => [
        'title' => 'プライバシーポリシー | くるま買取ケイヴィレッジ',
        'description' => 'くるま買取ケイヴィレッジの個人情報保護方針について。お客様の個人情報を適切に管理します。',
        'keywords' => 'プライバシーポリシー,個人情報保護,大分',
        'og_image' => 'assets/images/og-image.jpg'
    ],

    'sitemap' => [
        'title' => 'サイトマップ | くるま買取ケイヴィレッジ',
        'description' => 'くるま買取ケイヴィレッジのサイトマップ。サイト内の全ページへアクセスできます。',
        'keywords' => 'サイトマップ,サイト内検索',
        'og_image' => 'assets/images/og-image.jpg'
    ],

    'tokushoho' => [
        'title' => '特定商取引法に基づく表記 | くるま買取ケイヴィレッジ',
        'description' => '特定商取引法に基づく表記。販売業者情報、責任者、所在地、連絡先などを掲載しています。',
        'keywords' => '特定商取引法,販売業者,大分',
        'og_image' => 'assets/images/og-image.jpg'
    ]
];

/**
 * 指定されたページのmeta情報を取得
 * @param string $page_key ページキー（'index', 'about'など）
 * @param string $type 取得するmeta情報のタイプ（'title', 'description', 'keywords', 'og_image'）
 * @return string meta情報
 */
function get_meta($page_key, $type = 'title') {
    global $meta;

    if (isset($meta[$page_key][$type])) {
        return $meta[$page_key][$type];
    }

    // デフォルト値
    $defaults = [
        'title' => 'くるま買取ケイヴィレッジ',
        'description' => '大分市中判田の車買取・販売・車検専門店',
        'keywords' => '大分,車買取,中古車販売,車検',
        'og_image' => 'assets/images/og-image.jpg'
    ];

    return $defaults[$type] ?? '';
}

/**
 * OGP（Open Graph Protocol）タグを出力
 * @param string $page_key ページキー
 * @param string $custom_url カスタムURL（オプション）
 */
function output_ogp($page_key, $custom_url = null) {
    $title = get_meta($page_key, 'title');
    $description = get_meta($page_key, 'description');
    $og_image = SITE_URL . get_meta($page_key, 'og_image');
    $url = $custom_url ?? SITE_URL;

    echo '<meta property="og:title" content="' . htmlspecialchars($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . htmlspecialchars($description) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . htmlspecialchars($url) . '">' . "\n";
    echo '<meta property="og:image" content="' . htmlspecialchars($og_image) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . SITE_NAME . '">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";

    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . htmlspecialchars($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . htmlspecialchars($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . htmlspecialchars($og_image) . '">' . "\n";
}
