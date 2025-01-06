<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}

require_once "../../app/controller/reviews.php";
require_once "../../app/controller/users.php";

require_once '../../app/controller/statistiquesManager.php';

$stats = StatistiquesManager::getRStats();

$reviews = Review::getAll();
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
</head>

<body class="bg-dark text-gray-100">
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
                <li class="bg-dark/50 border-l-4 border-purple-600">
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
        <div class="flex-1 max-h-screen overflow-y-auto">
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
                <!-- Page Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des Avis Clients</h1>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Note Moyenne</p>
                                <div class="flex items-center">
                                    <h3 class="text-3xl font-bold"><?php echo number_format($stats['total_rating']/$stats['total_r'],2); ?></h3>
                                    <div class="ml-2 text-yellow-400">
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Total Avis</p>
                                <h3 class="text-3xl font-bold"><?php echo nl2br(htmlspecialchars($stats['total_r'])); ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-comments text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">À Modérer</p>
                                <h3 class="text-3xl font-bold"><?php echo nl2br(htmlspecialchars($stats['action_r'])); ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- reviews Section -->
                <section
                    class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-gray-800 to-black border border-gray-700 shadow-2xl p-8 mt-4">
                    <div class="relative text-white">
                        <?php if (empty($reviews)): ?>
                            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 text-center">
                                <i class="fas fa-calendar-xmark text-4xl text-gray-400 mb-4"></i>
                                <h2 class="text-xl font-semibold text-white mb-2">Aucune review</h2>
                                <p class="text-gray-400 mb-4">N'avez pas encore de reviews</p>
                            </div>
                        <?php else: ?>
                            <!-- Header Section -->
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold tracking-wide">Avis de nos clients</h2>
                            </div>
                            <!-- Carrousel -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($reviews as $review):
                                $user = User::getById($review['id_user_fk']); ?>
                                <div
                                    class="swiper-slide group bg-gray-800 rounded-xl transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl">
                                    <div class="p-6">
                                        <!-- User Info -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex items-center">
                                                <img src="https://ui-avatars.com/api/?name=John+Doe" alt="John Doe"
                                                    class="w-12 h-12 rounded-full border border-gray-600 mr-3">
                                                <div>
                                                    <h3 class="font-bold text-lg">
                                                        <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                                    </h3>
                                                    <p class="text-sm text-gray-400">Il y a 2 jours</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-1 ">
                                                <span class="text-yellow-400">
                                                    <?php for ($j = 0; $j < $review['rating']; $j++): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php endfor; ?></span><span>
                                                    <?php for ($j = 0; $j < 5 - $review['rating']; $j++): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php endfor; ?></span>
                                            </div>
                                        </div>
                                        <!-- Review Text -->
                                        <p class="text-gray-300 text-sm italic mb-4">
                                            "<?php echo nl2br(htmlspecialchars($review['comment'])); ?>"</p>
                                            <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                                    <div class="flex space-x-2">
                                        <button
                                            class="px-3 py-1 bg-green-600/20 text-green-400 rounded-lg hover:bg-green-600/30">
                                            <i class="fas fa-check mr-1"></i> Approuver
                                        </button>
                                        <button class="px-3 py-1 bg-red-600/20 text-red-400 rounded-lg hover:bg-red-600/30">
                                            <i class="fas fa-times mr-1"></i> Rejeter
                                        </button>
                                    </div>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Reviews Grid
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php for ($i = 0; $i < 9; $i++): ?>
                        <div class="bg-dark-light rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <img src="https://ui-avatars.com/api/?name=John+Doe"
                                            class="w-10 h-10 rounded-full mr-3">
                                        <div>
                                            <h3 class="font-semibold">John Doe</h3>
                                            <p class="text-sm text-gray-400">Il y a 2 jours</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-1 text-yellow-400">
                                        <?php for ($j = 0; $j < 5; $j++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <p class="text-gray-300 mb-4">
                                    "Excellent service, voiture en parfait état et personnel très professionnel. Je
                                    recommande vivement !"
                                </p>

                                <div class="flex items-center text-sm text-gray-400 mb-4">
                                    <i class="fas fa-car-side mr-2"></i>
                                    <span>BMW M3 Competition</span>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                                    <div class="flex space-x-2">
                                        <button
                                            class="px-3 py-1 bg-green-600/20 text-green-400 rounded-lg hover:bg-green-600/30">
                                            <i class="fas fa-check mr-1"></i> Approuver
                                        </button>
                                        <button class="px-3 py-1 bg-red-600/20 text-red-400 rounded-lg hover:bg-red-600/30">
                                            <i class="fas fa-times mr-1"></i> Rejeter
                                        </button>
                                    </div>
                                    <button class="text-gray-400 hover:text-white">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div> -->

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    <nav class="flex space-x-2">
                        <button class="px-3 py-1 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Précédent</button>
                        <button class="px-3 py-1 bg-purple-600 text-white rounded-lg">1</button>
                        <button class="px-3 py-1 bg-gray-800 text-white rounded-lg hover:bg-gray-700">2</button>
                        <button class="px-3 py-1 bg-gray-800 text-white rounded-lg hover:bg-gray-700">3</button>
                        <button class="px-3 py-1 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Suivant</button>
                    </nav>
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