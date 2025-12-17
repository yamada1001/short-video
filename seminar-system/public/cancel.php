<?php
/**
 * 欠席申請フォーム
 * トークンベース認証で欠席処理を行う
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Attendee;
use Seminar\Seminar;

$pageTitle = '欠席のお申し込み';

// トークン取得
$token = get('token', '');
$error = '';
$success = false;
$attendee = null;
$seminar = null;

if (!$token) {
    $error = '無効なURLです。メールに記載されたURLからアクセスしてください。';
} else {
    // トークンで参加者情報取得
    $attendee = Attendee::getByCancelToken($token);

    if (!$attendee) {
        $error = '無効なトークンです。URLが正しいか確認してください。';
    } elseif ($attendee['status'] === 'absent') {
        $error = '既に欠席申請済みです。';
    } elseif ($attendee['status'] === 'attended') {
        $error = '既に出席済みのため、欠席申請できません。';
    } else {
        // セミナー情報取得
        $seminar = Seminar::getById($attendee['seminar_id']);

        // セミナー開始日時チェック
        if (!isFuture($seminar['start_datetime'])) {
            $error = 'セミナー開始後は欠席申請できません。';
        }
    }
}

// POST処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error && $attendee && $seminar) {
    $reason = trim(post('reason', ''));

    if (empty($reason)) {
        $error = '欠席理由を入力してください。';
    } else {
        // クレジット金額はセミナー価格と同額
        $creditAmount = $seminar['price'];

        try {
            // 欠席処理
            Attendee::markAsAbsent($attendee['id'], $reason, $creditAmount);
            $success = true;

            // 成功後、参加者情報を再取得
            $attendee = Attendee::getById($attendee['id']);
        } catch (\Exception $e) {
            $error = '欠席申請に失敗しました: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($pageTitle); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.8;
            padding: 40px 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
        }

        .content {
            padding: 40px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #efe;
            color: #363;
            border: 1px solid #cec;
        }

        .info-section {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e8e8e8;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 120px;
            font-weight: 500;
            color: #666;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-label.required::after {
            content: '*';
            color: #e74c3c;
            margin-left: 4px;
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.2s;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .notice {
            background: #fff8e1;
            border: 1px solid #ffd54f;
            border-radius: 6px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #856404;
        }

        .notice strong {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .credit-info {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            border-radius: 6px;
            padding: 20px;
            margin-top: 24px;
        }

        .credit-info h3 {
            font-size: 16px;
            color: #2e7d32;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .credit-amount {
            font-size: 28px;
            font-weight: 700;
            color: #2e7d32;
            margin-bottom: 12px;
        }

        .credit-description {
            font-size: 14px;
            color: #558b2f;
            line-height: 1.6;
        }

        .text-muted {
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 24px 20px;
            }

            .info-row {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: auto;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo h($pageTitle); ?></h1>
        </div>

        <div class="content">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo h($error); ?>
                </div>
            <?php elseif ($success): ?>
                <div class="alert alert-success">
                    欠席申請を受け付けました。
                </div>

                <div class="credit-info">
                    <h3>繰越クレジットが付与されました</h3>
                    <div class="credit-amount"><?php echo formatPrice($attendee['credit_amount']); ?></div>
                    <div class="credit-description">
                        次回のセミナーお申し込み時に、このクレジット金額を差し引いてお支払いいただけます。<br>
                        お支払いページで「クレジットを使用する」にチェックを入れてください。
                    </div>
                </div>

                <div class="info-section" style="margin-top: 24px;">
                    <div class="info-row">
                        <div class="info-label">お名前</div>
                        <div class="info-value"><?php echo h($attendee['name']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">メール</div>
                        <div class="info-value"><?php echo h($attendee['email']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">セミナー</div>
                        <div class="info-value"><?php echo h($seminar['title']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">開催日時</div>
                        <div class="info-value"><?php echo formatDatetime($seminar['start_datetime'], 'Y年m月d日 H:i'); ?></div>
                    </div>
                </div>

                <p class="text-muted" style="margin-top: 24px;">
                    またのご参加を心よりお待ちしております。
                </p>

            <?php elseif ($attendee && $seminar): ?>
                <div class="notice">
                    <strong>欠席される場合</strong>
                    欠席申請いただくと、セミナー料金と同額のクレジットが付与されます。<br>
                    次回セミナーのお支払い時に、このクレジットをご利用いただけます。
                </div>

                <div class="info-section">
                    <div class="info-row">
                        <div class="info-label">お名前</div>
                        <div class="info-value"><?php echo h($attendee['name']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">メール</div>
                        <div class="info-value"><?php echo h($attendee['email']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">セミナー</div>
                        <div class="info-value"><?php echo h($seminar['title']); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">開催日時</div>
                        <div class="info-value"><?php echo formatDatetime($seminar['start_datetime'], 'Y年m月d日 H:i'); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">会場</div>
                        <div class="info-value"><?php echo h($seminar['venue']) ?: '-'; ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">付与クレジット</div>
                        <div class="info-value"><strong><?php echo formatPrice($seminar['price']); ?></strong></div>
                    </div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label required">欠席理由</label>
                        <textarea name="reason" class="form-textarea" required placeholder="欠席の理由をご記入ください"></textarea>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn">欠席申請する</button>
                    </div>

                    <p class="text-muted" style="margin-top: 16px;">
                        ※ 欠席申請後はキャンセルできません。
                    </p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
