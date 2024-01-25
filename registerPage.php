<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register Form</title>
</head>
<body class="LoginRegisterPage" style="height:100vh;">
    <div class="container"> 
        <div class="wrapper">
            <div class="loginRegisterRedirect">
                <p class="loginRegisterRedirectText">Already have an account ?</p><button class ="loginRegisterRedirectButton" onclick="location.href = 'loginPage.php';">Login</button>
            </div>

            <form action="register.php" method="post" class="register-form">
                
                <input type="text" id="username" name="username" required placeholder="Username" class="inputLogRes"><br>
                <input type="text" id="firstname" name="firstname" required placeholder="First Name" class="inputLogRes"><br>
                <input type="text" id="lastname" name="lastname" required placeholder="Last Name" class="inputLogRes"><br>
                <input type="password" id="password" name="password" required placeholder="Password" class="inputLogRes"><br>

                <input type="radio" id="user" name="user_type" value="user" checked hidden>
                <input type="radio" id="artist" name="user_type" value="artist" hidden>
                <input type="radio" id="admin" name="user_type" value="admin" hidden>

                <input class="startPageBttn" type="submit" value="Register" name="register">
                <input class="startPageBttn" type="reset" value="Reset">
            </form>

            <button type ="button" class ="startPageBttn" onclick="location.href = 'index.php';" style="width: 88%;">Go back</button>

            <script src="script.js"></script>
        </div>
    </div>
</body>
</html>
