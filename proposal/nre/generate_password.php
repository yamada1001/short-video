<?php
/**
 * .htpasswd用のパスワード生成ツール
 *
 * 使い方：
 * 1. ブラウザでこのファイルにアクセス
 * 2. ユーザー名とパスワードを入力
 * 3. 生成されたハッシュを.htpasswdファイルに保存
 */

$generated_hash = '';
$username = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'ユーザー名とパスワードを入力してください。';
    } else {
        // Apacheのhtpasswd形式でパスワードをハッシュ化
        // crypt関数を使用してAPR1（MD5）形式でハッシュ化
        $salt = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        // bcryptを使用（より安全）
        if (defined('PASSWORD_BCRYPT')) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $generated_hash = $username . ':' . $hashed_password;
        } else {
            // 代替：MD5ベースのハッシュ
            $hashed_password = crypt($password, '$apr1$' . $salt . '$');
            $generated_hash = $username . ':' . $hashed_password;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード生成ツール</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2em;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 1.1em;
        }

        input[type="text"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: 'Courier New', monospace;
            background-color: #f8f9fa;
        }

        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .result {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .result h2 {
            color: #155724;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .result p {
            color: #155724;
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #721c24;
        }

        .instructions {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
        }

        .instructions h3 {
            color: #856404;
            margin-bottom: 15px;
        }

        .instructions ol {
            color: #856404;
            padding-left: 25px;
            line-height: 2;
        }

        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 0.9em;
        }

        .copy-btn:hover {
            background: #218838;
        }

        .hint {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>パスワード生成ツール</h1>
        <p class="subtitle">.htpasswd用のパスワードハッシュを生成します</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <p class="hint">ベーシック認証で使用するユーザー名を入力</p>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
                <p class="hint">ベーシック認証で使用するパスワードを入力</p>
            </div>

            <button type="submit">パスワードハッシュを生成</button>
        </form>

        <?php if ($generated_hash): ?>
            <div class="result">
                <h2>生成完了</h2>
                <p><strong>以下の内容を .htpasswd ファイルに保存してください：</strong></p>
                <div class="form-group">
                    <textarea id="hashOutput" readonly><?php echo htmlspecialchars($generated_hash); ?></textarea>
                    <button class="copy-btn" onclick="copyToClipboard()">クリップボードにコピー</button>
                </div>
            </div>
        <?php endif; ?>

        <div class="instructions">
            <h3>使い方</h3>
            <ol>
                <li>上記フォームにユーザー名とパスワードを入力</li>
                <li>「パスワードハッシュを生成」ボタンをクリック</li>
                <li>生成されたハッシュをコピー</li>
                <li>.htpasswd ファイルに貼り付けて保存</li>
                <li>ベーシック認証が有効になります</li>
            </ol>
        </div>

        <div class="instructions" style="margin-top: 20px; background: #d1ecf1; border-left-color: #17a2b8;">
            <h3 style="color: #0c5460;">セキュリティのヒント</h3>
            <ol style="color: #0c5460;">
                <li>パスワードは8文字以上を推奨</li>
                <li>英数字と記号を組み合わせる</li>
                <li>定期的にパスワードを変更する</li>
                <li>このファイルは設定後に削除することを推奨</li>
            </ol>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const textarea = document.getElementById('hashOutput');
            textarea.select();
            document.execCommand('copy');

            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = 'コピーしました！';
            btn.style.background = '#218838';

            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '#28a745';
            }, 2000);
        }
    </script>
</body>
</html>
