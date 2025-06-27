<?php
require_once __DIR__ . '/../../Models/Appointment.php';

class AppointmentController {
    public function index() {
        $appointments = Appointment::all();
        $treatments = Appointment::getTreatments();
        $doctors = Appointment::getDoctors();

        // Devolver las variables necesarias
        return [
            'appointments' => $appointments,
            'treatments' => $treatments,
            'doctors' => $doctors
        ];
    }
}
