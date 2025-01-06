<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
    exit();
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../app/controller/reservations.php';
require_once __DIR__ . '/../../app/controller/users.php';
require_once __DIR__ . '/../../app/controller/vehicules.php';
require_once '../../app/controller/statistiquesManager.php';
require_once '../../app/controller/updateDisponibilite.php';

$stats = StatistiquesManager::getDashboardStats();
// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_reservation = $_POST['id_reservation'] ?? '';
    UpdateDisponibilite::UpdateDisponibilite();

    switch ($action) {
        case 'approve':
            // Mettre à jour les statistiques après l'inscription
            StatistiquesManager::calculerEtMettreAJour();
            
            Reservation::updateStatut($id_reservation, 'approuvée');
            break;
        case 'reject':
            // Mettre à jour les statistiques après l'inscription
            StatistiquesManager::calculerEtMettreAJour();
            
            Reservation::updateStatut($id_reservation, 'refusée');
            break;
        case 'complete':
            // Mettre à jour les statistiques après l'inscription
            StatistiquesManager::calculerEtMettreAJour();
            
            Reservation::updateStatut($id_reservation, 'terminée');
            break;
        case 'cancel':
            StatistiquesManager::calculerEtMettreAJour();
            
            Reservation::updateStatut($id_reservation, 'annuler');
            break;
    }
}

// Récupération des réservations avec filtres
$status_filter = $_GET['status'] ?? '';
$date_filter = $_GET['date'] ?? '';
$search = $_GET['search'] ?? '';

// Filtres
$reservations = Reservation::getAll();

if ($status_filter) {
    $reservations = array_filter($reservations, function ($r) use ($status_filter) {
        return $r['statut'] === $status_filter;
    });
}

if ($date_filter) {
    $reservations = array_filter($reservations, function ($r) use ($date_filter) {
        return date('Y-m-d', strtotime($r['date_reservation'])) === $date_filter;
    });
}

if ($search) {
    $reservations = array_filter($reservations, function ($r) use ($search) {
        return stripos($r['username'], $search) !== false ||
            stripos($r['vehicule_nom'], $search) !== false ||
            stripos($r['lieux'], $search) !== false;
    });
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-Loc Dashboard</title>
    <link rel="stylesheet" href="../../src/output.css">
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-dark">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar - avec toggle pour mobile -->
        <nav id="sidebar"
            class="transform -translate-x-full md:translate-x-0 fixed md:relative w-64 min-h-screen bg-dark-light backdrop-blur-xl bg-opacity-80 border-r border-gray-800 transition-transform duration-300 ease-in-out z-50">
            <div class="p-5 bg-gradient-to-r from-purple-600 to-blue-600">
                <a href="../index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="../img/logo.png" alt="logo"
                            class="rounded-lg shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                    </div>
                </a>
            </div>
            <ul class="py-5">
                <li class="hover:bg-gray-700">
                    <a href="../index.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-chart-line mr-3"></i> Dashboard
                    </a>
                </li>
                <li class="bg-dark/50 border-l-4 border-purple-600">
                    <a href="reservations.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-calendar-alt mr-3"></i> Reservations
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="categories.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-tags mr-3"></i> Categories
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="vehicules.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-car-side mr-3"></i> Vehicles
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="statistiques.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-chart-bar mr-3"></i> Statistiques
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="users.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-users mr-3"></i> Utilisateurs
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="reviews.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-star mr-3"></i> Reviews
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="admins.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-user-shield mr-3"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 max-h-screen overflow-y-auto custom-scrollbar">
            <!-- Top Navbar -->
            <nav class="bg-dark-light backdrop-blur-xl bg-opacity-80 shadow-lg border-b border-gray-800 p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center flex-1">
                        <button id="sidebarToggle" class="p-2 rounded-md bg-gray-800 text-white mr-4 md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="relative flex-1 max-w-xl hidden md:block">
                            <input type="search"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:border-blue-500"
                                placeholder="Search...">
                            <div class="absolute left-3 top-2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 p-2 rounded-md bg-gray-800 text-white">
                                <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-full">
                                <span>Admin</span>
                            </button>
                            <div
                                class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-xl hidden group-hover:block z-50">
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"><i
                                        class="fas fa-user-circle mr-2"></i> Profile</a>
                                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100"><i
                                        class="fas fa-cog mr-2"></i> Settings</a>
                                <hr class="my-2">
                                <a href="../../authentification/logout.php"
                                    class="block px-4 py-2 text-red-600 hover:bg-gray-100"><i
                                        class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="p-4 md:p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                    <div
                        class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60 text-sm">Total Réservations</p>
                                <h3 class="text-3xl font-bold text-white"><?php echo $stats['total_reservations'] ?></h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-calendar-check text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60 text-sm">En Attente</p>
                                <h3 class="text-3xl font-bold text-white"><?php echo $stats['reservations_en_attente']; ?></h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-500 to-teal-500 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60 text-sm">Approuvées</p>
                                <h3 class="text-3xl font-bold text-white"><?php echo $stats['reservations_approuvees']; ?></h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-check text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60 text-sm">Refusées</p>
                                <h3 class="text-3xl font-bold text-white"><?php echo $stats['reservations_refusees'] ?></h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-flag-checkered text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60 text-sm">Terminées</p>
                                <h3 class="text-3xl font-bold text-white"><?php echo $stats['reservations_terminee']; ?></h3>
                            </div>
                            <div class="bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-flag-checkered text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 mb-6">
                    <form class="grid grid-cols-1 md:grid-cols-4 gap-4" method="GET">
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Statut</label>
                            <select name="status"
                                class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 border border-gray-700 focus:ring-2 focus:ring-blue-500">
                                <option value="">Tous les statuts</option>
                                <option value="en attente" <?php echo $status_filter === 'en attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="approuvée" <?php echo $status_filter === 'approuvée' ? 'selected' : ''; ?>>
                                    Approuvée</option>
                                <option value="refusée" <?php echo $status_filter === 'refusée' ? 'selected' : ''; ?>>
                                    Refusée</option>
                                <option value="terminée" <?php echo $status_filter === 'terminée' ? 'selected' : ''; ?>>
                                    Terminée</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Date</label>
                            <input type="date" name="date" value="<?php echo $date_filter; ?>"
                                class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 border border-gray-700 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Recherche</label>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                                placeholder="Client, véhicule ou lieu..."
                                class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 border border-gray-700 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-filter mr-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Reservations Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($reservations as $reservation): ?>
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-700/50">
                            <div class="relative">
                                <img src="<?php echo getVehiculeImage($reservation['id_vehicule_fk']); ?>"
                                    class="w-full h-48 object-cover" alt="Véhicule">
                                <div class="absolute top-4 right-4">
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold 
                                        <?php echo getStatusClass($reservation['statut']); ?>">
                                        <?php echo ucfirst($reservation['statut']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Réservation
                                            #<?php echo $reservation['id_reservation']; ?></h3>
                                        <p class="text-gray-400">
                                            <?php echo getVehiculeName($reservation['id_vehicule_fk']); ?>
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <?php if ($reservation['statut'] === 'en attente'): ?>
                                            <form method="POST" class="flex-1">
                                                <input type="hidden" name="id_reservation"
                                                    value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg 
                                                           hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-300
                                                           flex items-center justify-center gap-2">
                                                    <i class="fas fa-check"></i>
                                                    <span>Approuver</span>
                                                </button>
                                            </form>
                                            <form method="POST" class="flex-1">
                                                <input type="hidden" name="id_reservation"
                                                    value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg 
                                                           hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300
                                                           flex items-center justify-center gap-2">
                                                    <i class="fas fa-times"></i>
                                                    <span>Refuser</span>
                                                </button>
                                            </form>
                                            <form method="POST" class="flex-1">
                                                <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg 
                                                           hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300
                                                           flex items-center justify-center gap-2">
                                                    <i class="fas fa-ban"></i>
                                                    <span>Annuler la réservation</span>
                                                </button>
                                            </form>
                                        <?php elseif ($reservation['statut'] === 'approuvée'): ?>
                                            <form method="POST" class="w-full">
                                                <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="action" value="complete">
                                                <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg 
                                                           hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300
                                                           flex items-center justify-center gap-2 group">
                                                    <i class="fas fa-flag-checkered group-hover:animate-bounce"></i>
                                                    <span>Terminer la réservation</span>
                                                </button>
                                            </form>
                                            <form method="POST" class="flex-1">
                                                <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" 
                                                    class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg 
                                                           hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300
                                                           flex items-center justify-center gap-2">
                                                    <i class="fas fa-ban"></i>
                                                    <span>Annuler la réservation</span>
                                                </button>
                                            </form>
                                        <?php elseif ($reservation['statut'] === 'terminée'): ?>
                                            <div class="w-full bg-gray-600/50 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                                <i class="fas fa-check-circle text-green-400"></i>
                                                <span class="text-xs">Réservation terminée</span>
                                            </div>
                                        <?php elseif ($reservation['statut'] === 'refusée'): ?>
                                            <div class="w-full bg-gray-600/50 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                                <i class="fas fa-ban text-red-400"></i>
                                                <span class="text-xs">Réservation refusée</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-user-circle w-5"></i>
                                        <span class="ml-2"><?php echo $reservation['username']; ?></span>
                                    </div>
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-map-marker-alt w-5"></i>
                                        <span class="ml-2"><?php echo $reservation['lieux']; ?></span>
                                    </div>
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-calendar w-5"></i>
                                        <span
                                            class="ml-2"><?php echo formatDate($reservation['date_reservation']); ?></span>
                                    </div>
                                </div>

                                <?php if ($reservation['commentaire']): ?>
                                    <div class="mt-4 p-3 bg-gray-800/50 rounded-lg">
                                        <p class="text-gray-400 text-sm"><?php echo $reservation['commentaire']; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-700 p-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full <?php echo $reservation['statut'] !== 'refusée' ? 'bg-green-500' : 'bg-gray-500'; ?> flex items-center justify-center">
                                            <i class="fas fa-clock text-white"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 mt-1">En attente</span>
                                    </div>
                                    <div class="flex-1 h-1 mx-2 bg-gray-700">
                                        <div class="h-full <?php echo $reservation['statut'] === 'approuvée' || $reservation['statut'] === 'terminée' ? 'bg-blue-500' : 'bg-gray-700'; ?>"></div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full <?php echo $reservation['statut'] === 'approuvée' || $reservation['statut'] === 'terminée' ? 'bg-blue-500' : 'bg-gray-500'; ?> flex items-center justify-center">
                                            <i class="fas fa-check text-white"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 mt-1">Approuvée</span>
                                    </div>
                                    <div class="flex-1 h-1 mx-2 bg-gray-700">
                                        <div class="h-full <?php echo $reservation['statut'] === 'terminée' ? 'bg-green-500' : 'bg-gray-700'; ?>"></div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full <?php echo $reservation['statut'] === 'terminée' ? 'bg-green-500' : 'bg-gray-500'; ?> flex items-center justify-center">
                                            <i class="fas fa-flag-checkered text-white"></i>
                                        </div>
                                        <span class="text-xs text-gray-400 mt-1">Terminée</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    function getStatusClass($status)
    {
        return match ($status) {
            'en attente' => 'status-pending',
            'approuvée' => 'status-approved',
            'refusée' => 'status-rejected',
            'terminée' => 'status-completed',
            'annuler' => 'status-cancelled',
            default => ''
        };
    }

    function formatDate($date)
    {
        return date('d/m/Y H:i', strtotime($date));
    }

    function getVehiculeImage($id)
    {
        $vehicule = Vehicule::getById($id);
        return $vehicule['image_url'] ?? '../img/default-car.jpg';
    }

    function getVehiculeName($id)
    {
        $vehicule = Vehicule::getById($id);
        return $vehicule['marque'] . ' ' . $vehicule['modele'] ?? 'Véhicule inconnu';
    }
    ?>
    <!-- Ajout du script pour le toggle du sidebar -->
    <script>
        // Handle Mobile Adjustments
        function handleMobileAdjustments() {
            const isMobile = window.innerWidth < 768;
            Chart.defaults.font.size = isMobile ? 10 : 12;

            // Update chart configurations for mobile
            if (charts.revenue) {
                charts.revenue.options.scales.y.ticks.maxTicksLimit = isMobile ? 5 : 8;
                charts.revenue.update('none');
            }
        }

        // Sidebar Toggle Handler
        function setupSidebarToggle() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const content = document.querySelector('.flex-1');

            sidebarToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 768 &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }

        // Initialize Everything
        function initialize() {
            setupSidebarToggle();
            handleMobileAdjustments();
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', initialize);

        // Debounced resize handler
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                handleMobileAdjustments();
            }, 250);
        });
    </script>
</body>

</html>