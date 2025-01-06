<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: ../../index.php");
    exit();
}

require_once "../../app/controller/reservations.php";
require_once "../../app/controller/vehicules.php";
require_once "../../app/helpers/functions.php"; // Add this line

// Get user's reservations
$user_id = $_SESSION['user_id'];
$reservations = Reservation::getByUser($user_id);

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_reservation = $_POST['id_reservation'] ?? '';

    if ($action === 'cancel') {
        try {
            if (Reservation::updateStatut($id_reservation, 'annulée')) {
                $success = "Votre réservation a été annulée avec succès.";
                header("Refresh:0"); // Refresh the page
            } else {
                $error = "Erreur lors de l'annulation de la réservation.";
            }
        } catch (Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Drive-Loc</title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 flex-col p-4">
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
    <main>
        <section
            class="relative overflow-hidden rounded-[2rem] bg-black/35 backdrop-blur-sm border-4 border-white shadow-2xl p-8 my-8">
            <div class="absolute inset-0  mix-blend-overlay  p-2">
                <img src="../img/herocar.jpg" alt="" class="w-full h-full rounded-3xl object-cover">
            </div>
            <div class="relative z-10 flex flex-col justify-center items-center text-white p-8">
                <h1 class="text-5xl font-bold mb-4 text-white">
                    Mes Réservations
                </h1>
                <p class="text-xl text-gray-100 text-center">
                    Gérez vos réservations de véhicules
                </p>
            </div>
        </section>


        <?php if (empty($reservations)): ?>
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 text-center">
                <i class="fas fa-calendar-xmark text-4xl text-gray-400 mb-4"></i>
                <h2 class="text-xl font-semibold text-white mb-2">Aucune réservation</h2>
                <p class="text-gray-400 mb-4">Vous n'avez pas encore de réservation active.</p>
                <a href="vehicules.php"
                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Découvrir nos véhicules
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($reservations as $reservation):
                    $vehicule = Vehicule::getById($reservation['id_vehicule_fk']);
                    ?>
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl overflow-hidden border border-white/10">
                        <!-- Vehicle Image -->
                        <div class="relative h-48">
                            <img src="<?php echo $vehicule['image_url']; ?>"
                                alt="<?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?>"
                                class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4">
                                <span
                                    class="px-4 py-2 rounded-full text-sm font-semibold <?php echo getStatusClass($reservation['statut']); ?>">
                                    <?php echo getStatusLabel($reservation['statut']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2">
                                <?php echo $vehicule['marque'] . ' ' . $vehicule['modele']; ?>
                            </h3>

                            <div class="space-y-3 text-gray-300">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt w-6"></i>
                                    <span>Du: <?php echo date('d/m/Y', strtotime($reservation['date_reservation'])); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check w-6"></i>
                                    <span>Au: <?php echo date('d/m/Y', strtotime($reservation['date_limite'])); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-6"></i>
                                    <span><?php echo $reservation['lieux']; ?></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill w-6"></i>
                                    <span><?php echo $vehicule['prix_a_loue']; ?> €/jour</span>
                                </div>
                            </div>

                            <?php if ($reservation['commentaire']): ?>
                                <div class="mt-4 p-4 bg-gray-800/50 rounded-lg">
                                    <p class="text-gray-400 text-sm italic">
                                        "<?php echo $reservation['commentaire']; ?>"
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Status Timeline -->
                            <div class="mt-6 relative">
                                <div class="flex justify-between mb-2">
                                    <?php
                                    $statuses = ['en attente', 'approuvée', 'terminée'];
                                    $currentIndex = array_search($reservation['statut'], $statuses);

                                    foreach ($statuses as $index => $status):
                                        $isActive = $index <= $currentIndex;
                                        ?>
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-6 h-6 rounded-full <?php echo $isActive ? 'bg-blue-500' : 'bg-gray-600'; ?> flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-xs text-gray-400 mt-1"><?php echo ucfirst($status); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="absolute top-3 left-0 right-0 h-[2px] bg-gray-600 -z-10">
                                    <div class="h-full bg-blue-500" style="width: <?php echo ($currentIndex / 2) * 100; ?>%">
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <?php if ($reservation['statut'] === 'en attente' || $reservation['statut'] === 'approuvée'): ?>
                                <div class="mt-6 flex gap-2">
                                    <form method="POST" class="w-full"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                        <input type="hidden" name="id_reservation"
                                            value="<?php echo $reservation['id_reservation']; ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit"
                                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-times"></i>
                                            Annuler
                                        </button>
                                    </form>
                                    <?php if ($reservation['statut'] === 'approuvée'): ?>
                                        <a href="facture.php?id=<?php echo $reservation['id_reservation']; ?>"
                                            class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-file-invoice"></i>
                                            Facture
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
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
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
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
                            <path
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