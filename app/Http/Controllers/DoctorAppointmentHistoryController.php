<?php
require_once __DIR__ . '/../../Models/Appointment.php';

class DoctorAppointmentHistoryController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validar sesión y rol
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $doctor_id = $_SESSION['user']['id'];
        $search = $_GET['search'] ?? '';

        // Obtener citas del doctor filtrando por búsqueda si hay
        $historiales = Appointment::getHistoryByDoctor($doctor_id, $search);

        return [
            'historiales' => $historiales,
            'search' => $search
        ];
    }
}
