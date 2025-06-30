<?php
require_once __DIR__ . '/../../Models/AppointmentHistory.php';

class AppointmentHistoryController {
    public function index() {
        $search = trim($_GET['search'] ?? '');
        $historiales = [];

        if (strlen($search) >= 2) {
            $historiales = AppointmentHistory::all($search);
        } elseif ($search === '') {
            $historiales = AppointmentHistory::all(); // sin filtro
        }

        return [
            'search' => $search,
            'historiales' => $historiales
        ];
    }
}
