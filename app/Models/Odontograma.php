<?php
require_once __DIR__ . '/../../config/Database.php';

class Odontograma
{
    public static function guardar($data)
    {
        $conn = Database::connect();

        $sql = "INSERT INTO odontograma (
                    appointment_id, paciente_id, pieza, zona,
                    tratamiento_id, observaciones,
                    accion, color_fondo,
                    simbolo, color_simbolo
                ) VALUES (
                    :appointment_id, :paciente_id, :pieza, :zona,
                    :tratamiento_id, :observaciones,
                    :accion, :color_fondo,
                    :simbolo, :color_simbolo
                )
                ON DUPLICATE KEY UPDATE
                    tratamiento_id = VALUES(tratamiento_id),
                    observaciones = VALUES(observaciones),
                    accion = VALUES(accion),
                    color_fondo = VALUES(color_fondo),
                    simbolo = VALUES(simbolo),
                    color_simbolo = VALUES(color_simbolo)";

        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':appointment_id'   => $data['appointment_id'],
            ':paciente_id'      => $data['paciente_id'],
            ':pieza'            => $data['pieza'],
            ':zona'             => $data['zona'],
            ':tratamiento_id'   => $data['tratamiento_id'],
            ':observaciones'    => $data['observaciones'],
            ':accion'           => $data['accion'],
            ':color_fondo'      => $data['color_fondo'],
            ':simbolo'          => $data['simbolo'],
            ':color_simbolo'    => $data['color_simbolo'],
        ]);
    }

    public static function obtenerPorPacienteYAppointment($paciente_id, $appointment_id)
    {
        $conn = Database::connect();

        $sql = "SELECT * FROM odontograma
                WHERE paciente_id = :paciente_id AND appointment_id = :appointment_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':paciente_id' => $paciente_id,
            ':appointment_id' => $appointment_id,
        ]);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resultadoFinal = [];

        foreach ($resultados as $fila) {
            $key = $fila['pieza'] . '_' . $fila['zona'];
            $resultadoFinal[$key] = $fila;
        }

        return $resultadoFinal;
    }
}
