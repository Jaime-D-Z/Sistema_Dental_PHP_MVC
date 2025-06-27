<?php
require_once __DIR__ . '/../../../config/auth.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;
if (!$id) die("ID no proporcionado.");

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) die("Usuario no encontrado.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .topbar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; }
        .topbar a { color: #0d6efd; margin-right: 20px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg topbar px-4 py-2">
  <a class="navbar-brand fw-bold text-primary" href="/resources/views/layouts/index.php">🦷 ODONTOLOGÍA TC</a>
  <div class="collapse navbar-collapse">
    <div class="navbar-nav">
      <a class="nav-link" href="/resources/views/layouts/index.php">Inicio</a>
      <a class="nav-link" href="#">Mantenimiento</a>
      <a class="nav-link" href="/resources/views/usuarios/index.php">Usuarios</a>
    </div>
  </div>
  <div class="ms-auto">
    <span class="text-success me-3">Admin Tarea Completa</span>
    <a class="btn btn-outline-danger btn-sm" href="/clinica-dental/logout.php">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</nav>

<div class="container mt-4">
  <h2>Editar Usuario</h2>
  <form action="guardar_usuario.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Foto</label>
        <input type="file" name="photo" class="form-control">
        <?php if (!empty($usuario['photo'])): ?>
          <img src="uploads/<?= htmlspecialchars($usuario['photo']) ?>" class="mt-2 img-thumbnail" width="80">
        <?php endif; ?>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nombre Completo</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($usuario['name']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Rol</label>
        <select name="role" class="form-select" required>
          <option value="admin" <?= $usuario['role']=='admin'?'selected':'' ?>>Admin</option>
          <option value="doctor" <?= $usuario['role']=='doctor'?'selected':'' ?>>Médico</option>
          <option value="patient" <?= $usuario['role']=='patient'?'selected':'' ?>>Paciente</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tipo Documento</label>
        <select name="document_type" class="form-select" required>
          <?php foreach(['DNI','Pasaporte','Carnet Ext.'] as $td): ?>
            <option value="<?= $td ?>" <?= $usuario['document_type']==$td?'selected':'' ?>><?= $td ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Número Documento</label>
<input type="text" name="document_number" class="form-control" value="<?= htmlspecialchars($usuario['document_number'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
<input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($usuario['phone'] ?? '') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
      </div>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
