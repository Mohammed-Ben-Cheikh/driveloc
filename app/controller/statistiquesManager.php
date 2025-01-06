<?php
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/statistiques.php';

class StatistiquesManager 
{
    private static $queries = [
        'total_clients' => "SELECT COUNT(*) FROM users WHERE id_role_fk = 3",
        'total_categories' => "SELECT COUNT(*) FROM categories",
        'total_vehicules' => "SELECT COUNT(*) FROM vehicules",
        'total_reservations' => "SELECT COUNT(*) FROM reservations",
        'reservations_en_attente' => "SELECT COUNT(*) FROM reservations WHERE statut = 'en attente'",
        'reservations_approuvees' => "SELECT COUNT(*) FROM reservations WHERE statut = 'approuvée'",
        'reservations_refusees' => "SELECT COUNT(*) FROM reservations WHERE statut = 'refusée'",
        'reservations_terminee' => "SELECT COUNT(*) FROM reservations WHERE statut = 'terminée'"
    ];

    private static function calculateStats($db)
    {
        $values = [];
        foreach (self::$queries as $key => $query) {
            $stmt = $db->query($query);
            $values[$key] = $stmt->fetchColumn();
        }
        return $values;
    }

    private static function checkExistingStats($db)
    {
        $stmt = $db->query("SELECT id_statistique FROM statistiques LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function calculerEtMettreAJour()
    {
        $database = new Database();
        $db = $database->connect();

        try {
            $values = self::calculateStats($db);
            $existing = self::checkExistingStats($db);

            $statistique = new Statistique(
                $existing ? $existing['id_statistique'] : null,
                $values['total_clients'],
                $values['total_categories'],
                $values['total_vehicules'],
                $values['total_reservations'],
                $values['reservations_en_attente'],
                $values['reservations_approuvees'],
                $values['reservations_refusees'],
                $values['reservations_terminee']
            );

            return $existing ? $statistique->update() : $statistique->create();

        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour des statistiques: " . $e->getMessage());
            return false;
        } finally {
            $database->disconnect();
        }
    }

    public static function getLatestStats()
    {
        $database = new Database();
        $db = $database->connect();
        
        try {
            $sql = "SELECT * FROM statistiques ORDER BY date_mise_a_jour DESC LIMIT 1";
            $stmt = $db->query($sql);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } finally {
            $database->disconnect();
        }
    }

    public static function getDashboardStats()
    {
        $latestStats = self::getLatestStats();
        if (!$latestStats) {
            self::calculerEtMettreAJour();
            $latestStats = self::getLatestStats();
        }
        return $latestStats;
    }

    public static function getVehicleStats()
    {
        $database = new Database();
        $db = $database->connect();

        $stats = [];

        // Total vehicles
        $query = "SELECT COUNT(*) as total_vehicules FROM vehicules";
        $stmt = $db->query($query);
        $stats['total_vehicules'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_vehicules'];

        // Available vehicles
        $query = "SELECT COUNT(*) as vehicules_disponibles FROM vehicules WHERE disponibilite = 'Disponible'";
        $stmt = $db->query($query);
        $stats['vehicules_disponibles'] = $stmt->fetch(PDO::FETCH_ASSOC)['vehicules_disponibles'];

        // Unavailable vehicles
        $query = "SELECT COUNT(*) as vehicules_indisponibles FROM vehicules WHERE disponibilite = 'Indisponible'";
        $stmt = $db->query($query);
        $stats['vehicules_indisponibles'] = $stmt->fetch(PDO::FETCH_ASSOC)['vehicules_indisponibles'];

        // Reserved vehicles
        $query = "SELECT COUNT(*) as vehicules_reserves FROM vehicules WHERE disponibilite = 'Réservé'";
        $stmt = $db->query($query);
        $stats['vehicules_reserves'] = $stmt->fetch(PDO::FETCH_ASSOC)['vehicules_reserves'];

        $database->disconnect();
        return $stats;
    }

    public static function getRStats()
    {
        $database = new Database();
        $db = $database->connect();

        $stats = [];

        $query = "SELECT COUNT(*) as total_r FROM reviews";
        $stmt = $db->query($query);
        $stats['total_r'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_r'];

        $query = "SELECT SUM(rating) as total_r FROM reviews";
        $stmt = $db->query($query);
        $stats['total_rating'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_r'];

        $query = "SELECT COUNT(*) as action_r FROM reviews WHERE statut = 'en attente'";
        $stmt = $db->query($query);
        $stats['action_r'] = $stmt->fetch(PDO::FETCH_ASSOC)['action_r'];

        $database->disconnect();
        return $stats;
    }
}
