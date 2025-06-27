<?php
require_once __DIR__ . '/../../Models/Appointment.php';
require_once __DIR__ . '/../../Models/Treatment.php';
require_once __DIR__ . '/../../Models/Doctor.php';

use App\Models\Treatment;

class DoctorCitaController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Asegurar que es un médico
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $user_id = $_SESSION['user']['id'];

        // Obtener el doctor_id real desde user_id
        $conn = \Database::connect();
        $stmt = $conn->prepare("SELECT id FROM doctors WHERE user_id = ? AND is_deleted = 0 AND is_active = 1");
        $stmt->execute([$user_id]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$doctor) {
            die("❌ No se encontró al doctor correspondiente al usuario.");
        }

        $doctor_id = $doctor['id'];

        // Obtener citas de ese doctor
        $appointments = \Appointment::getByDoctor($doctor_id); // ✅ Aquí usamos el ID correcto
        $treatments   = Treatment::all();

        return [
            'appointments' => $appointments,
            'treatments' => $treatments,
            'doctor_id' => $doctor_id
        ];
    }
}
