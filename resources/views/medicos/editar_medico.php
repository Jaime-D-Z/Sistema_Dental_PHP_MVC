<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
require_once __DIR__ . '/../../../config/auth.php';

$conn = Database::connect();

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['errors'] = ["ID no proporcionado"];
    header("Location: index.php");
    exit;
}

// Obtener doctor
$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener especialidades
$especialidades = $conn->query("SELECT * FROM specialties")->fetchAll(PDO::FETCH_ASSOC);

if (!$doctor) {
    $_SESSION['errors'] = ["Doctor no encontrado"];
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2>Editar Doctor</h2>
    <form method="POST" action="actualizar_medico.php">
        <input type="hidden" name="id" value="<?= $doctor['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nombres</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($doctor['first_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($doctor['last_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($doctor['dni']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Especialidad</label>
            <select name="specialty_id" class="form-select" required>
                <option disabled>Seleccione una especialidad</option>
                <?php foreach ($especialidades as $esp): ?>
                    <option value="<?= $esp['id'] ?>" <?= $esp['id'] == $doctor['specialty_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($esp['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($doctor['address'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($doctor['phone'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
