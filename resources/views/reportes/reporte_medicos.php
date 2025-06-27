<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/../../../config/Database.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$conn = Database::connect();

// Consulta actualizada solo con doctores activos y no eliminados
$stmt = $conn->query("
    SELECT d.first_name, d.last_name, d.dni, d.address, d.email, d.phone, s.name AS specialty
    FROM doctors d
    JOIN specialties s ON d.specialty_id = s.id
    WHERE d.is_active = 1 AND d.is_deleted = 0
    ORDER BY d.id DESC
");

$doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construcción del HTML
$html = '
<h2 style="text-align:center;">Reporte de Doctores - Clínica Dental</h2>
<table border="1" cellspacing="0" cellpadding="6" width="100%" style="border-collapse: collapse; font-family: Arial; font-size: 12px;">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th>#</th>
            <th>Nombre Completo</th>
            <th>DNI</th>
            <th>Especialidad</th>
            <th>Dirección</th>
            <th>Correo</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>';

foreach ($doctores as $i => $d) {
    $nombreCompleto = htmlspecialchars(trim($d['first_name'] . ' ' . $d['last_name']));
    $html .= "<tr>
        <td>" . ($i + 1) . "</td>
        <td>{$nombreCompleto}</td>
        <td>" . htmlspecialchars($d['dni']) . "</td>
        <td>" . htmlspecialchars($d['specialty']) . "</td>
        <td>" . htmlspecialchars($d['address']) . "</td>
        <td>" . htmlspecialchars($d['email']) . "</td>
        <td>" . htmlspecialchars($d['phone']) . "</td>
    </tr>";
}

$html .= '</tbody></table>';

// Crear el PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // horizontal
$dompdf->render();
$dompdf->stream("reporte_doctores.pdf", ["Attachment" => false]);
exit;
