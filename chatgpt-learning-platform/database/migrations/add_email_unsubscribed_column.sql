/**
 * メール配信停止カラムを追加
 * 特定電子メール法、CAN-SPAM Act準拠
 */

-- usersテーブルにemail_unsubscribedカラムを追加
ALTER TABLE users
ADD COLUMN email_unsubscribed TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'メール配信停止フラグ: 0=配信可, 1=配信停止';

-- インデックスを追加（配信停止チェックのパフォーマンス向上）
ALTER TABLE users
ADD INDEX idx_email_unsubscribed (email_unsubscribed);

-- 配信停止日時を記録するカラムも追加
ALTER TABLE users
ADD COLUMN email_unsubscribed_at DATETIME NULL COMMENT 'メール配信停止日時';
