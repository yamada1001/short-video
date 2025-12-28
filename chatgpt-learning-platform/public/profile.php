<?php
/**
 * プロフィールページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();
$success = '';
$errors = [];

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // CSRF対策
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = '不正なリクエストです。';
    }

    // バリデーション
    if (empty($name)) {
        $errors[] = '名前を入力してください。';
    }

    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '有効なメールアドレスを入力してください。';
    }

    // メールアドレスが変更された場合、重複チェック
    if ($email !== $user['email']) {
        $checkEmailSql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $existingUser = db()->fetchOne($checkEmailSql, [$email, $user['id']]);
        if ($existingUser) {
            $errors[] = 'このメールアドレスは既に使用されています。';
        }
    }

    // パスワード変更の処理
    if (!empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword)) {
            $errors[] = '現在のパスワードを入力してください。';
        } elseif (!password_verify($currentPassword, $user['password_hash'])) {
            $errors[] = '現在のパスワードが正しくありません。';
        }

        if (empty($newPassword)) {
            $errors[] = '新しいパスワードを入力してください。';
        } elseif (strlen($newPassword) < 8) {
            $errors[] = '新しいパスワードは8文字以上にしてください。';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = '新しいパスワードと確認用パスワードが一致しません。';
        }
    }

    // エラーがなければ更新
    if (empty($errors)) {
        try {
            if (!empty($newPassword)) {
                // パスワード変更あり
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?";
                db()->execute($updateSql, [$name, $email, $passwordHash, $user['id']]);
            } else {
                // パスワード変更なし
                $updateSql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
                db()->execute($updateSql, [$name, $email, $user['id']]);
            }

            $success = 'プロフィールを更新しました。';
            // セッションのユーザー情報を更新
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            // ユーザー情報を再取得
            $user = getCurrentUser();
        } catch (Exception $e) {
            $errors[] = '更新中にエラーが発生しました。';
            error_log($e->getMessage());
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
    <title>プロフィール | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="profile-page">
        <div class="container">
            <h1 class="page-title">プロフィール設定</h1>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= h($success) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="profile-grid">
                <!-- 基本情報 -->
                <div class="profile-card">
                    <h2 class="profile-card__title">基本情報</h2>
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text" id="name" name="name" value="<?= h($user['name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="email" id="email" name="email" value="<?= h($user['email']) ?>" required>
                        </div>

                        <h3 class="form-section-title">パスワード変更（任意）</h3>

                        <div class="form-group">
                            <label for="current_password">現在のパスワード</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">新しいパスワード</label>
                            <input type="password" id="new_password" name="new_password" minlength="8">
                            <small class="form-hint">8文字以上</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">新しいパスワード（確認）</label>
                            <input type="password" id="confirm_password" name="confirm_password" minlength="8">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">更新する</button>
                    </form>
                </div>

                <!-- アカウント情報 -->
                <div class="profile-sidebar">
                    <div class="info-card">
                        <h3 class="info-card__title">アカウント情報</h3>
                        <dl class="info-list">
                            <dt>登録日</dt>
                            <dd><?= date('Y年m月d日', strtotime($user['created_at'])) ?></dd>

                            <dt>プラン</dt>
                            <dd>
                                <span class="badge badge-free">無料（全コースアクセス可能）</span>
                            </dd>

                            <dt>認証方法</dt>
                            <dd>
                                <?php if ($user['oauth_provider'] === 'google'): ?>
                                    Google
                                <?php else: ?>
                                    メールアドレス
                                <?php endif; ?>
                            </dd>
                        </dl>
                    </div>

                    <div class="info-card">
                        <h3 class="info-card__title">メール設定</h3>
                        <dl class="info-list">
                            <dt>配信状態</dt>
                            <dd>
                                <?php if (!empty($user['email_unsubscribed'])): ?>
                                    <span style="color: #dc2626; font-weight: 600;">
                                        <i class="fas fa-bell-slash"></i> 停止中
                                    </span>
                                <?php else: ?>
                                    <span style="color: #10b981; font-weight: 600;">
                                        <i class="fas fa-bell"></i> 配信中
                                    </span>
                                <?php endif; ?>
                            </dd>
                        </dl>
                        <?php
                        // 配信停止リンクを生成
                        $unsubscribeToken = hash_hmac('sha256', $user['id'], UNSUBSCRIBE_SECRET);
                        $unsubscribeUrl = APP_URL . '/unsubscribe.php?user=' . $user['id'] . '&token=' . $unsubscribeToken;
                        ?>
                        <a href="<?= h($unsubscribeUrl) ?>" class="btn btn-outline btn-block" style="margin-top: 12px;">
                            <i class="fas fa-cog"></i> メール設定を変更
                        </a>
                    </div>

                    <div class="info-card info-card--danger">
                        <h3 class="info-card__title">アカウント削除</h3>
                        <p class="info-card__text">
                            アカウントを削除すると、全ての学習データが失われます。この操作は取り消せません。
                        </p>
                        <button type="button" class="btn btn-outline btn-block" onclick="showDeleteModal()">
                            アカウントを削除
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- アカウント削除モーダル -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header modal-header--danger">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>アカウント削除の確認</h2>
            </div>
            <div class="modal-body">
                <div class="warning-box">
                    <p><strong>この操作は取り消すことができません。</strong></p>
                    <p>アカウントを削除すると、以下のデータが完全に削除されます：</p>
                    <ul class="delete-list">
                        <li><i class="fas fa-times-circle"></i> すべての学習進捗データ</li>
                        <li><i class="fas fa-times-circle"></i> アンケート回答</li>
                        <li><i class="fas fa-times-circle"></i> ストリークとバッジ</li>
                        <li><i class="fas fa-times-circle"></i> フィードバック</li>
                        <li><i class="fas fa-times-circle"></i> アカウント情報</li>
                    </ul>
                </div>

                <div class="confirmation-box">
                    <p>本当に削除する場合は、下のボックスに <strong>DELETE</strong> と入力してください：</p>
                    <input type="text" id="deleteConfirmation" class="confirmation-input" placeholder="DELETE と入力">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">キャンセル</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()" id="deleteButton" disabled>
                    アカウントを完全に削除
                </button>
            </div>
        </div>
    </div>

    <script>
        // モーダル表示
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteConfirmation').value = '';
            document.getElementById('deleteButton').disabled = true;
        }

        // モーダル非表示
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // 確認テキストの入力チェック
        document.addEventListener('DOMContentLoaded', function() {
            const confirmationInput = document.getElementById('deleteConfirmation');
            const deleteButton = document.getElementById('deleteButton');

            if (confirmationInput && deleteButton) {
                confirmationInput.addEventListener('input', function() {
                    if (this.value === 'DELETE') {
                        deleteButton.disabled = false;
                    } else {
                        deleteButton.disabled = true;
                    }
                });
            }

            // モーダル外クリックで閉じる
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('deleteModal');
                if (event.target === modal) {
                    closeDeleteModal();
                }
            });
        });

        // アカウント削除処理
        async function deleteAccount() {
            const confirmation = document.getElementById('deleteConfirmation').value;

            if (confirmation !== 'DELETE') {
                alert('確認テキストが正しくありません。');
                return;
            }

            // 最終確認
            if (!confirm('本当にアカウントを削除しますか？この操作は取り消せません。')) {
                return;
            }

            try {
                const response = await fetch('<?= APP_URL ?>/api/delete-account.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        csrf_token: '<?= generateCsrfToken() ?>',
                        confirmation: confirmation
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    // ログインページにリダイレクト
                    window.location.href = '<?= APP_URL ?>/login.php';
                } else {
                    alert('エラー: ' + result.message);
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error.message);
            }
        }
    </script>
</body>
</html>
