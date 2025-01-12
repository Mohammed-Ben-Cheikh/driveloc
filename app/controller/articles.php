<?php
require_once __DIR__ . '/../database/Database.php';

class Article
{
    private $id_user_fk;
    private $titre;
    private $description;
    private $contenu;
    private $image_url;
    private $id_theme_fk;

    public function __construct(
        $id_user_fk,
        $titre,
        $description,
        $contenu,
        $image_url,
        $id_theme_fk
    ) {
        $this->id_user_fk = $id_user_fk;
        $this->titre = $titre;
        $this->description = $description;
        $this->contenu = $contenu;
        $this->image_url = $image_url;
        $this->id_theme_fk = $id_theme_fk;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO articles (
            id_user_fk,
            titre,
            contenu,
            image_url,
            id_theme_fk,description
        ) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->id_user_fk,
            $this->titre,
            $this->contenu,
            $this->image_url,
            $this->id_theme_fk,
            $this->description
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM articles";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByTheme($id_theme_fk)
    {
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM articles WHERE id_theme_fk = ?");
        $stmt->execute([$id_theme_fk]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $database->disconnect();
        return $result['total'];
    }

    public static function getById($id_article)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = 'SELECT * FROM articles WHERE id_article = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_article]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByTheme($id_theme_fk)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT a.*, t.nom as theme_nom 
                FROM articles a 
                LEFT JOIN themes t ON a.id_theme_fk = t.id_theme 
                WHERE a.id_theme_fk = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_theme_fk]);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id_article)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE articles SET 
            id_user_fk = ?,
            titre = ?,
            description = ?,
            contenu = ?,
            image_url = ?,
            id_theme_fk = ?
            WHERE id_article = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->id_user_fk,
            $this->titre,
            $this->description,
            $this->contenu,
            $this->image_url,
            $this->id_theme_fk,
            $id_article,
        ]);
        $database->disconnect();
        return $result;
    }

    public static function updateStatut($id, $statut)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE articles SET statut = ? WHERE id_article = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$statut, $id]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id_article)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM articles WHERE id_article = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id_article]);
        $database->disconnect();
        return $result;
    }
}