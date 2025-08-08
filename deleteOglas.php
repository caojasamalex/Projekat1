<?php
session_start();

if ($_SESSION) {
    if(isset($_POST['oglasavacID']) && isset($_POST['oglasID'])){
        if($_SESSION['user_type'] === 'admin' || $_SESSION['user_id'] === $_POST['oglasavacID']){
            require_once "database.php";
            $db = new DB;
            $oglasId = $_POST['oglasID'];

            $db->deleteOglasByOglasID($oglasId);

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