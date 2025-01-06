<?php
session_start();
require_once '../app/controller/users.php';
require_once '../app/controller/admins.php';
require_once '../app/controller/PasswordReset.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    $user = User::getByEmail($email);
    $admin = Admin::getByEmail($email);
    
    if ($user || $admin) {
        $token = PasswordReset::generateToken($email);
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/authentification/reset-password.php?email=" . urlencode($email) . "&token=" . $token;
        
        // Email content
        $to = $email;
        $subject = "Réinitialisation de votre mot de passe Drive-Loc";
        $message = "
            <h2>Réinitialisation de votre mot de passe</h2>
            <p>Bonjour,</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p>Cliquez sur le lien suivant pour réinitialiser votre mot de passe :</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>Ce lien expirera dans 1 heure.</p>
            <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
            <p>Cordialement,<br>L'équipe Drive-Loc</p>
        ";
        
        $headers = "From: bencheikh.official@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        if(mail($to, $subject, $message, $headers)) {
            $success = "Un email contenant les instructions de réinitialisation a été envoyé à votre adresse email.";
        } else {
            $error = "Erreur lors de l'envoi de l'email. Veuillez réessayer plus tard.";
        }
    } else {
        $success = "Si cette adresse email existe dans notre base de données, vous recevrez un email avec les instructions pour réinitialiser votre mot de passe.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive-Loc - Mot de passe oublié</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[url('../public/img/herocar.jpg')] bg-cover bg-center bg-fixed min-h-screen">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto p-8 bg-gradient-to-t from-blue-500/30 to-black/80 rounded-lg border border-white/20">
            <h2 class="text-center text-3xl font-bold text-white mb-8">Réinitialisation du mot de passe</h2>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-white">Email :</label>
                    <input type="email" name="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        placeholder="Entrez votre adresse email">
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Réinitialiser le mot de passe
                </button>

                <div class="text-center mt-4"></div>
                    <a href="login.php" class="text-sm text-indigo-400 hover:text-indigo-300 transition duration-150">
                        Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
