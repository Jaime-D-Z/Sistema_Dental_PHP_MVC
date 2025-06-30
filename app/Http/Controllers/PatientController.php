<?php
require_once __DIR__ . '/../../models/Patient.php';

class PatientController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $search = trim($_GET['search'] ?? '');
        $success = $_SESSION['success'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['success'], $_SESSION['errors']);

        // Exactamente igual que en doctores
        if ($search === '') {
            $patients = Patient::all();
        } elseif (strlen($search) < 2) {
            $patients = [];
        } else {
            $patients = Patient::all($search);
        }

        return [
            'patients' => $patients,
            'search'   => $search,
            'success'  => $success,
            'errors'   => $errors
        ];
    }
}
