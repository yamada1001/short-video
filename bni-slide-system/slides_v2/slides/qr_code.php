<?php
/**
 * BNI Slide System V2 - QR Code Display (p.242)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);

$stmt = $db->prepare("SELECT * FROM qr_codes ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$qr = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ビジターアンケート | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1400px; padding: 80px; text-align: center; }
        .title { font-size: 80px; font-weight: 700; color: #C8102E; margin-bottom: 30px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .subtitle { font-size: 42px; margin-bottom: 60px; opacity: 0.9; line-height: 1.6; }
        .content-wrapper { display: flex; align-items: center; justify-content: center; gap: 80px; }
        .qr-section { flex: 0 0 auto; }
        .qr-display { background: white; padding: 50px; border-radius: 30px; box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3); }
        .qr-display img { width: 400px; height: 400px; display: block; }
        .info-section { flex: 1; text-align: left; max-width: 600px; }
        .instruction { font-size: 36px; margin-bottom: 30px; line-height: 1.8; }
        .instruction-item { display: flex; align-items: flex-start; margin-bottom: 25px; }
        .instruction-icon { font-size: 40px; color: #C8102E; margin-right: 20px; min-width: 50px; }
        .thank-you { font-size: 32px; color: #FFD700; margin-top: 40px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-clipboard-list"></i> ビジターアンケート</div>
        <div class="subtitle">本日はご参加いただき、ありがとうございます</div>
        <?php if ($qr): ?>
            <div class="content-wrapper">
                <div class="qr-section">
                    <div class="qr-display">
                        <img src="../<?php echo htmlspecialchars($qr['qr_code_path']); ?>" alt="アンケートQRコード">
                    </div>
                </div>
                <div class="info-section">
                    <div class="instruction">
                        <div class="instruction-item">
                            <div class="instruction-icon"><i class="fas fa-mobile-alt"></i></div>
                            <div>スマートフォンのカメラでQRコードを読み取ってください</div>
                        </div>
                        <div class="instruction-item">
                            <div class="instruction-icon"><i class="fas fa-edit"></i></div>
                            <div>簡単なアンケートにご協力をお願いいたします</div>
                        </div>
                        <div class="instruction-item">
                            <div class="instruction-icon"><i class="fas fa-clock"></i></div>
                            <div>所要時間：約2〜3分</div>
                        </div>
                    </div>
                    <div class="thank-you">
                        <i class="fas fa-heart"></i> 皆様のご意見をお聞かせください
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p style="font-size: 36px; color: #999; margin-top: 60px;">アンケートの準備中です</p>
        <?php endif; ?>
    </div>
</body>
</html>
