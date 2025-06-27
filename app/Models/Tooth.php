<?php
require_once __DIR__ . '/../../config/Database.php';

class Tooth {
    public static function getAll($patientId, $appointmentId) {
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

    public static function save($data) {
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO teeth (appointment_id, patient_id, pieza, zona, tratamiento_id, observaciones, accion, color_fondo, simbolo, color_simbolo)
            VALUES (:appointment_id, :patient_id, :pieza, :zona, :tratamiento_id, :observaciones, :accion, :color_fondo, :simbolo, :color_simbolo)
            ON DUPLICATE KEY UPDATE
              tratamiento_id = VALUES(tratamiento_id),
              observaciones = VALUES(observaciones),
              accion = VALUES(accion),
              color_fondo = VALUES(color_fondo),
              simbolo = VALUES(simbolo),
              color_simbolo = VALUES(color_simbolo)
        ");
        return $stmt->execute($data);
    }
}
