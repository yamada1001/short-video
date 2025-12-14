<?php
/**
 * BNI Slide System V2 - Open Recruiting Categories (p.185)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);
$targetFriday = getTargetFriday();

$stmt = $db->prepare("SELECT * FROM recruiting_categories WHERE week_date = :week_date AND category_type = 'open' ORDER BY id");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();
$categories = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $categories[] = $row; }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>募集カテゴリ | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1400px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; color: #C8102E; margin-bottom: 60px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .categories-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
        .category-box { background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); border: 2px solid rgba(200, 16, 46, 0.3); }
        .category-name { font-size: 36px; font-weight: 700; color: #fff; }
        .category-icon { font-size: 48px; color: #C8102E; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-bullhorn"></i> 募集カテゴリ</div>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <div class="category-box">
                    <div class="category-icon"><i class="fas fa-briefcase"></i></div>
                    <div class="category-name"><?php echo htmlspecialchars($cat['category_name']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
