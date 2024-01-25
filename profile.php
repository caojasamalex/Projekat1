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
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
    <body class="LoginRegisterPage" style="height:100vh;">
        <?php

        echo '<div class="navbarWrapper">';
            echo '<nav class="navbar">';
                echo '<div class="navbarStatic">';
                        echo '<a class="navItem" href="pocetna.php">Home</a>';
                        echo '<a class="navItem" href="about.php">About</a>';
                        echo '<a class="navItem" href="contact.php">Contact</a>';
                    echo '</div>';

                    echo '<div class="navbarElem">';
                        echo '<ul class="navbarUl">';
                            $user = $db->getUserByID($_SESSION['user_id']);
                            if ($user && isset($user['user_id'])) {
                                $userID = $user['user_id'];
                                echo "<li class='navItemWrapper'> Hello, " . $_SESSION['firstname'] . "</a></li>";

                                if ($user['role'] == "artist") {
                                    echo "<li class='navItemWrapper'><a class='navItem' href='create_artwork.php'>Create Artwork</a></li>";
                                    echo "<li class='navItemWrapper'><a class='navItem' href='profile.php?id=$userID'>Profile</a></li>";
                                } else if ($user['role'] == "admin") {
                                    echo "<li class='navItemWrapper'><a class='navItem' href='profile.php?id={$userID}'>Profile</a></li>";
                                    echo "<li class='navItemWrapper'><a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a></li>";
                                } else {
                                    echo "<li class='navItemWrapper'><a class='navItem' href='profile.php?id={$userID}'>Profile</a></li>";
                                }
                            } else {
                                echo "Error fetching artist ID.";
                            }
                            echo '<li class="navItemWrapper"><a class="navItem" href="logout.php">Logout</a></li>';
                        echo '</ul>';
                echo '</div>';
            echo '</nav>';
        echo '</div>';
        
            if ($user['role'] == "artist") {
                $artworksQuery = "SELECT * FROM artworks WHERE artist_id = $userID";
                $result = $db->db->query($artworksQuery);

                if($result->num_rows > 0){
                    echo '<div class="container">';
                    echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                    echo '<h2>Your artworks</h2>';
                    while($artwork = $result->fetch_assoc()){
                        $title = $artwork["title"];
                        $imageURL = $artwork["image_url"];
                        $visits = $artwork["visits"];
                        $likes = $artwork["likes"];
                        $favorites = $artwork["favorites"];

                        $redirekcija = "inspect_picture.php?id=".$userID."-".$artwork['artwork_id'];

                        $artworkID = $artwork['artwork_id'];

                        ?>
                        <div class="container" style="margin: 15px;">
                            <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                <h2>Title: <?php echo $title; ?></h2>
                                <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                <h4>Number of visits: <?php echo $visits; ?></h4>
                                <h4>Number of likes: <?php echo $likes; ?></h4>
                                <h4>Number of saves (favorizations): <?php echo $favorites; ?></h4>
                                <h4>Rating: <?php 
                    
                                    $queryForRating = "SELECT grade FROM user_likes where artwork_id = $artworkID";
                                    $queryForRatingRes = $db->db->query($queryForRating);
                                    
                                    if($queryForRatingRes->num_rows){
                                        $brojac = 0;
                                        $ukupanRating = 0;
                                        $tempRating = 0;

                                        while($rate = $queryForRatingRes->fetch_assoc()){
                                            $brojac++;
                                            $tempRating += $rate['grade'];
                                        }
                                        
                                        $ukupanRating = $tempRating / $brojac;
                                        $ukupanRatingFormatirano = number_format($ukupanRating, 2, '.', '');


                                        echo $ukupanRatingFormatirano;
                                    } else {
                                        echo "Not rated yet.";
                                    } ?>
                                <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Details</button>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>';
                echo '</div>';
                }
            }

            if($user['role'] == "user" || $user['role'] == "artist" || $user['role'] == "admin"){
                
                $queryArtwork = "SELECT * FROM user_likes WHERE user_id = $userID and favorite = 1";
                $resultQueryArtwork = $db->db->query($queryArtwork);

                if($resultQueryArtwork->num_rows > 0){
                    while($tempVar = $resultQueryArtwork->fetch_assoc()){
                        $artworkID = $tempVar['artwork_id'];

                        $query = "SELECT * FROM artworks WHERE artwork_id = $artworkID";
                        $result = $db->db->query($query);

                        if($result->num_rows > 0){
                            echo '<div class="container">';
                            echo '<div class="wrapper" style="margin-top:65px; width: 80%;">';
                            echo '<h2>Favorite artworks</h2>';
                            while($artwork = $result->fetch_assoc()){
                                $authorID = $artwork["artist_id"];
                                $author = $db->getUserByID($authorID);
                                $authorName = $author["firstname"]. " " .$author["lastname"];

                                $title = $artwork["title"];
                                $imageURL = $artwork["image_url"];
                                $redirekcija = "inspect_picture.php?id=".$authorID."-".$artwork['artwork_id'];
                                ?>
                                <div class="container" style="margin: 15px;">
                                    <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                                        <h2>Title: <?php echo $title; ?></h2>
                                        <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                                        <h3>Author's Name: <?php echo $authorName; ?></h3>
                                        <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Details</button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                } else {
                   echo '<div class="container" style="width: 80%;">';
                        echo '<div class="wrapper" style="width: 80%; margin-top: 20px;">';
                            echo '<h2>No favorite artworks</h2>';
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