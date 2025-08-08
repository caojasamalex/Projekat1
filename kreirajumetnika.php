<?php session_start();

if($_SESSION){
    if($_SESSION['user_type'] === "admin"){
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create an Artist</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="LoginRegisterPage" style="height: 100vh;">
        <div class="container">
            <div class="wrapper">
                <div class="loginRegisterRedirect">
                    <button class ="loginRegisterRedirectButton" onclick="location.href = 'kontrolnipanel.php';">Go Back</button>
                    <p class="tekstStart">Create an Artist</p>
                </div>
                <form action="register.php" method="post" class="register-form">
                    <input type="text" id="username" name="username" required class="inputLogRes" placeholder="Username"><br>
                    <input type="text" id="firstname" name="firstname" required class="inputLogRes" placeholder="First Name"><br>
                    <input type="text" id="lastname" name="lastname" required class="inputLogRes" placeholder="Last Name"><br>
                    <input type="password" id="password" name="password" required class="inputLogRes" placeholder="Password"><br>
                    <input type="hidden" id="artist" name="user_type" value="artist" checked>         
                    <br>
                    <input class="startPageBttn" type="submit" value="Register" style="width: 48%" name="adminRegister">
                    <input class="startPageBttn" type="reset" value="Reset" style="width: 46%">
                </form>
            </div>
        </div>
    </body>
</html>

<?php } else echo 'Za malo -> Nisi admin !';
} else echo 'Za malo -> Nisi ni ulogovan !'?>