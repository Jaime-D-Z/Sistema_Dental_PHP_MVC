<?php

require_once __DIR__ . '/../../config/Database.php';

class Calendar {
    /**
     * Obtiene eventos de citas médicas.
     * Si se proporciona $doctor_id, solo devuelve citas de ese doctor.
     *
     * @param int|null $doctor_id
     * @return array
     */
    public static function getEvents($doctor_id = null) {
        $db = Database::connect();

        // Base SQL
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

        // Si hay un doctor logueado, filtramos
        if (!is_null($doctor_id)) {
            $sql .= " AND a.doctor_id = :doctor_id";
        }

        $sql .= " ORDER BY a.date, a.time";

        $stmt = $db->prepare($sql);

        // Enlazar doctor_id si aplica
        if (!is_null($doctor_id)) {
            $stmt->bindValue(':doctor_id', $doctor_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
