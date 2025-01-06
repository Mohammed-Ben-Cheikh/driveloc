<?php
require_once __DIR__ . '/../database/Database.php';
class Vehicule
{
    private $id_vehicule;
    private $nom;
    private $prix_a_loue;
    private $description;
    private $id_categorie_fk;
    private $matriculation;
    private $marque;
    private $modele;
    private $annee;
    private $climatisation;
    private $type_vitesse;
    private $nb_vitesses;
    private $toit_panoramique;
    private $kilometrage;
    private $carburant;
    private $nombre_de_places;
    private $nombre_de_portes;
    private $disponibilite;
    private $image_url;

    public function __construct(
        $id_vehicule,
        $nom,
        $prix_a_loue,
        $description,
        $id_categorie_fk,
        $matriculation,
        $marque,
        $modele,
        $annee,
        $climatisation,
        $type_vitesse,
        $nb_vitesses,
        $toit_panoramique,
        $kilometrage,
        $carburant,
        $nombre_de_places,
        $nombre_de_portes,
        $disponibilite,
        $image_url,
    ) {
        $this->id_vehicule = $id_vehicule;
        $this->nom = $nom;
        $this->prix_a_loue = $prix_a_loue;
        $this->description = $description;
        $this->id_categorie_fk = $id_categorie_fk;
        $this->matriculation = $matriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->annee = $annee;
        $this->climatisation = $climatisation;
        $this->type_vitesse = $type_vitesse;
        $this->nb_vitesses = $nb_vitesses;
        $this->toit_panoramique = $toit_panoramique;
        $this->kilometrage = $kilometrage;
        $this->carburant = $carburant;
        $this->nombre_de_places = $nombre_de_places;
        $this->nombre_de_portes = $nombre_de_portes;
        $this->disponibilite = $disponibilite;
        $this->image_url = $image_url;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO vehicules (
        nom,
        prix_a_loue,
        description,
        id_categorie_fk,
        matriculation,
        marque,
        modele,
        annee,
        climatisation,
        type_vitesse,
        nb_vitesses,
        toit_panoramique,
        kilometrage,
        carburant,
        nombre_de_places,
        nombre_de_portes,
        disponibilite,
        image_url) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->nom,
            $this->prix_a_loue,
            $this->description,
            $this->id_categorie_fk,
            $this->matriculation,
            $this->marque,
            $this->modele,
            $this->annee,
            $this->climatisation,
            $this->type_vitesse,
            $this->nb_vitesses,
            $this->toit_panoramique,
            $this->kilometrage,
            $this->carburant,
            $this->nombre_de_places,
            $this->nombre_de_portes,
            $this->disponibilite,
            $this->image_url,
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM vehicules";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByCategory($id)
    {
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM vehicules WHERE id_categorie_fk = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $database->disconnect();
        return $result['total'];
    }

    public static function getBy($getBy, $id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = 'SELECT * FROM vehicules WHERE ' . $getBy . ' = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = 'SELECT * FROM vehicules WHERE id_vehicule  = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByCategorie($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT v.*, c.nom as categorie_nom 
                FROM vehicules v 
                LEFT JOIN categories c ON v.id_categorie_fk = c.id_categorie 
                WHERE v.id_vehicule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE vehicules SET 
        nom = ?,
        prix_a_loue = ?,
        description = ?,
        id_categorie_fk = ?,
        matriculation = ?,
        marque = ?,
        modele = ?,
        annee = ?,
        climatisation = ?,
        type_vitesse = ?,
        nb_vitesses = ?,
        toit_panoramique = ?,
        kilometrage = ?,
        carburant = ?,
        nombre_de_places = ?,
        nombre_de_portes = ?,
        disponibilite = ?,
        image_url = ? WHERE id_vehicule = $id";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->nom,
            $this->prix_a_loue,
            $this->description,
            $this->id_categorie_fk,
            $this->matriculation,
            $this->marque,
            $this->modele,
            $this->annee,
            $this->climatisation,
            $this->type_vitesse,
            $this->nb_vitesses,
            $this->toit_panoramique,
            $this->kilometrage,
            $this->carburant,
            $this->nombre_de_places,
            $this->nombre_de_portes,
            $this->disponibilite,
            $this->image_url,
        ]);
        $database->disconnect();
        return $result;
    }

    // Delete - Supprimer un pays
    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM vehicules WHERE id_vehicule = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }

    public static function isAvailable($id) {
        $database = new Database();
        $db = $database->connect();
        
        $sql = "SELECT disponibilite FROM vehicules WHERE id_vehicule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $database->disconnect();
        return $result && $result['disponibilite'] === 'Disponible';
    }
}



