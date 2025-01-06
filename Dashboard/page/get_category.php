<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}

require_once '../../app/controller/categories.php';

if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];
    $category = Categorie::getById($categoryId);
    
    if ($category) {
        header('Content-Type: application/json');
        echo json_encode($category);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Category not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No category ID provided']);
}
