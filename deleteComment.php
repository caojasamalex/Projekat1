<?php
session_start();

if ($_SESSION) {
    if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_id'] === $_POST['oglasavac']) {
        require_once "database.php";
        $db = new DB;

        if (isset($_POST['identifikacija2'])) {
            $commentID = $_POST['identifikacija2'];
            $db->deleteCommentByCommentID($commentID);

            $artistID = $_POST['artist'];
            $artworkID = $_POST['artwork'];
            $location = $_POST['location'];

            header('Location: ' . $location);
            exit();
        }
    } else {
        echo "Dobar pokusaj -> Nisi admin !";
    }
} else {
    echo "Dobar pokusaj -> Nisi ulogovan !";
}
?>
