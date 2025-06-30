<?php
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../Models/Calendar.php';

class CalendarController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $user = $_SESSION['user'];
        $role = $user['role'] ?? null;
        $doctor_id = null;

        if ($role === 'doctor') {
            $user_id = $user['id'];

            $db = Database::connect();
            $stmt = $db->prepare("SELECT id FROM doctors WHERE user_id = ? AND is_deleted = 0 AND is_active = 1");
            $stmt->execute([$user_id]);
            $doctor = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($doctor) {
                $doctor_id = $doctor['id'];
            } else {
                header("Location: /resources/views/auth/login.php");
                exit;
            }
        } elseif ($role !== 'admin') {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $eventos = Calendar::getEvents($doctor_id);

        return [
            'eventos' => $eventos
        ];
    }
}
