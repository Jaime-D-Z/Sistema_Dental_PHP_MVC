<?php
require_once __DIR__ . '/../../../config/Database.php';
$db = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Paso 1: Obtener datos del formulario
    $dni_paciente   = trim($_POST['dni_paciente'] ?? '');
    $doctor_id      = $_POST['doctor_id'] ?? null;
    $treatment_id   = $_POST['treatment_id'] ?? null;
    $date           = $_POST['date'] ?? null;
    $time           = $_POST['time'] ?? null;
    $diagnosis      = $_POST['diagnosis'] ?? '';
    $status         = $_POST['status'] ?? null;
    $cost           = $_POST['cost'] ?? 0;
    $amount_paid    = $_POST['paid'] ?? 0;
    $notes          = $_POST['notes'] ?? '';

    if (!$dni_paciente || !$doctor_id || !$treatment_id || !$date || !$time || !$status) {
        header("Location: index.php?error=campos_incompletos");
        exit;
    }

    try {
        // Paso 2: Obtener ID del paciente
        $stmt = $db->prepare("SELECT id FROM patients WHERE TRIM(dni) = ? AND is_deleted = 0 AND is_active = 1");
        $stmt->execute([$dni_paciente]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            header("Location: index.php?error=paciente_no_encontrado&dni=" . urlencode($dni_paciente));
            exit;
        }

        $patient_id = $patient['id'];

        // Paso 3: Insertar cita
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

        // Paso 4: Insertar pago en tabla payments si hay monto pagado
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

        // Paso 5: Redirigir con éxito
        header("Location: index.php?success=registrada");
        exit;

    } catch (Exception $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }

} else {
    echo "Solicitud inválida.";
}
