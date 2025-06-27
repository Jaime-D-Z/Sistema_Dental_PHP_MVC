<?php
require_once __DIR__ . '/../../Models/AppointmentHistory.php';

class AppointmentHistoryController {
    public function index() {
        $search = $_GET['search'] ?? '';
        $historiales = AppointmentHistory::all($search);
        return [
            'search' => $search,
            'historiales' => $historiales
        ];
    }
}
