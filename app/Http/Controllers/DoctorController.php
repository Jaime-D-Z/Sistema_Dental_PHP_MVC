<?php
require_once __DIR__ . '/../../Models/Doctor.php';

class DoctorController
{
    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $success = $_SESSION['success'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors']);

        return [
            'doctors' => Doctor::all($search),
            'specialties' => Doctor::specialties(),
            'search' => $search,
            'success' => $success,
            'errors' => $errors
        ];
    }
}
