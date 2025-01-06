<?php
session_start();
require_once '../../../controller/admins.php';

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de l'administrateur manquant";
    header('Location: ../../../Dashboard/page/admins.php');
    exit();
}

$id = $_GET['id'];

try {
    if (Admin::delete($id)) {
        $_SESSION['success'] = "Administrateur supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'administrateur";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../../Dashboard/page/admins.php');
exit();