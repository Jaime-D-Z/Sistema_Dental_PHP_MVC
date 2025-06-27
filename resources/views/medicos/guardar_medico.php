<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');

$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $specialty_id = trim($_POST['specialty_id'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $errors = [];

    // Validaciones mínimas
    if (!$first_name || !$last_name || !$dni || !$email || !$specialty_id) {
        $errors[] = "Todos los campos obligatorios deben ser completados.";
    }

    // Validar formato de DNI
    if (!preg_match('/^\d{8}$/', $dni)) {
        $errors[] = "El DNI debe tener 8 dígitos.";
    }

    // Validar existencia de especialidad
    $checkEsp = $conn->prepare("SELECT COUNT(*) FROM specialties WHERE id = ?");
    $checkEsp->execute([$specialty_id]);
    if ($checkEsp->fetchColumn() == 0) {
        $errors[] = "La especialidad seleccionada no existe.";
    }

    // Validar que no se repita el email en users
    $checkEmail = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkEmail->execute([$email]);
    if ($checkEmail->fetchColumn() > 0) {
        $errors[] = "Ya existe un usuario con este correo electrónico.";
    }

    // Validar que el DNI no esté repetido en doctors
    $checkDNI = $conn->prepare("SELECT COUNT(*) FROM doctors WHERE dni = ?");
    $checkDNI->execute([$dni]);
    if ($checkDNI->fetchColumn() > 0) {
        $errors[] = "El DNI ya está registrado para otro médico.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
        exit();
    }

    try {
        // Inicia transacción
        $conn->beginTransaction();

        // 1. Crear usuario (rol: doctor)
        $passwordDefault = password_hash('doctor123', PASSWORD_DEFAULT); // contraseña por defecto
        $stmtUser = $conn->prepare("INSERT INTO users (name, email, password, role, is_active, created_at) VALUES (?, ?, ?, 'doctor', 1, NOW())");
        $stmtUser->execute(["$first_name $last_name", $email, $passwordDefault]);
        $userId = $conn->lastInsertId();

        // 2. Crear médico con user_id
        $stmtDoctor = $conn->prepare("INSERT INTO doctors (
            first_name, last_name, dni, specialty_id, address, email, phone, user_id, is_active, is_deleted, created_at
        ) VALUES (
            :first_name, :last_name, :dni, :specialty_id, :address, :email, :phone, :user_id, 1, 0, NOW()
        )");

        $stmtDoctor->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':dni' => $dni,
            ':specialty_id' => $specialty_id,
            ':address' => $address ?: null,
            ':email' => $email,
            ':phone' => $phone ?: null,
            ':user_id' => $userId
        ]);

        $conn->commit();
        $_SESSION['success'] = "✅ Médico y usuario creados correctamente. Contraseña por defecto: doctor123";

    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['errors'] = ["❌ Error al guardar: " . $e->getMessage()];
    }

    header("Location: index.php");
    exit();
}
