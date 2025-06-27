<?php
session_start();
require_once __DIR__ . '/../../../config/Database.php';

$conn = Database::connect();

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$errors = [];

if (!$id || $name === '') {
    $errors[] = "Todos los campos obligatorios deben estar completos.";
}

if (empty($errors)) {
    try {
        $stmt = $conn->prepare("UPDATE specialties SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);

        $_SESSION['success'] = "Especialidad actualizada correctamente.";
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Error al actualizar la especialidad: " . $e->getMessage()];
    }
} else {
    $_SESSION['errors'] = $errors;
}

header("Location: index.php");
exit;
