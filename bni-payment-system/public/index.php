<?php
/**
 * メンバー支払いページ
 * BNI定例会費の支払いリンク生成
 */
require_once __DIR__ . '/../config/config.php';

use BNI\Member;
use BNI\Payment;
use BNI\SquareClient;
use BNI\Logger;

$error = null;
$weekOf = getCurrentWeek();
$weekLabel = getWeekLabel($weekOf);

// 支払い処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $memberId = (int)($_POST['member_id'] ?? 0);

        if ($memberId <= 0) {
            throw new Exception('メンバーを選択してください');
        }

        // Square Payment Link作成
        $squareClient = new SquareClient();
        $paymentLink = $squareClient->createPaymentLink($memberId, 1000);

        // Payment Linkにリダイレクト
        redirect($paymentLink->getUrl());

    } catch (Exception $e) {
        $error = $e->getMessage();
        Logger::error('Payment link creation failed', [
            'error' => $error,
            'member_id' => $memberId ?? null
        ]);
    }
}

// アクティブなメンバー取得
$members = Member::getAll(true);

// 今週の支払い状況取得
$payments = Payment::getByWeek($weekOf);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNI定例会費 支払い</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.8;
            color: #333;
            background: #fafafa;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        /* ヘッダー */
        .header {
            margin-bottom: 60px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
        }

        .week-info {
            font-size: 13px;
            color: #666;
            font-weight: 300;
        }

        /* メインカード */
        .card {
            background: #fff;
            padding: 48px 32px;
            margin-bottom: 32px;
            border: 1px solid #e0e0e0;
        }

        .card-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
        }

        /* 価格 */
        .price-section {
            text-align: center;
            margin-bottom: 40px;
            padding: 32px 0;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .price-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            letter-spacing: 0.1em;
        }

        .price {
            font-size: 40px;
            font-weight: 300;
            color: #DC143C;
            letter-spacing: -0.02em;
        }

        /* フォーム */
        .form-group {
            margin-bottom: 32px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
            font-weight: 400;
        }

        .form-select {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0;
            font-family: 'Noto Sans JP', sans-serif;
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23666' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        .form-select:focus {
            outline: none;
            border-color: #DC143C;
        }

        /* ボタン */
        .btn {
            width: 100%;
            padding: 16px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #DC143C;
            border: none;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
            letter-spacing: 0.1em;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #A01225;
        }

        .btn:active {
            background: #8B0000;
        }

        /* エラー */
        .alert {
            padding: 16px;
            margin-bottom: 32px;
            background: #fff5f5;
            border: 1px solid #DC143C;
            color: #DC143C;
            font-size: 14px;
        }

        /* 支払い状況 */
        .status-card {
            background: #fff;
            padding: 32px;
            border: 1px solid #e0e0e0;
        }

        .status-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 24px;
            letter-spacing: 0.05em;
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 300;
            color: #333;
        }

        /* メンバーリスト */
        .members-list {
            list-style: none;
        }

        .member-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .member-item:last-child {
            border-bottom: none;
        }

        .member-name {
            color: #333;
        }

        .paid-time {
            font-size: 12px;
            color: #999;
        }

        /* フッター */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        /* レスポンシブ */
        @media (max-width: 640px) {
            .container {
                padding: 40px 16px;
            }

            .card {
                padding: 32px 24px;
            }

            .price {
                font-size: 32px;
            }

            .stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>BNI定例会費 支払い</h1>
            <p class="week-info"><?php echo h($weekLabel); ?></p>
        </header>

        <main>
            <?php if ($error): ?>
                <div class="alert">
                    <?php echo h($error); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2 class="card-title">お名前を選択してください</h2>

                <div class="price-section">
                    <div class="price-label">会費</div>
                    <div class="price">¥1,000</div>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="member_id" class="form-label">メンバー名</label>
                        <select name="member_id" id="member_id" class="form-select" required>
                            <option value="">選択してください</option>
                            <?php foreach ($members as $member): ?>
                                <?php
                                $isPaid = isset($payments[$member['id']]);
                                $disabled = $isPaid ? 'disabled' : '';
                                $paidLabel = $isPaid ? ' (支払い済み)' : '';
                                ?>
                                <option value="<?php echo h($member['id']); ?>" <?php echo $disabled; ?>>
                                    <?php echo h($member['name'] . $paidLabel); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn">お支払いページへ</button>
                </form>
            </div>

            <div class="status-card">
                <h3 class="status-title">今週の支払い状況</h3>

                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-label">支払い済み</div>
                        <div class="stat-value"><?php echo count($payments); ?> / <?php echo count($members); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">合計金額</div>
                        <div class="stat-value">¥<?php echo number_format(count($payments) * 1000); ?></div>
                    </div>
                </div>

                <?php if (!empty($payments)): ?>
                    <ul class="members-list">
                        <?php foreach ($payments as $payment): ?>
                            <li class="member-item">
                                <span class="member-name"><?php echo h($payment['name']); ?></span>
                                <span class="paid-time"><?php echo h(date('m/d H:i', strtotime($payment['paid_at']))); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> BNI Payment System</p>
        </footer>
    </div>
</body>
</html>
