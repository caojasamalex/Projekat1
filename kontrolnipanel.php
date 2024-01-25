<?php
session_start(); 
require_once "database.php";

$db = new DB;
if($_SESSION){
    if($_SESSION['user_type'] === "admin"){
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Control Panel</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrapperAdminPanel">
            <div class="navbar">
                <button type ="button" class ="adminPanelBttn" onclick="location.href = 'pocetna.php';" style="width: 88%;">Main Page</button>
                <button type ="button" class ="adminPanelBttn" onclick="location.href = 'kreirajumetnika.php';" style="width: 88%;">Create an Artist</button>
                <button type ="button" class ="adminPanelBttn" onclick="location.href = 'kreirajadmina.php';" style="width: 88%;">Create an Admin</button>
            </div>

            <div class="tableWrapper" style="margin-top: 70px;">
            <?php
                $temp = $_SESSION['user_id'];
                $queryUsers = "SELECT * FROM users WHERE user_id != $temp";
                // Uzimamo sve usere osim trenutnog administratorskog naloga kako bismo ih prikazali u tabeli
                $getusers = $db->db->query($queryUsers);
        
                if ($getusers->num_rows > 0){
                    echo "<table style='margin-top:20px;'>";
                        echo "<thead><tr><th scope='col'>User</th><th scope='col'>Link</th></tr></thead>";
                        echo "<tbody>";
                    while ($korisnik = $getusers->fetch_assoc()) {
                            $userid = $korisnik['user_id'];
                            $userName = $korisnik['username'];
                            echo "<tr class='row'>";
                                echo "<td>$userName</td>";
                                echo "<td><a class='adminPanelBttn' href='inspectuser.php?id=$userid'>Details</a></td>";
                            echo "</tr>";
                    }
                    echo "</tbody>
                    </table>";
                    echo "<hr>";
                    echo "</div>";
                }

                $queryArtworks = "SELECT * FROM artworks";
                $getArtowrks = $db->db->query($queryArtworks);

                if($getArtowrks->num_rows > 0){
                    echo '<div class="container" style="width: 80%;">';
                    echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                    echo '<h2>All artworks</h2>';
                    while($artwork = $getArtowrks->fetch_assoc()){
                        $title = $artwork["title"];
                        $authorID = $artwork["artist_id"];
                        $author = $db->getUserByID($authorID);
                        $authorName = $author["firstname"]. " " .$author["lastname"];
                        $imageURL = $artwork["image_url"];
                        $redirekcijaAdmin = "inspect_picture.php?id=".$authorID."-".$artwork['artwork_id'];
                        ?>
                        <div class="container" style="margin: 15px;">
                            <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                <h2>Title: <?php echo $title; ?></h2>
                                <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                <h3>Author's Name: <?php echo $authorName; ?></h3>
                                <button type="button" onclick="location.href = '<?php echo $redirekcijaAdmin; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Admin actions</button>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>
            </div>
        </div>
    </body>
</html>
<?php } else echo 'Za malo -> Nisi admin !';
} else echo 'Za malo -> Nisi ni ulogovan !'; ?>