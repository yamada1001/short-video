<?php
namespace Seminar;

/**
 * ログ記録クラス
 */
class Logger {
    /**
     * ログレベル
     */
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';

    /**
     * ログファイルパス
     */
    private static function getLogPath(string $type = 'app'): string {
        $logDir = __DIR__ . '/../logs';
        return "{$logDir}/{$type}.log";
    }

    /**
     * ログ書き込み
     */
    private static function write(string $level, string $message, array $context = [], string $type = 'app'): void {
        $logLevel = env('LOG_LEVEL', 'info');
        $levels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];

        $currentLevel = $levels[strtolower($logLevel)] ?? 1;
        $messageLevel = $levels[strtolower($level)] ?? 1;

        // ログレベルが設定より低い場合はスキップ
        if ($messageLevel < $currentLevel) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $logMessage = "[{$timestamp}] [{$level}] {$message}";
        if ($contextStr) {
            $logMessage .= " | Context: {$contextStr}";
        }
        $logMessage .= PHP_EOL;

        $logPath = self::getLogPath($type);
        $logDir = dirname($logPath);

        // ログディレクトリが存在しない場合は作成
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * デバッグログ
     */
    public static function debug(string $message, array $context = []): void {
        self::write(self::DEBUG, $message, $context);
    }

    /**
     * 情報ログ
     */
    public static function info(string $message, array $context = []): void {
        self::write(self::INFO, $message, $context);
    }

    /**
     * 警告ログ
     */
    public static function warning(string $message, array $context = []): void {
        self::write(self::WARNING, $message, $context);
    }

    /**
     * エラーログ
     */
    public static function error(string $message, array $context = []): void {
        self::write(self::ERROR, $message, $context);
    }

    /**
     * メール送信ログ
     */
    public static function email(string $message, array $context = []): void {
        self::write(self::INFO, $message, $context, 'email');
    }
}
