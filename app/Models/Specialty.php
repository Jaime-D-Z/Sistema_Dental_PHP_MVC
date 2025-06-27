<?php
require_once __DIR__ . '/../../config/Database.php';

class Specialty {
    public static function all($search = '') {
        $db = Database::connect();
        $sql = "SELECT id, name, description FROM specialties WHERE is_deleted = 0 AND is_active = 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params[':search'] = "%$search%";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
