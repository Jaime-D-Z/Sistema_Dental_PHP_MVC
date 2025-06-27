<?php
require_once __DIR__ . '/../../config/Database.php';

class ClinicSetting
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    // Get clinic settings (assuming only one row exists)
    public function get()
    {
        $stmt = $this->pdo->query("SELECT * FROM clinic_settings LIMIT 1");
        return $stmt->fetch();
    }

    // Update clinic settings by ID
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE clinic_settings SET
            clinic_name = :clinic_name,
            address = :address,
            phone = :phone,
            email = :email,
            logo = :logo,
            opening_time = :opening_time,
            closing_time = :closing_time
            WHERE id = :id");

        return $stmt->execute([
            'clinic_name'   => $data['clinic_name'],
            'address'       => $data['address'],
            'phone'         => $data['phone'],
            'email'         => $data['email'],
            'logo'          => $data['logo'],
            'opening_time'  => $data['opening_time'],
            'closing_time'  => $data['closing_time'],
            'id'            => $id
        ]);
    }

    // Create new clinic settings
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO clinic_settings 
            (clinic_name, address, phone, email, logo, opening_time, closing_time)
            VALUES (:clinic_name, :address, :phone, :email, :logo, :opening_time, :closing_time)");

        return $stmt->execute([
            'clinic_name'   => $data['clinic_name'],
            'address'       => $data['address'],
            'phone'         => $data['phone'],
            'email'         => $data['email'],
            'logo'          => $data['logo'],
            'opening_time'  => $data['opening_time'],
            'closing_time'  => $data['closing_time']
        ]);
    }
}
