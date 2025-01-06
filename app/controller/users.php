<?php
require_once __DIR__ . '/../database/Database.php';

class User
{
    private $id_user;
    private $nom;
    private $prenom;
    private $username;
    private $email;
    private $telephone;
    private $mot_de_passe;
    private $adresse;
    private $pays;
    private $ville;
    private $code_postal;
    private $date_creation;
    private $date_modification;
    private $id_role_fk;

    public function __construct(
        $id_user,
        $nom,
        $prenom,
        $username,
        $email,
        $telephone,
        $mot_de_passe,
        $adresse,
        $pays,
        $ville,
        $code_postal,
        $id_role_fk
    ) {
        $this->id_user = $id_user;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->username = $username;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->mot_de_passe = $mot_de_passe;
        $this->adresse = $adresse;
        $this->pays = $pays;
        $this->ville = $ville;
        $this->code_postal = $code_postal;
        $this->id_role_fk = $id_role_fk;
    }

    // Create - Ajouter un nouvel utilisateur
    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO users (
            nom, prenom, username, email, telephone, 
            mot_de_passe, adresse, pays, ville, 
            code_postal, id_role_fk
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->nom,
            $this->prenom,
            $this->username,
            $this->email,
            $this->telephone,
            $this->mot_de_passe,
            $this->adresse,
            $this->pays,
            $this->ville,
            $this->code_postal,
            $this->id_role_fk
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    // Read - Obtenir tous les utilisateurs
    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM users";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read - Obtenir un utilisateur par son ID
    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM users WHERE id_user = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Read - Obtenir un utilisateur par email
    public static function getByEmail($email)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update - Mettre à jour un utilisateur
    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE users SET 
            nom = ?, prenom = ?, username = ?, 
            email = ?, telephone = ?, mot_de_passe = ?,
            adresse = ?, pays = ?, ville = ?,
            code_postal = ?, id_role_fk = ?
            WHERE id_user = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->prenom,
            $this->username,
            $this->email,
            $this->telephone,
            $this->mot_de_passe,
            $this->adresse,
            $this->pays,
            $this->ville,
            $this->code_postal,
            $this->id_role_fk,
            $this->id_user
        ]);
        $database->disconnect();
        return $result;
    }

    // Update - Mettre à jour le mot de passe d'un utilisateur
    public static function updatePassword($email, $hashed_password) {
        $database = new Database();
        $db = $database->connect();
        
        $sql = "UPDATE users SET mot_de_passe = ? WHERE email = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$hashed_password, $email]);
        
        $database->disconnect();
        return $result;
    }

    // Delete - Supprimer un utilisateur
    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        
        try {
            $db->beginTransaction();
            
            // Delete related reservations first
            $sql = "DELETE FROM reservations WHERE id_user_fk = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            
            // Then delete the user
            $sql = "DELETE FROM users WHERE id_user = ?";
            $stmt = $db->prepare($sql);
            $result = $stmt->execute([$id]);
            
            $db->commit();
            return $result;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception("Impossible de supprimer l'utilisateur : " . $e->getMessage());
        } finally {
            $database->disconnect();
        }
    }
}
