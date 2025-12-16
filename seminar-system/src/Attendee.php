<?php
namespace Seminar;

/**
 * 参加者管理クラス
 */
class Attendee {
    /**
     * 全参加者取得
     *
     * @param int|null $seminarId セミナーIDでフィルター
     * @return array
     */
    public static function getAll(?int $seminarId = null): array {
        $db = Database::getInstance();

        $sql = "SELECT a.*, s.title as seminar_title, s.start_datetime
                FROM attendees a
                LEFT JOIN seminars s ON a.seminar_id = s.id";

        if ($seminarId) {
            $sql .= " WHERE a.seminar_id = ?";
            $params = [$seminarId];
        } else {
            $params = [];
        }

        $sql .= " ORDER BY a.applied_at DESC";

        return $db->query($sql, $params);
    }

    /**
     * ID指定で参加者取得
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array {
        $db = Database::getInstance();

        $sql = "SELECT a.*, s.title as seminar_title, s.start_datetime, s.price
                FROM attendees a
                LEFT JOIN seminars s ON a.seminar_id = s.id
                WHERE a.id = ?";

        return $db->fetch($sql, [$id]);
    }

    /**
     * トークンで参加者取得（欠席用）
     *
     * @param string $token
     * @return array|null
     */
    public static function getByCancelToken(string $token): ?array {
        $db = Database::getInstance();

        $sql = "SELECT a.*, s.title as seminar_title, s.start_datetime
                FROM attendees a
                LEFT JOIN seminars s ON a.seminar_id = s.id
                WHERE a.cancel_token = ?";

        return $db->fetch($sql, [$token]);
    }

    /**
     * QRコードトークンで参加者取得
     *
     * @param string $token
     * @return array|null
     */
    public static function getByQrToken(string $token): ?array {
        $db = Database::getInstance();

        $sql = "SELECT a.*, s.title as seminar_title, s.start_datetime
                FROM attendees a
                LEFT JOIN seminars s ON a.seminar_id = s.id
                WHERE a.qr_code_token = ?";

        return $db->fetch($sql, [$token]);
    }

    /**
     * メールアドレスで参加者検索
     *
     * @param string $email
     * @return array
     */
    public static function getByEmail(string $email): array {
        $db = Database::getInstance();

        $sql = "SELECT a.*, s.title as seminar_title, s.start_datetime
                FROM attendees a
                LEFT JOIN seminars s ON a.seminar_id = s.id
                WHERE a.email = ?
                ORDER BY a.applied_at DESC";

        return $db->query($sql, [$email]);
    }

    /**
     * 参加者作成
     *
     * @param array $data
     * @return int 作成された参加者ID
     */
    public static function create(array $data): int {
        $db = Database::getInstance();

        // トークン生成
        $cancelToken = generateToken();
        $qrToken = generateToken();

        $sql = "INSERT INTO attendees (
            seminar_id, name, email, phone, status,
            cancel_token, qr_code_token, credit_amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['seminar_id'],
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['status'] ?? 'applied',
            $cancelToken,
            $qrToken,
            $data['credit_amount'] ?? 0
        ];

        $db->execute($sql, $params);
        $attendeeId = (int)$db->lastInsertId();

        Logger::info('Attendee created', [
            'attendee_id' => $attendeeId,
            'seminar_id' => $data['seminar_id'],
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        return $attendeeId;
    }

    /**
     * ステータス更新
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateStatus(int $id, string $status): bool {
        $db = Database::getInstance();

        $sql = "UPDATE attendees SET status = ?";
        $params = [$status, $id];

        // ステータスに応じて日時も更新
        if ($status === 'paid') {
            $sql .= ", paid_at = NOW()";
        } elseif ($status === 'attended') {
            $sql .= ", attended_at = NOW()";
        }

        $sql .= " WHERE id = ?";

        $result = $db->execute($sql, $params) > 0;

        Logger::info('Attendee status updated', [
            'attendee_id' => $id,
            'status' => $status
        ]);

        return $result;
    }

    /**
     * Square Payment ID更新
     *
     * @param int $id
     * @param string $paymentId
     * @return bool
     */
    public static function updatePaymentId(int $id, string $paymentId): bool {
        $db = Database::getInstance();

        $sql = "UPDATE attendees SET square_payment_id = ? WHERE id = ?";
        return $db->execute($sql, [$paymentId, $id]) > 0;
    }

    /**
     * 欠席処理
     *
     * @param int $id
     * @param string $reason
     * @param int $creditAmount
     * @return bool
     */
    public static function markAsAbsent(int $id, string $reason, int $creditAmount): bool {
        $db = Database::getInstance();

        $sql = "UPDATE attendees SET
                status = 'absent',
                cancel_reason = ?,
                credit_amount = ?
                WHERE id = ?";

        $result = $db->execute($sql, [$reason, $creditAmount, $id]) > 0;

        Logger::info('Attendee marked as absent', [
            'attendee_id' => $id,
            'credit_amount' => $creditAmount
        ]);

        return $result;
    }

    /**
     * クレジット取得（メールアドレスで検索）
     *
     * @param string $email
     * @return int 繰越クレジット合計
     */
    public static function getTotalCredit(string $email): int {
        $db = Database::getInstance();

        $sql = "SELECT SUM(credit_amount) as total_credit
                FROM attendees
                WHERE email = ? AND status = 'absent' AND credit_amount > 0";

        $result = $db->fetch($sql, [$email]);

        return (int)($result['total_credit'] ?? 0);
    }

    /**
     * クレジット使用（最も古い欠席レコードから消費）
     *
     * @param string $email
     * @param int $amount 使用金額
     * @return bool
     */
    public static function useCredit(string $email, int $amount): bool {
        $db = Database::getInstance();

        // クレジットがある欠席レコードを古い順に取得
        $sql = "SELECT id, credit_amount FROM attendees
                WHERE email = ? AND status = 'absent' AND credit_amount > 0
                ORDER BY applied_at ASC";

        $records = $db->query($sql, [$email]);
        $remaining = $amount;

        foreach ($records as $record) {
            if ($remaining <= 0) break;

            if ($record['credit_amount'] >= $remaining) {
                // このレコードだけで足りる
                $newCredit = $record['credit_amount'] - $remaining;
                $db->execute(
                    "UPDATE attendees SET credit_amount = ? WHERE id = ?",
                    [$newCredit, $record['id']]
                );
                $remaining = 0;
            } else {
                // このレコードを全て使い切る
                $remaining -= $record['credit_amount'];
                $db->execute(
                    "UPDATE attendees SET credit_amount = 0 WHERE id = ?",
                    [$record['id']]
                );
            }
        }

        Logger::info('Credit used', [
            'email' => $email,
            'amount' => $amount
        ]);

        return $remaining === 0;
    }

    /**
     * ステータス別参加者数取得
     *
     * @param int $seminarId
     * @return array
     */
    public static function getStatusCounts(int $seminarId): array {
        $db = Database::getInstance();

        $sql = "SELECT status, COUNT(*) as count
                FROM attendees
                WHERE seminar_id = ?
                GROUP BY status";

        $results = $db->query($sql, [$seminarId]);

        $counts = [
            'applied' => 0,
            'absent' => 0,
            'paid' => 0,
            'attended' => 0
        ];

        foreach ($results as $row) {
            $counts[$row['status']] = (int)$row['count'];
        }

        return $counts;
    }

    /**
     * 参加者削除
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool {
        $db = Database::getInstance();

        $sql = "DELETE FROM attendees WHERE id = ?";
        $result = $db->execute($sql, [$id]) > 0;

        Logger::info('Attendee deleted', ['attendee_id' => $id]);

        return $result;
    }

    /**
     * セミナーに既に申込済みかチェック
     *
     * @param int $seminarId
     * @param string $email
     * @return bool
     */
    public static function hasRegistered(int $seminarId, string $email): bool {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) as count FROM attendees
                WHERE seminar_id = ? AND email = ?";

        $result = $db->fetch($sql, [$seminarId, $email]);

        return (int)($result['count'] ?? 0) > 0;
    }
}
