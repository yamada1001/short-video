<?php
/**
 * メンテナンスモード切り替えツール
 *
 * 使い方:
 *   メンテナンス開始: php maintenance-mode.php on
 *   メンテナンス終了: php maintenance-mode.php off
 *   状態確認:         php maintenance-mode.php status
 */

$htaccessPath = __DIR__ . '/.htaccess';

// 引数チェック
if (!isset($argv[1])) {
    echo "使い方: php maintenance-mode.php [on|off|status]\n";
    exit(1);
}

$command = strtolower($argv[1]);

// .htaccessファイルの読み込み
if (!file_exists($htaccessPath)) {
    echo "エラー: .htaccessファイルが見つかりません\n";
    exit(1);
}

$htaccess = file_get_contents($htaccessPath);

// 現在のメンテナンスモード状態を確認
$isMaintenanceMode = strpos($htaccess, '# MAINTENANCE_MODE_ACTIVE') !== false;

switch ($command) {
    case 'status':
        if ($isMaintenanceMode) {
            echo "🔧 メンテナンスモード: ON\n";
        } else {
            echo "✅ メンテナンスモード: OFF\n";
        }
        break;

    case 'on':
        if ($isMaintenanceMode) {
            echo "既にメンテナンスモードは有効です\n";
            exit(0);
        }

        // メンテナンスモードを有効化
        $maintenanceRules = <<<'HTACCESS'

# ====================================
# メンテナンスモード（自動追加）
# MAINTENANCE_MODE_ACTIVE
# ====================================
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/503\.php$
RewriteCond %{REQUEST_URI} !^/assets/
RewriteCond %{REMOTE_ADDR} !^127\.0\.0\.1$
# 特定のIPアドレスからのアクセスを除外する場合は以下のコメントを解除
# RewriteCond %{REMOTE_ADDR} !^xxx\.xxx\.xxx\.xxx$
RewriteRule ^.*$ /503.php [R=503,L]

HTACCESS;

        // .htaccessの最後に追加
        $newHtaccess = $htaccess . $maintenanceRules;
        file_put_contents($htaccessPath, $newHtaccess);

        echo "🔧 メンテナンスモードを有効にしました\n";
        echo "終了するには: php maintenance-mode.php off\n";
        break;

    case 'off':
        if (!$isMaintenanceMode) {
            echo "メンテナンスモードは無効です\n";
            exit(0);
        }

        // メンテナンスモードのルールを削除
        $newHtaccess = preg_replace(
            '/\n# ====================================\n# メンテナンスモード（自動追加）.*?(?=\n(?:# ====|$))/s',
            '',
            $htaccess
        );

        file_put_contents($htaccessPath, $newHtaccess);

        echo "✅ メンテナンスモードを解除しました\n";
        break;

    default:
        echo "エラー: 不正なコマンドです\n";
        echo "使い方: php maintenance-mode.php [on|off|status]\n";
        exit(1);
}
