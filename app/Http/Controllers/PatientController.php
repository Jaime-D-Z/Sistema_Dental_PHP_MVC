<?php
require_once __DIR__ . '/../../models/Patient.php';

class PatientController {
    public function index() {
        $search = $_GET['search'] ?? '';
        $patients = Patient::all($search);
        return ['patients' => $patients, 'search' => $search];
    }
}
