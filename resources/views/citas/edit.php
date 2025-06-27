<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = Database::connect();

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID de cita no proporcionado.");
}

// Obtener cita por ID
$stmt = $db->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$id]);
$appointment = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$appointment) {
    die("Cita no encontrada.");
}

// Obtener listas para los selects
$pacientes = $db->query("SELECT id, first_name, last_name FROM patients")->fetchAll(PDO::FETCH_ASSOC);
$medicos = $db->query("SELECT id, first_name, last_name FROM doctors")->fetchAll(PDO::FETCH_ASSOC);
$tratamientos = $db->query("SELECT id, name FROM treatments")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
    .topbar a { color: #0d6efd; font-weight: 500; margin-right: 20px; text-decoration: none; }
    .topbar a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg topbar px-4 py-2">
    <a class="navbar-brand fw-bold text-primary" href="/clinica-dental/resources/views/dashboard/index.php">🦷 ODONTOLOGÍA TC</a>
    <div class="collapse navbar-collapse">
        <div class="navbar-nav">
            <a class="nav-link" href="/clinica-dental/resources/views/dashboard/index.php">Inicio</a>
            <a class="nav-link" href="#">Mantenimiento</a>
            <a class="nav-link" href="/clinica-dental/resources/views/citas/index.php">Citas</a>
            <a class="nav-link" href="/clinica-dental/resources/views/historial/index.php">Historial Citas</a>
            <a class="nav-link" href="/clinica-dental/resources/views/calendario/index.php">Calendario</a>
        </div>
    </div>
    <div class="ms-auto">
        <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/clinica-dental/logout.php">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>
</nav>

<div class="container mt-5">
    <div class="mb-3">
        <a href="/clinica-dental/resources/views/citas/index.php" class="btn btn-outline-secondary">← Volver al menú</a>
    </div>

    <h2 class="mb-4">Editar Cita</h2>

    <form action="actualizar_cita.php" method="POST">
        <input type="hidden" name="id" value="<?= $appointment['id'] ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Paciente</label>
                <select name="patient_id" class="form-select" required>
                    <?php foreach ($pacientes as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Médico</label>
                <select name="doctor_id" class="form-select" required>
                    <?php foreach ($medicos as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $m['id'] == $appointment['doctor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tratamiento</label>
                <select name="treatment_id" class="form-select" required>
                    <?php foreach ($tratamientos as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= $t['id'] == $appointment['treatment_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="date" class="form-control" value="<?= $appointment['date'] ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Hora</label>
                <input type="time" name="time" class="form-control" value="<?= $appointment['time'] ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Diagnóstico</label>
                <input type="text" name="diagnosis" class="form-control" value="<?= htmlspecialchars($appointment['diagnosis']) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select" required>
                    <option value="asignado" <?= $appointment['status'] == 'asignado' ? 'selected' : '' ?>>Asignado</option>
                    <option value="atendido" <?= $appointment['status'] == 'atendido' ? 'selected' : '' ?>>Atendido</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Costo (S/.)</label>
                <input type="number" name="cost" class="form-control" step="0.01" value="<?= $appointment['cost'] ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Monto pagado (S/.)</label>
                <input type="number" name="paid" class="form-control" step="0.01" min="0" value="<?= $appointment['paid'] ?>" required>
            </div>

            <div class="col-12">
                <label class="form-label">Notas u Observaciones</label>
                <textarea name="notes" class="form-control"><?= htmlspecialchars($appointment['notes'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-arrow-repeat"></i> Actualizar Cita
            </button>
            <a href="/clinica-dental/resources/views/citas/index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
