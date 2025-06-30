<?php
require_once __DIR__ . '/../../../config/Database.php';

$conn = Database::connect();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Obtener datos de la cita y paciente
$sql = "SELECT 
            CONCAT_WS(' ', p.first_name, p.last_name) AS paciente,
            a.diagnosis AS enfermedad,
            t.name AS tratamiento,
            CONCAT(m.first_name, ' ', m.last_name) AS medico,
            a.status,
            a.cost,
            a.paid,
            (a.cost - a.paid) AS saldo
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN treatments t ON a.treatment_id = t.id
        LEFT JOIN doctors m ON a.doctor_id = m.id
        WHERE a.id = (
            SELECT appointment_id FROM payments WHERE id = :id AND is_deleted = 0
        )";


$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$detalle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$detalle) {
    echo "Pago no encontrado.";
    exit;
}

// Obtener historial de pagos para esa cita
$pagos = $conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%d/%m/%Y') AS fecha,
        DATE_FORMAT(created_at, '%H:%i:%s') AS hora,
        amount
    FROM payments
    WHERE appointment_id = (
        SELECT appointment_id FROM payments WHERE id = :id
    ) AND is_deleted = 0
    ORDER BY id DESC
");

$pagos->execute([':id' => $id]);
$historial = $pagos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <a href="index.php" class="btn btn-secondary mb-3">← Volver</a>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white fw-bold">Información del Paciente</div>
        <div class="card-body row">
            <div class="col-md-6">
                <p><strong>Paciente:</strong> <?= htmlspecialchars($detalle['paciente']) ?></p>
                <p><strong>Enfermedad:</strong> <?= htmlspecialchars($detalle['enfermedad']) ?></p>
                <p><strong>Tratamiento:</strong> <?= htmlspecialchars($detalle['tratamiento']) ?></p>
                <p><strong>Médico:</strong> <?= htmlspecialchars($detalle['medico']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Estado:</strong> <?= htmlspecialchars($detalle['status']) ?></p>
                <p><strong>Costo:</strong> S/. <?= number_format($detalle['cost'], 2) ?></p>
                <p><strong>Pagado:</strong> <span class="text-success">S/. <?= number_format($detalle['paid'], 2) ?></span></p>
                <p><strong>Saldo:</strong> <span class="<?= $detalle['saldo'] > 0 ? 'text-danger' : 'text-success' ?>">S/. <?= number_format($detalle['saldo'], 2) ?></span></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white fw-bold">Historial de Pagos</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $i => $pago): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($pago['fecha']) ?></td>
                            <td><?= htmlspecialchars($pago['hora']) ?></td>
                            <td>S/. <?= number_format($pago['amount'], 2) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
