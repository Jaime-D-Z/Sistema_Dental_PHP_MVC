<?php
require_once __DIR__ . '/../../../config/Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = Database::connect();

// Asegurarse que sea médico autenticado
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: /resources/views/auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id        = $_SESSION['user']['id'];
    $dni_paciente   = trim($_POST['dni_paciente'] ?? '');
    $treatment_id   = $_POST['treatment_id'] ?? null;
    $date           = $_POST['date'] ?? null;
    $time           = $_POST['time'] ?? null;
    $diagnosis      = $_POST['diagnosis'] ?? '';
    $status         = $_POST['status'] ?? null;
    $cost           = $_POST['cost'] ?? 0;
    $amount_paid    = $_POST['paid'] ?? 0;
    $notes          = $_POST['notes'] ?? '';

    if (!$dni_paciente || !$treatment_id || !$date || !$time || !$status) {
        header("Location: index.php?error=campos_incompletos");
        exit;
    }

    try {
        // Obtener doctor_id desde tabla doctors usando user_id
        $stmt = $db->prepare("SELECT id FROM doctors WHERE user_id = ? AND is_deleted = 0 AND is_active = 1");
        $stmt->execute([$user_id]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$doctor) {
            echo "Error: No se encontró el perfil del médico actual.";
            exit;
        }

        $doctor_id = $doctor['id'];

        // Buscar paciente por DNI
        $stmt = $db->prepare("SELECT id FROM patients WHERE TRIM(dni) = ? AND is_deleted = 0 AND is_active = 1");
        $stmt->execute([$dni_paciente]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            header("Location: index.php?error=paciente_no_encontrado&dni=" . urlencode($dni_paciente));
            exit;
        }

        $patient_id = $patient['id'];

        // Registrar cita
        $stmt = $db->prepare("
            INSERT INTO appointments (
                patient_id, doctor_id, treatment_id,
                date, time, diagnosis, status,
                cost, paid, notes,
                created_at, is_active, is_deleted
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1, 0)
        ");
        $stmt->execute([
            $patient_id, $doctor_id, $treatment_id,
            $date, $time, $diagnosis, $status,
            $cost, $amount_paid, $notes
        ]);

        $appointment_id = $db->lastInsertId();

        // Si pagó algo, registrar el pago
        if ($amount_paid > 0) {
            $pago = $db->prepare("
                INSERT INTO payments (
                    appointment_id, amount, payment_method,
                    payment_date, payment_status, is_active,
                    is_deleted, created_at
                ) VALUES (?, ?, 'efectivo', NOW(), 'completado', 1, 0, NOW())
            ");
            $pago->execute([$appointment_id, $amount_paid]);
        }

        header("Location: index.php?success=registrada");
        exit;

    } catch (Exception $e) {
        echo "Error al registrar la cita: " . $e->getMessage();
    }

} else {
    echo "Acceso inválido.";
}
