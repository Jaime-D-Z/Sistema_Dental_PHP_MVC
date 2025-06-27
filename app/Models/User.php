<?php
require_once __DIR__ . '/../../config/Database.php';

class User {
    public static function all($search = '') {
        $db = Database::connect();

        $sql = "SELECT * FROM users WHERE is_deleted = 0 AND is_active = 1";

        if ($search !== '') {
            $sql .= " AND (name LIKE :search OR email LIKE :search)";
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $db->prepare($sql);

        if ($search !== '') {
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
