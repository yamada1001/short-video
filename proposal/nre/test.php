<?php
/**
 * サーバー情報確認用テストファイル
 * このファイルでサーバー環境を確認してから.htaccessを設定します
 */

// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サーバー情報確認</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .info-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-section h2 {
            color: #4CAF50;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        .phpinfo-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .phpinfo-link:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <h1>サーバー環境情報</h1>

    <div class="info-section">
        <h2>基本情報</h2>
        <table>
            <tr>
                <th>項目</th>
                <th>値</th>
            </tr>
            <tr>
                <td>PHPバージョン</td>
                <td><?php echo PHP_VERSION; ?></td>
            </tr>
            <tr>
                <td>サーバーソフトウェア</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
                <td>ドキュメントルート</td>
                <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
                <td>現在のファイルパス</td>
                <td><?php echo __FILE__; ?></td>
            </tr>
            <tr>
                <td>サーバー名</td>
                <td><?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
                <td>サーバーアドレス</td>
                <td><?php echo $_SERVER['SERVER_ADDR'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
                <td>OS</td>
                <td><?php echo PHP_OS; ?></td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>.htaccess サポート確認</h2>
        <table>
            <tr>
                <th>項目</th>
                <th>ステータス</th>
            </tr>
            <tr>
                <td>Apache mod_rewrite</td>
                <td>
                    <?php
                    if (function_exists('apache_get_modules')) {
                        echo in_array('mod_rewrite', apache_get_modules()) ? '✓ 有効' : '✗ 無効';
                    } else {
                        echo '確認不可（CGI/FastCGIモード）';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>.htaccess 使用可能性</td>
                <td>
                    <?php
                    if (strpos($_SERVER['SERVER_SOFTWARE'] ?? '', 'Apache') !== false) {
                        echo '✓ Apacheサーバー - 使用可能';
                    } else {
                        echo '⚠ Apache以外 - 要確認';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>PHP拡張モジュール</h2>
        <table>
            <tr>
                <th>拡張モジュール</th>
            </tr>
            <?php
            $extensions = get_loaded_extensions();
            sort($extensions);
            foreach ($extensions as $ext) {
                echo "<tr><td>{$ext}</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="info-section">
        <h2>詳細情報</h2>
        <p>より詳細なPHP設定情報を確認したい場合は、以下のボタンをクリックしてください。</p>
        <a href="?phpinfo=1" class="phpinfo-link">phpinfo()を表示</a>
    </div>

    <?php if (isset($_GET['phpinfo'])): ?>
    <div class="info-section">
        <h2>phpinfo() 詳細情報</h2>
        <?php phpinfo(); ?>
    </div>
    <?php endif; ?>

    <div class="info-section">
        <h2>次のステップ</h2>
        <p>このサーバー情報を確認した上で、.htaccessファイルとベーシック認証を設定します。</p>
        <ul>
            <li>Apacheサーバーであることを確認</li>
            <li>.htaccessが使用可能であることを確認</li>
            <li>ドキュメントルートのパスを確認</li>
        </ul>
    </div>
</body>
</html>
