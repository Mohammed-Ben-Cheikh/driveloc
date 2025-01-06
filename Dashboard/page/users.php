<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}
require_once '../../app/controller/users.php';
require_once '../../app/controller/statistiquesManager.php'; // Add this line to include the statistiquesManager

$stats = StatistiquesManager::getDashboardStats(); // Add this line to get the statistics
$users = User::getAll(); // Add this line to get all users
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
                <li class="bg-dark/50 border-l-4 border-purple-600">
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
                                <a href="../../authentification/logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100"><i
                                        class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="p-4 md:p-6">
                <!-- Page Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des Utilisateurs</h1>
                    <button onclick="openAddModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Nouvel Utilisateur
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Total Utilisateurs</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_clients']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Utilisateurs Actifs</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_clients']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-user-check text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Nouveaux ce mois</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_clients']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-user-plus text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">À Vérifier</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_clients']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-user-clock text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="bg-dark-light rounded-xl p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                            <select class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600">
                                <option value="">Tous les statuts</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                                <option value="pending">En attente</option>
                                <option value="blocked">Bloqué</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Date d'inscription</label>
                            <input type="date" class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Rôle</label>
                            <select class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600">
                                <option value="">Tous les rôles</option>
                                <option value="client">Client</option>
                                <option value="vip">Client VIP</option>
                                <option value="business">Business</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Rechercher</label>
                            <div class="relative">
                                <input type="search" placeholder="Nom, email, téléphone..." 
                                       class="w-full bg-gray-800 text-white rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-purple-600">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-dark-light rounded-xl shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr class="bg-gray-800">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dernière Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-dark-light divide-y divide-gray-700">
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">Aucun utilisateur trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="hover:bg-gray-800/50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['nom'] . ' ' . $user['prenom']); ?>" alt="">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium"><?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></div>
                                                        <div class="text-sm text-gray-400">Inscrit le <?php echo date('d/m/Y', strtotime($user['date_creation'])); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm"><?php echo htmlspecialchars($user['email']); ?></div>
                                                <div class="text-sm text-gray-400"><?php echo htmlspecialchars($user['telephone']); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Actif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm">BMW M3 Competition</div>
                                                <div class="text-sm text-gray-400">Il y a 2 jours</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <button class="text-red-400 hover:text-red-300" onclick="deleteUser(<?php echo $user['id_user']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="bg-gray-800 px-4 py-3 flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button class="bg-gray-700 px-4 py-2 text-sm font-medium text-gray-400 hover:bg-gray-600 rounded-md">Previous</button>
                            <button class="bg-gray-700 px-4 py-2 text-sm font-medium text-gray-400 hover:bg-gray-600 rounded-md">Next</button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-400">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">97</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <!-- Pagination buttons -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add User Modal -->
            <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <!-- Modal content -->
            </div>
        </div>
    </div>

    <!-- Ajout du script pour le toggle du sidebar -->
    <script>
        // Handle Mobile Adjustments
        function handleMobileAdjustments() {
            const isMobile = window.innerWidth < 768;
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

        function deleteUser(userId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                fetch(`../../app/action/admin/user/delete.php?id=${userId}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur: ' + error.message);
                });
            }
        }
    </script>
    <script src="../js/script.js"></script>
</body>

</html>