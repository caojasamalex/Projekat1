<?php
session_start(); 
require_once "database.php";

$db = new DB;
if($_SESSION){
    if($_SESSION['user_type'] === "user"){
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Novi oglas</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="LoginRegisterPage" style="height: 100vh;">
        <?php
        if (isset($_POST['submitOglas'])){
            $userID = $_SESSION['user_id'];
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . $userID . "_" . basename($_FILES['photo']['name']);
        
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {       
                $cost = filter_var($_POST['cost'], FILTER_VALIDATE_FLOAT);
                if ($cost === false) {
                    $cost = 0;
                }

                $title = $db->db->real_escape_string($_POST['title']);
                $description = $db->db->real_escape_string($_POST['description']);
                $selectedCategory = intval($_POST['category']);
                $uploadFile = $db->db->real_escape_string($uploadFile);

                $insertPhoto = "INSERT INTO oglasi (user_id, title, description, image_url, cost, visits, category_id) VALUES ('$userID', '$title', '$description', '$uploadFile', '$cost', 0, $selectedCategory)";
                $db->db->query($insertPhoto);

                header("Location: pocetna.php");
                exit;
            } else {
                echo 'Error uploading the file.';
            }
        }
        ?>
        <div class="container">
            <div class="wrapper">
                    <div class="loginRegisterRedirect" style="justify-content: center;">
                        <button class ="loginRegisterRedirectButton" onclick="location.href = 'pocetna.php';">Go back to the Main Page</button>
                    </div>
                <form action="create_oglas.php" method="post" enctype="multipart/form-data">
                    <div class="formOglas">
                        <label for="photo">Upload Photo:</label>
                        <input type="file" name="photo" id="photo" required class="formOglasFile"><br>
                    </div>
                    <select name="category" class="inputLogRes" style="width:100%;">
                        <option value="">All Categories</option>
                        <?php
                        $queryCategory = "SELECT * FROM categories";
                        $queryCategoryRes = $db->db->query($queryCategory);
                        if($queryCategoryRes->num_rows > 0){
                            while($row = $queryCategoryRes->fetch_assoc()){
                                echo "<option value='{$row['category_id']}'>{$row['category_name']}</option>";
                            }
                        }
                        ?>
                    </select>
            
                    <input type="text" name="title" id="title" required placeholder="Title" class="inputLogRes"><br>
                    <input type="text" name="cost" id="cost" placeholder="Price" class="inputLogRes"><br>
            
            
                    <textarea name="description" id="description" rows="4" required placeholder="Description" class="inputLogRes"></textarea><br>
                
                    <input class="startPageBttn" type="submit" value="Submit" name="submitOglas">
                </form>
            </div>
        </div>
    </body>
</html>

<?php } else echo 'Za malo -> Nisi umetnik !';
} else echo 'Za malo -> Nisi ni ulogovan !'?>