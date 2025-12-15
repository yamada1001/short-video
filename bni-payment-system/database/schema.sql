-- BNI Payment System - Database Schema
-- 作成日: 2025-12-15

-- Members テーブル
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'メンバー名',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'メールアドレス',
    active TINYINT(1) DEFAULT 1 COMMENT 'アクティブフラグ（1:有効, 0:無効）',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
    INDEX idx_active (active),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='BNIメンバー';

-- Payments テーブル
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL COMMENT 'メンバーID',
    amount INT NOT NULL COMMENT '金額（円）',
    week_of DATE NOT NULL COMMENT 'その週の火曜日の日付',
    square_payment_id VARCHAR(255) UNIQUE COMMENT 'Square決済ID',
    paid_at DATETIME NOT NULL COMMENT '支払い日時',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_week_of (week_of),
    INDEX idx_member_week (member_id, week_of),
    UNIQUE KEY unique_member_week (member_id, week_of) COMMENT '同一メンバーの週内重複支払い防止'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支払い記録';
