<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サーバー環境確認 | 旅行の栞</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .info-box {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4A90E2;
            padding-bottom: 10px;
        }
        h2 {
            color: #4A90E2;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        td:first-child {
            font-weight: bold;
            width: 200px;
            color: #666;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .warning {
            color: #ffc107;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🔧 サーバー環境確認</h1>

    <div class="info-box">
        <h2>PHP情報</h2>
        <table>
            <tr>
                <td>PHPバージョン</td>
                <td class="success"><?php echo phpversion(); ?></td>
            </tr>
            <tr>
                <td>サーバーソフトウェア</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
            </tr>
            <tr>
                <td>ドキュメントルート</td>
                <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td>
            </tr>
            <tr>
                <td>現在のスクリプトパス</td>
                <td><?php echo __FILE__; ?></td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <h2>パス情報</h2>
        <table>
            <tr>
                <td>現在のディレクトリ</td>
                <td><?php echo getcwd(); ?></td>
            </tr>
            <tr>
                <td>BASE_URL (想定)</td>
                <td>/travel-guide</td>
            </tr>
            <tr>
                <td>京都トップページ</td>
                <td>/travel-guide/kyoto/index.php</td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <h2>ファイル存在チェック</h2>
        <table>
            <?php
            $files_to_check = [
                'includes/config.php',
                'includes/header.php',
                'includes/footer.php',
                'assets/css/common.css',
                'assets/css/guide.css',
                'assets/js/common.js',
                'assets/js/guide.js',
                'kyoto/index.php',
                'kyoto/days/day1.php',
                'kyoto/days/day2.php',
                'kyoto/days/day3.php',
            ];

            foreach ($files_to_check as $file) {
                $full_path = __DIR__ . '/' . $file;
                $exists = file_exists($full_path);
                $status_class = $exists ? 'success' : 'error';
                $status_text = $exists ? '✓ 存在します' : '✗ 存在しません';
                echo "<tr>";
                echo "<td>{$file}</td>";
                echo "<td class='{$status_class}'>{$status_text}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="info-box">
        <h2>推奨される次のステップ</h2>
        <ol style="line-height: 2; color: #666;">
            <li>上記のファイルがすべて存在することを確認</li>
            <li>京都トップページにアクセス: <a href="/travel-guide/kyoto/index.php" target="_blank">/travel-guide/kyoto/index.php</a></li>
            <li>問題なければ、Basic認証を設定:
                <ul>
                    <li><code>.htaccess</code> ファイルを作成</li>
                    <li><code>.htpasswd</code> ファイルを作成</li>
                </ul>
            </li>
        </ol>
    </div>

    <div class="info-box">
        <h2>Basic認証設定方法</h2>
        <p style="color: #666; line-height: 1.8;">
            Basic認証をかける際は、以下のコマンドで<code>.htpasswd</code>を生成してください:
        </p>
        <pre style="background: #f8f9fa; padding: 12px; border-radius: 4px; overflow-x: auto;">htpasswd -c .htpasswd ユーザー名</pre>
        <p style="color: #666; line-height: 1.8; margin-top: 12px;">
            その後、<code>.htaccess</code>に認証設定を記述します。
        </p>
    </div>

    <div class="info-box">
        <h2>PHP設定情報</h2>
        <table>
            <tr>
                <td>display_errors</td>
                <td><?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></td>
            </tr>
            <tr>
                <td>error_reporting</td>
                <td><?php echo error_reporting(); ?></td>
            </tr>
            <tr>
                <td>max_execution_time</td>
                <td><?php echo ini_get('max_execution_time'); ?>秒</td>
            </tr>
            <tr>
                <td>memory_limit</td>
                <td><?php echo ini_get('memory_limit'); ?></td>
            </tr>
        </table>
    </div>

    <p style="text-align: center; color: #999; margin-top: 40px;">
        このページは開発用です。本番環境では削除してください。
    </p>
</body>
</html>
