<?php
require_once __DIR__ . '/../../Models/Payment.php';

class PaymentController
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $payments = Payment::all($search);
        return [
            'search' => $search,
            'payments' => $payments
        ];
    }
}
