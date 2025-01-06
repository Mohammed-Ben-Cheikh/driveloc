<?php
session_start();
require_once '../app/controller/users.php';
require_once '../app/controller/admins.php';
require_once '../app/controller/PasswordReset.php';

$success = $error = '';
$validToken = false;

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];
    
    if (PasswordReset::verifyToken($email, $token)) {
        $validToken = true;
    } else {
        $error = "Le lien de réinitialisation est invalide ou a expiré.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $user = User::getByEmail($email);
        $admin = Admin::getByEmail($email);
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        if ($user) {
            User::updatePassword($email, $hashed_password);
        } else if ($admin) {
            Admin::updatePassword($email, $hashed_password);
        }
        
        PasswordReset::markTokenAsUsed($email, $token);
        $success = "Votre mot de passe a été réinitialisé avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-Loc - Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[url('../public/img/herocar.jpg')] bg-cover bg-center bg-fixed min-h-screen">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto p-8 bg-gradient-to-t from-blue-500/30 to-black/80 rounded-lg border border-white/20">
            <h2 class="text-center text-3xl font-bold text-white mb-8">Nouveau mot de passe</h2>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <?php echo $success; ?>
                    <div class="text-center mt-4">
                        <a href="login.php" class="text-green-700 hover:text-green-900 underline">Retour à la connexion</a>
                    </div>
                </div>
            <?php elseif ($validToken): ?>
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-white">Nouveau mot de passe :</label>
                        <input type="password" name="password" required minlength="8"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white">Confirmer le mot de passe :</label>
                        <input type="password" name="confirm_password" required minlength="8"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Réinitialiser le mot de passe
                    </button>
                </form>
            <?php else: ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <?php echo $error ?: "Lien de réinitialisation invalide."; ?>
                    <div class="text-center mt-4">
                        <a href="login.php" class="text-red-700 hover:text-red-900 underline">Retour à la connexion</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
