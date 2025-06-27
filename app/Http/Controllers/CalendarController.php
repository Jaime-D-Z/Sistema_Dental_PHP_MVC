<?php
require_once __DIR__ . '/../../Models/Calendar.php';

class CalendarController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validar sesión
        if (!isset($_SESSION['user'])) {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $role = $_SESSION['user']['role'] ?? null;
        $doctor_id = null;

        if ($role === 'doctor') {
            // Si es doctor, obtener su id para filtrar eventos
            $doctor_id = $_SESSION['user']['id'];
        } elseif ($role !== 'admin') {
            // Si no es admin ni doctor, no tiene permiso
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        // Obtener eventos, filtrando si es doctor
        $eventos = Calendar::getEvents($doctor_id);

        return [
            'eventos' => $eventos
        ];
    }
}
