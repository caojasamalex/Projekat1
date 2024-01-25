<?php
session_start();


if(!$_SESSION){ echo "Nisi ulogovan !"; } else {
    require_once "database.php";
    $db = new DB;

    $dataNeeded = $_GET['id'];
    $split = explode('-', $dataNeeded);

    $artistID = $split[0];
    $artworkID = $split[1];

    $queryArtwork = "SELECT * FROM artworks WHERE artwork_id =  $artworkID";
    $getArtwork = $db->db->query($queryArtwork);
    if($getArtwork->num_rows){
        $artwork = $getArtwork->fetch_assoc();

        if($artwork['artist_id'] != $artistID){
            echo "Dobar pokusaj -> Ovaj artist nije autor !";
            exit();
        } else {
            $queryArtist = "SELECT * FROM users WHERE user_id = $artistID";
            $getArtist = $db->db->query($queryArtist);

            if($getArtist->num_rows){
                $artist = $getArtist->fetch_assoc();

                $title = $artwork['title'] . " By " . $artist['firstname'] . " " . $artist['lastname'];

                $updateVisits = "UPDATE artworks SET visits = visits + 1 WHERE artwork_id = $artworkID";
                $db->db->query($updateVisits);
                
                if($_SESSION['user_type'] != 'guest'){
                    $queryUserLikes = "SELECT * FROM user_likes WHERE user_id = {$_SESSION['user_id']} and artwork_id = $artworkID";
                    $queryUserLikesRes = $db->db->query($queryUserLikes);
                    
                    $row = null;

                    if ($queryUserLikesRes) {
                        $row = $queryUserLikesRes->fetch_assoc();
                        $currentGrade = isset($row['grade']) ? $row['grade'] : "Not rated yet";
                    } else {
                        echo "Error: " . $db->db->error;
                    }
                }

                if (isset($_POST['likeSubmit'])) {
                    $var = 0;
                
                    if ($row) {
                        if ($row['liked'] == 0) $var = 1;
                
                        $queryLike = "UPDATE user_likes SET liked = $var WHERE artwork_id = $artworkID AND user_id = {$_SESSION['user_id']}";
                        $queryLikeRes = $db->db->query($queryLike);
                
                        if (!$queryLikeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            // Update artworks table based on the like action
                            if ($var === 1) {
                                $queryUpdateLikes = "UPDATE artworks SET likes = likes + 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            } else {
                                $queryUpdateLikes = "UPDATE artworks SET likes = likes - 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            }
                
                            $queryUpdateLikesRes = $db->db->query($queryUpdateLikes);
                            
                            if (!$queryUpdateLikesRes) {
                                echo "Error updating artworks table: " . $db->db->error;
                            } else {
                                header("Refresh:0");
                            }
                        }
                    } else {
                        $var = 1;
                
                        $queryLike = "INSERT INTO user_likes(user_id, artwork_id, liked) VALUES ({$_SESSION['user_id']}, $artworkID, $var)";
                        $queryLikeRes = $db->db->query($queryLike);
                
                        if (!$queryLikeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            $queryUpdateLikes = "UPDATE artworks SET likes = likes + 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            $queryUpdateLikesRes = $db->db->query($queryUpdateLikes);
                            
                            if (!$queryUpdateLikesRes) {
                                echo "Error updating artworks table: " . $db->db->error;
                            } else {
                                header("Refresh:0");
                            }
                        }
                    }
                }
                
                
                if (isset($_POST['favoriteSubmit'])) {
                    $var = 0;
                    
                    if ($row) {
                        if ($row['favorite'] == 0) $var = 1;
                
                        $queryFavorize = "UPDATE user_likes SET favorite = $var WHERE artwork_id = $artworkID AND user_id = {$_SESSION['user_id']}";
                        $queryFavorizeRes = $db->db->query($queryFavorize);
                
                        if (!$queryFavorizeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            if ($var === 1) {
                                $queryUpdateFavorites = "UPDATE artworks SET favorites = favorites + 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            } else {
                                $queryUpdateFavorites = "UPDATE artworks SET favorites = favorites - 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            }
                
                            $queryUpdateFavoritesRes = $db->db->query($queryUpdateFavorites);
                
                            if (!$queryUpdateFavoritesRes) {
                                echo "Error updating artworks table: " . $db->db->error;
                            } else {
                                header("Refresh:0");
                            }
                        }
                    } else {
                        $var = 1;
                
                        $queryFavorize = "INSERT INTO user_likes(user_id, artwork_id, favorite) VALUES ({$_SESSION['user_id']}, $artworkID, $var)";
                        $queryFavorizeRes = $db->db->query($queryFavorize);
                
                        if (!$queryFavorizeRes) {
                            echo "Error: " . $db->db->error;
                        } else {
                            $queryUpdateFavorites = "UPDATE artworks SET favorites = favorites + 1 WHERE artwork_id = $artworkID AND artist_id = $artistID";
                            $queryUpdateFavoritesRes = $db->db->query($queryUpdateFavorites);
                
                            if (!$queryUpdateFavoritesRes) {
                                echo "Error updating artworks table: " . $db->db->error;
                            } else {
                                header("Refresh:0");
                            }
                        }
                    }
                }
                
            } else {
                echo "Dobar pokusaj -> Artist ovog ID-a ne postoji";
                exit();
            }
        }
        
    } else {
        echo "Dobar pokusaj -> Artwork ID ne postoji !";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gradeSubmit'])) {
        $newGrade = (isset($_POST['grade']) && is_numeric($_POST['grade'])) ? intval($_POST['grade']) : null;

        if ($newGrade !== null && $newGrade >= 0 && $newGrade <= 10) {
            $updateQuery = "INSERT INTO user_likes (user_id, artwork_id, grade) VALUES ({$_SESSION['user_id']}, $artworkID, $newGrade) ON DUPLICATE KEY UPDATE grade = $newGrade";
            $db->db->query($updateQuery);
            
            header("Refresh:0");
        }
    }

    if(isset($_POST['commentSubmit'])){
        $comment = $db->db->real_escape_string($_POST['comment']);

        $query = "INSERT INTO comments(user_id, artwork_id, comment) VALUES ({$_SESSION['user_id']}, $artworkID, '$comment')";
        $result = $db->db->query($query);

        if(!$result) {
            echo "Error: " . $db->db->error;
        } else {
            header("Refresh:0");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $title; ?> </title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="LoginRegisterPage">
    <div class="container" style="width: 50%;">
        <div class="wrapper" style="width: 80%;">
            <a href="pocetna.php" class="adminPanelBttnAHREF">Main Page</a>
            <h2>Title: <?php echo $artwork['title']; ?></h2>
            <h3>Author: <?php echo $artist['firstname'] . " " . $artist['lastname']; ?></h3>
            <img src='<?php echo $artwork['image_url']; ?>' alt='Artwork Image' style='max-width: 95%; max-height: 95%' class='imageCard'><br>

            <?php
                if($_SESSION['user_type'] != "guest"){
                    if ($_SESSION['user_type'] === "admin" || $_SESSION['user_id'] == $artwork['artist_id']) {
                        echo "<button type='button' class='adminPanelBttn' onclick='posaljidelete()'>Delete Artwork</button>";
                    }
                }
            ?>

            <div class ="container">
                <div class="wrapper" style="border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                    <h3>Description</h3>
                    <h4> <?php echo $artwork['description']; ?> </h4>
                </div>
                <div class="wrapper" style="width: 50%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                    <h3>Technique: <?php echo $artwork['technique']; ?> </h4>
                    <?php if($artwork['on_sale']){ ?>
                    <h3>For sale: <?php echo $artwork['cost']; ?> €</h4>
                    <?php } ?>
                </div>
            </div>

            <div class ="container">
                <div class="wrapper" style="width: 50%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                    <h4>Likes: <?php echo $artwork['likes']; ?></h4>
                    <h4>Favorizations: <?php echo $artwork['favorites']; ?></h4>
                    <h4>Visits: <?php echo $artwork['visits']; ?></h4>
                    <?php
                    if($_SESSION['user_type'] != "guest"){
                        if($_SESSION['user_id'] === $artwork['artist_id']){ ?>
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
                            }
                            echo "</h4>";
                        } else {
                            echo "<h4>Your rating: " . $currentGrade . "</h4>";
                        }
                    } ?>

                    <?php if($_SESSION['user_type'] != 'guest'){ if($_SESSION['user_id'] != $artistID){ ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $artistID . '-' . $artworkID; ?>">
                        <label for="grade">Ocena(0-10):</label>
                        <input type="number" name="grade" id="grade" min="0" max="10" placeholder="Rate this artwork" required class="inputLogRes">
                        <button type="submit" name="gradeSubmit" class="adminPanelBttn">Submit</button>
                    </form>
                    <form method="post" action="">
                        <button type="submit" name="likeSubmit" class="adminPanelBttn"><?php 
                        
                        if($row === null){
                            echo "L";
                        } else {
                            if($row['liked'] == 0) echo "L";
                            else echo "Disl";
                        }
                        
                        ?>ike</button>
                    </form>
                    <form method="post" action="">
                        <button type="submit" name="favoriteSubmit" class="adminPanelBttn"><?php
                        
                        if($row === null){
                            echo "Mark";
                        } else {
                            if($row['favorite'] == 0) echo "Mark";
                            else echo "Unmark";
                        }
                        
                        ?> as favorite</button>
                    </form>

                    <?php } ?>
                    </div>
            </div>
                    <div class ="container">
                        <div class="wrapper" style="width: 50%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                            <form method="post" action="">
                                <h3>Leave a comment</h3>
                                <textarea name="comment" id="comment" rows="4" required placeholder="Comment" class="inputLogRes"></textarea><br>
                                <button type="submit" name="commentSubmit" class="adminPanelBttn">Post it</button>
                            </form>
                        </div>
                    </div>
                
                <?php } else { ?>
                    
                    <?php } ?>

            <div class ="container">
                <div class="wrapper" style="width: 100%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                    <h3>Comments</h3>
                    <?php
                        $artworkID = $artwork['artwork_id'];
                        $queryComments = "SELECT * FROM comments WHERE artwork_id = $artworkID";
                        
                        $resultComments = $db->db->query($queryComments);

                        if($resultComments->num_rows){
                            while ($comment = $resultComments->fetch_assoc()) {
                                $commentPosterID = $comment['user_id'];
                                $commentPosterQuery = "SELECT * FROM users WHERE user_id = $commentPosterID";
                            
                                $commentPosterResult = $db->db->query($commentPosterQuery);
                            
                                if ($commentPosterResult->num_rows) {
                                    $commentPoster = $commentPosterResult->fetch_assoc();
                                    $commentPosterName = $commentPoster['firstname'] . " " . $commentPoster['lastname'];
                                    ?>
                                    <div class="container">
                                        <div class="wrapper" style="width: 100%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                                            <h5>Posted by: <?php echo $commentPosterName . " - " . $commentPoster['username']; ?></h5>
                                            <p class="tekstStart"><?php echo $comment['comment'];?></p>
                                            <?php
                                            if ($_SESSION['user_type'] === "admin") {
                                                echo "<p class='identifikator' hidden>".$comment['comment_id']."</p>";
                                                echo "<p class='identifikatorArtistID' hidden>".$artistID."</p>";
                                                echo "<p class='identifikatorArtworkID' hidden>".$artworkID."</p>";
                                                echo "<button type='button' class='adminPanelBttn' onclick="."posaljideletekoment(this)".">Delete the Comment</button>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            
                        } else {
                            ?>
                            <h4>No comments</h4>
                        <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    function posaljideletekoment(button) {
        var pozicijadugme = button.parentNode;
        var identitet = pozicijadugme.querySelector('p.identifikator');
        var praviidentitet = identitet.textContent;

        var artistID = pozicijadugme.querySelector('p.identifikatorArtistID');
        var artist = artistID.textContent;
        var artworkID = pozicijadugme.querySelector('p.identifikatorArtworkID');
        var artwork = artworkID.textContent;

        var form = document.createElement("form");
        form.method = "post";
        form.action = "deleteComment.php";

        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "identifikacija2";
        input.value = praviidentitet;

        var input2 = document.createElement("input");
        input2.type = "hidden";
        input2.name = "artist";
        input2.value = artist;

        var input3 = document.createElement("input");
        input3.type = "hidden";
        input3.name = "artwork";
        input3.value = artwork;

        var input3 = document.createElement("input");
        input3.type = "hidden";
        input3.name = "location";
        input3.value = "inspect_picture.php?id=".<?php echo $artistID; ?>."-".<?php echo $artworkID; ?>;

        form.appendChild(input);
        form.appendChild(input2);
        form.appendChild(input3);


        document.body.appendChild(form);
        form.submit();
    }

    function posaljidelete() {
        var artistID = "<?php echo $artistID; ?>";
        var artworkID = "<?php echo $artworkID; ?>";

        var form = document.createElement("form");
        form.method = "post";
        form.action = "deletepicture.php";

        var inputArtist = document.createElement("input");
        inputArtist.type = "hidden";
        inputArtist.name = "artistID";
        inputArtist.value = artistID;

        var inputArtwork = document.createElement("input");
        inputArtwork.type = "hidden";
        inputArtwork.name = "artworkID";
        inputArtwork.value = artworkID;

        form.appendChild(inputArtist);
        form.appendChild(inputArtwork);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php
}
?>