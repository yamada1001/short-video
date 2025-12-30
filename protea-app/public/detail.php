<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Protea\KeywordRepository;

$repository = new KeywordRepository();

$keywordId = intval($_GET['id'] ?? 0);
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($keyword['keyword']) ?> - Protea</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .tabs {
            display: flex;
            border-bottom: 2px solid var(--color-border);
            margin-bottom: var(--spacing-md);
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border: none;
            background: none;
            color: var(--color-text-light);
            font-size: 14px;
            transition: all var(--transition-base);
        }

        .tab:hover {
            color: var(--color-natural-brown);
        }

        .tab.active {
            color: var(--color-natural-brown);
            border-bottom: 2px solid var(--color-natural-brown);
            margin-bottom: -2px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .data-list {
            list-style: none;
            margin: 0;
        }

        .data-list li {
            padding: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
        }

        .data-list li:last-child {
            border-bottom: none;
        }

        .article-preview {
            background: var(--color-off-white);
            padding: var(--spacing-md);
            border-left: 3px solid var(--color-natural-brown);
            margin-bottom: var(--spacing-sm);
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Protea Webアプリ</h1>
            <p class="subtitle">キーワード詳細</p>
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
        <div class="card">
            <h2><?= htmlspecialchars($keyword['keyword']) ?></h2>
            <p>
                <span class="badge <?= ($keyword['status'] === '公開済') ? 'badge-published' : (($keyword['status'] === '記事完成') ? 'badge-completed' : 'badge-processing') ?>">
                    <?= htmlspecialchars($keyword['status']) ?>
                </span>
                <span style="margin-left: var(--spacing-sm); color: var(--color-text-light);">
                    登録日時: <?= date('Y-m-d H:i', strtotime($keyword['created_at'])) ?>
                </span>
            </p>

            <div style="margin-top: var(--spacing-md);">
                <a href="generate.php?keyword_id=<?= $keywordId ?>" class="btn">
                    <i class="fas fa-magic"></i> 記事生成
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 一覧に戻る
                </a>
            </div>
        </div>

        <!-- タブUI -->
        <div class="card">
            <div class="tabs">
                <button class="tab active" data-tab="blog">
                    競合ブログ (<?= count($relatedData['blog_articles']) ?>)
                </button>
                <button class="tab" data-tab="cooccurrence">
                    共起語 (<?= count($relatedData['cooccurrence_words']) ?>)
                </button>
                <button class="tab" data-tab="suggestions">
                    サジェスト (<?= count($relatedData['suggestions']) ?>)
                </button>
                <button class="tab" data-tab="contents">
                    競合記事本文 (<?= count($relatedData['article_contents']) ?>)
                </button>
                <button class="tab" data-tab="yahoo">
                    Yahoo知恵袋 (<?= count($relatedData['yahoo_qa']) ?>)
                </button>
                <button class="tab" data-tab="goo">
                    goo Q&A (<?= count($relatedData['goo_qa']) ?>)
                </button>
            </div>

            <!-- 競合ブログ -->
            <div class="tab-content active" id="tab-blog">
                <ul class="data-list">
                    <?php foreach ($relatedData['blog_articles'] as $article): ?>
                        <li>
                            <strong><?= htmlspecialchars($article['title']) ?></strong><br>
                            <a href="<?= htmlspecialchars($article['url']) ?>" target="_blank" style="font-size: 13px; color: var(--color-natural-brown);">
                                <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($article['url']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- 共起語 -->
            <div class="tab-content" id="tab-cooccurrence">
                <table>
                    <tr>
                        <th>共起語</th>
                        <th style="width: 100px;">出現回数</th>
                    </tr>
                    <?php foreach ($relatedData['cooccurrence_words'] as $word): ?>
                        <tr>
                            <td><?= htmlspecialchars($word['word']) ?></td>
                            <td><?= $word['count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- サジェスト -->
            <div class="tab-content" id="tab-suggestions">
                <table>
                    <tr>
                        <th>クエリ</th>
                        <th>サジェスト</th>
                    </tr>
                    <?php foreach ($relatedData['suggestions'] as $suggestion): ?>
                        <tr>
                            <td><?= htmlspecialchars($suggestion['query']) ?></td>
                            <td><?= htmlspecialchars($suggestion['suggestion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- 競合記事本文 -->
            <div class="tab-content" id="tab-contents">
                <?php foreach ($relatedData['article_contents'] as $content): ?>
                    <div class="article-preview">
                        <strong><?= htmlspecialchars($content['title']) ?></strong>
                        <span style="color: var(--color-text-light); font-size: 13px; margin-left: 10px;">
                            (<?= number_format($content['word_count']) ?>文字)
                        </span>
                        <br>
                        <a href="<?= htmlspecialchars($content['url']) ?>" target="_blank" style="font-size: 13px; color: var(--color-natural-brown);">
                            <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($content['url']) ?>
                        </a>
                        <p style="margin-top: 10px; white-space: pre-wrap; font-size: 14px;">
                            <?= nl2br(htmlspecialchars(mb_substr($content['content'], 0, 500))) ?>
                            <?php if (mb_strlen($content['content']) > 500): ?>...<?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Yahoo知恵袋 -->
            <div class="tab-content" id="tab-yahoo">
                <?php foreach ($relatedData['yahoo_qa'] as $qa): ?>
                    <div style="margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 1px solid var(--color-border);">
                        <h4 style="color: var(--color-natural-brown); margin-bottom: 8px;">
                            <i class="fas fa-question-circle"></i> 質問
                        </h4>
                        <p><?= nl2br(htmlspecialchars($qa['question'])) ?></p>

                        <h4 style="color: var(--color-natural-brown); margin-top: var(--spacing-sm); margin-bottom: 8px;">
                            <i class="fas fa-check-circle"></i> ベストアンサー
                        </h4>
                        <p><?= nl2br(htmlspecialchars($qa['best_answer'])) ?></p>

                        <?php if ($qa['url']): ?>
                            <a href="<?= htmlspecialchars($qa['url']) ?>" target="_blank" style="font-size: 13px; color: var(--color-natural-brown);">
                                <i class="fas fa-external-link-alt"></i> 元の質問を見る
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- goo Q&A -->
            <div class="tab-content" id="tab-goo">
                <?php if (empty($relatedData['goo_qa'])): ?>
                    <p style="text-align: center; color: var(--color-text-light); padding: var(--spacing-lg);">
                        goo Q&Aのデータがありません
                    </p>
                <?php else: ?>
                    <?php foreach ($relatedData['goo_qa'] as $qa): ?>
                        <div style="margin-bottom: var(--spacing-md); padding-bottom: var(--spacing-md); border-bottom: 1px solid var(--color-border);">
                            <h4 style="color: var(--color-natural-brown); margin-bottom: 8px;">
                                <i class="fas fa-question-circle"></i> 質問
                            </h4>
                            <p><?= nl2br(htmlspecialchars($qa['question'])) ?></p>

                            <h4 style="color: var(--color-natural-brown); margin-top: var(--spacing-sm); margin-bottom: 8px;">
                                <i class="fas fa-check-circle"></i> ベストアンサー
                            </h4>
                            <p><?= nl2br(htmlspecialchars($qa['best_answer'])) ?></p>

                            <?php if ($qa['url']): ?>
                                <a href="<?= htmlspecialchars($qa['url']) ?>" target="_blank" style="font-size: 13px; color: var(--color-natural-brown);">
                                    <i class="fas fa-external-link-alt"></i> 元の質問を見る
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // タブ切り替え
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetTab = tab.getAttribute('data-tab');

                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(tc => tc.classList.remove('active'));

                tab.classList.add('active');
                document.getElementById('tab-' + targetTab).classList.add('active');
            });
        });
    </script>
</body>
</html>
