<?php
require_once __DIR__ . '/../database/Database.php';

class Review
{
    private $id_user_fk;
    private $id_vehicule_fk;
    private $rating;
    private $comment;

    public function __construct(
        $id_user_fk,
        $id_vehicule_fk,
        $comment,
        $rating,
    ) {
        $this->id_user_fk = $id_user_fk;
        $this->id_vehicule_fk = $id_vehicule_fk;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO reviews (id_user_fk, id_vehicule_fk, rating, comment) 
                VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->id_user_fk,
            $this->id_vehicule_fk,
            $this->rating,
            $this->comment
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM reviews";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM reviews WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByVehicle($vehicleId)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT r.*, u.username FROM reviews r 
                JOIN users u ON r.id_user_fk = u.id_user 
                WHERE r.id_vehicule_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$vehicleId]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByUser($userId)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT r.*, v.nom as vehicle_name FROM reviews r 
                JOIN vehicules v ON r.id_vehicule_fk = v.id_vehicule 
                WHERE r.id_user_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE reviews SET rating = ?, comment = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->rating,
            $this->comment,
            $id
        ]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }

    public static function updateStatut($id, $statut)
    {

        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE reviews SET statut = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$statut, $id]);
        $database->disconnect();
        return $result;
    }
}



