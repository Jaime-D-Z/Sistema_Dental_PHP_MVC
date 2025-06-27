<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../../config/Database.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$conn = Database::connect();

// Solo pacientes activos y no eliminados
$stmt = $conn->query("SELECT first_name, last_name, dni, phone, email 
                      FROM patients 
                      WHERE is_active = 1 AND is_deleted = 0 
                      ORDER BY id DESC");

$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construir el HTML
$html = '
<h2 style="text-align:center;">Reporte de Pacientes - Clínica Dental</h2>
<br>
<table border="1" cellspacing="0" cellpadding="5" width="100%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th>#</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Correo</th>
        </tr>
    </thead>
    <tbody>';

foreach ($pacientes as $i => $p) {
    $nombreCompleto = trim($p['first_name'] . ' ' . $p['last_name']);
    $html .= "<tr>
        <td>" . ($i + 1) . "</td>
        <td>" . htmlspecialchars($nombreCompleto) . "</td>
        <td>" . htmlspecialchars($p['dni']) . "</td>
        <td>" . htmlspecialchars($p['phone']) . "</td>
        <td>" . htmlspecialchars($p['email']) . "</td>
    </tr>";
}

$html .= '</tbody></table>';

// Configurar y generar PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_pacientes.pdf", ["Attachment" => false]);
exit;
