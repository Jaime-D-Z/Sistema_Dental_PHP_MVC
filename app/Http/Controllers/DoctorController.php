<?php
require_once __DIR__ . '/../../Models/Doctor.php';

class DoctorController
{
    public function index()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $search = trim($_GET['search'] ?? '');
    $success = $_SESSION['success'] ?? null;
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['success'], $_SESSION['errors']);

    if ($search === '') {
        $doctors = Doctor::all(); // sin filtro
    } elseif (strlen($search) < 2) {
        $doctors = []; // búsqueda inválida, no mostrar nada
    } else {
        $doctors = Doctor::all($search); // búsqueda válida
    }

    return [
        'doctors' => $doctors,
        'specialties' => Doctor::specialties(),
        'search' => $search,
        'success' => $success,
        'errors' => $errors
    ];
}

}
