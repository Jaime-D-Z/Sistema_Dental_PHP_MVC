<?php
require_once __DIR__ . '/../../config/Database.php';

class AppointmentHistory {
    public static function all($search = '') {
        $db = Database::connect();

        if ($search) {
            $stmt = $db->prepare("
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
                JOIN patients p ON h.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN treatments t ON a.treatment_id = t.id
                WHERE CONCAT(p.first_name, ' ', p.last_name) LIKE ? 
                   OR CONCAT(d.first_name, ' ', d.last_name) LIKE ? 
                   OR t.name LIKE ? 
                   OR h.details LIKE ?
                ORDER BY h.created_at DESC
            ");
            $stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);
        } else {
            $stmt = $db->query("
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
                JOIN patients p ON h.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN treatments t ON a.treatment_id = t.id
                ORDER BY h.created_at DESC
            ");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
