<?php
require_once __DIR__ . '/../../config/Database.php';

class Payment
{
    // ✅ Para Administrador
    public static function all($search = '')
    {
        $db = Database::connect();

       $sql = "
    SELECT 
        a.id,
        a.date,
        a.time,
        a.diagnosis,
        a.cost,
        IFNULL(SUM(p.amount), 0) AS paid,
        t.name AS treatment_name,
        CONCAT_WS(' ', d.first_name, d.last_name) AS doctor_name,
        CONCAT_WS(' ', pa.first_name, pa.last_name) AS patient_name,
        a.status,
        (
            SELECT id 
            FROM payments 
            WHERE appointment_id = a.id AND is_deleted = 0 
            ORDER BY id DESC 
            LIMIT 1
        ) AS payment_id
    FROM appointments a
    INNER JOIN treatments t ON a.treatment_id = t.id
    INNER JOIN doctors d ON a.doctor_id = d.id
    INNER JOIN patients pa ON a.patient_id = pa.id
    LEFT JOIN payments p ON p.appointment_id = a.id
    WHERE a.is_deleted = 0 
      AND a.is_active = 1 
      AND TRIM(LOWER(a.status)) != 'atendido'
";


        if (!empty($search)) {
            $sql .= " AND (
                pa.first_name LIKE :search OR
                pa.last_name LIKE :search OR
                CONCAT_WS(' ', pa.first_name, pa.last_name) LIKE :search OR
                a.diagnosis LIKE :search OR
                a.date LIKE :search
            )";
        }

        $sql .= " GROUP BY a.id ORDER BY a.date DESC, a.time DESC";

        $stmt = $db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Para Médico
    public static function allByDoctor($doctor_id, $search = '')
    {
        $conn = Database::connect();

        $sql = "
            SELECT 
                payments.*,
                CONCAT_WS(' ', patients.first_name, patients.last_name) AS patient_name,
                treatments.name AS treatment_name,
                appointments.cost,
                appointments.paid,
                appointments.status,
                appointments.diagnosis, 
                appointments.date, 
                appointments.time
            FROM payments 
            JOIN appointments ON payments.appointment_id = appointments.id 
            JOIN patients ON appointments.patient_id = patients.id 
            JOIN treatments ON appointments.treatment_id = treatments.id
            WHERE payments.is_deleted = 0 
              AND appointments.is_deleted = 0 
              AND appointments.doctor_id = :doctor_id
        ";

        if (!empty($search)) {
            $sql .= " AND (
                patients.first_name LIKE :search OR
                patients.last_name LIKE :search OR
                CONCAT_WS(' ', patients.first_name, patients.last_name) LIKE :search OR
                appointments.diagnosis LIKE :search OR
                appointments.date LIKE :search
            )";
        }

        $sql .= " ORDER BY payments.id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':doctor_id', $doctor_id, PDO::PARAM_INT);

        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
