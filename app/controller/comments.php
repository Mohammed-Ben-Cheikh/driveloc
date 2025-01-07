<?php
require_once __DIR__ . '/../database/Database.php';

class Comment
{
    private $id_user_fk;
    private $id_article_fk;
    private $comment;

    public function __construct(
        $id_user_fk,
        $id_article_fk,
        $comment,
    ) {
        $this->id_user_fk = $id_user_fk;
        $this->id_article_fk = $id_article_fk;
        $this->comment = $comment;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO comments (id_user_fk, id_article_fk,comment) 
                VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->id_user_fk,
            $this->id_article_fk,
            $this->comment
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM comments";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM comments WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByVehicle($articleId)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT r.*, u.username FROM comments r 
                JOIN users u ON r.id_user_fk = u.id_user 
                WHERE r.id_article_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$articleId]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByUser($userId)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT r.*, v.nom as vehicle_name FROM comments r 
                JOIN articles v ON r.id_article_fk = v.id_article 
                WHERE r.id_user_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM comments WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }

    public static function updateStatut($id, $statut)
    {

        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE comments SET statut = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$statut, $id]);
        $database->disconnect();
        return $result;
    }
}



