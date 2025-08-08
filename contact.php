<?php
session_start();
if(!$_SESSION){ echo 'Nisi ulogovan !'; } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="LoginRegisterPage" style="height: 100vh;">
    <div class="container">
        <div class="wrapper">
        <table class="tekstStart">
            <tr>
                <td>E-mail:</td>
            </tr>
            <tr>
                <td>adjokic24@gmail.com</td>
            </tr>
            <tr>
                <td>arsa03radnicki@gmail.com</td>
            </tr>
            <tr></tr>
            <tr>
                <td>Phone:</td>
            </tr>
            <tr>
                <td>XXXXXXXXXXXX</td>
            </tr>
            <tr>
                <td>XXXXXXXXXXXX</td>    
            </tr>
        </table>
        <br>
            
            <button type ="button" class ="startPageBttn" onclick="location.href = 'pocetna.php';" style="width: 88%;">Go back</button>
        </div>
    </div>
</body>
</html>

<?php } ?>