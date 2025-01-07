<?php
require_once __DIR__ . '/../database/Database.php';

class Theme
{
    private $id_theme;
    private $nom;
    private $description;
    private $image_url;

    public function __construct(
        $nom,
        $description = null,
        $image_url = null,
        $id_theme = null
    ) {
        $this->nom = $nom;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->id_theme = $id_theme;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO themes (nom, description, image_url) 
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
        $sql = "SELECT * FROM themes";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM themes WHERE id_theme = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE themes SET nom = ?, description = ?, 
                image_url = ? WHERE id_theme = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->description,
            $this->image_url,
            $this->id_theme
        ]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM themes WHERE id_theme = ?";
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
                    FROM themes c 
                    LEFT JOIN vehicules v ON c.id_theme = v.id_theme_fk 
                    GROUP BY c.id_theme 
                    ORDER BY vehicle_count DESC 
                    LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function Nbthemes()
    {
        $database = new Database();
        $db = $database->connect();
        $query = $db->query("SELECT COUNT(*) as total FROM themes");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $database->disconnect();
        return (int)$result['total'];
    }

    public static function Getthemes($lignes, $page = 1)
    {
        $database = new Database();
        $db = $database->connect();
    
        $offset = ($page - 1) * $lignes;
    
        $query = $db->prepare("SELECT * FROM themes LIMIT :limit OFFSET :offset");
        
        $query->bindParam(':limit', $lignes, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    
        $query->execute();
    
        $database->disconnect();
    
        return $query->fetchAll();
    }
}
