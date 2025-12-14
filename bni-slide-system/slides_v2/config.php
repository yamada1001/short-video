<?php
/**
 * BNI Slide System V2 - 設定ファイル
 *
 * データベース接続やパス設定など、システム全体で共通する設定を定義
 */

// エラーレポート設定（本番環境では0に設定）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// データベースパス
$db_path = __DIR__ . '/data/bni_slide_system.db';

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

/**
 * 次の金曜日を取得
 *
 * @return string YYYY-MM-DD形式の日付
 */
function getTargetFriday() {
    $today = new DateTime();
    $dayOfWeek = (int)$today->format('N'); // 1(月) - 7(日)

    if ($dayOfWeek == 5) {
        // 今日が金曜日の場合は今日
        return $today->format('Y-m-d');
    } elseif ($dayOfWeek < 5) {
        // 月〜木の場合は今週の金曜日
        $daysUntilFriday = 5 - $dayOfWeek;
        $today->modify("+{$daysUntilFriday} days");
        return $today->format('Y-m-d');
    } else {
        // 土日の場合は次週の金曜日
        $daysUntilFriday = (7 - $dayOfWeek) + 5;
        $today->modify("+{$daysUntilFriday} days");
        return $today->format('Y-m-d');
    }
}

/**
 * データベース接続を取得
 *
 * @return PDO データベース接続
 * @throws PDOException 接続エラー
 */
function getDbConnection() {
    global $db_path;

    try {
        $db = new PDO('sqlite:' . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch (PDOException $e) {
        throw new PDOException('データベース接続エラー: ' . $e->getMessage());
    }
}

/**
 * JSON レスポンスを返す
 *
 * @param mixed $data レスポンスデータ
 * @param int $status HTTPステータスコード
 */
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * エラーレスポンスを返す
 *
 * @param string $message エラーメッセージ
 * @param int $status HTTPステータスコード
 */
function errorResponse($message, $status = 400) {
    jsonResponse([
        'success' => false,
        'error' => $message
    ], $status);
}

/**
 * 成功レスポンスを返す
 *
 * @param mixed $data レスポンスデータ
 * @param string $message メッセージ
 */
function successResponse($data = null, $message = 'Success') {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

// アップロードディレクトリ
define('UPLOAD_DIR', __DIR__ . '/data/uploads/');

// 最大アップロードサイズ（バイト）
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB

// 許可される画像拡張子
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// 許可されるドキュメント拡張子
define('ALLOWED_DOCUMENT_EXTENSIONS', ['pdf', 'doc', 'docx', 'ppt', 'pptx']);

// BNI カラー
define('BNI_RED', '#C8102E');

/**
 * スライドPHPファイルからPNG画像を生成
 *
 * @param string $slideFile スライドファイル名（例: seating.php）
 * @param int $pageNumber ページ番号（例: 7）
 * @param string|null $date 対象日（省略可）
 * @return bool 成功したらtrue
 */
function generateSlideImage($slideFile, $pageNumber, $date = null) {
    $scriptPath = __DIR__ . '/scripts/generate_slide_image.py';

    // Pythonスクリプトが存在するか確認
    if (!file_exists($scriptPath)) {
        error_log("画像生成スクリプトが見つかりません: {$scriptPath}");
        return false;
    }

    // コマンド構築
    $cmd = sprintf(
        'python3 %s %s %d %s 2>&1',
        escapeshellarg($scriptPath),
        escapeshellarg($slideFile),
        $pageNumber,
        $date ? escapeshellarg($date) : ''
    );

    // バックグラウンドで実行（非同期）
    exec($cmd . ' &', $output, $returnCode);

    // ログ出力
    error_log("スライド画像生成開始: {$slideFile} -> page {$pageNumber}");

    return true;
}
