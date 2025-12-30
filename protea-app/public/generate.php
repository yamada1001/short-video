<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Protea\KeywordRepository;
use Protea\MockAPIClient;
use Protea\Database;

$repository = new KeywordRepository();
$mockAPI = new MockAPIClient();

$keywordId = intval($_GET['keyword_id'] ?? 0);
if (!$keywordId) {
    header('Location: index.php');
    exit;
}

$keyword = $repository->getKeywordById($keywordId);
if (!$keyword) {
    header('Location: index.php');
    exit;
}

$relatedData = $repository->getRelatedData($keywordId);

$error = null;
$success = null;
$generatedArticle = null;

// POST処理（記事生成）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // キーワードデータ準備
        $keywordData = [
            'keyword' => $keyword['keyword'],
            'competitors' => array_column($relatedData['blog_articles'], 'title'),
            'cooccurrence' => array_column($relatedData['cooccurrence_words'], 'word'),
            'yahoo_qa' => $relatedData['yahoo_qa'],
        ];

        // モックAPI（記事生成）
        $result = $mockAPI->generateFullArticle($keywordData);

        // DBに保存
        $db = Database::getInstance();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("
            INSERT INTO generated_articles (keyword_id, title, content, gpt_model, word_count, status)
            VALUES (:keyword_id, :title, :content, :model, :word_count, '下書き')
        ");

        $stmt->execute([
            'keyword_id' => $keywordId,
            'title' => $result['title'],
            'content' => $result['content'],
            'model' => $result['model'],
            'word_count' => $result['word_count'],
        ]);

        $articleId = $pdo->lastInsertId();

        // キーワードステータス更新
        $pdo->prepare("UPDATE keywords SET status = '記事完成' WHERE id = :id")
             ->execute(['id' => $keywordId]);

        $generatedArticle = $result;
        $success = "記事を生成しました（{$result['word_count']}文字）";

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// 既存の生成記事を取得
$db = Database::getInstance();
$pdo = $db->getPDO();
$stmt = $pdo->prepare("SELECT * FROM generated_articles WHERE keyword_id = :id ORDER BY created_at DESC LIMIT 1");
$stmt->execute(['id' => $keywordId]);
$existingArticle = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事生成 - <?= htmlspecialchars($keyword['keyword']) ?></title>
    <link rel="stylesheet" href="/protea-app/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .preview-area {
            background: white;
            padding: var(--spacing-lg);
            border: 1px solid var(--color-border);
            margin-top: var(--spacing-md);
            max-height: 600px;
            overflow-y: auto;
        }

        .preview-area h1 {
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
        }

        .preview-area h2 {
            color: var(--color-charcoal);
            margin-top: var(--spacing-lg);
            margin-bottom: var(--spacing-sm);
            padding-bottom: var(--spacing-sm);
            border-bottom: 2px solid var(--color-border);
        }

        .preview-area h3 {
            color: var(--color-text);
            margin-top: var(--spacing-md);
            margin-bottom: var(--spacing-sm);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .stat-card {
            background: var(--color-off-white);
            padding: var(--spacing-md);
            text-align: center;
            border-left: 3px solid var(--color-natural-brown);
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: var(--color-natural-brown);
        }

        .stat-card .label {
            font-size: 13px;
            color: var(--color-text-light);
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Protea Webアプリ</h1>
            <p class="subtitle">記事生成</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">キーワード一覧</a></li>
                <li><a href="upload.php">Excelアップロード</a></li>
                <li><a href="dashboard.php">ダッシュボード</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><?= htmlspecialchars($keyword['keyword']) ?></h2>
            <p style="color: var(--color-text-light);">
                キーワードID: <?= $keywordId ?>
            </p>

            <div style="margin-top: var(--spacing-md);">
                <a href="detail.php?id=<?= $keywordId ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 詳細に戻る
                </a>
            </div>
        </div>

        <!-- 統計情報 -->
        <div class="card">
            <h3>利用可能なデータ</h3>
            <div class="stats">
                <div class="stat-card">
                    <div class="value"><?= count($relatedData['blog_articles']) ?></div>
                    <div class="label">競合ブログ</div>
                </div>
                <div class="stat-card">
                    <div class="value"><?= count($relatedData['cooccurrence_words']) ?></div>
                    <div class="label">共起語</div>
                </div>
                <div class="stat-card">
                    <div class="value"><?= count($relatedData['yahoo_qa']) ?></div>
                    <div class="label">Yahoo知恵袋</div>
                </div>
            </div>
        </div>

        <!-- 記事生成フォーム -->
        <div class="card">
            <h3>記事生成</h3>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                開発環境のため、モックレスポンスを使用します（実際のGPT APIは呼び出されません）
            </div>

            <form method="POST">
                <div class="form-group">
                    <label>モデル選択</label>
                    <select name="model" class="form-control">
                        <option value="mock-gpt-4-turbo">モック GPT-4 Turbo</option>
                        <option value="mock-gpt-4">モック GPT-4</option>
                        <option value="mock-gpt-3.5-turbo">モック GPT-3.5 Turbo</option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-magic"></i> 記事を生成
                </button>
            </form>
        </div>

        <!-- 生成結果プレビュー -->
        <?php if ($generatedArticle): ?>
            <div class="card">
                <h3>生成結果</h3>
                <div class="preview-area">
                    <?= $generatedArticle['content'] ?>
                </div>
            </div>
        <?php elseif ($existingArticle): ?>
            <div class="card">
                <h3>既存の生成記事</h3>
                <p style="color: var(--color-text-light);">
                    生成日時: <?= date('Y-m-d H:i', strtotime($existingArticle['created_at'])) ?> |
                    文字数: <?= number_format($existingArticle['word_count']) ?>文字 |
                    モデル: <?= htmlspecialchars($existingArticle['gpt_model']) ?>
                </p>
                <div class="preview-area">
                    <?= $existingArticle['content'] ?>
                </div>

                <div style="margin-top: var(--spacing-md);">
                    <a href="edit.php?article_id=<?= $existingArticle['id'] ?>" class="btn">
                        <i class="fas fa-edit"></i> 編集する
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
