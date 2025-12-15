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
    <link rel="stylesheet" href="/bni-payment-system/public/assets/css/style.css">
    <link rel="stylesheet" href="/bni-payment-system/public/admin/assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- サイドバー -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>BNI管理</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/index.php" class="nav-link active">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>ダッシュボード</span>
                </a>
                <a href="/admin/members.php" class="nav-link">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>メンバー管理</span>
                </a>
                <a href="/index.php" class="nav-link" target="_blank">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>支払いページ</span>
                </a>
            </nav>
        </aside>

        <!-- メインコンテンツ -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>支払い状況ダッシュボード</h1>

                <!-- 週選択 -->
                <form method="GET" class="week-selector">
                    <label for="week">表示週:</label>
                    <select name="week" id="week" class="week-select" onchange="this.form.submit()">
                        <?php foreach ($weeksList as $week): ?>
                            <option value="<?php echo h($week); ?>" <?php echo $week === $weekOf ? 'selected' : ''; ?>>
                                <?php echo h(getWeekLabel($week)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <!-- 統計カード -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-total">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">総メンバー数</div>
                        <div class="stat-value"><?php echo $totalMembers; ?>人</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-paid">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">支払い済み</div>
                        <div class="stat-value"><?php echo $paidCount; ?>人</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-unpaid">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">未払い</div>
                        <div class="stat-value"><?php echo $unpaidCount; ?>人</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-amount">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">合計金額</div>
                        <div class="stat-value">¥<?php echo number_format($totalAmount); ?></div>
                    </div>
                </div>
            </div>

            <!-- エクスポートボタン -->
            <div class="actions">
                <a href="/admin/export.php?week=<?php echo urlencode($weekOf); ?>" class="btn btn-export">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>CSVエクスポート</span>
                </a>
            </div>

            <!-- メンバーテーブル -->
            <div class="table-card">
                <h2 class="table-title">メンバー別支払い状況</h2>

                <div class="table-wrapper">
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
                                <tr class="<?php echo $isPaid ? 'paid-row' : 'unpaid-row'; ?>">
                                    <td class="member-name">
                                        <?php echo h($member['name']); ?>
                                    </td>
                                    <td class="member-email">
                                        <?php echo h($member['email']); ?>
                                    </td>
                                    <td>
                                        <?php if ($isPaid): ?>
                                            <span class="badge badge-success">
                                                <svg class="badge-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                支払い済み
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <svg class="badge-icon" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                未払い
                                            </span>
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
            </div>
        </main>
    </div>

    <script src="/assets/js/app.js"></script>
</body>
</html>
