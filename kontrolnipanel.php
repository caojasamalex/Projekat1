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
        <title>Kontrolni Panel - ProdajemKupujem</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrapperAdminPanel">
            <div class="navbar">
                <button type ="button" class ="adminPanelBttn" onclick="location.href = 'pocetna.php';" style="width: 88%;">Main Page</button>
                <button type ="button" class ="adminPanelBttn" onclick="location.href = 'request_category.php';" style="width: 88%;">Category requests</button>
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
                        echo "<thead><tr><th scope='col'>Korisnik</th><th scope='col'>Profil</th></tr></thead>";
                        echo "<tbody>";
                    while ($korisnik = $getusers->fetch_assoc()) {
                            $userid = $korisnik['user_id'];
                            $userName = $korisnik['username'];
                            echo "<tr class='row'>";
                                echo "<td>$userName</td>";
                                echo "<td><a class='adminPanelBttn' href='inspectuser.php?id=$userid'>Detalji profila</a></td>";
                            echo "</tr>";
                    }
                    echo "</tbody>
                    </table>";
                    echo "<hr>";
                    echo "</div>";
                }

                $queryOglasi = "SELECT * FROM oglasi";
                $getOglasi = $db->db->query($queryOglasi);

                if($getOglasi->num_rows > 0){
                    echo '<div class="container" style="width: 80%;">';
                    echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                    echo '<h2>Svi oglasi</h2>';
                    while($oglas = $getOglasi->fetch_assoc()){
                        $title = $oglas["title"];
                        $authorID = $oglas["user_id"];
                        $author = $db->getUserByID($authorID);
                        $authorName = $author["firstname"]. " " .$author["lastname"];
                        $imageURL = $oglas["image_url"];
                        $redirekcijaAdmin = "inspect_oglas.php?id=".$authorID."-".$oglas['oglas_id'];
                        ?>
                        <div class="container" style="margin: 15px;">
                            <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                <h2>Naslov: <?php echo $title; ?></h2>
                                <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                <h3>Oglašavač: <?php echo $authorName; ?></h3>
                                <button type="button" onclick="location.href = '<?php echo $redirekcijaAdmin; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Admin akcije</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="container" style="width: 80%;">';
                        echo '<div class="wrapper" style="width: 80%; margin-top: 20px;">';
                            echo '<h2>Nema oglasa.</h2>';
                        echo '</div>';
                    echo '</div>';
                }
            ?>
            </div>
        </div>
    </body>
</html>
<?php } else echo 'Za malo -> Nisi admin !';
} else echo 'Za malo -> Nisi ni ulogovan !'; ?>