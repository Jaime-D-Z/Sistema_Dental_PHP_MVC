<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

$id = $_GET['id'] ?? null;

if ($id && is_numeric($id)) {
    try {
        // Eliminación lógica: marcamos como eliminado y desactivado
        $stmt = $conn->prepare("UPDATE patients SET is_deleted = 1, is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = "✅ Paciente eliminado correctamente.";
        } else {
            $_SESSION['msg'] = "⚠️ No se encontró el paciente para eliminar.";
        }

    } catch (PDOException $e) {
        $_SESSION['msg'] = "❌ Error al eliminar paciente: " . $e->getMessage();
    }

} else {
    $_SESSION['msg'] = "⚠️ ID inválido o no proporcionado.";
}

header("Location: index.php");
exit;
