<?php
session_start();

if ($_SESSION) {
    if(isset($_POST['artistID']) && isset($_POST['artworkID'])){
        if($_SESSION['user_type'] === 'admin' || $_SESSION['user_id'] === $_POST['artistID']){
            require_once "database.php";
            $db = new DB;
            $artworkID = $_POST['artworkID'];

            $db->deleteArtworkByArtworkID($artworkID);

            if($_SESSION['user_type'] === 'admin') header('Location: kontrolnipanel.php');
            else header('Location: profile.php?id='.$_SESSION['user_id']);
            exit();
        } else {
            echo "Dobar pokusaj -> Nisi admin ni vlasnik slike !";
        }
    }
 } else {
        echo "Dobar pokusaj -> Nisi ulogovan !";
}
?>