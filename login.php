<?php
require_once "database.php";

$db = new DB;

if (isset($_POST['login'])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = $db->logIN($username, $password);

    if($user){
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['firstname']= $user['firstname'];
        $_SESSION['user_type'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];

        header("Location: pocetna.php");
    } else {
        header("Location: index.php");
    }
} 
?>