<?php
require_once __DIR__ . '/../../config/Database.php';


class Appointment {
    public static function all() {
        $db = Database::connect();
 $stmt = $db->prepare("
    SELECT 
        a.id,
        a.date,
        a.time,
        a.diagnosis,
        a.cost,
        IFNULL(SUM(p.amount), 0) AS paid,
        t.name AS treatment,
        CONCAT_WS(' ', d.first_name, d.last_name) AS doctor,
        CONCAT_WS(' ', pa.first_name, pa.last_name) AS patient
    FROM appointments a
    INNER JOIN treatments t ON a.treatment_id = t.id
    INNER JOIN doctors d ON a.doctor_id = d.id
    INNER JOIN patients pa ON a.patient_id = pa.id
    LEFT JOIN payments p ON p.appointment_id = a.id
    WHERE a.is_deleted = 0 AND a.is_active = 1
      AND a.status != 'atendido'
    GROUP BY a.id
    ORDER BY a.date DESC, a.time DESC
");


        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTreatments() {
        $db = Database::connect();
        return $db->query("SELECT id, name FROM treatments WHERE is_deleted = 0 AND is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDoctors() {
        $db = Database::connect();
        return $db->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM doctors WHERE is_deleted = 0 AND is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
    }

     public static function lastForPatient($patientId) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$patientId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
public static function getByDoctor($doctor_id) {
    $db = Database::connect();
    $stmt = $db->prepare("
        SELECT 
            a.*, 
            t.name AS treatment, 
                CONCAT_WS(' ', p.first_name, p.last_name) AS patient
        FROM appointments a
        INNER JOIN treatments t ON a.treatment_id = t.id
        INNER JOIN patients p ON a.patient_id = p.id
        WHERE a.doctor_id = ? AND a.is_deleted = 0
        ORDER BY a.date DESC, a.time DESC
    ");
    $stmt->execute([$doctor_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function getHistoryByDoctor($doctor_id, $search = '')
{
    $db = Database::connect();

    $sql = "SELECT 
                a.*, 
                t.name AS tratamiento_nombre,
                CONCAT(d.first_name, ' ', d.last_name) AS medico_nombre,
                CONCAT(p.first_name, ' ', p.last_name) AS paciente_nombre
            FROM appointments a
            INNER JOIN treatments t ON a.treatment_id = t.id
            INNER JOIN doctors d ON a.doctor_id = d.id
            INNER JOIN patients p ON a.patient_id = p.id
            WHERE a.is_deleted = 0 
              AND a.doctor_id = :doctor_id ";

    if (!empty($search)) {
        $sql .= " AND (p.first_name LIKE :search OR p.last_name LIKE :search OR t.name LIKE :search OR a.diagnosis LIKE :search)";
    }

    $sql .= " ORDER BY a.date DESC, a.time DESC";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':doctor_id', $doctor_id, PDO::PARAM_INT);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public static function getCalendarEventsByDoctor($doctor_id) {
    $db = Database::connect();
    $stmt = $db->prepare("
        SELECT 
            a.id,
            p.first_name AS paciente_nombre,
            p.last_name AS paciente_apellido,
            a.date AS inicio,
            a.time AS hora
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE a.doctor_id = ? AND a.is_active = 1 AND a.is_deleted = 0
        ORDER BY a.date, a.time
    ");
    $stmt->execute([$doctor_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Preparar datos para JSON en el calendario (puedes ajustar nombres según tu JS)
    $events = [];
    foreach ($results as $row) {
        $events[] = [
            'id' => $row['id'],
            'paciente' => $row['paciente_nombre'] . ' ' . $row['paciente_apellido'],
            'inicio' => $row['inicio'],
            'hora' => $row['hora']
        ];
    }
    return $events;
}



}
