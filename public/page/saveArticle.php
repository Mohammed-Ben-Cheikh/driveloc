<?php
session_start();
require_once '../../app/controller/articles.php';

// Headers pour permettre les requêtes cross-origin (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Utilisateur non connecté.';
        echo json_encode($response);
        exit;
    }

    $id = $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $theme = $_POST['theme'] ?? '';
    $disc = $_POST['disc'] ?? '';
    $imagePath = null;

    // Validation des champs obligatoires
    if (empty($title) || empty($content) || empty($theme) || empty($disc)) {
        $response['message'] = 'Tous les champs sont requis.';
        echo json_encode($response);
        exit;
    }

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDir . uniqid() . '_' . $fileName;
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array(mime_content_type($_FILES['image']['tmp_name']), $allowedMimeTypes)) {
            $response['message'] = 'Type de fichier non supporté.';
            echo json_encode($response);
            exit;
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            $imagePath = $uploadFilePath;
        } else {
            $response['message'] = 'Erreur lors de l\'upload de l\'image.';
            echo json_encode($response);
            exit;
        }
    }

    // Création de l'article
    $article = new Article($id, $title, $disc, $content, $imagePath, $theme);

    if ($article->create()) {
        $response['success'] = true;
        $response['message'] = 'Article enregistré avec succès.';
    } else {
        $response['message'] = 'Erreur lors de l\'enregistrement de l\'article.';
    }
} else {
    $response['message'] = 'Méthode non autorisée.';
}

// Envoyer la réponse en JSON
echo json_encode($response);
