<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-Loc Dashboard</title>
    <link rel="stylesheet" href="../src/output.css">
    <script src="http://localhost/Drive-Loc-/tailwindcss.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-dark text-gray-100">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar - avec toggle pour mobile -->
        <nav id="sidebar"
            class="transform -translate-x-full md:translate-x-0 fixed md:relative w-64 min-h-screen bg-dark-light backdrop-blur-xl bg-opacity-80 border-r border-gray-800 transition-transform duration-300 ease-in-out z-50">
            <div class="p-5 bg-gradient-to-r from-purple-600 to-blue-600">
                <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="./img/logo.png" alt="logo"
                            class="rounded-lg shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                    </div>
                </a>
            </div>
            <ul class="py-5">
                <li class="bg-dark/50 border-l-4 border-purple-600">
                    <a href="index.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-chart-line mr-3"></i> Dashboard
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/reservations.php"
                        class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-calendar-alt mr-3"></i> Reservations
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/categories.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-tags mr-3"></i> Categories
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/vehicules.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-car-side mr-3"></i> Vehicles
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/statistiques.php"
                        class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-chart-bar mr-3"></i> Statistiques
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/users.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-users mr-3"></i> Utilisateurs
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/reviews.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
                        <i class="fas fa-star mr-3"></i> Reviews
                    </a>
                </li>
                <li class="hover:bg-gray-700">
                    <a href="./page/admins.php" class="flex items-center px-6 py-3 text-gray-300 hover:text-white">
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
                    <div class="flex items-center space-x-4 z-50">
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
                                <a href="../authentification/logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100"><i
                                        class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="p-4 md:p-6">
                <!-- Quick Actions -->
                <div class="mb-6 overflow-x-auto">
                    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                    <div class="flex space-x-4 min-w-max">
                        <button
                            class="flex items-center px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus-circle mr-2"></i> New Rental
                        </button>
                        <button
                            class="flex items-center px-4 py-2 bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-car mr-2"></i> Add Vehicle
                        </button>
                        <button
                            class="flex items-center px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i> Add Customer
                        </button>
                    </div>
                </div>

                <!-- Stats Cards with More Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6">
                    <div
                        class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold">Total Vehicles</h5>
                                <h2 class="text-3xl font-bold mt-2">150</h2>
                                <p class="text-blue-100 text-sm mt-2">↑ 12% from last month</p>
                            </div>
                            <div class="text-4xl opacity-50"><i class="fas fa-car"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl shadow-xl p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold">Active Rentals</h5>
                                <h2 class="text-3xl font-bold mt-2">45</h2>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-emerald-100">↑ 8% from last month</p>
                                    <p class="text-xs text-emerald-200">Daily Average: 38</p>
                                    <p class="text-xs text-emerald-200">Peak Time: 14:00-16:00</p>
                                </div>
                            </div>
                            <div class="text-4xl opacity-50"><i class="fas fa-car"></i></div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-orange-600 to-red-600 rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold">Pending Returns</h5>
                                <h2 class="text-3xl font-bold mt-2">12</h2>
                                <p class="text-yellow-100 text-sm mt-2">↓ 5% from last month</p>
                            </div>
                            <div class="text-4xl opacity-50"><i class="fas fa-car"></i></div>
                        </div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-pink-600 to-rose-600 rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold">Total Users</h5>
                                <h2 class="text-3xl font-bold mt-2">1,250</h2>
                                <p class="text-sky-100 text-sm mt-2">↑ 15% from last month</p>
                            </div>
                            <div class="text-4xl opacity-50"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                    <div
                        class="bg-dark-light rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80 border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-gray-100">Revenue Overview</h3>
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                    <div
                        class="bg-dark-light rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80 border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-gray-100">Vehicle Usage</h3>
                        <canvas id="usageChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Additional Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
                    <!-- Vehicle Categories Chart -->
                    <div
                        class="bg-dark-light rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80 border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-gray-100">Vehicle Categories</h3>
                        <canvas id="categoryChart" height="250"></canvas>
                    </div>

                    <!-- Monthly Bookings Chart -->
                    <div
                        class="bg-dark-light rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80 border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-gray-100">Monthly Bookings</h3>
                        <canvas id="bookingsChart" height="250"></canvas>
                    </div>

                    <!-- Customer Satisfaction Chart -->
                    <div
                        class="bg-dark-light rounded-xl shadow-xl p-6 backdrop-blur-xl bg-opacity-80 border border-gray-800">
                        <h3 class="text-lg font-semibold mb-4 text-gray-100">Customer Satisfaction</h3>
                        <canvas id="satisfactionChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Enhanced Recent Rentals Table -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
                    <div class="lg:col-span-2 bg-dark-light rounded-xl shadow-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Rentals</h3>
                            <button class="text-sm text-purple-400 hover:text-purple-300">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-800">
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Customer</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Vehicle</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Duration</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                            Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    <tr class="hover:bg-gray-800/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-8 w-8 rounded-full"
                                                    src="https://ui-avatars.com/api/?name=John+Doe" alt="">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium">John Doe</div>
                                                    <div class="text-sm text-gray-400">#CUS-2024-001</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">BMW X5 2023</div>
                                            <div class="text-sm text-gray-400">Premium SUV</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">3 Days</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">$450.00</td>
                                    </tr>
                                    <!-- Add more rows with similar structure -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Enhanced Activity Feed -->
                    <div class="bg-dark-light rounded-xl shadow-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Activities</h3>
                            <button class="text-sm text-purple-400 hover:text-purple-300">View All</button>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start p-3 rounded-lg hover:bg-gray-800/50">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                    <i class="fas fa-key text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium">New rental: BMW X5</p>
                                    <p class="text-xs text-gray-400">John Doe • 2 minutes ago</p>
                                    <p class="text-xs text-gray-500 mt-1">3 days rental • $450.00</p>
                                </div>
                            </div>
                            <!-- Add more activity items with different icons and colors -->
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