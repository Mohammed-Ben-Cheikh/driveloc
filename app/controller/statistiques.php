<?php
require_once __DIR__ . '/../database/Database.php';

class Statistique
{
    private $id_statistique;
    private $total_clients;
    private $total_categories;
    private $total_vehicules;
    private $total_reservations;
    private $reservations_en_attente;
    private $reservations_approuvees;
    private $reservations_refusees;
    private $reservations_terminee;
    private $date_mise_a_jour;

    public function __construct(
        $id_statistique = null,
        $total_clients = 0,
        $total_categories = 0,
        $total_vehicules = 0,
        $total_reservations = 0,
        $reservations_en_attente = 0,
        $reservations_approuvees = 0,
        $reservations_refusees = 0,
        $reservations_terminee = 0
    ) {
        $this->id_statistique = $id_statistique;
        $this->total_clients = $total_clients;
        $this->total_categories = $total_categories;
        $this->total_vehicules = $total_vehicules;
        $this->total_reservations = $total_reservations;
        $this->reservations_en_attente = $reservations_en_attente;
        $this->reservations_approuvees = $reservations_approuvees;
        $this->reservations_refusees = $reservations_refusees;
        $this->reservations_terminee = $reservations_terminee;
    }

    public function create()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO statistiques (
            total_clients, total_categories, total_vehicules,
            total_reservations, reservations_en_attente,
            reservations_approuvees, reservations_refusees,
            reservations_terminee
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $this->total_clients,
            $this->total_categories,
            $this->total_vehicules,
            $this->total_reservations,
            $this->reservations_en_attente,
            $this->reservations_approuvees,
            $this->reservations_refusees,
            $this->reservations_terminee
        ]);
        $database->disconnect();
        return $db->lastInsertId();
    }

    public static function getAll()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM statistiques";
        $stmt = $db->query($sql);
        $database->disconnect();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM statistiques WHERE id_statistique = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE statistiques SET 
            total_clients = ?, 
            total_categories = ?,
            total_vehicules = ?,
            total_reservations = ?,
            reservations_en_attente = ?,
            reservations_approuvees = ?,
            reservations_refusees = ?,
            reservations_terminee = ?
            WHERE id_statistique = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $this->total_clients,
            $this->total_categories,
            $this->total_vehicules,
            $this->total_reservations,
            $this->reservations_en_attente,
            $this->reservations_approuvees,
            $this->reservations_refusees,
            $this->reservations_terminee,
            $this->id_statistique
        ]);
        $database->disconnect();
        return $result;
    }

    public static function delete($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "DELETE FROM statistiques WHERE id_statistique = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$id]);
        $database->disconnect();
        return $result;
    }
}