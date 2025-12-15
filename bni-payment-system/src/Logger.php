<?php
namespace BNI;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * ログクラス
 * Monologのラッパー
 */
class Logger {
    private static $loggers = [];

    /**
     * ロガー取得
     *
     * @param string $channel ログチャンネル（app, webhook等）
     * @return MonologLogger
     */
    private static function getLogger(string $channel = 'app'): MonologLogger {
        if (!isset(self::$loggers[$channel])) {
            $logger = new MonologLogger($channel);

            // ログレベル
            $levelMap = [
                'debug' => MonologLogger::DEBUG,
                'info' => MonologLogger::INFO,
                'warning' => MonologLogger::WARNING,
                'error' => MonologLogger::ERROR,
            ];
            $level = $levelMap[env('LOG_LEVEL', 'info')] ?? MonologLogger::INFO;

            // ログファイルパス
            $logPath = __DIR__ . '/../logs/' . $channel . '.log';

            // ハンドラー設定
            $handler = new StreamHandler($logPath, $level);

            // フォーマッター設定
            $formatter = new LineFormatter(
                "[%datetime%] %channel%.%level_name%: %message% %context%\n",
                "Y-m-d H:i:s"
            );
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            self::$loggers[$channel] = $logger;
        }

        return self::$loggers[$channel];
    }

    /**
     * DEBUGログ
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return void
     */
    public static function debug(string $message, array $context = [], string $channel = 'app'): void {
        self::getLogger($channel)->debug($message, $context);
    }

    /**
     * INFOログ
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return void
     */
    public static function info(string $message, array $context = [], string $channel = 'app'): void {
        self::getLogger($channel)->info($message, $context);
    }

    /**
     * WARNINGログ
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return void
     */
    public static function warning(string $message, array $context = [], string $channel = 'app'): void {
        self::getLogger($channel)->warning($message, $context);
    }

    /**
     * ERRORログ
     *
     * @param string $message
     * @param array $context
     * @param string $channel
     * @return void
     */
    public static function error(string $message, array $context = [], string $channel = 'app'): void {
        self::getLogger($channel)->error($message, $context);
    }
}
