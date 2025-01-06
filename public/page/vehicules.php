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

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 flex-col p-4 space-y-4">
    <nav
        class="relative bg-gradient-to-r from-blue-400 to-blue-600 rounded-[2rem] border-gray-200 shadow-2xl border-4 border-white/20 backdrop-blur-sm">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <div
                class="flex justify-center items-center rounded-lg w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="../img/logo.png" alt="Chef"
                            class="rounded-lg shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                    </div>
                </a>
            </div>
            <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="../../authentification/logout.php"
                    class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 m-2">Logout</a>
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul
                    class="flex flex-col justify-center items-center font-medium p-4 rounded-lg w-full md:w-[38rem] md:h-10 md:p-0 mt-4 border border-gray-100 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 bg-white">
                    <li>
                        <a href="../index.php"
                            class="block py-2 px-3 text-white bg-blue-600 rounded md:bg-transparent md:text-blue-600 md:p-0">Home</a>
                    </li>
                    <li>
                        <a href="vehicules.php"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600 md:p-0 ">Vehicules</a>
                    </li>
                    <li>
                        <a href="categories.php"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600 md:p-0 ">Categories</a>
                    </li>
                    <li>
                        <a href="reservation.php"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600 md:p-0 ">Reservation</a>
                    </li>
                    <li>
                        <a href="users.php"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600 md:p-0 ">Espace
                            client</a>
                    </li>
                    <li>
                        <a href="contact.php"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-600 md:p-0 ">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="">
        <!-- Filtres et recherche -->
        <section class="overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Filtre par catégorie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select id="categoryFilter" onchange="filterVehicles(this.value)"
                        class="p-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id_categorie'] ?>" <?= $selectedCategory == $category['id_categorie'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Barre de recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" id="searchVehicle" placeholder="Rechercher un véhicule..."
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1">
                </div>
            </div>
        </section>

        <!-- Liste des véhicules -->
        <section id="vehiclesContainer" class="overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Les véhicules seront chargés ici dynamiquement -->
            <div class="col-span-full text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">Chargement des véhicules...</p>
            </div>
        </section>
    </main>
    <footer class="bg-gradient-to-t from-blue-400 to-blue-600 rounded-[2rem] shadow-2xl border-4 border-white/20 mt-8">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div
                    class="flex justify-center items-center rounded-[33px] w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                    <a href="index.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img src="../img/logo.png" class="rounded-lg" alt="Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase ">Resources
                        </h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="https://flowbite.com/" class="hover:underline">Flowbite</a>
                            </li>
                            <li>
                                <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase ">Follow us
                        </h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="https://github.com/themesberg/flowbite" class="hover:underline ">Github</a>
                            </li>
                            <li>
                                <a href="https://discord.gg/4eeurUVvTy" class="hover:underline">Discord</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase ">Legal</h2>
                        <ul class="text-white dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <a href="#" class="hover:underline">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-bla sm:text-center">© 2023 <a href="https://flowbite.com/"
                        class="hover:underline">DriveLoc™</a>. All Rights Reserved.
                </span>
                <div class="flex mt-4 sm:justify-center sm:mt-0">
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 8 19">
                            <path fill-rule="evenodd"
                                d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Facebook page</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 21 16">
                            <path
                                d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z" />
                        </svg>
                        <span class="sr-only">Discord community</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 17">
                            <path fill-rule="evenodd"
                                d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Twitter page</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">GitHub account</span>
                    </a>
                    <a href="#" class="text-white hover:text-gray-900 dark:hover:text-white ms-5">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.094 20.094 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM8 1.707a8.821 8.821 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.758 45.758 0 0 0 8 1.707ZM1.642 8.262a8.57 8.57 0 0 1 4.73-5.981A53.998 53.998 0 0 1 9.54 7.222a32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.64 31.64 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM10 18.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 13.113 11a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Dribbble account</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        async function loadVehicles(category = '') {
            try {
                const url = new URL('http://localhost/Drive-Loc/app/api/vehicules.php');
                if (category) {
                    url.searchParams.set('category', category);
                }

                const response = await fetch(url);
                const data = await response.json();

                if (data.status === 200 && data.data) {
                    const container = document.getElementById('vehiclesContainer');
                    container.innerHTML = data.data.map(vehicle => `
                        <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <div class="relative aspect-[16/10]">
                                <img src="${vehicle.image_url}"
                                    alt="${vehicle.nom}" 
                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <div class="absolute top-4 right-4 z-10">
                                    <span class="bg-gradient-to-r ${getStatusClass(vehicle.disponibilite)} text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg backdrop-blur-sm">
                                        ${vehicle.disponibilite}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        ${vehicle.nom}
                                    </h3>
                                    <span class="flex items-center gap-1 text-gray-500 bg-gray-100 px-3 py-1 rounded-full text-sm">
                                        ${vehicle.categorie.nom}
                                    </span>
                                </div>

                                <p class="text-gray-600 mb-6 line-clamp-2">
                                    ${vehicle.description || 'Aucune description disponible'}
                                </p>

                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-500">Prix par jour</span>
                                        <span class="text-2xl font-bold text-blue-600">
                                            ${vehicle.prix_formatte}
                                        </span>
                                    </div>
                                </div>

                                ${vehicle.disponibilite === 'Disponible' 
                                    ? `<div class="space-x-4 flex">
                                        <a href="addCommand.php?id=${vehicle.id_vehicule}"
                                        class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-blue-500/30">
                                        Réserver maintenant
                                       </a>
                                       <a href="/app/action/vehicule/ficheTechnique.php?id=${vehicle.id_vehicule}"
                                        class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-blue-500/30">
                                        Voir les détails
                                       </a>
                                    </div>`
                                    : `<button disabled
                                        class="w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-3 rounded-lg cursor-not-allowed opacity-75">
                                        Non disponible
                                       </button>`
                                }
                            </div>
                        </article>
                    `).join('');
                }
            } catch (error) {
                console.error('Erreur lors du chargement des véhicules:', error);
                document.getElementById('vehiclesContainer').innerHTML = `
                    <div class="col-span-full text-center text-red-600">
                        <p>Une erreur est survenue lors du chargement des véhicules.</p>
                    </div>
                `;
            }
        }

        function getStatusClass(status) {
            const classes = {
                'Disponible': 'from-green-500 to-green-600',
                'Réservé': 'from-yellow-500 to-yellow-600',
                'Indisponible': 'from-red-500 to-red-600'
            };
            return classes[status] || 'from-gray-500 to-gray-600';
        }

        // Modifier la fonction filterVehicles existante
        function filterVehicles(category) {
            loadVehicles(category);
        }

        // Charger les véhicules au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');
            loadVehicles(category);
        });

        // Modifier la fonction de recherche
        document.getElementById('searchVehicle').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const articles = document.querySelectorAll('article');
            
            articles.forEach(article => {
                const title = article.querySelector('h3').textContent.toLowerCase();
                const description = article.querySelector('p').textContent.toLowerCase();
                
                article.style.display = 
                    title.includes(searchTerm) || description.includes(searchTerm)
                    ? ''
                    : 'none';
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