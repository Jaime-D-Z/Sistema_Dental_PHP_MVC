<?php
session_start();
require_once __DIR__ . '/../../../config/Database.php';

$conn = Database::connect();

$id = $_POST['id'] ?? null;

if ($id) {
    try {
        // Eliminación lógica: desactivar y marcar como eliminado
        $stmt = $conn->prepare("UPDATE specialties SET is_deleted = 1, is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Especialidad marcada como eliminada.";
        } else {
            $_SESSION['errors'] = ["No se encontró la especialidad para eliminar."];
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Error al eliminar especialidad: " . $e->getMessage()];
    }
} else {
    $_SESSION['errors'] = ["ID no proporcionado para eliminar la especialidad."];
}

header("Location: index.php");
exit;
