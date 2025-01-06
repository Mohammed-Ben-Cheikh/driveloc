<?php
require_once __DIR__ . '/../database/Database.php';

class Categorie
{
    private $id_categorie;
    private $nom;
    private $description;
    private $image_url;
    private $created_at;
    private $updated_at;

    public function __construct(
        $nom,
        $description = null,
        $image_url = null,
        $id_categorie = null
    ) {
        $this->nom = $nom;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->id_categorie = $id_categorie;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO categories (nom, description, image_url) 
                VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->nom,
            $this->description,
            $this->image_url
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM categories";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM categories WHERE id_categorie = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE categories SET nom = ?, description = ?, 
                image_url = ? WHERE id_categorie = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->description,
            $this->image_url,
            $this->id_categorie
        ]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM categories WHERE id_categorie = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }

    public static function getMostPopularCategory()
    {
        $database = new Database();
        $db = $database->connect();
        $query = "SELECT c.*, COUNT(v.id_vehicule) as vehicle_count 
                    FROM categories c 
                    LEFT JOIN vehicules v ON c.id_categorie = v.id_categorie_fk 
                    GROUP BY c.id_categorie 
                    ORDER BY vehicle_count DESC 
                    LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function NbCategories()
    {
        $database = new Database();
        $db = $database->connect();
        $query = "SELECT count(*) AS total FROM categories";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public static function GetCategories($lignes, $page = 1)
    {
        $database = new Database();
        $db = $database->connect();
        $offset = ($page - 1) * $lignes;
        $query = $db->prepare("SELECT * FROM categories LIMIT :offset, :limit");
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->bindParam(':limit', $lignes, PDO::PARAM_INT);
        $query->execute();
        $database->disconnect();
        return $query->fetchAll();
    }
}
