<?php
/**
 * 支払いページ
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Attendee;
use Seminar\SquareClient;

// フラッシュメッセージ取得
$flash = getFlash();

// メールアドレス取得
$email = trim(get('email', ''));
$unpaidAttendees = [];
$totalCredit = 0;

if ($email) {
    // 参加者検索
    $attendees = Attendee::getByEmail($email);

    // 未払いのみフィルター
    $unpaidAttendees = array_filter($attendees, function($a) {
        return $a['status'] === 'applied';
    });

    // 繰越クレジット取得
    $totalCredit = Attendee::getTotalCredit($email);
}

// POSTリクエスト処理（支払い実行）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF検証
    if (!verifyCsrfToken(post('csrf_token', ''))) {
        setFlash('error', '不正なリクエストです');
        redirect('/public/payment.php');
    }

    $attendeeId = (int)post('attendee_id');
    $useCredit = (bool)post('use_credit', false);

    if (!$attendeeId) {
        setFlash('error', '参加者を選択してください');
        redirect('/public/payment.php?email=' . urlencode($email));
    }

    // 参加者情報取得
    $attendee = Attendee::getById($attendeeId);

    if (!$attendee) {
        setFlash('error', '参加者が見つかりません');
        redirect('/public/payment.php?email=' . urlencode($email));
    }

    // 既に支払済みかチェック
    if ($attendee['status'] !== 'applied') {
        setFlash('error', 'この参加者は既に支払い済みです');
        redirect('/public/payment.php?email=' . urlencode($email));
    }

    try {
        // セミナー情報取得
        $seminar = \Seminar\Seminar::getById($attendee['seminar_id']);
        $price = $seminar['price'];

        // クレジット適用
        $finalAmount = $price;
        if ($useCredit && $totalCredit > 0) {
            $creditToUse = min($totalCredit, $price);
            $finalAmount = $price - $creditToUse;

            // クレジット使用
            Attendee::useCredit($attendee['email'], $creditToUse);
        }

        // 金額が0の場合は自動的に支払済みに
        if ($finalAmount <= 0) {
            Attendee::updateStatus($attendeeId, 'paid');
            setFlash('success', 'クレジットで全額お支払い完了しました');
            redirect('/public/thank-you.php?attendee_id=' . $attendeeId);
        }

        // Square Payment Link作成
        $squareClient = new SquareClient();
        $paymentLink = $squareClient->createPaymentLink($attendeeId, $finalAmount);

        // Payment URLにリダイレクト
        $paymentUrl = $paymentLink->getUrl();
        header('Location: ' . $paymentUrl);
        exit;

    } catch (\Exception $e) {
        Logger::error('Payment link creation failed', [
            'attendee_id' => $attendeeId,
            'error' => $e->getMessage()
        ]);
        setFlash('error', '決済リンクの作成に失敗しました: ' . $e->getMessage());
        redirect('/public/payment.php?email=' . urlencode($email));
    }
}

// CSRFトークン生成
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>参加費のお支払い - セミナー管理システム</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .header {
            margin-bottom: 60px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .subtitle {
            font-size: 13px;
            color: #999;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 32px;
            border: 1px solid #e0e0e0;
            background: #fff;
        }

        .alert-success {
            border-color: #4caf50;
            background: #f1f8f4;
            color: #2e7d32;
        }

        .alert-error {
            border-color: #f44336;
            background: #fef5f5;
            color: #c62828;
        }

        .search-form {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 48px 32px;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #333;
        }

        .btn {
            display: inline-block;
            padding: 16px 48px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #333;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
            width: 100%;
        }

        .btn:hover {
            background: #000;
        }

        .credit-card {
            background: #fffbf0;
            border: 1px solid #ffc107;
            padding: 24px;
            margin-bottom: 32px;
            text-align: center;
        }

        .credit-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .credit-amount {
            font-size: 32px;
            font-weight: 300;
            color: #333;
        }

        .attendee-list {
            margin-bottom: 32px;
        }

        .attendee-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 32px;
            margin-bottom: 16px;
        }

        .attendee-title {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin-bottom: 16px;
        }

        .attendee-meta {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .attendee-price {
            font-size: 20px;
            font-weight: 500;
            color: #333;
            margin-top: 16px;
            margin-bottom: 16px;
        }

        .form-checkbox {
            margin-bottom: 16px;
        }

        .form-checkbox label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .form-checkbox input {
            margin-right: 8px;
        }

        .final-amount {
            font-size: 16px;
            color: #f44336;
            margin-top: 8px;
        }

        .no-attendees {
            text-align: center;
            padding: 60px 24px;
            color: #999;
        }

        .back-link {
            text-align: center;
            margin-top: 32px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }

        .back-link a:hover {
            color: #333;
        }

        @media (max-width: 640px) {
            .container {
                padding: 40px 16px;
            }

            .search-form,
            .credit-card,
            .attendee-card {
                padding: 32px 24px;
            }

            .btn {
                padding: 14px 32px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ヘッダー -->
        <header class="header">
            <h1>参加費のお支払い</h1>
            <p class="subtitle">メールアドレスを入力して未払いのセミナーを確認してください</p>
        </header>

        <!-- フラッシュメッセージ -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo h($flash['type']); ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <!-- メールアドレス検索 -->
        <form method="GET" action="/seminar-system/public/payment.php" class="search-form">
            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input" value="<?php echo h($email); ?>" required>
            </div>
            <button type="submit" class="btn">検索</button>
        </form>

        <?php if ($email): ?>
            <?php if ($totalCredit > 0): ?>
                <!-- 繰越クレジット表示 -->
                <div class="credit-card">
                    <div class="credit-label">繰越クレジット</div>
                    <div class="credit-amount"><?php echo formatPrice($totalCredit); ?></div>
                </div>
            <?php endif; ?>

            <?php if (empty($unpaidAttendees)): ?>
                <!-- 未払いなし -->
                <div class="no-attendees">
                    <p>お支払いが必要なセミナーはありません</p>
                </div>
            <?php else: ?>
                <!-- 未払い一覧 -->
                <div class="attendee-list">
                    <?php foreach ($unpaidAttendees as $att): ?>
                        <form method="POST" action="/seminar-system/public/payment.php" class="attendee-card">
                            <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">
                            <input type="hidden" name="attendee_id" value="<?php echo $att['id']; ?>">

                            <div class="attendee-title"><?php echo h($att['seminar_title']); ?></div>
                            <div class="attendee-meta">
                                <?php echo formatDatetime($att['start_datetime'], 'Y年m月d日（' . getWeekday($att['start_datetime']) . '）H:i'); ?>
                            </div>
                            <div class="attendee-meta">
                                参加者: <?php echo h($att['name']); ?>
                            </div>

                            <div class="attendee-price">
                                参加費: <?php echo formatPrice($att['price']); ?>
                            </div>

                            <?php if ($totalCredit > 0): ?>
                                <div class="form-checkbox">
                                    <label>
                                        <input type="checkbox" name="use_credit" value="1" checked onchange="updateFinalAmount(this, <?php echo $att['price']; ?>, <?php echo $totalCredit; ?>)">
                                        繰越クレジット（<?php echo formatPrice(min($totalCredit, $att['price'])); ?>）を使用する
                                    </label>
                                </div>
                                <div class="final-amount" id="final-amount-<?php echo $att['id']; ?>">
                                    お支払い金額: <?php echo formatPrice(max(0, $att['price'] - $totalCredit)); ?>
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn">支払いに進む</button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- トップへ戻る -->
        <div class="back-link">
            <a href="/seminar-system/public/index.php">← トップページへ戻る</a>
        </div>
    </div>

    <script>
        function updateFinalAmount(checkbox, price, credit) {
            const finalAmountElement = checkbox.closest('.attendee-card').querySelector('.final-amount');
            if (!finalAmountElement) return;

            const creditToUse = Math.min(credit, price);
            const finalAmount = checkbox.checked ? Math.max(0, price - creditToUse) : price;

            finalAmountElement.textContent = 'お支払い金額: ¥' + finalAmount.toLocaleString();
        }
    </script>
</body>
</html>
