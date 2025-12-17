<?php
/**
 * チェックインAPI
 * QRスキャン時のトークン検証とステータス更新
 */
require_once __DIR__ . '/../../config/config.php';

use Seminar\Attendee;
use Seminar\Seminar;

header('Content-Type: application/json');

// POSTのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// JSONデータ取得
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['token'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'トークンが指定されていません']);
    exit;
}

$token = trim($data['token']);

try {
    // トークンで参加者情報取得
    $attendee = Attendee::getByQrToken($token);

    if (!$attendee) {
        echo json_encode([
            'success' => false,
            'message' => '無効なトークンです'
        ]);
        exit;
    }

    // セミナー情報取得
    $seminar = Seminar::getById($attendee['seminar_id']);

    if (!$seminar) {
        echo json_encode([
            'success' => false,
            'message' => 'セミナー情報が見つかりません'
        ]);
        exit;
    }

    // 既に出席済みの場合
    if ($attendee['status'] === 'attended') {
        echo json_encode([
            'success' => true,
            'message' => '既にチェックイン済みです',
            'attendee' => [
                'id' => $attendee['id'],
                'name' => $attendee['name'],
                'email' => $attendee['email'],
                'status' => $attendee['status']
            ],
            'seminar' => [
                'id' => $seminar['id'],
                'title' => $seminar['title'],
                'start_datetime' => $seminar['start_datetime']
            ]
        ]);
        exit;
    }

    // 欠席の場合
    if ($attendee['status'] === 'absent') {
        echo json_encode([
            'success' => false,
            'message' => 'この参加者は欠席申請済みです'
        ]);
        exit;
    }

    // 支払い未完了の場合
    if ($attendee['status'] === 'applied') {
        echo json_encode([
            'success' => false,
            'message' => 'お支払いが完了していません'
        ]);
        exit;
    }

    // ステータスを'attended'に更新
    if (Attendee::updateStatus($attendee['id'], 'attended')) {
        echo json_encode([
            'success' => true,
            'message' => 'チェックイン完了',
            'attendee' => [
                'id' => $attendee['id'],
                'name' => $attendee['name'],
                'email' => $attendee['email'],
                'status' => 'attended'
            ],
            'seminar' => [
                'id' => $seminar['id'],
                'title' => $seminar['title'],
                'start_datetime' => $seminar['start_datetime']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ステータス更新に失敗しました'
        ]);
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'サーバーエラー: ' . $e->getMessage()
    ]);
}
