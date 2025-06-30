<?php
require_once __DIR__ . '/../../Models/Appointment.php';

class AppointmentController {
    public function index() {
        $search = trim($_GET['search'] ?? '');

        if (isset($_GET['search']) && strlen($search) < 2) {
            // No buscar si escribió algo pero con menos de 2 letras
            $appointments = [];
        } else {
            // Buscar si tiene al menos 2 letras o si no hay búsqueda
            $appointments = Appointment::all($search);
        }

        return [
            'appointments' => $appointments,
            'treatments'   => Appointment::getTreatments(),
            'doctors'      => Appointment::getDoctors(),
            'search'       => $search
        ];
    }
}
