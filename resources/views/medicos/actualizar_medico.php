<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');

$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        $_SESSION['errors'] = ["ID del doctor no proporcionado."];
        header("Location: index.php");
        exit;
    }

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $specialty_id = trim($_POST['specialty_id'] ?? '');
    $address = trim($_POST['address'] ?? null);
    $email = trim($_POST['email'] ?? null);
    $phone = trim($_POST['phone'] ?? null);

    // Validar que la especialidad exista
    $check = $conn->prepare("SELECT COUNT(*) FROM specialties WHERE id = ?");
    $check->execute([$specialty_id]);
    if ($check->fetchColumn() == 0) {
        $_SESSION['errors'] = ["La especialidad seleccionada no existe."];
        header("Location: index.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE doctors SET 
            first_name = :first_name,
            last_name = :last_name,
            dni = :dni,
            specialty_id = :specialty_id,
            address = :address,
            email = :email,
            phone = :phone
            WHERE id = :id");

        $stmt->execute([
            ':id' => $id,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':dni' => $dni,
            ':specialty_id' => $specialty_id,
            ':address' => $address,
            ':email' => $email,
            ':phone' => $phone,
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Doctor actualizado correctamente.";
        } else {
            $_SESSION['success'] = "No se realizaron cambios en el registro.";
        }
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Error al actualizar el doctor: " . $e->getMessage()];
    }

    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;
