<?php
/**
 * 参加者管理画面
 */
require_once __DIR__ . '/../../config/config.php';

use Seminar\Attendee;
use Seminar\Seminar;

$currentPage = 'attendees';
$pageTitle = '参加者管理';

// フラッシュメッセージ取得
$flash = getFlash();

// ステータス更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'update_status') {
    $attendeeId = (int)post('attendee_id');
    $newStatus = post('status');

    if ($attendeeId && in_array($newStatus, ['applied', 'absent', 'paid', 'attended'])) {
        if (Attendee::updateStatus($attendeeId, $newStatus)) {
            setFlash('success', 'ステータスを更新しました');
        } else {
            setFlash('error', 'ステータスの更新に失敗しました');
        }
    }

    redirect('/public/admin/attendees.php?' . http_build_query($_GET));
}

// 参加者削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'delete') {
    $attendeeId = (int)post('attendee_id');

    if ($attendeeId && Attendee::delete($attendeeId)) {
        setFlash('success', '参加者を削除しました');
    } else {
        setFlash('error', '参加者の削除に失敗しました');
    }

    redirect('/public/admin/attendees.php?' . http_build_query($_GET));
}

// フィルター
$seminarId = (int)get('seminar_id');
$statusFilter = get('status', '');

// 参加者取得
$attendees = Attendee::getAll($seminarId ?: null);

// ステータスフィルター適用
if ($statusFilter) {
    $attendees = array_filter($attendees, function($a) use ($statusFilter) {
        return $a['status'] === $statusFilter;
    });
}

// セミナー一覧取得（フィルター用）
$seminars = Seminar::getAll();

// 統計
$stats = [
    'total' => 0,
    'applied' => 0,
    'absent' => 0,
    'paid' => 0,
    'attended' => 0
];

foreach ($attendees as $att) {
    $stats['total']++;
    $stats[$att['status']]++;
}

// ヘッダー読み込み
include __DIR__ . '/includes/header.php';
?>

<!-- 統計 -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">総参加者数</div>
        <div class="stat-value"><?php echo $stats['total']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">申込済</div>
        <div class="stat-value"><?php echo $stats['applied']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">支払済</div>
        <div class="stat-value"><?php echo $stats['paid']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">出席済</div>
        <div class="stat-value"><?php echo $stats['attended']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">欠席</div>
        <div class="stat-value"><?php echo $stats['absent']; ?></div>
    </div>
</div>

<!-- フィルター -->
<div class="card">
    <form method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label class="form-label">セミナー</label>
            <select name="seminar_id" class="form-select">
                <option value="">全てのセミナー</option>
                <?php foreach ($seminars as $sem): ?>
                    <option value="<?php echo $sem['id']; ?>" <?php echo $seminarId === $sem['id'] ? 'selected' : ''; ?>>
                        <?php echo h($sem['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label class="form-label">ステータス</label>
            <select name="status" class="form-select">
                <option value="">全てのステータス</option>
                <option value="applied" <?php echo $statusFilter === 'applied' ? 'selected' : ''; ?>>申込済</option>
                <option value="paid" <?php echo $statusFilter === 'paid' ? 'selected' : ''; ?>>支払済</option>
                <option value="attended" <?php echo $statusFilter === 'attended' ? 'selected' : ''; ?>>出席済</option>
                <option value="absent" <?php echo $statusFilter === 'absent' ? 'selected' : ''; ?>>欠席</option>
            </select>
        </div>

        <button type="submit" class="btn">フィルター</button>
        <a href="/seminar-system/public/admin/attendees.php" class="btn btn-secondary">クリア</a>
    </form>
</div>

<!-- 参加者一覧 -->
<div class="card">
    <h2 class="card-title">参加者一覧（<?php echo count($attendees); ?>名）</h2>

    <?php if (empty($attendees)): ?>
        <p class="text-muted">参加者が見つかりません</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>セミナー</th>
                    <th>名前</th>
                    <th>メール</th>
                    <th>電話</th>
                    <th>ステータス</th>
                    <th>申込日時</th>
                    <th>クレジット</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendees as $att): ?>
                    <tr>
                        <td><?php echo $att['id']; ?></td>
                        <td>
                            <strong><?php echo h($att['seminar_title']); ?></strong><br>
                            <span class="text-muted">
                                <?php echo formatDatetime($att['start_datetime'], 'Y/m/d H:i'); ?>
                            </span>
                        </td>
                        <td><?php echo h($att['name']); ?></td>
                        <td class="text-muted"><?php echo h($att['email']); ?></td>
                        <td class="text-muted"><?php echo h($att['phone']) ?: '-'; ?></td>
                        <td>
                            <span class="badge <?php echo getStatusBadgeClass($att['status']); ?>">
                                <?php echo getStatusLabel($att['status']); ?>
                            </span>
                        </td>
                        <td class="text-muted">
                            <?php echo formatDatetime($att['applied_at'], 'Y/m/d H:i'); ?>
                        </td>
                        <td>
                            <?php if ($att['credit_amount'] > 0): ?>
                                <strong><?php echo formatPrice($att['credit_amount']); ?></strong>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- ステータス変更 -->
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="attendee_id" value="<?php echo $att['id']; ?>">
                                <select name="status" class="form-select" style="width: auto; display: inline-block; padding: 6px 12px; font-size: 13px;" onchange="this.form.submit();">
                                    <option value="applied" <?php echo $att['status'] === 'applied' ? 'selected' : ''; ?>>申込済</option>
                                    <option value="paid" <?php echo $att['status'] === 'paid' ? 'selected' : ''; ?>>支払済</option>
                                    <option value="attended" <?php echo $att['status'] === 'attended' ? 'selected' : ''; ?>>出席済</option>
                                    <option value="absent" <?php echo $att['status'] === 'absent' ? 'selected' : ''; ?>>欠席</option>
                                </select>
                            </form>

                            <!-- 削除 -->
                            <form method="POST" style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="attendee_id" value="<?php echo $att['id']; ?>">
                                <button type="submit" class="btn btn-small btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
