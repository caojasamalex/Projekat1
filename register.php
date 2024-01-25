<?php
require_once "database.php";

$db = new DB;

if (isset($_POST["register"])){
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    $checkIfUsernameExistsQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkIfUsernameExistsQueryRes = $db->db->query($checkIfUsernameExistsQuery);

    if($checkIfUsernameExistsQueryRes->num_rows){
        header("Location: register.php");
        exit();
    }

    $query = "INSERT INTO users (username, firstname, lastname, password, role) VALUES ('$username', '$firstname','$lastname','$password', '$user_type')";
    $result = $db->db->query($query);

    if ($result) {
        header("Location: index.php");
    } else {
        echo "Registration failed. Some values are NULL or something else...\n Check error logs\n";
    }
} else if(isset($_POST["adminRegister"])){
    session_start();

    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    $checkIfUsernameExistsQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkIfUsernameExistsQueryRes = $db->db->query($checkIfUsernameExistsQuery);

    if($checkIfUsernameExistsQueryRes->num_rows){
        header("Location: kontrolnipanel.php");
        exit();
    }

    $query = "INSERT INTO users (username, firstname, lastname, password, role) VALUES ('$username', '$firstname','$lastname','$password', '$user_type')";
    $result = $db->db->query($query);

    if($result) {
        header("Location: kontrolnipanel.php");
    } else {
        echo "Registration failed. Some values are NULL or something else...\n Check error logs\n";
    }
}
?>