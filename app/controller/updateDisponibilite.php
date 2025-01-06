<?php


class UpdateDisponibilite
{
    public static function update($disponibilite, $id_vehicule)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "UPDATE vehicules SET disponibilite = ? WHERE id_vehicule = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$disponibilite, $id_vehicule]);
        $database->disconnect();
    }

    public static function getLatestReservationForVehicule($id)
    {
        $database = new Database();
        $db = $database->connect();
        $sql = "SELECT * FROM reservations WHERE id_vehicule_fk = ? ORDER BY date_creation DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $database->disconnect();
        return $stmt;
    }


    public static function UpdateDisponibilite()
    {
        $reservations = Reservation::getall();
        foreach ($reservations as $reservation) {
            $latestReservationForVehicule = self::getLatestReservationForVehicule($reservation['id_vehicule_fk']);
            $vehicule = Vehicule::getById($reservation['id_vehicule_fk']);
            $statutReservation = $reservation['statut'];

            if ($statutReservation == "terminée" || $statutReservation == "annulée" || $statutReservation == "annulée" && $reservation === $latestReservationForVehicule) {
                self::update('Disponible', $reservation['id_vehicule_fk']);
            }
            
            if ($statutReservation == "en attente" || $statutReservation == "approuvée" && $reservation === $latestReservationForVehicule) {
                self::update('Réservé', $reservation['id_vehicule_fk']);
            }
        }
    }
}