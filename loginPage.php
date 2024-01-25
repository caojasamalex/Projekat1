<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Form</title>
</head>
<body class="LoginRegisterPage" style="height:100vh;">
    <div class="container">
        <div class="wrapper">
            <div class="loginRegisterRedirect">
                <p class="loginRegisterRedirectText">Don't have an account ?</p><button class ="loginRegisterRedirectButton" onclick="location.href = 'registerPage.php';">Register</button>
            </div>
            
            <form action="login.php" method="post" class="login-form">
                <input type="text" id="loginUsername" name="username" required placeholder="Username" class="inputLogRes"><br>

                <input type="password" id="loginPassword" name="password" required placeholder="Password" class="inputLogRes"><br>

                <input class="startPageBttn" type="submit" value="Login" name="login">
                <input class="startPageBttn" type="reset" value="Reset">
            </form>
            <button type ="button" class ="startPageBttn" onclick="location.href = 'index.php';" style="width: 88%;">Go back</button>

            <script src="script.js"></script>
        </div>
    </div>
</body>
</html>
