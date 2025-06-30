<?php
require_once __DIR__ . '/../../config/Database.php';

class Doctor
{
    public static function all($search = '')
    {
        $db = Database::connect();
        $sql = "SELECT d.*, s.name AS specialty 
                FROM doctors d
                JOIN specialties s ON d.specialty_id = s.id
                WHERE d.is_deleted = 0";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (
                LOWER(d.first_name) LIKE :search OR
                LOWER(d.last_name) LIKE :search OR
                LOWER(CONCAT(d.first_name, ' ', d.last_name)) LIKE :search OR
                d.dni LIKE :search
            )";
            $params[':search'] = '%' . strtolower($search) . '%';
        }

        $sql .= " ORDER BY d.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function specialties()
    {
        $db = Database::connect();
        $stmt = $db->query("SELECT id, name FROM specialties WHERE is_deleted = 0 AND is_active = 1 ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
