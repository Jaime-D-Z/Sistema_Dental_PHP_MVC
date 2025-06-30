<?php
require_once __DIR__ . '/../../config/Database.php';

class Specialty {
    public static function all($search = '') {
        $db = Database::connect();

        $sql = "SELECT id, name, description FROM specialties WHERE is_deleted = 0 AND is_active = 1";

        if (!empty($search) && strlen(trim($search)) >= 2) {
            $sql .= " AND (
                LOWER(name) LIKE LOWER(:search) OR
                LOWER(description) LIKE LOWER(:search)
            )";
        }

        $stmt = $db->prepare($sql);

        if (!empty($search) && strlen(trim($search)) >= 2) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
