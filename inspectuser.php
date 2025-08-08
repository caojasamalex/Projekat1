<?php
session_start();

require_once "database.php";
$db = new DB;

if(!$_SESSION){
    echo 'Dobar pokusaj -> Nisi ulogovan !';
    exit();
}

if($_SESSION['user_type'] != 'admin'){
    echo 'Dobar pokusaj -> Nisi admin !';
    exit();
}

if(isset($_GET['id'])){

    $userID = $_GET['id'];
    $userQuery = "SELECT * FROM users WHERE user_id = $userID";
    $userQueryRes = $db->db->query($userQuery);

    if(!$userQueryRes->num_rows){
        echo 'Dobar pokusaj -> Nema user-a sa tim ID-em';
        exit();
    }

    $commentQuery = "SELECT * FROM comments WHERE user_id = $userID";
    $commentQueryRes = $db->db->query($commentQuery);

    if(!$commentQueryRes->num_rows){
        $commentsExist = 0;
    } else {
        $commentsExist = 1;
    }

    $user = $userQueryRes->fetch_assoc();
}

if(isset($_POST['deleteUser'])){
    if(!$_SESSION){
        echo 'Dobar pokusaj -> Nisi ulogovan !';
        exit();
    }
    
    if($_SESSION['user_type'] != 'admin'){
        echo 'Dobar pokusaj -> Nisi admin !';
        exit();
    }

    $db->deleteUserByID($user['user_id']);
    header("Location: kontrolnipanel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User <?php echo $userID; ?></title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript">
        function posaljideletekoment(button) {
            var pozicijadugme = button.parentNode;
            var identitet = pozicijadugme.querySelector('p.identifikator');
            var praviidentitet = identitet.textContent;

            var oglasavacID = pozicijadugme.querySelector('p.identifikatorOglasavacID');
            var oglasavac = oglasavacID.textContent;
            var oglasID = pozicijadugme.querySelector('p.identifikatorOglasID');
            var oglas = oglasID.textContent;

            var form = document.createElement("form");
            form.method = "post";
            form.action = "deleteComment.php";

            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "identifikacija2";
            input.value = praviidentitet;

            var input2 = document.createElement("input");
            input2.type = "hidden";
            input2.name = "oglasavac";
            input2.value = oglasavac;

            var input3 = document.createElement("input");
            input3.type = "hidden";
            input3.name = "oglas";
            input3.value = oglas;

            var input4 = document.createElement("input");
            input4.type = "hidden";
            input4.name = "location";
            input4.value = "inspectuser.php?id=" + <?php echo $userID; ?>;

            form.appendChild(input);
            form.appendChild(input2);
            form.appendChild(input3);
            form.appendChild(input4);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
    <body class="LoginRegisterPage" style="height:100vh;">
        <div class="container">
            <div class="wrapper" style="width: 75%; background-color: inherit; box-shadow:none;">
            <div class="container">
                <div class="wrapper">
                    <h4>Name : <?php echo $user['firstname'] . " " . $user['lastname']; ?></h4>
                    <h4>Email: <?php echo $user['email'];?></h4>
                    <h4>Username: <?php echo $user['username']; ?></h4>
                    <h4>User ID: <?php echo $user['user_id']; ?></h4>
                    <h4>User's Role: <?php echo $user['role']; ?></h4>
                    <form action="" method="post">
                        <input class="loginRegisterRedirectButton" type="submit" name="deleteUser" style="width: 100%;" value="Ban this user from the platform"></input>
                    </form>
                    <div class="loginRegisterRedirect" style="justify-content: center;">
                            <button class ="loginRegisterRedirectButton" onclick="location.href = 'kontrolnipanel.php';">Go back</button>
                        </div>
                </div>
            </div>
            <div class="container">
                <div class="wrapper" style="margin-top: 20px; width: 100%;">
                    <?php 
                        if($commentsExist === 0) echo "<h4>This user hasn't posted any comments yet.</h4>";
                        else { 
                            while ($comment = $commentQueryRes->fetch_assoc()) {
                                $commentPosterID = $comment['user_id'];
                                $commentPosterQuery = "SELECT * FROM users WHERE user_id = $commentPosterID";
                            
                                $commentPosterResult = $db->db->query($commentPosterQuery);
                            
                                if ($commentPosterResult->num_rows) {
                                    $commentPoster = $commentPosterResult->fetch_assoc();
                                    $commentPosterName = $commentPoster['firstname'] . " " . $commentPoster['lastname'];

                                    $queryArtist = "SELECT * FROM oglasi WHERE oglas_id = {$comment['oglas_id']}";
                                    $queryArtistRes = $db->db->query($queryArtist);

                                    if(!$queryArtistRes->num_rows){
                                        echo "Error: " . $db->db->error;
                                    } else {
                                        $oglas = $queryArtistRes->fetch_assoc();
                                        $oglasavacID = $oglas['user_id'];
                                        $oglasID = $oglas['oglas_id'];

                                        ?>
                                        <div class="container">
                                            <div class="wrapper" style="width: 100%; margin-top: 10px; border: 1px solid black; box-shadow: none; backdrop-filter: none; background-color: inherit;">
                                                <h5>Posted by: <?php echo $commentPosterName . " - " . $commentPoster['username']; ?></h5>
                                                <p class="tekstStart"><?php echo $comment['comment'];?></p>
                                                <p class='identifikatorOglasavacID' hidden><?php echo $oglasavacID; ?></p>
                                                <p class='identifikatorOglasID' hidden> <?php echo $oglasID; ?></p>
                                                <p class='identifikator' hidden> <?php echo$comment['comment_id'];?></p>
                                                <button type='button' class='startPageBttn' onclick=posaljideletekoment(this) style="width: 100%;">Delete the Comment</button>
                                            </div>
                                        </div>
                            <?php
                                    }
                                }
                            }
                        }
                    ?>
                </div>
            </div>

            <?php
                if($user['role'] === 'user'){ ?>
                    <div class="container">
                        <div class="wrapper" style="margin-top: 20px; width: 100%;">
                            <?php
                                $queryOglasi = "SELECT * FROM oglasi WHERE user_id = {$user['user_id']}";
                                $getOglasi = $db->db->query($queryOglasi);
                
                                if($getOglasi->num_rows > 0){
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
                                } else { ?>

                                <h4>No artworks</h4>

                            <?php
                                }
                            ?>
                        </div>
                    </div>
                <?php } ?>
        </div>
    </body>
</html>