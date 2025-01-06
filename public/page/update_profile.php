<?php
    require_once '../app/controller/users.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['user_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $adresse = $_POST['adresse'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $id_role_fk = 3;

    $user = new User($id_user, $nom, $prenom, $username, $email, $telephone, $mot_de_passe, $adresse, $pays, $ville, $code_postal, $id_role_fk);
    $user->update();
    if ($user) {
        $_SESSION['user_nom'] = $nom;
        $_SESSION['user_prenom'] = $prenom;
        $_SESSION['user_username'] = $username;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_telephone'] = $telephone;
        $_SESSION['user_adresse'] = $adresse;
        $_SESSION['user_pays'] = $pays;
        $_SESSION['user_ville'] = $ville;
        $_SESSION['user_code_postal'] = $code_postal;
        header("Location: user.php");
    }
    
}