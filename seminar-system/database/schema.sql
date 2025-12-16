-- セミナー管理システム - データベーススキーマ
-- 作成日: 2025-12-16

-- データベース作成（本番環境では管理画面で作成済み）
-- CREATE DATABASE IF NOT EXISTS xs545151_seminar DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE xs545151_seminar;

-- ==================== seminars テーブル ====================
CREATE TABLE IF NOT EXISTS seminars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'セミナー名',
    description TEXT COMMENT '説明',
    venue VARCHAR(255) COMMENT '開催場所',
    start_datetime DATETIME NOT NULL COMMENT '開始日時',
    end_datetime DATETIME NOT NULL COMMENT '終了日時',
    registration_deadline DATETIME COMMENT '申込締切日時',
    price INT NOT NULL DEFAULT 1000 COMMENT '価格（円）',
    pdf_path VARCHAR(255) COMMENT 'スライドPDFパス',
    thanks_mail_subject VARCHAR(255) COMMENT 'サンクスメール件名',
    thanks_mail_body TEXT COMMENT 'サンクスメール本文',
    mail_sender_name VARCHAR(100) COMMENT 'メール送信者名',
    is_active TINYINT(1) DEFAULT 1 COMMENT '有効/無効',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_start_datetime (start_datetime),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='セミナー情報';

-- ==================== attendees テーブル ====================
CREATE TABLE IF NOT EXISTS attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seminar_id INT NOT NULL,
    name VARCHAR(100) NOT NULL COMMENT '氏名',
    email VARCHAR(255) NOT NULL COMMENT 'メールアドレス',
    phone VARCHAR(20) COMMENT '電話番号',
    status ENUM('applied', 'absent', 'paid', 'attended') DEFAULT 'applied' COMMENT 'ステータス',
    cancel_token VARCHAR(64) UNIQUE COMMENT '欠席用トークン',
    cancel_reason TEXT COMMENT '欠席理由',
    credit_amount INT DEFAULT 0 COMMENT '繰越クレジット（円）',
    qr_code_token VARCHAR(64) UNIQUE COMMENT 'QRコード用トークン',
    square_payment_id VARCHAR(255) COMMENT 'Square Payment ID',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '申込日時',
    paid_at TIMESTAMP NULL COMMENT '支払日時',
    attended_at TIMESTAMP NULL COMMENT '出席確認日時',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seminar_id) REFERENCES seminars(id) ON DELETE CASCADE,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_cancel_token (cancel_token),
    INDEX idx_qr_code_token (qr_code_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='参加者情報';

-- ==================== survey_questions テーブル ====================
CREATE TABLE IF NOT EXISTS survey_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seminar_id INT NULL COMMENT 'NULL=全セミナー共通',
    survey_type ENUM('registration', 'post_seminar') NOT NULL COMMENT '申込時/セミナー後',
    question_text TEXT NOT NULL COMMENT '質問文',
    question_type ENUM('text', 'radio', 'checkbox') NOT NULL COMMENT '回答形式',
    options JSON COMMENT '選択肢（radio/checkbox用）',
    is_required TINYINT(1) DEFAULT 0 COMMENT '必須回答',
    order_index INT DEFAULT 0 COMMENT '表示順',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seminar_id) REFERENCES seminars(id) ON DELETE CASCADE,
    INDEX idx_survey_type (survey_type),
    INDEX idx_order_index (order_index)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='アンケート質問';

-- ==================== survey_answers テーブル ====================
CREATE TABLE IF NOT EXISTS survey_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendee_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT COMMENT '回答内容',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attendee_id) REFERENCES attendees(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES survey_questions(id) ON DELETE CASCADE,
    INDEX idx_attendee_id (attendee_id),
    INDEX idx_question_id (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='アンケート回答';

-- ==================== email_logs テーブル ====================
CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendee_id INT NOT NULL,
    email_type ENUM('registration', 'reminder', 'thanks', 'individual') NOT NULL COMMENT 'メール種別',
    subject VARCHAR(255) COMMENT '件名',
    body TEXT COMMENT '本文',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attendee_id) REFERENCES attendees(id) ON DELETE CASCADE,
    INDEX idx_email_type (email_type),
    INDEX idx_sent_at (sent_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='メール送信履歴';
