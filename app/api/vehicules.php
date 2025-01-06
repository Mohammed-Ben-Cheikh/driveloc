<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Fix paths and add categories controller
require_once '../database/Database.php';
require_once '../controller/vehicules.php';
require_once '../controller/categories.php';  // Add this line

try {
    // Initialiser la réponse
    $response = [
        'status' => 200,
        'message' => 'Success',
        'data' => null
    ];

    // Vérifier la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Method not allowed', 405);
    }

    // Récupérer les paramètres de filtrage (optionnels)
    $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
    $disponibilite = isset($_GET['disponibilite']) ? $_GET['disponibilite'] : null;

    // Récupérer les véhicules
    $vehicles = Vehicule::getAll();

    // Appliquer les filtres si nécessaire
    if ($categoryId) {
        $vehicles = array_filter($vehicles, function($vehicle) use ($categoryId) {
            return $vehicle['id_categorie_fk'] == $categoryId;
        });
    }

    if ($disponibilite) {
        $vehicles = array_filter($vehicles, function($vehicle) use ($disponibilite) {
            return $vehicle['disponibilite'] == $disponibilite;
        });
    }

    // Enrichir les données avec les informations de catégorie
    foreach ($vehicles as &$vehicle) {
        $categorie = Categorie::getById($vehicle['id_categorie_fk']);
        $vehicle['categorie'] = $categorie;
        
        // Formater le prix
        $vehicle['prix_formatte'] = number_format($vehicle['prix_a_loue'], 2) . ' €';
        
        // Ajouter l'URL complète de l'image
        $vehicle['image_url'] = !empty($vehicle['image_url']) 
            ? $vehicle['image_url']
            : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTT8Dz8qYs4KOK0y_U-rzqpNubte8HQBQ48dw&s';
    }

    $response['data'] = array_values($vehicles);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    $response = [
        'status' => $e->getCode() ?: 500,
        'message' => $e->getMessage(),
        'data' => null
    ];
}

// Envoyer la réponse
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
