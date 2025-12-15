<?php
/**
 * BNI Slide System V2 - Main Presenter Slide (p.204)
 * メインプレゼンアイキャッチスライド（拡張版開始ページ）
 */

require_once __DIR__ . '/../config.php';

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
    ORDER BY mp.created_at DESC
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

// YouTube URLを埋め込み用URLに変換
$youtubeEmbedUrl = null;
if (!empty($presentation['youtube_url'])) {
    $url = $presentation['youtube_url'];

    // 様々な形式のYouTube URLに対応
    // https://www.youtube.com/watch?v=VIDEO_ID
    // https://youtu.be/VIDEO_ID
    // https://www.youtube.com/embed/VIDEO_ID

    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches);

    if (!empty($matches[1])) {
        $videoId = $matches[1];
        $youtubeEmbedUrl = "https://www.youtube.com/embed/{$videoId}";
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メインプレゼン (p.204) | BNI Slide System V2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #1a0a0f 0%, #C8102E 50%, #a00a24 100%);
            color: white;
            overflow: hidden;
            position: relative;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,215,0,0.15) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            z-index: 0;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            z-index: 1;
        }

        /* Spotlight Effect */
        .slide-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(255,215,0,0.2) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }

        /* Decorative Corner Elements */
        .slide-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(90deg, rgba(255,215,0,0.3) 0%, transparent 20%),
                linear-gradient(180deg, rgba(255,215,0,0.3) 0%, transparent 20%),
                linear-gradient(-90deg, rgba(255,215,0,0.3) 0%, transparent 20%),
                linear-gradient(0deg, rgba(255,215,0,0.3) 0%, transparent 20%);
            z-index: 0;
        }

        /* Content */
        .slide-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1400px;
            width: 100%;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Photo Container */
        .photo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 50px;
        }

        /* Photo */
        .member-photo {
            width: 350px;
            height: 350px;
            border-radius: 50%;
            object-fit: cover;
            border: 10px solid #FFD700;
            margin: 0 auto;
            box-shadow:
                0 0 40px rgba(255, 215, 0, 0.6),
                0 0 80px rgba(255, 215, 0, 0.4),
                0 20px 60px rgba(0,0,0,0.5);
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            animation: photoGlow 2s ease-in-out infinite;
        }

        @keyframes photoGlow {
            0%, 100% {
                box-shadow:
                    0 0 40px rgba(255, 215, 0, 0.6),
                    0 0 80px rgba(255, 215, 0, 0.4),
                    0 20px 60px rgba(0,0,0,0.5);
            }
            50% {
                box-shadow:
                    0 0 60px rgba(255, 215, 0, 0.8),
                    0 0 120px rgba(255, 215, 0, 0.6),
                    0 20px 60px rgba(0,0,0,0.5);
            }
        }

        /* Category */
        .member-category {
            font-size: 48px;
            font-weight: 600;
            margin-bottom: 30px;
            letter-spacing: 4px;
            text-transform: uppercase;
            background: linear-gradient(90deg, #FFD700 0%, #FFA500 50%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
            background-size: 200% auto;
        }

        @keyframes shimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        /* Name */
        .member-name {
            font-size: 110px;
            font-weight: 900;
            margin-bottom: 35px;
            line-height: 1.2;
            text-shadow:
                0 0 20px rgba(255, 215, 0, 0.5),
                0 0 40px rgba(255, 215, 0, 0.3),
                4px 4px 12px rgba(0,0,0,0.4);
            letter-spacing: 2px;
            animation: titlePulse 2s ease-in-out infinite;
        }

        @keyframes titlePulse {
            0%, 100% {
                text-shadow:
                    0 0 20px rgba(255, 215, 0, 0.5),
                    0 0 40px rgba(255, 215, 0, 0.3),
                    4px 4px 12px rgba(0,0,0,0.4);
            }
            50% {
                text-shadow:
                    0 0 30px rgba(255, 215, 0, 0.8),
                    0 0 60px rgba(255, 215, 0, 0.5),
                    4px 4px 12px rgba(0,0,0,0.4);
            }
        }

        /* Company */
        .member-company {
            font-size: 58px;
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 50px;
            padding: 20px 40px;
            background: linear-gradient(90deg, transparent 0%, rgba(255,215,0,0.2) 50%, transparent 100%);
            border-left: 4px solid #FFD700;
            border-right: 4px solid #FFD700;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
        }

        /* YouTube Video */
        .youtube-video {
            margin-top: 50px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 0 40px rgba(255, 215, 0, 0.4),
                0 20px 60px rgba(0,0,0,0.5);
            border: 4px solid #FFD700;
            position: relative;
        }

        .youtube-video::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #FFD700, #FFA500, #FFD700);
            border-radius: 20px;
            z-index: -1;
            animation: borderGlow 3s linear infinite;
            background-size: 200% 200%;
        }

        @keyframes borderGlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .youtube-video iframe {
            width: 100%;
            height: 500px;
            border: none;
            display: block;
        }

        /* No Data */
        .no-data {
            font-size: 56px;
            opacity: 0.8;
            text-align: center;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }

        /* Page Number */
        .page-number {
            position: fixed;
            bottom: 40px;
            right: 50px;
            font-size: 32px;
            font-weight: 700;
            color: #FFD700;
            text-shadow:
                0 0 10px rgba(255, 215, 0, 0.6),
                2px 2px 4px rgba(0,0,0,0.5);
            z-index: 10;
        }

        /* Title Banner */
        .title-banner {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg, transparent 0%, rgba(255,215,0,0.3) 20%, rgba(255,215,0,0.3) 80%, transparent 100%);
            padding: 15px 80px;
            border-top: 2px solid #FFD700;
            border-bottom: 2px solid #FFD700;
            z-index: 10;
        }

        .title-banner h1 {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 6px;
            text-shadow:
                0 0 20px rgba(255, 215, 0, 0.6),
                2px 2px 6px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="title-banner">
        <h1>★ MAIN PRESENTATION ★</h1>
    </div>

    <div class="slide-container">
        <?php if ($hasData): ?>
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

                <?php if ($youtubeEmbedUrl): ?>
                    <div class="youtube-video">
                        <iframe src="<?= htmlspecialchars($youtubeEmbedUrl) ?>"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="no-data">
                メインプレゼンデータが登録されていません
            </div>
        <?php endif; ?>
    </div>

    <div class="page-number">p.204</div>
</body>
</html>
