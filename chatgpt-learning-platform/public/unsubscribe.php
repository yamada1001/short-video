<?php
/**
 * メール配信停止ページ
 * 特定電子メール法、CAN-SPAM Act準拠
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$success = '';
$error = '';
$userId = $_GET['user'] ?? null;
$token = $_GET['token'] ?? null;

// ユーザーIDとトークンの検証
if ($userId && $token) {
    // トークン検証（user_id + UNSUBSCRIBE_SECRET のハッシュ）
    $expectedToken = hash_hmac('sha256', $userId, UNSUBSCRIBE_SECRET);

    if (hash_equals($expectedToken, $token)) {
        // ユーザー情報を取得
        $userSql = "SELECT id, email, name, email_unsubscribed FROM users WHERE id = ?";
        $user = db()->fetchOne($userSql, [$userId]);

        if ($user) {
            // 既に配信停止済みかチェック
            if ($user['email_unsubscribed']) {
                $success = '既にメール配信を停止しています。';
            } else {
                // 配信停止処理
                try {
                    $updateSql = "UPDATE users SET email_unsubscribed = 1, email_unsubscribed_at = NOW() WHERE id = ?";
                    db()->execute($updateSql, [$userId]);
                    $success = 'メール配信を停止しました。';

                    // ユーザー情報を再取得
                    $user = db()->fetchOne($userSql, [$userId]);
                } catch (Exception $e) {
                    $error = 'エラーが発生しました。時間をおいて再度お試しください。';
                    error_log('Unsubscribe error: ' . $e->getMessage());
                }
            }
        } else {
            $error = 'ユーザーが見つかりませんでした。';
        }
    } else {
        $error = '不正なリンクです。';
    }
} else {
    $error = 'パラメータが不足しています。';
}

// 再登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resubscribe'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = '不正なリクエストです。';
    } else if ($userId && $user && $user['email_unsubscribed']) {
        try {
            $updateSql = "UPDATE users SET email_unsubscribed = 0, email_unsubscribed_at = NULL WHERE id = ?";
            db()->execute($updateSql, [$userId]);
            $success = 'メール配信を再開しました。';

            // ユーザー情報を再取得
            $userSql = "SELECT id, email, name, email_unsubscribed FROM users WHERE id = ?";
            $user = db()->fetchOne($userSql, [$userId]);
        } catch (Exception $e) {
            $error = 'エラーが発生しました。時間をおいて再度お試しください。';
            error_log('Resubscribe error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール配信停止 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
    <style>
        .unsubscribe-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .unsubscribe-icon {
            font-size: 64px;
            margin-bottom: 24px;
        }

        .unsubscribe-icon.success {
            color: #10b981;
        }

        .unsubscribe-icon.error {
            color: #ef4444;
        }

        .unsubscribe-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 16px;
        }

        .unsubscribe-message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .unsubscribe-info {
            background: #f7fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: left;
        }

        .unsubscribe-info p {
            margin: 8px 0;
            font-size: 14px;
            color: #4a5568;
        }

        .unsubscribe-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="unsubscribe-container">
        <?php if ($success): ?>
            <div class="unsubscribe-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="unsubscribe-title">配信設定を変更しました</h1>
            <p class="unsubscribe-message"><?= h($success) ?></p>

            <?php if (isset($user) && $user): ?>
                <div class="unsubscribe-info">
                    <p><strong>メールアドレス:</strong> <?= h($user['email']) ?></p>
                    <p><strong>配信状態:</strong>
                        <?php if ($user['email_unsubscribed']): ?>
                            <span style="color: #dc2626;">停止中</span>
                        <?php else: ?>
                            <span style="color: #10b981;">配信中</span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if ($user['email_unsubscribed']): ?>
                    <p class="unsubscribe-message">
                        今後、当サービスからのメールは配信されません。<br>
                        再度配信を希望される場合は、下のボタンから再開できます。
                    </p>

                    <form method="POST" class="unsubscribe-actions">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="resubscribe" value="1">
                        <button type="submit" class="btn btn-primary">
                            メール配信を再開する
                        </button>
                    </form>
                <?php else: ?>
                    <p class="unsubscribe-message">
                        今後も当サービスのメールをお楽しみください。
                    </p>
                <?php endif; ?>
            <?php endif; ?>

        <?php elseif ($error): ?>
            <div class="unsubscribe-icon error">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h1 class="unsubscribe-title">エラーが発生しました</h1>
            <p class="unsubscribe-message"><?= h($error) ?></p>

        <?php else: ?>
            <div class="unsubscribe-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h1 class="unsubscribe-title">メール配信停止</h1>
            <p class="unsubscribe-message">
                URLのパラメータが不正です。<br>
                メール内のリンクから再度アクセスしてください。
            </p>
        <?php endif; ?>

        <div class="unsubscribe-actions">
            <a href="<?= APP_URL ?>/" class="btn btn-secondary">
                トップページに戻る
            </a>
        </div>
    </div>
</body>
</html>
