<?php
require_once "database.php";

$db = new DB;

if (isset($_POST["register"])){
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $user_type = $_POST["user_type"];

    $checkIfUsernameExistsQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkIfUsernameExistsQueryRes = $db->db->query($checkIfUsernameExistsQuery);

    if($checkIfUsernameExistsQueryRes->num_rows){
        header("Location: registerPage.php");
        exit();
    }

    $checkIfEmailExistsQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkIfEmailExistsQueryRes = $db->db->query($checkIfEmailExistsQuery);

    if($checkIfEmailExistsQueryRes->num_rows){
        header("Location: registerPage.php");
        exit();
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: registerPage.php");
        exit();
    }

    $query = "INSERT INTO users (username, email, firstname, lastname, password, role) VALUES ('$username', '$email', '$firstname','$lastname','$password', '$user_type')";
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