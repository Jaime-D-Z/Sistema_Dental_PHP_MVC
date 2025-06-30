<?php
    namespace App\Models;

    require_once __DIR__ . '/../../config/Database.php';

    use Database;
    use PDO;

    class Treatment {
       public static function all($search = '') {
    $db = Database::connect();
    $sql = "SELECT * FROM treatments WHERE is_deleted = 0 AND is_active = 1";

    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR description LIKE :search)";
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $db->prepare($sql);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


        public static function activos() {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT id, name FROM treatments WHERE is_deleted = 0 AND is_active = 1 ORDER BY name ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function store($data) {
            $db = Database::connect();
            $stmt = $db->prepare("INSERT INTO treatments (name, description, price, is_active, is_deleted) VALUES (:name, :description, :price, 1, 0)");
            return $stmt->execute($data);
        }

        public static function find($id) {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT * FROM treatments WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function update($id, $data) {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE treatments SET name = :name, description = :description, price = :price WHERE id = :id");
            $data['id'] = $id;
            return $stmt->execute($data);
        }

        public static function delete($id) {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE treatments SET is_deleted = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
