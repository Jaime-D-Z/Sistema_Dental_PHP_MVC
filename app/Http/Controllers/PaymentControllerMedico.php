<?php
require_once __DIR__ . '/../../Models/Payment.php';

class PaymentControllerMedico
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header("Location: /resources/views/auth/login.php");
            exit;
        }

        $doctor_id = $_SESSION['user']['id'];
        $search = $_GET['search'] ?? '';

        $payments = Payment::allByDoctor($doctor_id, $search);

        return [
            'search' => $search,
            'payments' => $payments
        ];
    }
}
