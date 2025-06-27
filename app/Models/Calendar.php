<?php

require_once __DIR__ . '/../../config/Database.php';

class Calendar {
    // Ahora recibe un parámetro opcional $doctor_id para filtrar citas por doctor
    public static function getEvents($doctor_id = null) {
        $db = Database::connect();

        $sql = "
            SELECT 
                a.date AS inicio,
                a.time AS hora,
                CONCAT_WS(' ', p.first_name, p.last_name) AS paciente
            FROM appointments a
            INNER JOIN patients p ON a.patient_id = p.id
            WHERE 
                (a.status = 'asignado' OR a.status = 'atendido')
                AND a.is_deleted = 0
        ";

        // Si se pasa doctor_id, agregamos filtro
        if ($doctor_id !== null) {
            $sql .= " AND a.doctor_id = :doctor_id ";
        }

        $sql .= " ORDER BY a.date, a.time";

        $stmt = $db->prepare($sql);

        if ($doctor_id !== null) {
            $stmt->bindValue(':doctor_id', $doctor_id, \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
