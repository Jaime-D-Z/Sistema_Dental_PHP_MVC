<?php
require_once __DIR__ . '/../../models/Payment.php';

class PaymentController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $search = trim($_GET['search'] ?? '');
        $payments = [];

        if ($search === '') {
            $payments = Payment::all(); // sin filtro
        } elseif (strlen($search) >= 2) {
            $payments = Payment::all($search); // búsqueda válida
        }

        return [
            'payments' => $payments,
            'search' => $search
        ];
    }
}
