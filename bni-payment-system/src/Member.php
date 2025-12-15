<?php
namespace BNI;

use PDO;

/**
 * Memberモデル
 * メンバー管理（CRUD操作）
 */
class Member {
    /**
     * 全メンバー取得
     *
     * @param bool $activeOnly アクティブのみ取得
     * @return array
     */
    public static function getAll(bool $activeOnly = true): array {
        $db = Database::getInstance()->getConnection();
        $sql = 'SELECT * FROM members';
        if ($activeOnly) {
            $sql .= ' WHERE active = 1';
        }
        $sql .= ' ORDER BY name ASC';

        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * ID指定でメンバー取得
     *
     * @param int $id
     * @return array|null
     */
    public static function getById(int $id): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM members WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * メールアドレスでメンバー取得
     *
     * @param string $email
     * @return array|null
     */
    public static function getByEmail(string $email): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM members WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * メンバー作成
     *
     * @param array $data ['name' => string, 'email' => string]
     * @return int 作成されたメンバーのID
     * @throws \Exception
     */
    public static function create(array $data): int {
        // バリデーション
        if (empty($data['name'])) {
            throw new \Exception('名前は必須です');
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('有効なメールアドレスを入力してください');
        }

        // メールアドレス重複チェック
        if (self::getByEmail($data['email'])) {
            throw new \Exception('このメールアドレスは既に登録されています');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO members (name, email, active) VALUES (?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['active'] ?? 1
        ]);

        $id = (int)$db->lastInsertId();
        Logger::info('Member created', ['id' => $id, 'name' => $data['name']]);

        return $id;
    }

    /**
     * メンバー更新
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function update(int $id, array $data): bool {
        // 存在確認
        if (!self::getById($id)) {
            throw new \Exception('メンバーが見つかりません');
        }

        // バリデーション
        if (empty($data['name'])) {
            throw new \Exception('名前は必須です');
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('有効なメールアドレスを入力してください');
        }

        // メールアドレス重複チェック（自分以外）
        $existing = self::getByEmail($data['email']);
        if ($existing && $existing['id'] !== $id) {
            throw new \Exception('このメールアドレスは既に登録されています');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'UPDATE members SET name = ?, email = ?, active = ? WHERE id = ?'
        );
        $result = $stmt->execute([
            $data['name'],
            $data['email'],
            $data['active'] ?? 1,
            $id
        ]);

        if ($result) {
            Logger::info('Member updated', ['id' => $id, 'name' => $data['name']]);
        }

        return $result;
    }

    /**
     * メンバー削除
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool {
        $member = self::getById($id);
        if (!$member) {
            return false;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('DELETE FROM members WHERE id = ?');
        $result = $stmt->execute([$id]);

        if ($result) {
            Logger::info('Member deleted', ['id' => $id, 'name' => $member['name']]);
        }

        return $result;
    }

    /**
     * メンバー数取得
     *
     * @param bool $activeOnly
     * @return int
     */
    public static function count(bool $activeOnly = true): int {
        $db = Database::getInstance()->getConnection();
        $sql = 'SELECT COUNT(*) FROM members';
        if ($activeOnly) {
            $sql .= ' WHERE active = 1';
        }

        $stmt = $db->query($sql);
        return (int)$stmt->fetchColumn();
    }
}
