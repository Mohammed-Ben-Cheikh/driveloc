<?php
session_start();
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id']) {
    header("Location: ../../dashboard");
} else if (!isset($_SESSION['user_id']) && !$_SESSION['user_id']) {
    header("Location: ../../index.php");
}

require_once '../../app/controller/vehicules.php';
require_once '../../app/controller/categories.php';

// Get all available vehicles and categories

$categories = Categorie::getAll();
$vehicles = Vehicule::getAll();

// Filter by category if set
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
if ($selectedCategory) {
    $vehicles = array_filter($vehicles, function ($vehicle) use ($selectedCategory) {
        return $vehicle['id_categorie_fk'] == $selectedCategory;
    });
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../src/output.css">
    <script src="http://localhost/Drive-Loc-/tailwindcss.js"></script>
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 flex-col p-4">
    <main class="">
        <!-- Filtres et recherche -->
        <section
            class="overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8">
            <div class="flex justify-between items-center">
                <!-- Barre de recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" id="searchVehicle" placeholder="Rechercher un véhicule..."
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1">
                </div>
                <div>
                    <button onclick="window.history.back()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Retour
                    </button>
                </div>
            </div>
        </section>

        <!-- Liste des véhicules -->
        <section
            class="overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($vehicles as $vehicle): ?>
                <article
                    class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                    <!-- Image du véhicule avec overlay -->
                    <div class="relative aspect-[16/10]">
                        <img src="<?= htmlspecialchars($vehicle['image_url'] ?: '../../public/img/default-car.jpg') ?>"
                            alt="<?= htmlspecialchars($vehicle['nom']) ?>"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4 z-10">
                            <?php
                            $statusClass = [
                                'Disponible' => 'bg-gradient-to-r from-green-500 to-green-600',
                                'Réservé' => 'bg-gradient-to-r from-yellow-500 to-yellow-600',
                                'Indisponible' => 'bg-gradient-to-r from-red-500 to-red-600'
                            ][$vehicle['disponibilite']] ?? 'bg-gradient-to-r from-gray-500 to-gray-600';
                            ?>
                            <span
                                class="<?= $statusClass ?> text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg backdrop-blur-sm">
                                <?= htmlspecialchars($vehicle['disponibilite']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Informations du véhicule -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                <?= htmlspecialchars($vehicle['nom']) ?>
                            </h3>
                            <span class="flex items-center gap-1 text-gray-500 bg-gray-100 px-3 py-1 rounded-full text-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <?php
                                $categorie = Categorie::getById($vehicle['id_categorie_fk']);
                                echo htmlspecialchars($categorie['nom']);
                                ?>
                            </span>
                        </div>

                        <p class="text-gray-600 mb-6 line-clamp-2">
                            <?= htmlspecialchars($vehicle['description'] ?? 'Aucune description disponible') ?>
                        </p>

                        <!-- Caractéristiques -->
                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Immédiat</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Assurance incluse</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500">Prix par jour</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    <?= number_format($vehicle['prix_a_loue'], 2) ?> €
                                </span>
                            </div>
                        </div>

                        <!-- Bouton de réservation -->
                        <?php if ($vehicle['disponibilite'] === 'Disponible'): ?>
                            <a href="/app/action/vehicule/ficheTechnique.php?id=<?= $vehicle['id_vehicule'] ?>"
                                class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-blue-500/30">
                                Voir les détails
                            </a>
                        <?php else: ?>
                            <button disabled
                                class="w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-3 rounded-lg cursor-not-allowed opacity-75">
                                Non disponible
                            </button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <script>
        document.getElementById('searchVehicle').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const vehicles = document.querySelectorAll('article');

            vehicles.forEach(vehicle => {
                const title = vehicle.querySelector('h3').textContent.toLowerCase();
                const description = vehicle.querySelector('p').textContent.toLowerCase();

                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    vehicle.style.display = '';
                } else {
                    vehicle.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.querySelector("[data-collapse-toggle='navbar-user']");
            const navbarMenu = document.getElementById("navbar-user");
            toggleButton.addEventListener("click", () => {
                navbarMenu.classList.toggle("hidden");
            });
            const userMenuButton = document.getElementById("user-menu-button");
            const userDropdown = document.getElementById("user-dropdown");
            userMenuButton.addEventListener("click", () => {
                userDropdown.classList.toggle("hidden");
            });
            window.addEventListener("click", (e) => {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add("hidden");
                }
            });
        });
    </script>
</body>

</html>