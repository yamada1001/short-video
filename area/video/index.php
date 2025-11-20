<?php
/**
 * ショート動画制作エリアページ
 * - ?area=xxx の場合：詳細ページを表示
 * - パラメータなしの場合：サービスページにリダイレクト
 */

// エリアパラメータがある場合は詳細ページを表示
if (isset($_GET['area']) && !empty($_GET['area'])) {
    require_once __DIR__ . '/detail.php';
    exit;
}

// パラメータなしの場合はサービスページにリダイレクト
header('Location: /video-production.php');
exit;
