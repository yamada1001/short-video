<?php
/**
 * BNI Slide System V2 - QR Code Display (p.242)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);
$targetFriday = getTargetFriday();

$stmt = $db->prepare("SELECT * FROM qr_codes WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();
$qr = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>QRコード | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1000px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; margin-bottom: 40px; text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); }
        .qr-display { background: white; padding: 40px; border-radius: 30px; box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3); margin: 40px auto; max-width: 600px; }
        .qr-display img { width: 100%; max-width: 500px; }
        .url-display { font-size: 28px; margin-top: 40px; word-break: break-all; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-qrcode"></i> QRコード</div>
        <?php if ($qr): ?>
            <div class="qr-display">
                <img src="../<?php echo htmlspecialchars($qr['qr_code_path']); ?>" alt="QR Code">
            </div>
            <div class="url-display"><?php echo htmlspecialchars($qr['url']); ?></div>
        <?php else: ?>
            <p style="font-size: 32px; color: #fff; opacity: 0.8;">QRコードが登録されていません</p>
        <?php endif; ?>
    </div>
</body>
</html>
