<?php
session_start();
if(isset($_SESSION)){
    session_unset();
}

$_SESSION['username'] ="Guest";
$_SESSION['user_type'] = "guest";

header("LOCATION: pocetna.php" );
exit();
?>