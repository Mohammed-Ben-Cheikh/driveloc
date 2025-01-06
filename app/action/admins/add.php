<?php
require_once '../../controller/admins.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = 'Admin-' . str_replace(' ', '', $_POST['nom']);
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } else if (Admin::getByEmail($email)) {
        $error = 'Cet email est déjà utilisé';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $admin = new Admin(
            $role,
            $nom,
            $prenom,
            $username,
            $email,
            $telephone,
            $hashed_password
        );
        $id = $admin->create();
        if ($id) {
            header('Location: ../../../Dashboard/page/admins.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Administrateur</title>
    <link rel="stylesheet" href="../../../src/output.css">
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

        .fade-in-left {
            animation: fadeInLeft 1s ease-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-[url('../../img/herocar.jpg')] bg-cover bg-center bg-fixed min-h-screen">
    <div class="flex min-h-screen">
        <!-- Zone gauche avec contenu -->
        <div
            class="hidden md:flex md:w-1/2 bg-gradient-to-r from-black/80 to-transparent p-8 flex-col justify-center items-start">
            <div class="max-w-lg fade-in-left">
                <h1 class="text-5xl font-bold text-white mb-6">Ajouter un Administrateur</h1>
                <p class="text-xl text-white/90 mb-4">Gérez les administrateurs de votre plateforme en ajoutant de
                    nouveaux membres.</p>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-white">Gestion simplifiée</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-white">Sécurité renforcée</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="text-white">Accès contrôlé</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone droite (formulaire) -->
        <div class="w-full md:w-1/2 bg-gradient-to-t from-blue-500/30 to-black/80 border-l border-white/20">
            <div class="h-screen p-8 flex flex-col">
                <!-- Header -->
                <div class="flex-none">
                    <h2 class="text-center text-3xl font-bold text-white mb-4">Ajouter un Administrateur</h2>
                    <?php if ($error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Scrollable Form -->
                <div class="flex-grow overflow-y-auto custom-scrollbar pr-4">
                    <form method="POST" id="addAdminForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white">Nom :</label>
                                <input type="text" name="nom" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white">Prénom :</label>
                                <input type="text" name="prenom" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white">Email :</label>
                            <input type="email" name="email" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white">Téléphone :</label>
                            <input type="text" name="telephone" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white">Mot de passe :</label>
                                <input type="password" name="password" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-white">Confirmer le mot de passe :</label>
                                <input type="password" name="confirm_password" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-white">Rôle :</label>
                            <select name="role"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="flex-none pt-4 mt-4 border-t border-white/20">
                    <button type="submit" form="addAdminForm"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Ajouter
                    </button>
                    <p class="mt-4 text-center text-sm text-white">
                        <a href="../../../Dashboard/page/admins.php"
                            class="font-medium text-indigo-400 hover:text-indigo-300 transition duration-150">Annuler</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>