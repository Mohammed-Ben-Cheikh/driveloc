<?php
require_once __DIR__ . '/../database/Database.php';

class Admin
{
    private $id_admin;
    private $nom;
    private $prenom;
    private $username;
    private $email;
    private $telephone;
    private $mot_de_passe;
    private $id_role_fk;

    public function __construct(
        $id_role_fk,
        $nom,
        $prenom,
        $username,
        $email,
        $telephone,
        $mot_de_passe,
        $id_admin = null  // Paramètre optionnel déplacé à la fin
    ) {
        $this->id_admin = $id_admin;
        $this->id_role_fk = $id_role_fk;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->username = $username;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->mot_de_passe = $mot_de_passe;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO admins (
            nom, prenom, username, email,
            telephone, mot_de_passe, id_role_fk
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->nom,
            $this->prenom,
            $this->username,
            $this->email,
            $this->telephone,
            $this->mot_de_passe,
            $this->id_role_fk
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT a.*, r.role as role_nom 
                FROM admins a 
                JOIN roles r ON a.id_role_fk = r.id_role";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT a.*, r.role as role_nom 
                FROM admins a 
                JOIN roles r ON a.id_role_fk = r.id_role 
                WHERE a.id_admin = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByEmail($email)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUsername($username)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE admins SET 
            nom = ?, prenom = ?, username = ?,
            email = ?, telephone = ?, mot_de_passe = ?,
            id_role_fk = ? WHERE id_admin = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->prenom,
            $this->username,
            $this->email,
            $this->telephone,
            $this->mot_de_passe,
            $this->id_role_fk,
            $this->id_admin
        ]);
        $database->disconnect();
        return $result;
    }

    public static function updatePassword($email, $hashed_password) {
        $database = new Database();
        $db = $database->connect();
        
        $sql = "UPDATE admins SET mot_de_passe = ? WHERE email = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$hashed_password, $email]);
        
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM admins WHERE id_admin = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }
}



