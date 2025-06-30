<?php
require_once __DIR__ . '/../../config/Database.php';

class AppointmentHistory {
    public static function all($search = '') {
        $db = Database::connect();

        $sql = "
            SELECT 
                h.id AS historial_id,
                CONCAT_WS(' ', p.first_name, p.last_name) AS paciente_nombre,
                CONCAT_WS(' ', d.first_name, d.last_name) AS medico_nombre,
                t.name AS tratamiento_nombre,
                a.date,
                a.time,
                a.diagnosis,
                a.status,
                a.cost,
                a.paid,
                h.details
            FROM appointment_history h
            JOIN appointments a ON h.appointment_id = a.id
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN treatments t ON a.treatment_id = t.id
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($search) && strlen(trim($search)) >= 2) {
            $sql .= " AND (
                LOWER(p.first_name) LIKE :search OR
                LOWER(p.last_name) LIKE :search OR
                LOWER(CONCAT(d.first_name, ' ', d.last_name)) LIKE :search OR
                LOWER(t.name) LIKE :search OR
                LOWER(h.details) LIKE :search
            )";
            $params[':search'] = '%' . strtolower(trim($search)) . '%';
        }

        $sql .= " ORDER BY h.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
