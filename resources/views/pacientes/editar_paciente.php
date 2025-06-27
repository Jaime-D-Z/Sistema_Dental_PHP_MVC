<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    exit("ID inválido o no proporcionado.");
}

// Obtener paciente por ID
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    exit("Paciente no encontrado.");
}

// Mapeo para mantener consistencia en selects
$booleanFields = [
    'under_treatment' => 'Bajo tratamiento médico',
    'bleeding' => 'Propenso a la hemorragia',
    'allergy' => 'Alérgico a medicamentos',
    'hypertensive' => 'Hipertenso',
    'diabetic' => 'Diabético',
    'pregnant' => 'Embarazada'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Editar Información del Paciente</h2>
    <form action="actualizar_paciente.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($paciente['id']) ?>">

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de Documento</label>
                <select name="document_type" class="form-select" required>
                    <?php
                    $documentos = ['DNI', 'Pasaporte', 'Carnet Ext.'];
                    foreach ($documentos as $tipo) {
                        $selected = $paciente['document_type'] === $tipo ? 'selected' : '';
                        echo "<option value=\"$tipo\" $selected>$tipo</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nro Documento</label>
                <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($paciente['dni']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($paciente['first_name']) ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($paciente['email']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($paciente['phone']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Historia Clínica</label>
                <input type="text" name="medical_history" class="form-control" value="<?= htmlspecialchars($paciente['medical_history']) ?>">
            </div>
        </div>

        <hr>
        <h5>Antecedentes Médicos</h5>
        <div class="row mb-3">
            <?php foreach ($booleanFields as $campo => $label): ?>
                <div class="col-md-4">
                    <label class="form-label"><?= $label ?></label>
                    <select name="<?= $campo ?>" class="form-select" required>
                        <option value="">Selecciona...</option>
                        <option value="1" <?= $paciente[$campo] == 1 ? 'selected' : '' ?>>Sí</option>
                        <option value="0" <?= $paciente[$campo] == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo de la consulta</label>
            <textarea name="reason" class="form-control" rows="2"><?= htmlspecialchars($paciente['reason']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Diagnóstico</label>
            <textarea name="diagnosis" class="form-control" rows="2"><?= htmlspecialchars($paciente['diagnosis']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Observaciones</label>
            <textarea name="observations" class="form-control" rows="2"><?= htmlspecialchars($paciente['observations']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Referido por</label>
            <input type="text" name="referred_by" class="form-control" value="<?= htmlspecialchars($paciente['referred_by']) ?>">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
