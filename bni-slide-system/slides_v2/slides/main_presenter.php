<?php
/**
 * BNI Slide System V2 - Main Presenter Slide (p.8 / p.204)
 * メインプレゼンスライド表示
 */

require_once __DIR__ . '/../config.php';

// 対象の金曜日を取得


// データベース接続
$db = new PDO('sqlite:' . $db_path);

// 最新のプレゼンデータ取得
$stmt = $db->query("
    SELECT
        mp.*,
        m.name as member_name,
        m.company_name,
        m.category,
        m.photo_path
    FROM main_presenter mp
    LEFT JOIN members m ON mp.member_id = m.id
    ORDER BY mp.week_date DESC
    LIMIT 1
");
$presentation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$presentation) {
    // データがない場合
    $presentation = [
        'member_name' => '',
        'company_name' => '',
        'category' => '',
        'photo_path' => '',
        'youtube_url' => '',
    ];
    $hasData = false;
} else {
    $hasData = true;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メインプレゼン | BNI Slide System V2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            overflow: hidden;
            position: relative;
        }

        /* Full Screen Container */
        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
        }

        /* Background Pattern */
        .slide-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
            z-index: 0;
        }

        /* Content */
        .slide-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 1200px;
            width: 100%;
        }

        /* Photo */
        .member-photo {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            object-fit: cover;
            border: 8px solid white;
            margin: 0 auto 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            background: #fff;
        }

        /* Category */
        .member-category {
            font-size: 42px;
            font-weight: 500;
            margin-bottom: 25px;
            opacity: 0.95;
            letter-spacing: 2px;
        }

        /* Name */
        .member-name {
            font-size: 96px;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.2;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        }

        /* Company */
        .member-company {
            font-size: 52px;
            font-weight: 400;
            opacity: 0.95;
            line-height: 1.4;
        }

        /* No Data */
        .no-data {
            font-size: 48px;
            opacity: 0.7;
            text-align: center;
        }

        /* Page Number */
        .page-number {
            position: fixed;
            bottom: 30px;
            right: 40px;
            font-size: 28px;
            font-weight: 500;
            opacity: 0.8;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <?php if ($hasData): ?>
            <?php if ($presentation['presentation_type'] === 'extended' && !empty($presentation['pdf_path'])): ?>
                <!-- 拡張版: PDF表示 -->
                <iframe src="<?= htmlspecialchars($presentation['pdf_path']) ?>"
                        style="width: 100%; height: 100vh; border: none;"></iframe>
            <?php else: ?>
                <!-- シンプル版: メンバー情報表示 -->
                <div class="slide-content">
                    <?php if ($presentation['photo_path']): ?>
                        <img src="<?= htmlspecialchars($presentation['photo_path']) ?>"
                             alt="<?= htmlspecialchars($presentation['member_name']) ?>"
                             class="member-photo">
                    <?php else: ?>
                        <div class="member-photo"></div>
                    <?php endif; ?>

                    <?php if ($presentation['category']): ?>
                        <div class="member-category">
                            <?= htmlspecialchars($presentation['category']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="member-name">
                        <?= htmlspecialchars($presentation['member_name']) ?>
                    </div>

                    <?php if ($presentation['company_name']): ?>
                        <div class="member-company">
                            <?= htmlspecialchars($presentation['company_name']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-data">
                メインプレゼンデータが登録されていません
            </div>
        <?php endif; ?>
    </div>

    <div class="page-number">p.8</div>
</body>
</html>
