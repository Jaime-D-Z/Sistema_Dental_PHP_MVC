<?php
session_start();
require_once __DIR__ . '/../../../config/Database.php';

$conn = Database::connect();

$id = $_GET['id'] ?? null;
if (!$id) exit("ID no proporcionado");

$stmt = $conn->prepare("SELECT * FROM specialties WHERE id = ?");
$stmt->execute([$id]);
$specialty = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$specialty) exit("Especialidad no encontrada.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Especialidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    
</head>
<body class="bg-light">

<!-- Menú superior -->
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
  <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">🦷 ODONTOLOGÍA TC</a>
  <div class="collapse navbar-collapse">
    <div class="navbar-nav">
      <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
      <a class="nav-link" href="#">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/citas/index.php">Citas</a>
      <a class="nav-link" href="/resources/views/historial/index.php">Historial Citas</a>
      <a class="nav-link" href="/resources/views/calendario/index.php">Calendario</a>
    </div>
  </div>
  <div class="ms-auto">
    <span class="text-success me-3">Admin Tarea Completa</span>
        <a class="btn btn-outline-danger btn-sm" href="/resources/views/auth/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<div class="container mt-5">
    <h2>Editar Especialidad</h2>
<form action="guardar_especialidad.php" method="POST">
        <input type="hidden" name="id" value="<?= $specialty['id'] ?>">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($specialty['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($specialty['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>


</html>
