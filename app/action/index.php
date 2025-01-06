<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: ../../public");
} else if (isset($_SESSION['admin_id']) && $_SESSION['admin_id']) {
    header("Location: ../../dashboard");
} else {
    header("Location: ../../index.php");
}
?>