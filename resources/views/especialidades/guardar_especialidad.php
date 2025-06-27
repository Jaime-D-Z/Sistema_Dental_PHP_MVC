<?php
session_start();
require_once __DIR__ . '/../../../config/Database.php';

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$id = $_POST['id'] ?? null;

$errors = [];

if ($name === '') {
    $errors[] = "El nombre de la especialidad es obligatorio.";
}

$db = Database::connect();

if ($id) {
    // Modo EDICIÓN
    // Validar duplicado en otros
    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM specialties WHERE name = :name AND id != :id AND is_deleted = 0");
    $stmtCheck->execute([':name' => $name, ':id' => $id]);
    $exists = $stmtCheck->fetchColumn();

    if ($exists) {
        $errors[] = "Ya existe otra especialidad con ese nombre.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: editar_specialty.php?id=" . $id);
        exit;
    }

    $stmt = $db->prepare("UPDATE specialties SET name = :name, description = :description WHERE id = :id");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':id' => $id
    ]);

    $_SESSION['success'] = "Especialidad actualizada correctamente.";
    header("Location: index.php");
    exit;
} else {
    // Modo CREACIÓN
    $stmtCheck = $db->prepare("SELECT id, is_deleted FROM specialties WHERE name = :name");
    $stmtCheck->execute([':name' => $name]);
    $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['is_deleted']) {
            // Restaurar
            $stmtRestore = $db->prepare("UPDATE specialties SET is_deleted = 0, is_active = 1, description = :description WHERE id = :id");
            $stmtRestore->execute([
                ':description' => $description,
                ':id' => $row['id']
            ]);
            $_SESSION['success'] = "Especialidad restaurada correctamente.";
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Ya existe una especialidad con ese nombre.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
        exit;
    }

    $stmt = $db->prepare("INSERT INTO specialties (name, description, is_active, is_deleted) VALUES (:name, :description, 1, 0)");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description
    ]);

    $_SESSION['success'] = "Especialidad registrada correctamente.";
    header("Location: index.php");
    exit;
}
