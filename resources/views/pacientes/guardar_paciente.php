<?php
session_start();
ini_set('display_errors', 1);
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dni = $_POST['dni'];
        $email = $_POST['email'];

        // Verificar si ya existe un usuario con ese email o DNI
        $verificarUsuario = $conn->prepare("SELECT id FROM users WHERE email = ? OR document_number = ?");
        $verificarUsuario->execute([$email, $dni]);
        if ($verificarUsuario->fetch()) {
            $_SESSION['msg'] = "⚠️ Ya existe un usuario con ese correo o DNI.";
            header('Location: index.php');
            exit;
        }

        // 1. Crear usuario automáticamente con rol patient
        $stmtUser = $conn->prepare("INSERT INTO users 
            (name, email, password, role, document_type, document_number, phone, is_active, created_at) 
            VALUES (?, ?, ?, 'patient', ?, ?, ?, 1, NOW())");

        $hashedPassword = password_hash('123456', PASSWORD_BCRYPT); // Contraseña por defecto
        $stmtUser->execute([
            $_POST['nombre'],     // name
            $email,               // email
            $hashedPassword,      // password
            $_POST['tipo_documento'], // document_type
            $dni,                 // document_number
            $_POST['telefono']    // phone
        ]);

        $user_id = $conn->lastInsertId(); // <- Capturamos el ID del usuario

        // 2. Insertar en tabla patients, relacionado al user_id
        $sql = "INSERT INTO patients (
                    user_id, document_type, dni, first_name, email, phone, medical_history,
                    under_treatment, bleeding, allergy, hypertensive, diabetic, pregnant,
                    reason, diagnosis, observations, referred_by
                ) VALUES (
                    :user_id, :document_type, :dni, :first_name, :email, :phone, :medical_history,
                    :under_treatment, :bleeding, :allergy, :hypertensive, :diabetic, :pregnant,
                    :reason, :diagnosis, :observations, :referred_by
                )";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':document_type' => $_POST['tipo_documento'],
            ':dni' => $dni,
            ':first_name' => $_POST['nombre'],
            ':email' => $email,
            ':phone' => $_POST['telefono'],
            ':medical_history' => $_POST['historia_clinica'],
            ':under_treatment' => $_POST['bajo_tratamiento'],
            ':bleeding' => $_POST['hemorragia'],
            ':allergy' => $_POST['alergia'],
            ':hypertensive' => $_POST['hipertenso'],
            ':diabetic' => $_POST['diabetico'],
            ':pregnant' => $_POST['embarazada'],
            ':reason' => $_POST['motivo'],
            ':diagnosis' => $_POST['diagnostico'],
            ':observations' => $_POST['observaciones'],
            ':referred_by' => $_POST['referido_por'],
        ]);

        $_SESSION['msg'] = "✅ Paciente y usuario registrados correctamente.";
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['msg'] = "❌ Error al guardar: " . $e->getMessage();
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
