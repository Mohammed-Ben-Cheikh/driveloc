<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}
require_once '../../app/controller/statistiquesManager.php';
$stats = StatistiquesManager::getDashboardStats();
$statsV = StatistiquesManager::getVehicleStats(); // Add this line to get the statistics
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-Loc Dashboard</title>
    <link rel="stylesheet" href="../../src/output.css">
    <script src="http://localhost/Drive-Loc-/tailwindcss.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .stat-card {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body class="bg-dark text-gray-100">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar - avec toggle pour mobile -->
        <nav id="sidebar"
            class="transform -translate-x-full md:translate-x-0 fixed md:relative w-64 min-h-screen bg-dark-light backdrop-blur-xl bg-opacity-80 border-r border-gray-800 transition-transform duration-300 ease-in-out z-50">
            <!-- ...existing sidebar code... -->
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
                        <i class="fas fa-home mr-3"></i> Dashboard
                    </a>
                </li>
                <li class="hover:bg-gray-700">
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
                <li class="bg-dark/50 border-l-4 border-purple-600">
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
                <!-- ...existing navbar code... -->
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
                                <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-xl">
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
                <!-- Statistics Dashboard -->
                <div class="container mx-auto">
                    <!-- Header -->
                    <h1 class="text-2xl font-bold text-white mb-8">Tableau de Bord des Statistiques</h1>

                    <!-- Statistiques Générales -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-white/80 mb-4">Statistiques Générales</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="stat-card bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Total Clients</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['total_clients']; ?></h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Total Catégories</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['total_categories']; ?></h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-tags text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques des Réservations -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-white/80 mb-4">Statistiques des Réservations</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="stat-card bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Total Réservations</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['total_reservations']; ?></h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Réservations en Attente</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['reservations_en_attente']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-hourglass-half text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-teal-500 to-green-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Réservations Approuvées</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['reservations_approuvees']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-check text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-gray-500 to-gray-700 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Réservations Refusées</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['reservations_refusees']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-times text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Réservations Terminées</p>
                                        <h3 class="text-3xl font-bold"><?php echo $stats['reservations_terminee']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-flag-checkered text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques des Véhicules -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-white/80 mb-4">Statistiques des Véhicules</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="stat-card bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Véhicules Disponibles</p>
                                        <h3 class="text-3xl font-bold"><?php echo $statsV['vehicules_disponibles']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-car text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-gray-500 to-gray-700 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Véhicules Indisponibles</p>
                                        <h3 class="text-3xl font-bold"><?php echo $statsV['vehicules_indisponibles']; ?>
                                        </h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-car text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="stat-card bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg shadow-lg p-4 hover:scale-105 transition-transform">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-white/80">Véhicules Réservés</p>
                                        <h3 class="text-3xl font-bold"><?php echo $statsV['vehicules_reserves']; ?></h3>
                                    </div>
                                    <div class=" bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-car text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            initCharts();
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', initialize);

        // Debounced resize handler
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                handleMobileAdjustments();
                initCharts(); // Reinitialize charts on resize
            }, 250);
        });
    </script>
</body>

</html>