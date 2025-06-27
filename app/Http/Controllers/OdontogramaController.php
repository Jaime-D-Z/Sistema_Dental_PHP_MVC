<?php
require_once __DIR__ . '/../Models/Patient.php';
require_once __DIR__ . '/../Models/Appointment.php';
require_once __DIR__ . '/../Models/Tooth.php';

class OdontogramaController {
    public function handle() {
        $patient = null;
        $appointment = null;
        $teeth = [];

        if (!empty($_GET['dni'])) {
            $patient = Patient::findByDNI($_GET['dni']);
            if ($patient) {
                $appointment = Appointment::lastForPatient($patient['id']);
                if ($appointment) {
                    $teeth = Tooth::getAll($patient['id'], $appointment['id']);
                }
            }
        }

        return [
            'patient' => $patient,
            'appointment' => $appointment,
            'teeth' => $teeth
        ];
    }

    public function guardarDiente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'appointment_id'   => $_POST['appointment_id'],
                'patient_id'       => $_POST['patient_id'],
                'pieza'            => $_POST['pieza'],
                'zona'             => $_POST['zona'],
                'tratamiento_id'   => $_POST['tratamiento_id'],
                'observaciones'    => $_POST['observaciones'],
                'accion'           => $_POST['accion'],
                'color_fondo'      => $_POST['color_fondo'],
                'simbolo'          => $_POST['simbolo'],
                'color_simbolo'    => $_POST['color_simbolo']
            ];

            if (Tooth::save($data)) {
                header("Location: index.php?dni=" . $_POST['dni'] . "&success=1");
                exit;
            } else {
                header("Location: index.php?dni=" . $_POST['dni'] . "&error=1");
                exit;
            }
        }
    }
}
