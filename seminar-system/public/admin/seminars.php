<?php
/**
 * セミナー管理画面
 */
require_once __DIR__ . '/../../config/config.php';

use Seminar\Seminar;

$currentPage = 'seminars';
$pageTitle = 'セミナー管理';

// フラッシュメッセージ取得
$flash = getFlash();

// セミナー削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'delete') {
    $seminarId = (int)post('seminar_id');

    if ($seminarId && Seminar::delete($seminarId)) {
        setFlash('success', 'セミナーを削除しました');
    } else {
        setFlash('error', 'セミナーの削除に失敗しました');
    }

    redirect('/public/admin/seminars.php');
}

// 全セミナー取得
$seminars = Seminar::getAll();

// 各セミナーの統計情報を取得
$seminarStats = [];
foreach ($seminars as $seminar) {
    $seminarStats[$seminar['id']] = [
        'total' => Seminar::getAttendeeCount($seminar['id']),
        'paid' => Seminar::getPaidCount($seminar['id']),
        'attended' => Seminar::getAttendedCount($seminar['id'])
    ];
}

// ヘッダー読み込み
include __DIR__ . '/includes/header.php';
?>

<!-- 追加ボタン -->
<div class="actions">
    <a href="/seminar-system/public/admin/seminar-form.php" class="btn">新規セミナー作成</a>
</div>

<!-- セミナー一覧 -->
<div class="card">
    <h2 class="card-title">セミナー一覧</h2>

    <?php if (empty($seminars)): ?>
        <p class="text-muted">セミナーが登録されていません</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>セミナー名</th>
                    <th>開催日時</th>
                    <th>開催場所</th>
                    <th>価格</th>
                    <th>申込者数</th>
                    <th>ステータス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seminars as $sem): ?>
                    <?php
                    $stats = $seminarStats[$sem['id']];
                    $isUpcoming = isFuture($sem['start_datetime']);
                    ?>
                    <tr>
                        <td><?php echo $sem['id']; ?></td>
                        <td>
                            <strong><?php echo h($sem['title']); ?></strong>
                            <?php if (!$sem['is_active']): ?>
                                <span class="badge badge-muted">無効</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo formatDatetime($sem['start_datetime'], 'Y/m/d H:i'); ?>
                            <?php if ($isUpcoming): ?>
                                <span class="badge badge-info">予定</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted"><?php echo h($sem['venue']) ?: '-'; ?></td>
                        <td><?php echo formatPrice($sem['price']); ?></td>
                        <td>
                            <?php echo $stats['total']; ?>名
                            <span class="text-muted">(支払済: <?php echo $stats['paid']; ?>名)</span>
                        </td>
                        <td>
                            <?php if (Seminar::isRegistrationOpen($sem['id'])): ?>
                                <span class="badge badge-success">受付中</span>
                            <?php elseif ($isUpcoming): ?>
                                <span class="badge badge-warning">締切</span>
                            <?php else: ?>
                                <span class="badge badge-muted">終了</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/seminar-system/public/admin/seminar-form.php?id=<?php echo $sem['id']; ?>" class="btn btn-small btn-secondary">編集</a>
                            <a href="/seminar-system/public/admin/attendees.php?seminar_id=<?php echo $sem['id']; ?>" class="btn btn-small btn-secondary">参加者</a>

                            <form method="POST" style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="seminar_id" value="<?php echo $sem['id']; ?>">
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
