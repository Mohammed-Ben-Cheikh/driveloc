<?php
session_start();
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id']) {
    header("Location: ../../dashboard");
    exit();
} else if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: ../../index.php");
    exit();
}
require_once "../../app/controller/vehicules.php";
require_once "../../app/controller/reservations.php";
require_once "../../app/controller/statistiquesManager.php";

$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user_fk = $_SESSION['user_id'];
    $id_vehicule_fk = $_GET['id']; // Récupéré depuis les paramètres d'URL
    $date_reservation = $_POST['date_reservation'];
    $pickup_location = $_POST['pickup_location'];
    $date_limite = $_POST['date_limite'];
    $commentaire = $_POST['commentaire'] ?? null;

    $reservation = new Reservation(
        $id_user_fk,
        $id_vehicule_fk,
        $date_reservation,
        $pickup_location,
        $date_limite,
        'en attente', // Statut initial
        $commentaire,
        'no' // Pas encore modifié
    );

    $result = $reservation->ajouterReservationAvecProcedure();
    // Mettre à jour les statistiques après l'inscription
    StatistiquesManager::calculerEtMettreAJour();
    if ($result) {
        $success = $result['message'];
        header('Location: reservation.php?makayanWalo');
    } else {
        $error = "Erreur : " . $result['message'];
        header('Location: reservation.php?kayankhata2');
    }
}


$id = isset($_GET['id']) ? $_GET['id'] : null;
$vehicule = Vehicule::getById($id);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un véhicule de luxe | Drive-Loc</title>
    <script src="http://localhost/Drive-Loc-/tailwindcss.js"></script>
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 flex-col p-4">
    <main class="max-w-7xl mx-auto p-4">
        <?php if ($vehicule): ?>
            <!-- Vehicle Showcase Section -->
            <div class="mb-8 animate__animated animate__fadeIn">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-blue-800 shadow-2xl">
                    <div class="absolute inset-0 bg-black/40 z-10"></div>
                    <img src="<?php echo $vehicule['image_url']; ?>"
                        alt="<?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?>"
                        class="w-full h-[400px] object-cover object-center transform hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/80 to-transparent z-20">
                        <h1 class="text-4xl font-bold text-white mb-2">
                            <?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?>
                        </h1>
                        <div class="flex items-center gap-6 text-white/90">
                            <p class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" />
                                </svg>
                                Année: <?php echo $vehicule['annee']; ?>
                            </p>
                            <p class="flex items-center gap-2 text-2xl font-bold text-blue-400">
                                <?php echo $vehicule['prix_a_loue']; ?> €<span class="text-sm font-normal">/jour</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservation Form -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl animate__animated animate__fadeInUp">
                <h2 class="text-3xl font-bold text-white mb-6">Réserver maintenant</h2>
                <form method="POST" class="space-y-6" id="reservationForm">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-blue-300 mb-2">
                                Date de réservation
                            </label>
                            <input type="date" name="date_reservation" required
                                class="w-full px-4 py-3 rounded-lg border-0 bg-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all"
                                min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-blue-300 mb-2">
                                Date limite
                            </label>
                            <input type="date" name="date_limite" required
                                class="w-full px-4 py-3 rounded-lg border-0 bg-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all"
                                min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-300 mb-2">
                            Lieu de prise en charge
                        </label>
                        <input type="text" name="pickup_location" required
                            class="w-full px-4 py-3 rounded-lg border-0 bg-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="Entrez le lieu de prise en charge">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-blue-300 mb-2">
                            Commentaire
                        </label>
                        <textarea name="commentaire" rows="4"
                            class="w-full px-4 py-3 rounded-lg border-0 bg-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all"
                            placeholder="Vos préférences particulières..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-blue-500/50">
                        Réserver maintenant
                        <span class="ml-2">→</span>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Add smooth scrolling and form validation
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('reservationForm');
            form.addEventListener('submit', function (e) {
                const pickupDate = new Date(form.date_reservation.value);
                const dropoffDate = new Date(form.date_limite.value);

                if (dropoffDate <= pickupDate) {
                    e.preventDefault();
                    alert('La date limite doit être postérieure à la date de réservation');
                }
            });
        });
    </script>
</body>

</html>