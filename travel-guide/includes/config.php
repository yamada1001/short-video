<?php
/**
 * 旅行栞システム - 共通設定ファイル
 */

// サイト基本情報
define('SITE_TITLE', '旅行の栞');
define('BASE_URL', '/travel-guide');

// 旅行先リスト（追加時はここに登録）
$destinations = [
    'kyoto' => [
        'name' => '京都旅行',
        'dates' => '2025年12月7日〜9日',
        'duration' => '2泊3日',
        'spots_total' => 19,
        'description' => '全19スポット制覇プラン'
    ]
    // 将来的に追加
    // 'osaka' => [...],
    // 'hokkaido' => [...],
];

// noindex設定（全ページ共通）
define('NO_INDEX', true);
