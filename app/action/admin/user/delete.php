<?php
require_once '../../../controller/users.php';
require_once '../../../controller/statistiquesManager.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    try {
        $result = User::delete($userId);
        
        if ($result) {
            // Update statistics after successful deletion
            StatistiquesManager::calculerEtMettreAJour();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la suppression de l\'utilisateur');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Requête invalide ou ID utilisateur manquant'
    ]);
}
