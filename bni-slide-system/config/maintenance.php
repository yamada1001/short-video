<?php
/**
 * BNI Slide System - Maintenance Mode Configuration
 * メンテナンスモード設定
 */

// メンテナンスモード (true = メンテナンス中, false = 通常運用)
define('MAINTENANCE_MODE', false);

// メンテナンス中でもアクセス可能なメールアドレス（テスト担当者のみ）
define('MAINTENANCE_ALLOWED_EMAILS', [
    'yamada@yojitu.com',  // テスト中はこのアカウントのみアクセス可能
]);

// メンテナンスメッセージ
define('MAINTENANCE_MESSAGE', '現在、システムメンテナンス中です。しばらくお待ちください。');

// メンテナンス終了予定時刻（オプション）
define('MAINTENANCE_END_TIME', ''); // 例: '2025-12-09 18:00'
