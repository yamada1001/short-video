<?php
/**
 * 管理者ダッシュボード
 * 週ごとの支払い状況確認
 */
require_once __DIR__ . '/../../config/config.php';

use BNI\Member;
use BNI\Payment;

// 表示する週を取得（デフォルトは今週）
$weekOf = $_GET['week'] ?? getCurrentWeek();

// 週のリスト（過去4週間）
$weeksList = Payment::getWeeksList(4);

// メンバー一覧（アクティブのみ）
$members = Member::getAll(true);

// 週ごとの支払い取得
$payments = Payment::getByWeek($weekOf);

// 統計
$stats = Payment::getWeekStats($weekOf);
$totalMembers = count($members);
$paidCount = $stats['paid_count'];
$unpaidCount = $totalMembers - $paidCount;
$totalAmount = $stats['total_amount'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード - BNI Payment System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/bni-payment-system/public/assets/css/style.css">
    <link rel="stylesheet" href="/bni-payment-system/public/admin/assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- ヘッダー -->
        <header class="admin-header">
            <h1>BNI Payment System</h1>
            <p class="admin-subtitle">支払い状況管理</p>
        </header>

        <!-- ナビゲーション -->
        <nav class="admin-nav">
            <a href="/bni-payment-system/public/admin/index.php" class="active">ダッシュボード</a>
            <a href="/bni-payment-system/public/admin/members.php">メンバー管理</a>
            <a href="/bni-payment-system/public/index.php" target="_blank">支払いページ</a>
        </nav>

        <!-- 週選択 -->
        <form method="GET" class="week-selector">
            <label for="week">表示週</label>
            <select name="week" id="week" class="week-select" onchange="this.form.submit()">
                <?php foreach ($weeksList as $week): ?>
                    <option value="<?php echo h($week); ?>" <?php echo $week === $weekOf ? 'selected' : ''; ?>>
                        <?php echo h(getWeekLabel($week)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- 統計 -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">総メンバー数</div>
                <div class="stat-value"><?php echo $totalMembers; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">支払い済み</div>
                <div class="stat-value"><?php echo $paidCount; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">未払い</div>
                <div class="stat-value"><?php echo $unpaidCount; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">合計金額</div>
                <div class="stat-value">¥<?php echo number_format($totalAmount); ?></div>
            </div>
        </div>

        <!-- エクスポート -->
        <div class="actions">
            <a href="/bni-payment-system/public/admin/export.php?week=<?php echo urlencode($weekOf); ?>" class="btn btn-export">CSVエクスポート</a>
        </div>

        <!-- メンバーテーブル -->
        <div class="table-card">
            <h2 class="table-title">メンバー別支払い状況</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>メールアドレス</th>
                        <th>ステータス</th>
                        <th>支払い日時</th>
                        <th>金額</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <?php
                        $payment = $payments[$member['id']] ?? null;
                        $isPaid = !is_null($payment);
                        ?>
                        <tr>
                            <td class="member-name"><?php echo h($member['name']); ?></td>
                            <td class="member-email"><?php echo h($member['email']); ?></td>
                            <td>
                                <?php if ($isPaid): ?>
                                    <span class="badge badge-success">支払い済み</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">未払い</span>
                                <?php endif; ?>
                            </td>
                            <td class="payment-date">
                                <?php if ($isPaid): ?>
                                    <?php echo h(date('Y/m/d H:i', strtotime($payment['paid_at']))); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="payment-amount">
                                <?php if ($isPaid): ?>
                                    ¥<?php echo number_format($payment['amount']); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- フッター -->
        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> BNI Payment System</p>
        </footer>
    </div>
</body>
</html>
