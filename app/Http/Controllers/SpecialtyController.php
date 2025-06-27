<?php
require_once __DIR__ . '/../../Models/Specialty.php';

class SpecialtyController {
    public function index() {
        $search = $_GET['search'] ?? '';
        $specialties = Specialty::all($search);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $success = $_SESSION['success'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors']);

        return [
            'specialties' => $specialties,
            'search' => $search,
            'success' => $success,
            'errors' => $errors
        ];
    }
}
