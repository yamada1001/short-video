<?php
/**
 * お知らせデータ
 * サンプルデータを3件用意
 */

$news = [
    [
        'id' => 1,
        'date' => '2025-12-01',
        'category' => 'キャンペーン',
        'title' => '年末買取強化キャンペーン実施中！',
        'slug' => 'campaign-year-end-2025',
        'content' => '
            <p>年末の買取強化キャンペーンを実施しております。</p>
            <p>12月中のご成約で、査定額を通常よりもアップいたします。</p>
            <h3>キャンペーン期間</h3>
            <p>2025年12月1日〜12月28日まで</p>
            <h3>対象車両</h3>
            <p>国産車・輸入車問わず、すべての車両が対象です。</p>
            <p>まずは無料査定をお試しください！</p>
        ',
        'thumbnail' => 'assets/images/news/campaign-01.jpg'
    ],

    [
        'id' => 2,
        'date' => '2025-11-20',
        'category' => 'お知らせ',
        'title' => '年末年始の営業時間について',
        'slug' => 'holiday-schedule-2025',
        'content' => '
            <p>年末年始の営業時間についてお知らせいたします。</p>
            <h3>年内営業</h3>
            <p>12月28日（土）まで通常営業</p>
            <h3>年末年始休業</h3>
            <p>12月29日（日）〜1月3日（金）</p>
            <h3>新年営業開始</h3>
            <p>1月4日（土）より通常営業</p>
            <p>※お急ぎの場合は、メールフォームにてお問い合わせください。</p>
        ',
        'thumbnail' => 'assets/images/news/holiday-01.jpg'
    ],

    [
        'id' => 3,
        'date' => '2025-11-01',
        'category' => 'お知らせ',
        'title' => '無料出張査定サービスを開始しました',
        'slug' => 'new-service-mobile-assessment',
        'content' => '
            <p>お客様のご自宅やご指定の場所まで無料で出張査定に伺うサービスを開始いたしました。</p>
            <h3>対象エリア</h3>
            <p>大分市内およ び周辺地域</p>
            <h3>ご利用方法</h3>
            <p>お電話またはお問い合わせフォームにて「出張査定希望」とお伝えください。</p>
            <p>お車を持ち込む手間が省けて、とても便利です。ぜひご利用ください！</p>
        ',
        'thumbnail' => 'assets/images/news/service-01.jpg'
    ]
];

/**
 * お知らせIDから記事を取得
 * @param int $news_id お知らせID
 * @return array|null お知らせ情報
 */
function get_news_by_id($news_id) {
    global $news;
    foreach ($news as $item) {
        if ($item['id'] == $news_id) {
            return $item;
        }
    }
    return null;
}

/**
 * スラッグから記事を取得
 * @param string $slug スラッグ
 * @return array|null お知らせ情報
 */
function get_news_by_slug($slug) {
    global $news;
    foreach ($news as $item) {
        if ($item['slug'] === $slug) {
            return $item;
        }
    }
    return null;
}

/**
 * 最新のお知らせを取得
 * @param int $limit 取得件数
 * @return array お知らせ情報の配列
 */
function get_latest_news($limit = 3) {
    global $news;
    // 日付で降順ソート
    usort($news, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });
    return array_slice($news, 0, $limit);
}

/**
 * カテゴリーでフィルタリング
 * @param string $category カテゴリー名
 * @return array お知らせ情報の配列
 */
function get_news_by_category($category) {
    global $news;
    return array_filter($news, function($item) use ($category) {
        return $item['category'] === $category;
    });
}

/**
 * すべてのお知らせを取得
 * @return array お知らせ情報の配列
 */
function get_all_news() {
    global $news;
    // 日付で降順ソート
    usort($news, function($a, $b) {
        return strcmp($b['date'], $a['date']);
    });
    return $news;
}

/**
 * カテゴリー一覧を取得
 * @return array カテゴリーの配列
 */
function get_news_categories() {
    global $news;
    $categories = array_unique(array_column($news, 'category'));
    return $categories;
}
