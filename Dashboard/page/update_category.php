<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}
require_once '../../app/controller/categories.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $categoryId = $_POST['categoryId'];
        $nom = $_POST['categoryName'];
        $description = $_POST['categoryDesc'];
        $currentCategory = Categorie::getById($categoryId);
        $image_url = $currentCategory['image_url'];

        // Handle new image upload
        if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['categoryImage']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($filetype), $allowed)) {
                $newname = uniqid() . '.' . $filetype;
                $upload_path = '../uploads/categories/' . $newname;

                if (!is_dir('../uploads/categories/')) {
                    mkdir('../uploads/categories/', 0777, true);
                }

                if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if ($image_url && file_exists('../' . $image_url)) {
                        unlink('../' . $image_url);
                    }
                    $image_url = 'uploads/categories/' . $newname;
                }
            }
        }

        // Update category
        $category = new Categorie($nom, $description, $image_url,$categoryId);
        $result = $category->update();

        echo json_encode(['success' => true, 'message' => 'Catégorie mise à jour avec succès']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>
