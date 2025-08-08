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
                <td>Razvojni tim:</td>
            </tr>
            <tr>
                <td>Mihajlo Spasić</td>
            </tr>
            <tr>
                <td>Aleksandar Đokić</td>
            </tr>
            <tr>
                <td>Arsenije Jokić</td>
            </tr>
            <tr>
                <td>Janko Jakovljević</td>
            </tr>
            <tr>
                <td>Ognjen Obradović</td>
            </tr>
            <tr></tr>
            <tr>
                <td>Telefon:</td>
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