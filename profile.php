<?php
session_start(); 
require_once "database.php";

$db = new DB;

if($_SESSION){
    if($_SESSION['user_type'] != "guest"){
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
    <body class="LoginRegisterPage" style="height:100vh;">
        <?php

        echo '<div class="navbarWrapper">';
            echo '<nav class="navbar">';
                echo '<div class="navbarStatic">';
                        echo '<a class="navItem" href="pocetna.php">Početna</a>';
                        echo '<a class="navItem" href="about.php">O nama</a>';
                        echo '<a class="navItem" href="contact.php">Kontaktirajte nas</a>';
                    echo '</div>';

                    echo '<div class="navbarElem">';
                        echo '<ul class="navbarUl">';
                            $user = $db->getUserByID($_SESSION['user_id']);
                            if ($user && isset($user['user_id'])) {
                                $userID = $user['user_id'];
                                echo "<li class='navItemWrapper'> Hello, " . $_SESSION['firstname'] . "</a></li>";

                                if ($user['role'] == "user") {
                                    echo "<li class='navItemWrapper'><a class='navItem' href='create_oglas.php'>Kreiraj oglas</a></li>";
                                    echo "<li class='navItemWrapper'><a class='navItem' href='request_category.php'>Zatraži novu kategoriju</a></li>";
                                    echo "<li class='navItemWrapper'><a class='navItem' href='profile.php?id=$userID'>Profil</a></li>";
                                } else if ($user['role'] == "admin") {
                                    echo "<li class='navItemWrapper'><a class='navItem' href='profile.php?id={$userID}'>Profil</a></li>";
                                    echo "<li class='navItemWrapper'><a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a></li>";
                                }
                            } else {
                                echo "Error fetching user ID.";
                            }
                            echo '<li class="navItemWrapper"><a class="navItem" href="logout.php">Izlogujte se</a></li>';
                        echo '</ul>';
                echo '</div>';
            echo '</nav>';
        echo '</div>';
        
            if ($user['role'] == "user") {
                $oglasiQuery = "SELECT * FROM oglasi WHERE user_id = $userID";
                $result = $db->db->query($oglasiQuery);

                if($result->num_rows > 0){
                    echo '<div class="container">';
                    echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                    echo '<h2>Vaši oglasi</h2>';
                    while($oglas = $result->fetch_assoc()){
                        $title = $oglas["title"];
                        $imageURL = $oglas["image_url"];
                        $visits = $oglas["visits"];
                        
                        $redirekcija = "inspect_oglas.php?id=".$userID."-".$oglas['oglas_id'];

                        $oglasID = $oglas['oglas_id'];

                        $likesCountQuery = "SELECT COUNT(*) FROM user_likes WHERE oglas_id = $oglasID";
                        $likesResult = $db->db->query($likesCountQuery);
                        $likesCountQuery = "SELECT COUNT(*) as likes_count FROM user_likes WHERE oglas_id = $oglasID";
                        $likesResult = $db->db->query($likesCountQuery);
                        
                        if ($likesResult) {
                            $likesRow = $likesResult->fetch_assoc();
                            $likes = $likesRow['likes_count'];
                        } else {
                            $likes = 0;
                        }

                        ?>
                        <div class="container" style="margin: 15px;">
                            <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                <h2>Title: <?php echo $title; ?></h2>
                                <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                <h4>Number of visits: <?php echo $visits; ?></h4>
                                <h4>Number of likes: <?php echo $likes; ?></h4>

                                <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Detalji</button>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>';
                echo '</div>';
                }
            }

            if($user['role'] == "user" || $user['role'] == "admin"){
                
                $oglasiQuery = "SELECT * FROM user_likes WHERE user_id = $userID";
                $resultOglasiQuery = $db->db->query($oglasiQuery);

                if($resultOglasiQuery->num_rows > 0){
                    echo '<div class="container">';
                    echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                    echo '<h2>Sačuvani oglasi</h2>';
                    while($tempVar = $resultOglasiQuery->fetch_assoc()){
                        $oglasID = $tempVar['oglas_id'];

                        $query = "SELECT * FROM oglasi WHERE oglas_id = $oglasID";
                        $result = $db->db->query($query);

                        if($result->num_rows > 0){
                            while($oglas = $result->fetch_assoc()){
                                $authorID = $oglas["user_id"];
                                $author = $db->getUserByID($authorID);
                                $authorName = $author["firstname"]. " " .$author["lastname"];

                                $title = $oglas["title"];
                                $imageURL = $oglas["image_url"];
                                $redirekcija = "inspect_oglas.php?id=".$authorID."-".$oglas['oglas_id'];
                                ?>
                                <div class="container" style="margin: 15px;">
                                    <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                        <h2>Naslov: <?php echo $title; ?></h2>
                                        <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                        <h3>Oglašavač: <?php echo $authorName; ?></h3>
                                        <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Detalji</button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                } else {
                   echo '<div class="container" style="width: 80%;">';
                        echo '<div class="wrapper" style="width: 80%; margin-top: 20px;">';
                            echo '<h2>Nema sačuvanih oglasa.</h2>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            ?>
            </div>
        </div>
    </body>
</html>
<?php } else echo 'Za malo -> Guest si !';
} else echo 'Za malo -> Nisi ni ulogovan !'?>