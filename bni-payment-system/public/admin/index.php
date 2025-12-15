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

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 24px;
        }

        .admin-header {
            margin-bottom: 60px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin-header h1 {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .admin-subtitle {
            font-size: 13px;
            color: #999;
        }

        .admin-nav {
            margin-bottom: 40px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admin-nav a {
            display: inline-block;
            padding: 12px 24px;
            font-size: 14px;
            color: #666;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }

        .admin-nav a:hover {
            color: #333;
        }

        .admin-nav a.active {
            color: #DC143C;
            border-bottom-color: #DC143C;
        }

        .week-selector {
            margin-bottom: 40px;
        }

        .week-selector label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
        }

        .week-select {
            width: 100%;
            max-width: 400px;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .week-select:focus {
            outline: none;
            border-color: #DC143C;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 60px;
        }

        .stat-card {
            background: #fff;
            padding: 32px 24px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            letter-spacing: 0.1em;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 300;
            color: #333;
            letter-spacing: -0.02em;
        }

        .actions {
            margin-bottom: 32px;
        }

        .btn {
            display: inline-block;
            padding: 16px 32px;
            font-size: 14px;
            font-weight: 500;
            color: #fff;
            background: #DC143C;
            border: none;
            text-decoration: none;
            letter-spacing: 0.1em;
            transition: background 0.2s;
            cursor: pointer;
            font-family: 'Noto Sans JP', sans-serif;
        }

        .btn:hover {
            background: #A01225;
        }

        .btn-export {
            background: #333;
        }

        .btn-export:hover {
            background: #000;
        }

        .table-card {
            background: #fff;
            padding: 48px 32px;
            border: 1px solid #e0e0e0;
            margin-bottom: 32px;
        }

        .table-title {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 32px;
            letter-spacing: 0.05em;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 16px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: #999;
            border-bottom: 1px solid #e0e0e0;
            letter-spacing: 0.1em;
        }

        .data-table td {
            padding: 16px 12px;
            font-size: 15px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:hover {
            background: #fafafa;
        }

        .member-name {
            font-weight: 400;
            color: #333;
        }

        .member-email {
            font-size: 13px;
            color: #999;
        }

        .payment-date,
        .payment-amount {
            color: #666;
        }

        .text-muted {
            color: #ccc;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 400;
            border-radius: 0;
        }

        .badge-success {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #e0e0e0;
        }

        .badge-warning {
            background: #fff;
            color: #999;
            border: 1px solid #e0e0e0;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 40px 16px;
            }

            .table-card {
                padding: 32px 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 28px;
            }

            .data-table {
                font-size: 13px;
            }

            .data-table th,
            .data-table td {
                padding: 12px 8px;
            }
        }
    </style>
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
