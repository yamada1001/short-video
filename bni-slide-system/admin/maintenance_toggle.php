<?php
/**
 * BNI Slide System - Maintenance Mode Toggle
 * メンテナンスモードON/OFF切り替え（管理者専用）
 */

require_once __DIR__ . '/../includes/user_auth.php';

// 管理者チェック
$user = getCurrentUserInfo();
if (!$user || ($user['role'] !== 'admin' && $user['email'] !== 'yamada@yojitu.com')) {
    header('HTTP/1.1 403 Forbidden');
    die('アクセス権限がありません');
}

$configFile = __DIR__ . '/../config/maintenance.php';

// POSTリクエストの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $content = file_get_contents($configFile);

    if ($_POST['action'] === 'enable') {
        // メンテナンスモードON
        $content = preg_replace(
            "/define\('MAINTENANCE_MODE', false\);/",
            "define('MAINTENANCE_MODE', true);",
            $content
        );
        $message = 'メンテナンスモードを<strong>有効</strong>にしました';
        $messageClass = 'warning';
    } else {
        // メンテナンスモードOFF
        $content = preg_replace(
            "/define\('MAINTENANCE_MODE', true\);/",
            "define('MAINTENANCE_MODE', false);",
            $content
        );
        $message = 'メンテナンスモードを<strong>解除</strong>しました';
        $messageClass = 'success';
    }

    file_put_contents($configFile, $content);
}

// 現在の設定を読み込み
require_once $configFile;
$isMaintenanceMode = MAINTENANCE_MODE;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンテナンスモード設定 - BNI管理</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans JP", sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .status {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }

        .status.active {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }

        .status.inactive {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }

        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .status-text {
            font-size: 24px;
            font-weight: 600;
        }

        .buttons {
            display: flex;
            gap: 15px;
        }

        button {
            flex: 1;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-enable {
            background: #ffc107;
            color: #856404;
        }

        .btn-enable:hover {
            background: #e0a800;
        }

        .btn-disable {
            background: #28a745;
            color: white;
        }

        .btn-disable:hover {
            background: #218838;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            margin-top: 20px;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 14px;
            color: #495057;
        }

        .info-box h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .info-box ul {
            margin-left: 20px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 メンテナンスモード設定</h1>
        <div class="subtitle">システムのメンテナンスモードをON/OFFできます</div>

        <?php if (isset($message)): ?>
        <div class="message <?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="status <?php echo $isMaintenanceMode ? 'active' : 'inactive'; ?>">
            <div class="status-icon"><?php echo $isMaintenanceMode ? '🔧' : '✅'; ?></div>
            <div class="status-text">
                <?php echo $isMaintenanceMode ? 'メンテナンス中' : '通常運用中'; ?>
            </div>
        </div>

        <form method="POST">
            <div class="buttons">
                <button type="submit" name="action" value="enable" class="btn-enable"
                        <?php echo $isMaintenanceMode ? 'disabled' : ''; ?>>
                    🔧 メンテナンスモードON
                </button>
                <button type="submit" name="action" value="disable" class="btn-disable"
                        <?php echo !$isMaintenanceMode ? 'disabled' : ''; ?>>
                    ✅ メンテナンスモード解除
                </button>
            </div>
        </form>

        <button onclick="location.href='sitemap.php'" class="btn-back">
            ← 管理画面に戻る
        </button>

        <div class="info-box">
            <h3>📝 メンテナンスモードについて</h3>
            <ul>
                <li><strong>yamada@yojitu.com</strong> のみアクセス可能</li>
                <li>他のメンバーはメンテナンス画面が表示されます</li>
                <li>テスト完了後は必ず解除してください</li>
            </ul>
        </div>
    </div>
</body>
</html>
