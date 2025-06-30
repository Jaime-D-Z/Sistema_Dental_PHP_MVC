<?php
require_once __DIR__ . '/../../config/Database.php';

class Patient {
    public static function all($search = '') {
        $db = Database::connect();

        $sql = "SELECT * FROM patients WHERE is_deleted = 0 AND is_active = 1";
        $params = [];

        // Solo si la búsqueda es válida (2 letras o más)
        if (!empty($search) && strlen(trim($search)) >= 2) {
            $sql .= " AND (
                LOWER(first_name) LIKE :search OR
                LOWER(last_name) LIKE :search OR
                LOWER(CONCAT(first_name, ' ', last_name)) LIKE :search OR
                dni LIKE :search OR
                LOWER(email) LIKE :search
            )";
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByDNI($dni) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM patients WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
