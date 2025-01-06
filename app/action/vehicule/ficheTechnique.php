<?php
session_start();
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id']) {
    header("Location: ../../../dashboard");
    exit();
} else if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: ../../../index.php");
    exit();
}


require_once "../../controller/reviews.php";
require_once "../../controller/users.php";

$reviews = Review::getByVehicle($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_vehicule = $_POST['id_vehicule'];
    $rating = $_POST['rating'];
    $comment = $_POST['review'];

    if ($id_vehicule && $rating && $comment) {
        // Create a new review instance
        $reviews = new Review(
            $_SESSION['user_id'],
            $id_vehicule,
            $comment,
            $rating
        );

        if ($reviews->create()) {
            header("Location: ficheTechnique.php?id=" . $id_vehicule . "");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Technique - DriveLoc</title>
    <link rel="stylesheet" href="../../../src/output.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Swiper Navigation Customization */
        .swiper-button-next,
        .swiper-button-prev {
            width: 3rem;
            height: 3rem;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease-in-out;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(255, 255, 255, 0.8);
            color: black;
            transform: scale(1.2);
        }

        /* Card Hover Effects */
        .group:hover {
            box-shadow: 0 12px 30px rgba(255, 193, 7, 0.3), 0 8px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        /* Button Glow Effect */
        button:hover {
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.6);
        }

        /* Smooth Scrollbar for Horizontal Scrolling */
        .swiper-container {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .swiper-container::-webkit-scrollbar {
            height: 8px;
            background: transparent;
        }

        .swiper-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 flex-col p-4 space-y-8">
    <nav
        class="relative bg-gradient-to-r from-blue-400 to-blue-600 rounded-[2rem] border-gray-200 shadow-2xl border-4 border-white/20 backdrop-blur-sm">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <div
                class="flex justify-center items-center rounded-lg w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                <a href="../../../public/page/vehicules.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="relative">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <img src="../../img/logo.png" alt="Chef"
                            class="rounded-lg shadow-2xl transform group-hover:scale-[1.02] transition-all duration-500 relative">
                    </div>
                </a>
            </div>
            <div
                class="flex justify-center items-center rounded-lg w-52 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                <a href="../../../public/page/vehicules.php"
                    class="flex items-center space-x-3 rtl:space-x-reverse p-3">
                    <div class="relative">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-gray-600 to-gray-400 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <h1 class="text-xl text-blue-700">la page vehicules</h1>
                        </h1>
                    </div>
                </a>
            </div>
        </div>
    </nav>
    <main class="space-y-8">
        <section
            class="relative overflow-hidden rounded-[2rem] bg-white/50 backdrop-blur-sm border-4 border-white shadow-2xl p-8 mt-8">
            <?php
            if (isset($_GET['id'])) {
                require_once '../../database/Database.php';
                require_once '../../controller/vehicules.php';
                $id = $_GET['id'];
                $vehicule = Vehicule::getByCategorie($id);
                if ($vehicule) {
                    ?>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Image Section -->
                        <div class="relative space-x-4 space-y-4">
                            <img src="<?php echo htmlspecialchars($vehicule['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($vehicule['nom']); ?>"
                                class="rounded-[2rem] shadow-2xl w-full h-[400px] sm:h-full  object-cover">
                            <!-- Availability Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="px-4 py-2 rounded-full text-white font-semibold <?php
                                echo match ($vehicule['disponibilite']) {
                                    'Disponible' => 'bg-green-500',
                                    'Réservé' => 'bg-yellow-500',
                                    'Indisponible' => 'bg-red-500',
                                    default => 'bg-gray-500'
                                };
                                ?>">
                                    <?php echo htmlspecialchars($vehicule['disponibilite']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="space-y-6">
                            <h1 class="text-4xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>
                            </h1>
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-blue-600">
                                    <?php echo number_format($vehicule['prix_a_loue'], 2); ?>€
                                </span>
                                <span class="text-gray-500">/ jour</span>
                            </div>

                            <!-- Key Features Grid -->
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div class="flex items-center gap-2 bg-white/80 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-gray-600">Année:
                                        <?php echo htmlspecialchars($vehicule['annee']); ?></span>
                                </div>
                                <div class="flex items-center gap-2 bg-white/80 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span
                                        class="text-gray-600"><?php echo htmlspecialchars($vehicule['type_vitesse']); ?></span>
                                </div>
                            </div>

                            <!-- Detailed Specs -->
                            <div class="bg-white/80 rounded-xl p-6 space-y-4">
                                <h3 class="text-xl font-semibold text-gray-800">Caractéristiques</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <p class="text-gray-600">Carburant: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['carburant']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Places: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nombre_de_places']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Portes: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nombre_de_portes']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Kilométrage: <span
                                                class="font-medium"><?php echo number_format($vehicule['kilometrage'], 0); ?>
                                                km</span></p>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-gray-600">Climatisation: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['climatisation']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Vitesses: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['nb_vitesses']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Toit: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['toit_panoramique']); ?></span>
                                        </p>
                                        <p class="text-gray-600">Catégorie: <span
                                                class="font-medium"><?php echo htmlspecialchars($vehicule['categorie_nom']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="bg-white/80 rounded-xl p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                                <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($vehicule['description'])); ?></p>
                            </div>
                            <!-- Action Buttons -->
                            <div class="flex gap-4">
                                <?php if ($vehicule['disponibilite'] === 'Disponible'): ?>
                                    <a href="/public/page/addCommand.php?id=<?php echo $vehicule['id_vehicule']; ?>"
                                        class="flex-1 bg-blue-600 text-white text-center py-3 rounded-xl hover:bg-blue-700 transition">
                                        Réserver maintenant
                                    </a>
                                <?php endif; ?>
                                <a href="javascript:history.back()"
                                    class="px-6 py-3 border-2 border-blue-600 text-blue-600 rounded-xl hover:bg-blue-50 transition">
                                    Retour
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<div class="text-center text-red-600">Véhicule non trouvé.</div>';
                }
            } else {
                echo '<div class="text-center text-red-600">ID du véhicule non spécifié.</div>';
            }
            ?>
        </section>
        <!-- reviews Section -->
        <section
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-gray-800 to-black border border-gray-700 shadow-2xl p-8 mt-12">
            <div class="relative text-white">
                <?php if (empty($reviews)): ?>
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 text-center">
                        <i class="fas fa-calendar-xmark text-4xl text-gray-400 mb-4"></i>
                        <h2 class="text-xl font-semibold text-white mb-2">Aucune review</h2>
                        <p class="text-gray-400 mb-4">La vehicule n'avez pas encore de reviews</p>
                        <button type="button" id="openModal"
                            class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-black font-semibold py-3 px-6 rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105">
                            Ajouter un avis
                        </button>
                    </div>
                <?php else: ?>
                    <!-- Header Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold tracking-wide">Avis de nos clients</h2>
                        <button type="button" id="openModal"
                            class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-black font-semibold py-3 px-6 rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105">
                            Ajouter un avis
                        </button>
                    </div>
                    <!-- Carrousel -->
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
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
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <div id="reviewModal" class="fixed inset-0 bg-black/75 z-50 hidden">
            <!-- Modal Content -->
            <div class="flex items-center justify-center w-full h-full">
                <div class="bg-white dark:bg-gray-800 w-full max-w-md mx-4 rounded-xl shadow-xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Laisser un Avis</h2>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                            ✕
                        </button>
                    </div>

                    <form action="ficheTechnique.php" method="post" id="reviewForm">
                        <input type="hidden" name="id_vehicule" id="id_vehicule"
                            value="<?php echo htmlspecialchars($vehicule['id_vehicule']); ?>">
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Note</label>
                            <div class="flex gap-1">
                                <span data-rating="1" class="star text-3xl cursor-pointer text-gray-300"><i
                                        class="fas fa-star"></i></span>
                                <span data-rating="2" class="star text-3xl cursor-pointer text-gray-300"><i
                                        class="fas fa-star"></i></span>
                                <span data-rating="3" class="star text-3xl cursor-pointer text-gray-300"><i
                                        class="fas fa-star"></i></span>
                                <span data-rating="4" class="star text-3xl cursor-pointer text-gray-300"><i
                                        class="fas fa-star"></i></span>
                                <span data-rating="5" class="star text-3xl cursor-pointer text-gray-300"><i
                                        class="fas fa-star"></i></span>
                            </div>
                            <!-- Input caché pour stocker la valeur -->
                            <input type="hidden" name="rating" id="ratingInput" value="0">
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label for="review" class="block text-gray-700 mb-2">Votre Commentaire</label>
                            <textarea id="review" name="review" rows="4" required
                                class="w-full rounded-lg border-gray-300 border p-3 focus:border-blue-500"
                                placeholder="Partagez votre expérience..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Soumettre l'Avis
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-gradient-to-t from-blue-400 to-blue-600 rounded-[2rem] shadow-2xl border-4 border-white/20 mt-8">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div
                    class="flex justify-center items-center rounded-[33px] w-44 h-10 bg-[#e0e0e0] [box-shadow:inset_15px_15px_33px_#bebebe,_inset_-15px_-15px_33px_#ffffff]">
                    <a href="index.html" class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img src="../../img/logo.png" class="rounded-lg" alt="Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources
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
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us
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
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const modal = document.getElementById('reviewModal');
            const addReviewButton = document.getElementById('openModal');
            const closeBtn = document.getElementById('closeModal');
            const form = document.getElementById('reviewForm');
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('ratingInput');
            let currentRating = 0;

            // Action du bouton "Ajouter un avis"
            addReviewButton.onclick = function () {
                modal.classList.remove('hidden');
            };

            // Close modal
            closeBtn.onclick = function () {
                modal.classList.add('hidden');
            }

            // Star rating
            stars.forEach((star, index) => {
                // Hover effect
                star.addEventListener('mouseover', () => {
                    updateStars(index + 1);
                });

                star.addEventListener('mouseout', () => {
                    updateStars(currentRating);
                });

                // Click effect
                star.addEventListener('click', () => {
                    currentRating = index + 1;
                    ratingInput.value = currentRating;
                    updateStars(currentRating);
                });
            });

            function updateStars(count) {
                stars.forEach((star, index) => {
                    star.style.color = index < count ? '#fbbf24' : '#d1d5db';
                });
            }

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    modal.classList.add('hidden');
                }
            });

            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const swiper = new Swiper('.swiper-container', {
                loop: false,
                spaceBetween: 20,
                slidesPerView: 1,
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                },
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