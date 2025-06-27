<?php
require_once __DIR__ . '/../../config/Database.php';

class Payment
{
    public static function all($search = '')
    {
        $conn = Database::connect();

        $sql = "SELECT payments.*, 
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
                WHERE payments.is_deleted = 0 AND appointments.is_deleted = 0";

        if (!empty($search)) {
            $sql .= " AND CONCAT(patients.first_name, ' ', patients.last_name) LIKE :search";
        }

        $sql .= " ORDER BY payments.id DESC";

        $stmt = $conn->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
