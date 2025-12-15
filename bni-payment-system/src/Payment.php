<?php
namespace BNI;

use PDO;

/**
 * Paymentモデル
 * 支払い管理
 */
class Payment {
    /**
     * 支払い記録作成
     *
     * @param array $data
     * @return int 作成された支払いID
     * @throws \Exception
     */
    public static function create(array $data): int {
        // バリデーション
        if (empty($data['member_id'])) {
            throw new \Exception('メンバーIDは必須です');
        }
        if (empty($data['amount'])) {
            throw new \Exception('金額は必須です');
        }
        if (empty($data['week_of'])) {
            throw new \Exception('週の日付は必須です');
        }
        if (empty($data['square_payment_id'])) {
            throw new \Exception('Square決済IDは必須です');
        }
        if (empty($data['paid_at'])) {
            throw new \Exception('支払い日時は必須です');
        }

        // 重複チェック
        if (self::exists($data['member_id'], $data['week_of'])) {
            throw new \Exception('この週の支払いは既に記録されています');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO payments (member_id, amount, week_of, square_payment_id, paid_at)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['member_id'],
            $data['amount'],
            $data['week_of'],
            $data['square_payment_id'],
            $data['paid_at']
        ]);

        $id = (int)$db->lastInsertId();
        Logger::info('Payment created', [
            'id' => $id,
            'member_id' => $data['member_id'],
            'week_of' => $data['week_of']
        ]);

        return $id;
    }

    /**
     * 週ごとの支払い取得（member_idをキーにした連想配列）
     *
     * @param string $weekOf Y-m-d形式の火曜日
     * @return array
     */
    public static function getByWeek(string $weekOf): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT p.*, m.name, m.email
             FROM payments p
             JOIN members m ON p.member_id = m.id
             WHERE p.week_of = ?
             ORDER BY p.paid_at ASC'
        );
        $stmt->execute([$weekOf]);
        $results = $stmt->fetchAll();

        // member_id をキーにした配列に変換
        $payments = [];
        foreach ($results as $row) {
            $payments[$row['member_id']] = $row;
        }
        return $payments;
    }

    /**
     * 特定メンバー・週の支払い確認
     *
     * @param int $memberId
     * @param string $weekOf
     * @return bool
     */
    public static function exists(int $memberId, string $weekOf): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM payments WHERE member_id = ? AND week_of = ?'
        );
        $stmt->execute([$memberId, $weekOf]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * メンバーの支払い履歴取得
     *
     * @param int $memberId
     * @param int $limit
     * @return array
     */
    public static function getHistory(int $memberId, int $limit = 10): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT * FROM payments
             WHERE member_id = ?
             ORDER BY week_of DESC
             LIMIT ?'
        );
        $stmt->execute([$memberId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * 週ごとの統計取得
     *
     * @param string $weekOf
     * @return array ['total_amount' => int, 'paid_count' => int]
     */
    public static function getWeekStats(string $weekOf): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT
                COUNT(*) as paid_count,
                SUM(amount) as total_amount
             FROM payments
             WHERE week_of = ?'
        );
        $stmt->execute([$weekOf]);
        $result = $stmt->fetch();

        return [
            'paid_count' => (int)($result['paid_count'] ?? 0),
            'total_amount' => (int)($result['total_amount'] ?? 0),
        ];
    }

    /**
     * 週リスト取得（過去N週間）
     *
     * @param int $weeks
     * @return array
     */
    public static function getWeeksList(int $weeks = 4): array {
        $weeksList = [];
        for ($i = 0; $i < $weeks; $i++) {
            $date = date('Y-m-d', strtotime("this tuesday - {$i} weeks"));
            $weeksList[] = $date;
        }
        return $weeksList;
    }

    /**
     * CSVエクスポート用データ取得
     *
     * @param string $weekOf
     * @return array
     */
    public static function getForExport(string $weekOf): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT
                m.name,
                m.email,
                p.amount,
                p.paid_at,
                p.square_payment_id
             FROM members m
             LEFT JOIN payments p ON m.id = p.member_id AND p.week_of = ?
             WHERE m.active = 1
             ORDER BY m.name ASC'
        );
        $stmt->execute([$weekOf]);
        return $stmt->fetchAll();
    }
}
