-- BNI Payment System - Seed Data
-- テスト用初期データ

-- テストメンバー追加
INSERT INTO members (name, email, active) VALUES
('山田太郎', 'yamada@example.com', 1),
('鈴木花子', 'suzuki@example.com', 1),
('田中一郎', 'tanaka@example.com', 1),
('佐藤美咲', 'sato@example.com', 1),
('高橋健太', 'takahashi@example.com', 1);

-- 今週の支払いデータ（テスト用）
-- 実際の運用では、このデータは不要（Webhookで自動登録される）
INSERT INTO payments (member_id, amount, week_of, square_payment_id, paid_at) VALUES
(1, 1100, DATE(DATE_SUB(NOW(), INTERVAL (WEEKDAY(NOW()) - 1) DAY)), 'test_payment_001', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(3, 1100, DATE(DATE_SUB(NOW(), INTERVAL (WEEKDAY(NOW()) - 1) DAY)), 'test_payment_002', DATE_SUB(NOW(), INTERVAL 1 HOUR));
