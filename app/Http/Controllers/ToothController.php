<?php
require_once __DIR__ . '/../Models/Tooth.php';

class ToothController {
    public function guardar($payload) {
        return Tooth::save($payload);
    }

    public static function getByPatientAndAppointment($patientId, $appointmentId) {
    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM teeth WHERE patient_id = ? AND appointment_id = ?");
    $stmt->execute([$patientId, $appointmentId]);
    $result = [];
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $key = $r['pieza'] . '_' . $r['zona'];
        $result[$key] = $r;
    }
    return $result;
}

}
