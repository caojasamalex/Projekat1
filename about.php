<?php
session_start();

if(!$_SESSION){ echo 'Nisi ulogovan !'; } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="LoginRegisterPage" style="height: 100vh;">
    <div class="container">
        <div class="wrapper">
            <p class="tekstStart">
                Test about us
            </p>
            <button type ="button" class ="startPageBttn" onclick="location.href = 'pocetna.php';" style="width: 88%;">Go back</button>
        </div>
    </div>
</body>
</html>
<?php } ?>