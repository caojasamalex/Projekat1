<?php
session_start(); 
require_once "database.php";

$db = new DB;
if($_SESSION){
    if($_SESSION['user_type'] === "artist"){
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create an artwork</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="LoginRegisterPage" style="height: 100vh;">
        <?php
        if (isset($_POST['submitArtwork'])){
            $userID = $_SESSION['user_id'];
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . $userID ."_". basename($_FILES['photo']['name']);
         
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {        
                $date = $db->db->real_escape_string($_POST['creation_date']);
                $dateObject = DateTime::createFromFormat('Y-m-d', $date);
                if (!$dateObject || $dateObject->format('Y-m-d') !== $date) {
                    die("Error: Invalid date format. Please use YYYY-MM-DD.");
                }
                
                $cost = filter_var($_POST['cost'], FILTER_VALIDATE_FLOAT);
                if ($cost === false) {
                    $cost = 0;
                }
        
                $on_sale = isset($_POST['on_sale']) ? 1 : 0;
                $title = $db->db->real_escape_string($_POST['title']);
                $description = $db->db->real_escape_string($_POST['description']);
                $dimensions = $db->db->real_escape_string($_POST['dimensions']);
                $technique = $db->db->real_escape_string($_POST['technique']);
                $uploadFile = $uploadDir . $userID ."_". basename($_FILES['photo']['name']);
        
                $insertPhoto = "INSERT INTO artworks (artist_id, title, description, image_url, creation_date, technique, cost, on_sale, dimensions) VALUES ('$userID', '$title', '$description', '$uploadFile', '$date', '$technique', '$cost', '$on_sale', '$dimensions')";
        
                $db->db->query($insertPhoto);
        
                header("Location: pocetna.php");
        
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
                <form action="create_artwork.php" method="post" enctype="multipart/form-data">
                    <div class="formArtwork">
                        <label for="photo">Upload Photo:</label>
                        <input type="file" name="photo" id="photo" required class="formArtworkFile"><br>
                    </div>

                    <input type="text" name="creation_date" id="creation_date" required placeholder="Date of Creation" class="inputLogRes"><br>
                    <input type="text" name="dimensions" id="dimensions" required placeholder="Dimensions" class="inputLogRes"><br>
                
                    <input type="text" name="technique" id="technique" required placeholder="Technique" class="inputLogRes"><br>
            
                    <input type="text" name="title" id="title" required placeholder="Title" class="inputLogRes"><br>
                    <input type="text" name="cost" id="cost" placeholder="Price" class="inputLogRes"><br>
                
                    <div class="onSale">
                        <label for="on_sale">On Sale:</label>
                        <input type="checkbox" name="on_sale" id="on_sale"><br>
                    </div>
            
                    <textarea name="description" id="description" rows="4" required placeholder="Description" class="inputLogRes"></textarea><br>
                
                    <input class="startPageBttn" type="submit" value="Submit" name="submitArtwork">
                </form>
            </div>
        </div>
    </body>
</html>

<?php } else echo 'Za malo -> Nisi umetnik !';
} else echo 'Za malo -> Nisi ni ulogovan !'?>