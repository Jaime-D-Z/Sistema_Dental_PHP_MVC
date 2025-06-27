<?php
require_once __DIR__ . '/../../config/Database.php';

class Patient {
    public static function all($search = '') {
        $db = Database::connect();

        $sql = "SELECT * FROM patients WHERE is_deleted = 0 AND is_active = 1";
        if (!empty($search)) {
            $sql .= " AND (first_name LIKE :search OR dni LIKE :search OR email LIKE :search)";
        }
        $sql .= " ORDER BY id DESC";

        $stmt = $db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


   public static function findByDNI($dni) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM patients WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
