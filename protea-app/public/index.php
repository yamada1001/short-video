<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Protea\KeywordRepository;

$repository = new KeywordRepository();

// 検索パラメータ取得
$params = [
    'keyword' => $_GET['keyword'] ?? '',
    'status' => $_GET['status'] ?? '',
    'page' => intval($_GET['page'] ?? 1),
    'per_page' => 20,
];

// キーワード一覧取得
$result = $repository->getKeywords($params);
$keywords = $result['data'];
$pagination = [
    'total' => $result['total'],
    'page' => $result['page'],
    'total_pages' => $result['total_pages'],
];

// ステータスバッジのクラス取得
function getStatusBadgeClass($status) {
    $map = [
        '未着手' => 'badge-pending',
        'スクレイピング完了' => 'badge-processing',
        '記事生成中' => 'badge-processing',
        '記事完成' => 'badge-completed',
        '公開済' => 'badge-published',
    ];
    return $map[$status] ?? 'badge-pending';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>キーワード一覧 - Protea</title>
    <link rel="stylesheet" href="/protea-app/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Protea Webアプリ</h1>
            <p class="subtitle">キーワード管理</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php" class="active">キーワード一覧</a></li>
                <li><a href="upload.php">Excelアップロード</a></li>
                <li><a href="dashboard.php">ダッシュボード</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- 検索フォーム -->
        <div class="card">
            <form method="GET" action="">
                <div style="display: grid; grid-template-columns: 1fr 1fr 150px; gap: var(--spacing-sm);">
                    <div class="form-group" style="margin: 0;">
                        <input type="text" name="keyword" class="form-control"
                               placeholder="キーワード検索..."
                               value="<?= htmlspecialchars($params['keyword']) ?>">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <select name="status" class="form-control">
                            <option value="">すべてのステータス</option>
                            <option value="未着手" <?= $params['status'] === '未着手' ? 'selected' : '' ?>>未着手</option>
                            <option value="スクレイピング完了" <?= $params['status'] === 'スクレイピング完了' ? 'selected' : '' ?>>スクレイピング完了</option>
                            <option value="記事生成中" <?= $params['status'] === '記事生成中' ? 'selected' : '' ?>>記事生成中</option>
                            <option value="記事完成" <?= $params['status'] === '記事完成' ? 'selected' : '' ?>>記事完成</option>
                            <option value="公開済" <?= $params['status'] === '公開済' ? 'selected' : '' ?>>公開済</option>
                        </select>
                    </div>

                    <button type="submit" class="btn">
                        <i class="fas fa-search"></i> 検索
                    </button>
                </div>
            </form>
        </div>

        <!-- 統計情報 -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            全<?= $pagination['total'] ?>件のキーワード
        </div>

        <!-- キーワード一覧テーブル -->
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>キーワード</th>
                    <th style="width: 150px;">ステータス</th>
                    <th style="width: 180px;">更新日時</th>
                    <th style="width: 200px;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($keywords)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--spacing-lg); color: var(--color-text-light);">
                            キーワードが登録されていません。<br>
                            <a href="upload.php" class="btn btn-sm" style="margin-top: var(--spacing-sm);">
                                Excelをアップロード
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($keywords as $keyword): ?>
                        <tr>
                            <td><?= $keyword['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($keyword['keyword']) ?></strong>
                            </td>
                            <td>
                                <span class="badge <?= getStatusBadgeClass($keyword['status']) ?>">
                                    <?= htmlspecialchars($keyword['status']) ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($keyword['updated_at'])) ?></td>
                            <td>
                                <a href="detail.php?id=<?= $keyword['id'] ?>" class="btn btn-sm">
                                    <i class="fas fa-eye"></i> 詳細
                                </a>
                                <?php if ($keyword['status'] === 'スクレイピング完了' || $keyword['status'] === '記事生成中'): ?>
                                    <a href="generate.php?keyword_id=<?= $keyword['id'] ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-magic"></i> 記事生成
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- ページネーション -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="?page=<?= $pagination['page'] - 1 ?>&keyword=<?= urlencode($params['keyword']) ?>&status=<?= urlencode($params['status']) ?>">
                        前へ
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <?php if ($i === $pagination['page']): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&keyword=<?= urlencode($params['keyword']) ?>&status=<?= urlencode($params['status']) ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?= $pagination['page'] + 1 ?>&keyword=<?= urlencode($params['keyword']) ?>&status=<?= urlencode($params['status']) ?>">
                        次へ
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
