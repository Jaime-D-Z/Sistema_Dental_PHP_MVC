<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');

$conn = Database::connect();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Marcamos como eliminado (eliminación lógica)
        $stmt = $conn->prepare("UPDATE doctors SET is_deleted = 1, is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Doctor marcado como eliminado correctamente.";
        } else {
            $_SESSION['errors'] = ["No se encontró el doctor para eliminar."];
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Error al eliminar doctor: " . $e->getMessage()];
    }
} else {
    $_SESSION['errors'] = ["ID de doctor no proporcionado."];
}

header("Location: index.php");
exit;
