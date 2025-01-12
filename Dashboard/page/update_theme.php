<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (!isset($_SESSION['admin_id']) && !$_SESSION['admin_id']) {
    header("Location: ../../index.php");
}
require_once '../../app/controller/themes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $themeId = $_POST['themeId'];
        $nom = $_POST['themeName'];
        $description = $_POST['themeDesc'];
        $currenttheme = Theme::getById($themeId);
        $image_url = $currenttheme['image_url'];

        // Handle new image upload
        if (isset($_FILES['themeImage']) && $_FILES['themeImage']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['themeImage']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($filetype), $allowed)) {
                $newname = uniqid() . '.' . $filetype;
                $upload_path = '../uploads/themes/' . $newname;

                if (!is_dir('../uploads/themes/')) {
                    mkdir('../uploads/themes/', 0777, true);
                }

                if (move_uploaded_file($_FILES['themeImage']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if ($image_url && file_exists('../' . $image_url)) {
                        unlink('../' . $image_url);
                    }
                    $image_url = 'uploads/themes/' . $newname;
                }
            }
        }

        // Update theme
        $theme = new Theme($nom, $description, $image_url,$themeId);
        $result = $theme->update();

        echo json_encode(['success' => true, 'message' => 'Catégorie mise à jour avec succès']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>
