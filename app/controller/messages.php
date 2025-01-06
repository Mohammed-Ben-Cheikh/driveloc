<?php
require_once __DIR__ . '/../database/Database.php';

class Message
{
    private $id_message;
    private $nom;
    private $email;
    private $comment;
    private $created_at;

    public function __construct(
        $id_message = null,
        $nom,
        $email,
        $comment = null
    ) {
        $this->id_message = $id_message;
        $this->nom = $nom;
        $this->email = $email;
        $this->comment = $comment;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO messages (nom, email, comment) 
                VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->nom,
            $this->email,
            $this->comment
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM messages ORDER BY created_at DESC";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM messages WHERE id_message = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByEmail($email)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM messages WHERE email = ? ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE messages SET nom = ?, email = ?, comment = ? 
                WHERE id_message = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->email,
            $this->comment,
            $this->id_message
        ]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM messages WHERE id_message = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }
}
