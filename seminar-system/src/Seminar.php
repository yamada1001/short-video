<?php
namespace Seminar;

/**
 * セミナー管理クラス
 */
class Seminar {
    /**
     * 全セミナー取得
     *
     * @param bool $activeOnly 有効なセミナーのみ取得
     * @return array
     */
    public static function getAll(bool $activeOnly = false): array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM seminars";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY start_datetime ASC";

        return $db->query($sql);
    }

    /**
     * ID指定でセミナー取得
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM seminars WHERE id = ?";
        return $db->fetch($sql, [$id]);
    }

    /**
     * 今後開催されるセミナー取得
     *
     * @return array
     */
    public static function getUpcoming(): array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM seminars
                WHERE is_active = 1
                AND start_datetime > NOW()
                ORDER BY start_datetime ASC";

        return $db->query($sql);
    }

    /**
     * 申込受付中のセミナー取得
     *
     * @return array
     */
    public static function getOpenForRegistration(): array {
        $db = Database::getInstance();

        $sql = "SELECT * FROM seminars
                WHERE is_active = 1
                AND start_datetime > NOW()
                AND (registration_deadline IS NULL OR registration_deadline > NOW())
                ORDER BY start_datetime ASC";

        return $db->query($sql);
    }

    /**
     * セミナー作成
     *
     * @param array $data
     * @return int 作成されたセミナーID
     */
    public static function create(array $data): int {
        $db = Database::getInstance();

        $sql = "INSERT INTO seminars (
            title, description, venue, start_datetime, end_datetime,
            registration_deadline, price, pdf_path, thanks_mail_subject,
            thanks_mail_body, mail_sender_name, is_active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['title'],
            $data['description'] ?? null,
            $data['venue'] ?? null,
            $data['start_datetime'],
            $data['end_datetime'],
            $data['registration_deadline'] ?? null,
            $data['price'] ?? 1000,
            $data['pdf_path'] ?? null,
            $data['thanks_mail_subject'] ?? null,
            $data['thanks_mail_body'] ?? null,
            $data['mail_sender_name'] ?? null,
            $data['is_active'] ?? 1
        ];

        $db->execute($sql, $params);
        $seminarId = (int)$db->lastInsertId();

        Logger::info('Seminar created', [
            'seminar_id' => $seminarId,
            'title' => $data['title']
        ]);

        return $seminarId;
    }

    /**
     * セミナー更新
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update(int $id, array $data): bool {
        $db = Database::getInstance();

        $sql = "UPDATE seminars SET
            title = ?,
            description = ?,
            venue = ?,
            start_datetime = ?,
            end_datetime = ?,
            registration_deadline = ?,
            price = ?,
            pdf_path = ?,
            thanks_mail_subject = ?,
            thanks_mail_body = ?,
            mail_sender_name = ?,
            is_active = ?
        WHERE id = ?";

        $params = [
            $data['title'],
            $data['description'] ?? null,
            $data['venue'] ?? null,
            $data['start_datetime'],
            $data['end_datetime'],
            $data['registration_deadline'] ?? null,
            $data['price'] ?? 1000,
            $data['pdf_path'] ?? null,
            $data['thanks_mail_subject'] ?? null,
            $data['thanks_mail_body'] ?? null,
            $data['mail_sender_name'] ?? null,
            $data['is_active'] ?? 1,
            $id
        ];

        $result = $db->execute($sql, $params) > 0;

        Logger::info('Seminar updated', [
            'seminar_id' => $id,
            'title' => $data['title']
        ]);

        return $result;
    }

    /**
     * セミナー削除
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool {
        $db = Database::getInstance();

        $sql = "DELETE FROM seminars WHERE id = ?";
        $result = $db->execute($sql, [$id]) > 0;

        Logger::info('Seminar deleted', ['seminar_id' => $id]);

        return $result;
    }

    /**
     * 申込受付中かチェック
     *
     * @param int $id
     * @return bool
     */
    public static function isRegistrationOpen(int $id): bool {
        $seminar = self::getById($id);

        if (!$seminar || !$seminar['is_active']) {
            return false;
        }

        // 開始時刻が過去
        if (isPast($seminar['start_datetime'])) {
            return false;
        }

        // 締切が設定されている場合、締切をチェック
        if ($seminar['registration_deadline'] && isPast($seminar['registration_deadline'])) {
            return false;
        }

        return true;
    }

    /**
     * セミナーの参加者数取得
     *
     * @param int $id
     * @return int
     */
    public static function getAttendeeCount(int $id): int {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) as count FROM attendees WHERE seminar_id = ?";
        $result = $db->fetch($sql, [$id]);

        return (int)($result['count'] ?? 0);
    }

    /**
     * セミナーの支払済み参加者数取得
     *
     * @param int $id
     * @return int
     */
    public static function getPaidCount(int $id): int {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) as count FROM attendees
                WHERE seminar_id = ? AND status IN ('paid', 'attended')";
        $result = $db->fetch($sql, [$id]);

        return (int)($result['count'] ?? 0);
    }

    /**
     * セミナーの出席者数取得
     *
     * @param int $id
     * @return int
     */
    public static function getAttendedCount(int $id): int {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) as count FROM attendees
                WHERE seminar_id = ? AND status = 'attended'";
        $result = $db->fetch($sql, [$id]);

        return (int)($result['count'] ?? 0);
    }

    /**
     * PDFパス更新
     *
     * @param int $id
     * @param string $pdfPath
     * @return bool
     */
    public static function updatePdfPath(int $id, string $pdfPath): bool {
        $db = Database::getInstance();

        $sql = "UPDATE seminars SET pdf_path = ? WHERE id = ?";
        return $db->execute($sql, [$pdfPath, $id]) > 0;
    }
}
