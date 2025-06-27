<?php
session_start();
require_once(__DIR__ . '/../../../config/Database.php');
$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE patients SET 
                    document_type = :document_type,
                    dni = :dni,
                    first_name = :first_name,
                    email = :email,
                    phone = :phone,
                    medical_history = :medical_history,
                    under_treatment = :under_treatment,
                    bleeding = :bleeding,
                    allergy = :allergy,
                    hypertensive = :hypertensive,
                    diabetic = :diabetic,
                    pregnant = :pregnant,
                    reason = :reason,
                    diagnosis = :diagnosis,
                    observations = :observations,
                    referred_by = :referred_by
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':document_type' => $_POST['document_type'],
            ':dni' => $_POST['dni'],
            ':first_name' => $_POST['first_name'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':medical_history' => $_POST['medical_history'],
            ':under_treatment' => $_POST['under_treatment'],
            ':bleeding' => $_POST['bleeding'],
            ':allergy' => $_POST['allergy'],
            ':hypertensive' => $_POST['hypertensive'],
            ':diabetic' => $_POST['diabetic'],
            ':pregnant' => $_POST['pregnant'],
            ':reason' => $_POST['reason'],
            ':diagnosis' => $_POST['diagnosis'],
            ':observations' => $_POST['observations'],
            ':referred_by' => $_POST['referred_by'],
            ':id' => $_POST['id']
        ]);

        $_SESSION['msg'] = "Paciente actualizado correctamente.";
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Error al actualizar paciente: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
