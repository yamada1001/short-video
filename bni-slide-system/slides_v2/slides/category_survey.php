<?php
/**
 * BNI Slide System V2 - Category Survey Results (p.194)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);

$stmt = $db->prepare("SELECT * FROM recruiting_categories WHERE type = 'survey' AND week_date = (SELECT MAX(week_date) FROM recruiting_categories WHERE type = 'survey') ORDER BY rank");
$stmt->execute();
$categories = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $categories[] = $row; }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カテゴリアンケート結果 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1400px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; color: #C8102E; margin-bottom: 60px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .rankings { display: flex; flex-direction: column; gap: 30px; margin-top: 40px; }
        .rank-item { background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px 50px; backdrop-filter: blur(10px); border: 2px solid rgba(200, 16, 46, 0.3); display: flex; align-items: center; gap: 40px; }
        .rank-number { font-size: 64px; font-weight: 700; color: #C8102E; min-width: 100px; }
        .rank-1 .rank-number { color: #FFD700; }
        .category-name { font-size: 48px; font-weight: 700; color: #fff; flex: 1; text-align: left; }
        .vote-count { font-size: 56px; font-weight: 700; color: #C8102E; }
        .rank-1 .vote-count { color: #FFD700; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-poll"></i> カテゴリアンケート結果</div>
        <div class="rankings">
            <?php foreach ($categories as $cat): ?>
                <div class="rank-item rank-<?php echo $cat['rank']; ?>">
                    <div class="rank-number"><?php echo $cat['rank']; ?>位</div>
                    <div class="category-name"><?php echo htmlspecialchars($cat['category_name']); ?></div>
                    <div class="vote-count"><?php echo $cat['vote_count']; ?>票</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
