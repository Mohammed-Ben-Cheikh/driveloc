<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}
require_once '../../app/database/Database.php';
require_once '../../app/controller/categories.php';
require_once '../../app/controller/statistiquesManager.php';
require_once '../../app/controller/statistiques.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $nom = $_POST['categoryName'];
        $description = $_POST['categoryDesc'];
        $image_url = '';

        // Handle file upload
        if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['categoryImage']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($filetype), $allowed)) {
                $newname = uniqid() . '.' . $filetype;
                $upload_path = '../uploads/categories/' . $newname;

                if (!is_dir('../uploads/categories/')) {
                    mkdir('../uploads/categories/', 0777, true);
                }

                if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $upload_path)) {
                    $image_url = 'uploads/categories/' . $newname;
                }
            }
        }

        $category = new Categorie($nom, $description, $image_url);
        $result = $category->create();
        StatistiquesManager::calculerEtMettreAJour();

        echo json_encode(['success' => true, 'message' => 'Catégorie ajoutée avec succès']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }

}


$stats = StatistiquesManager::getDashboardStats();
$mostPopularCategory = Categorie::getMostPopularCategory(); // Add this line to get the most popular category

// Get all categories
$categories = Categorie::getAll();

// Get category by ID for edit form
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $categoryId = $_GET['id'];
        $categorie = Categorie::getById($categoryId);
        if ($categorie) {
            header('Content-Type: application/json');
            echo json_encode($category);
            exit;
        }
    }
}
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
                <li class="bg-dark/50 border-l-4 border-purple-600">
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

            <!-- Dashboard Content -->
            <div class="p-4 md:p-6">
                <!-- Page Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Gestion des Catégories</h1>
                    <button onclick="openAddModal()"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i> Nouvelle Catégorie
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Total Catégories</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_categories']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-tags text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Véhicules Catégorisés</p>
                                <h3 class="text-3xl font-bold"><?php echo $stats['total_vehicules']; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-car text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-white/60">Catégorie la + Populaire</p>
                                <h3 class="text-xl font-bold"><?php echo !empty($mostPopularCategory['nom']) ? htmlspecialchars($mostPopularCategory['nom']) : "Aucune catégorie"; ?></h3>
                            </div>
                            <div class="text-white/80 bg-white/10 p-3 rounded-lg">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($categories)): ?>
                        <div class="col-span-full text-center py-10">
                            <i class="fas fa-folder-open text-4xl text-gray-600 mb-3"></i>
                            <p class="text-gray-500">Aucune catégorie trouvée</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($categories as $category): ?>
                            <div
                                class="bg-dark-light rounded-xl overflow-hidden group hover:shadow-xl transition-all duration-300">
                                <div class="relative h-48">
                                    <img src="../<?php echo htmlspecialchars($category['image_url'] ?: 'img/default-category.jpg'); ?>"
                                        class="w-full h-full object-cover"
                                        alt="<?php echo htmlspecialchars($category['nom']); ?>">
                                    <div
                                        class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="flex space-x-2">
                                            <button class="p-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                                                onclick="editCategory(<?php echo $category['id_categorie']; ?>)">
                                                <i class="fas fa-edit text-white"></i>
                                            </button>
                                            <button class="p-2 bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                                                onclick="deleteCategory(<?php echo $category['id_categorie']; ?>)">
                                                <i class="fas fa-trash text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($category['nom']); ?></h3>
                                        <span class="bg-purple-600/20 text-purple-400 px-2 py-1 rounded-lg text-sm">
                                            <?php
                                            require_once '../../app/controller/vehicules.php';
                                            $vehicleCount = Vehicule::countByCategory($category['id_categorie']);
                                            echo $vehicleCount . ' véhicule' . ($vehicleCount > 1 ? 's' : '');
                                            ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-400 text-sm mb-3">
                                        <?php echo htmlspecialchars($category['description']); ?>
                                    </p>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-400">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            <?php echo date('d/m/Y', strtotime($category['created_at'])); ?>
                                        </span>
                                        <button class="text-purple-400 hover:text-purple-300">
                                            Voir détails <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add Category Modal -->
            <div id="addCategoryModal"
                class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-dark-light rounded-xl max-w-md w-full mx-4 shadow-2xl">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-white">Ajouter une Catégorie</h3>
                            <button onclick="closeAddModal()" class="text-gray-400 hover:text-white transition-colors"
                                aria-label="Fermer">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="addCategoryForm" class="space-y-4" onsubmit="handleSubmit(event)"
                            enctype="multipart/form-data">
                            <div>
                                <label for="categoryName" class="block text-sm font-medium text-gray-400 mb-2">Nom de la
                                    catégorie *</label>
                                <input type="text" id="categoryName" name="categoryName" required
                                    class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600 transition-all border border-gray-700"
                                    placeholder="Entrez le nom de la catégorie">
                                <div class="text-red-500 text-xs mt-1 hidden" id="categoryNameError"></div>
                            </div>

                            <div>
                                <label for="categoryDesc"
                                    class="block text-sm font-medium text-gray-400 mb-2">Description *</label>
                                <textarea id="categoryDesc" name="categoryDesc" required
                                    class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600 transition-all border border-gray-700"
                                    rows="3" placeholder="Décrivez la catégorie"></textarea>
                                <div class="text-red-500 text-xs mt-1 hidden" id="categoryDescError"></div>
                            </div>

                            <div class="relative">
                                <div class="flex items-center justify-center w-full">
                                    <label for="categoryImage"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-700 bg-gray-800 border-gray-600 hover:border-purple-600 group transition-all">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-400 group-hover:text-purple-500"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Cliquez
                                                    pour télécharger</span> ou glissez et déposez</p>
                                            <p class="text-xs text-gray-400">PNG, JPG or WEBP (MAX. 2Mo)</p>
                                        </div>
                                        <div id="fileInfo"
                                            class="hidden absolute bottom-3 left-3 right-3 text-sm text-gray-400">
                                            <span id="fileName"></span>
                                        </div>
                                        <div id="imagePreview"
                                            class="hidden absolute top-2 right-2 w-16 h-16 rounded-lg overflow-hidden bg-gray-700">
                                            <img src="" alt="Aperçu" class="w-full h-full object-cover">
                                        </div>
                                    </label>
                                    <input type="file" id="categoryImage" name="categoryImage" required accept="image/*"
                                        class="hidden" onchange="handleFileSelect(event)">
                                </div>
                                <div class="text-red-500 text-xs mt-1 hidden" id="categoryImageError"></div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="closeAddModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" id="submitBtn"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                                    <span>Ajouter</span>
                                    <div id="loadingSpinner" class="hidden ml-2">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div id="editCategoryModal"
                class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-dark-light rounded-xl max-w-md w-full mx-4 shadow-2xl">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-white">Modifier la Catégorie</h3>
                            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="editCategoryForm" class="space-y-4" onsubmit="handleEditSubmit(event)"
                            enctype="multipart/form-data">
                            <input type="hidden" id="editCategoryId" name="categoryId">
                            <div>
                                <label for="editCategoryName" class="block text-sm font-medium text-gray-400 mb-2">Nom
                                    de la catégorie *</label>
                                <input type="text" id="editCategoryName" name="categoryName" required
                                    class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600 transition-all border border-gray-700"
                                    placeholder="Entrez le nom de la catégorie">
                                <div class="text-red-500 text-xs mt-1 hidden" id="editCategoryNameError"></div>
                            </div>

                            <div>
                                <label for="editCategoryDesc"
                                    class="block text-sm font-medium text-gray-400 mb-2">Description *</label>
                                <textarea id="editCategoryDesc" name="categoryDesc" required
                                    class="w-full bg-gray-800 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-600 transition-all border border-gray-700"
                                    rows="3" placeholder="Décrivez la catégorie"></textarea>
                                <div class="text-red-500 text-xs mt-1 hidden" id="editCategoryDescError"></div>
                            </div>

                            <div class="relative">
                                <div class="flex items-center justify-center w-full">
                                    <label for="editCategoryImage"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-700 bg-gray-800 border-gray-600 hover:border-purple-600 group transition-all">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-400 group-hover:text-purple-500"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Cliquez
                                                    pour télécharger</span> ou glissez et déposez</p>
                                            <p class="text-xs text-gray-400">PNG, JPG or WEBP (MAX. 2Mo)</p>
                                        </div>
                                        <div id="fileInfoEdit"
                                            class="hidden absolute bottom-3 left-3 right-3 text-sm text-gray-400">
                                            <span id="fileNameEdit"></span>
                                        </div>
                                        <div id="currentImage"
                                            class="absolute top-2 right-2 w-16 h-16 rounded-lg overflow-hidden bg-gray-700">
                                            <img src="" alt="Aperçu" class="w-full h-full object-cover">
                                        </div>
                                    </label>
                                    <input type="file" id="editCategoryImage" name="categoryImage" accept="image/*"
                                        class="hidden" onchange="handleFileSelectEdit(event)">
                                </div>
                                <div class="text-red-500 text-xs mt-1 hidden" id="editCategoryImageError"></div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="closeEditModal()"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" id="submitEditBtn"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                                    <span>Mettre à jour</span>
                                    <div id="loadingEditSpinner" class="hidden ml-2">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function openAddModal() {
                    document.getElementById('addCategoryModal').classList.remove('hidden');
                    document.getElementById('addCategoryModal').classList.add('flex');
                }

                function closeAddModal() {
                    const modal = document.getElementById('addCategoryModal');
                    modal.classList.add('hidden');
                    document.getElementById('addCategoryForm').reset();
                    document.getElementById('imagePreview').classList.add('hidden');
                }

                // Close modal when clicking outside
                document.getElementById('addCategoryModal').addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeAddModal();
                    }
                });

                function previewImage(event) {
                    const preview = document.getElementById('imagePreview');
                    const file = event.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function () {
                        preview.querySelector('img').src = reader.result;
                        preview.classList.remove('hidden');
                    }

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }

                function handleSubmit(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('#submitBtn');
                    const spinner = form.querySelector('#loadingSpinner');

                    // Show loading state
                    submitBtn.disabled = true;
                    spinner.classList.remove('hidden');

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
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
                            alert('Erreur: ' + error.message);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            spinner.classList.add('hidden');
                        });
                }

                function handleFileSelect(event) {
                    const file = event.target.files[0];
                    const fileInfo = document.getElementById('fileInfo');
                    const fileName = document.getElementById('fileName');
                    const preview = document.getElementById('imagePreview');
                    const previewImg = preview.querySelector('img');

                    if (file) {
                        // Show file name
                        fileName.textContent = file.name;
                        fileInfo.classList.remove('hidden');

                        // Preview image
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewImg.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                }

                function handleFileSelectEdit(event) {
                    const file = event.target.files[0];
                    const fileInfo = document.getElementById('fileInfoEdit');
                    const fileName = document.getElementById('fileNameEdit');
                    const preview = document.getElementById('currentImage');
                    const previewImg = preview.querySelector('img');

                    if (file) {
                        // Show file name
                        fileName.textContent = file.name;
                        fileInfo.classList.remove('hidden');

                        // Preview image
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            previewImg.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                }

                // Add drag and drop support
                const dropZone = document.querySelector('label[for="categoryImage"]');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    dropZone.classList.add('border-purple-500', 'bg-gray-700');
                }

                function unhighlight(e) {
                    dropZone.classList.remove('border-purple-500', 'bg-gray-700');
                }

                dropZone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const file = dt.files[0];
                    const fileInput = document.getElementById('categoryImage');

                    fileInput.files = dt.files;
                    handleFileSelect({ target: { files: [file] } });
                }

                function deleteCategory(id) {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
                        const formData = new FormData();
                        formData.append('id', id);

                        fetch('delete_category.php', {
                            method: 'POST',
                            body: formData
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
                                alert('Erreur: ' + error.message);
                            });
                    }
                }

                // Add these new functions
                function editCategory(categoryId) {
                    // Show modal
                    const modal = document.getElementById('editCategoryModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // Fetch category data
                    fetch(`get_category.php?id=${categoryId}`)
                        .then(response => response.json())
                        .then(category => {
                            document.getElementById('editCategoryId').value = category.id_categorie;
                            document.getElementById('editCategoryName').value = category.nom;
                            document.getElementById('editCategoryDesc').value = category.description;

                            // Show current image if exists
                            const currentImage = document.querySelector('#currentImage img');
                            if (currentImage && category.image_url) {
                                currentImage.src = '../' + category.image_url;
                                currentImage.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error loading category data');
                        });
                }

                function closeEditModal() {
                    const modal = document.getElementById('editCategoryModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.getElementById('editCategoryForm').reset();
                    const currentImage = document.querySelector('#currentImage img');
                    if (currentImage) {
                        currentImage.classList.add('hidden');
                    }
                }

                function handleEditSubmit(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('#submitEditBtn');
                    const spinner = form.querySelector('#loadingEditSpinner');
                    // Show loading state
                    submitBtn.disabled = true;
                    spinner.classList.remove('hidden');

                    fetch('update_category.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeEditModal();
                                window.location.reload();
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            alert('Erreur: ' + error.message);
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            spinner.classList.add('hidden');
                        });
                }

                // Close modal when clicking outside
                document.getElementById('editCategoryModal').addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            </script>

            <!-- Ajout du script pour le toggle du sidebar -->
            <script>
                // Handle Mobile Adjustments
                function handleMobileAdjustments() {
                    const isMobile = window.innerWidth < 768;
                    // Update chart configurations for mobile
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
        </div>
    </div>
</body>

</html>