<?php 
session_start();

require_once "database.php";

$db = new DB;

if(!$_SESSION){
    echo "Nisi ulogovan !";
    exit();
}

if(isset($_GET['oglasavacID'])){
    if($_SESSION['user_id'] != $_GET['oglasavacID']){
        $userIDQuery = "SELECT * FROM users WHERE user_id = {$_GET['oglasavacID']}";
        $userIDQueryRes = $db->db->query($userIDQuery);

        if($userIDQueryRes->num_rows){
            $user = $userIDQueryRes->fetch_assoc();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Korisnik <?php echo $user['user_id']; ?> - Kontakt Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="LoginRegisterPage" style="height: 100vh;">
    <div class="container">
        <div class="wrapper">
            <h4>Name : <?php echo $user['firstname'] . " " . $user['lastname']; ?></h4>
            <h4>Username: <?php echo $user['username']; ?></h4>
            <h4>Email: <?php echo $user['email']; ?></h4>
            <form method="post" action="send_email.php">
                <input type="hidden" name="recipient_email" value="<?php echo $user['email']; ?>">
                <input type="hidden" name="sender_email" value="<?php
                $senderQuery = "SELECT * FROM users WHERE user_id = {$_SESSION['user_id']}";
                $senderQueryRes = $db->db->query($senderQuery);

                $sender = $senderQueryRes->fetch_assoc();

                echo $sender['email'];
                ?>">
                <textarea name="message" id="message" rows="4" required class="inputLogRes"></textarea><br>
                <button type="submit" name="send_email" class="startPageBttn" style="width: 100%;">Contact by Email</button>
            </form>
        </div>
    </div>
</body>
</html>


        <?php
        }
    } else {
        echo "Ne mozete kontaktirati samu/og sebe";
        exit();
    }
}
?>