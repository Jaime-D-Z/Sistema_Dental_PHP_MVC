<?php
require_once __DIR__ . '/../../../config/Database.php';
$db = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = $_POST['id'] ?? null;
    $patient_id     = $_POST['patient_id'] ?? null;
    $doctor_id      = $_POST['doctor_id'] ?? null;
    $treatment_id   = $_POST['treatment_id'] ?? null;
    $date           = $_POST['date'] ?? null;
    $time           = $_POST['time'] ?? null;
    $diagnosis      = $_POST['diagnosis'] ?? '';
    $status         = $_POST['status'] ?? null;
    $cost           = $_POST['cost'] ?? 0;
    $amount_paid    = $_POST['paid'] ?? 0;
    $notes          = $_POST['notes'] ?? '';

    if (!$id || !$patient_id || !$doctor_id || !$treatment_id || !$date || !$time || !$status) {
        die("Faltan campos obligatorios.");
    }

    try {
        // 1. Actualizar cita
        $stmt = $db->prepare("
            UPDATE appointments 
            SET patient_id = ?, doctor_id = ?, treatment_id = ?, 
                date = ?, time = ?, diagnosis = ?, status = ?, 
                cost = ?, paid = ?, notes = ?, updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $patient_id, $doctor_id, $treatment_id,
            $date, $time, $diagnosis, $status,
            $cost, $amount_paid, $notes,
            $id
        ]);

        // 2. Actualizar o insertar pago
        $stmtPago = $db->prepare("SELECT id FROM payments WHERE appointment_id = ?");
        $stmtPago->execute([$id]);
        $payment = $stmtPago->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            $updatePayment = $db->prepare("
                UPDATE payments SET 
                    amount = ?, 
                    payment_method = 'efectivo', 
                    payment_date = NOW(),
                    payment_status = ?, 
                    updated_at = NOW()
                WHERE appointment_id = ?
            ");
            $updatePayment->execute([
                $amount_paid,
                $amount_paid >= $cost ? 'pagado' : 'pendiente',
                $id
            ]);
        } else {
            $insertPayment = $db->prepare("
                INSERT INTO payments (
                    appointment_id, amount, payment_method, 
                    payment_date, payment_status, is_active, is_deleted, created_at
                ) VALUES (?, ?, 'efectivo', NOW(), ?, 1, 0, NOW())
            ");
            $insertPayment->execute([
                $id,
                $amount_paid,
                $amount_paid >= $cost ? 'pagado' : 'pendiente'
            ]);
        }

        // 3. Guardar en historial si fue atendido
        if ($status === 'atendido') {
            $stmtHist = $db->prepare("SELECT id FROM appointment_history WHERE appointment_id = ?");
            $stmtHist->execute([$id]);
            if (!$stmtHist->fetch()) {
                $insertHist = $db->prepare("
                    INSERT INTO appointment_history (patient_id, appointment_id, details, created_at) 
                    VALUES (?, ?, ?, NOW())
                ");
                $insertHist->execute([$patient_id, $id, $diagnosis ?: 'Sin diagnóstico']);
            }
        }

        // 4. Redirigir
        header("Location: /resources/views/citas/index.php?success=actualizada");
        exit;

    } catch (Exception $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "Solicitud inválida.";
}
