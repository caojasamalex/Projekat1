<?php
session_start();
require_once "database.php";

$db = new DB;

if(!$_SESSION){ echo 'Nisi ulogovan !'; } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The World of Art</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class = "LoginRegisterPage">
    <div class="navbarWrapper">
        <nav class="navbar">
            <div class="navbarStatic">
                <a class="navItem" href="pocetna.php">Home</a>
                <a class="navItem" href="about.php">About</a>
                <a class="navItem" href="contact.php">Contact</a>
            </div>

            <div class="navbarElem">
                <ul class="navbarUl">
                    <?php
                    if($_SESSION["user_type"] != "guest"){
                        $user = $db->getUserByID($_SESSION['user_id']);

                        if ($user && isset($user['user_id'])) {
                            $userID = $user['user_id'];
                            echo "<li class='navItemWrapper'> Hello, " . $_SESSION['firstname'] . "</a></li>";
                                        
                            if ($user['role'] == "artist") {
                                echo "<li class='navItemWrapper'><a class='navItem' href='create_artwork.php'>Create Artwork</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='request_category.php'>Request a new category</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='profile.php'>Profile</a></li>";
                            } else if ($user['role'] == "admin") {
                                echo "<li class='navItemWrapper'><a class='navItem' href='profile.php'>Profile</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a></li>";
                            } else {
                                echo "<li class='navItemWrapper'><a class='navItem' href='request_artistsaccount.php'>Request an artist account</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='profile.php'>Profile</a></li>";
                            }
                        } else {
                            echo "Error fetching user's ID.";
                        }
                    }
                    ?>
                    <li class="navItemWrapper"><a class="navItem" href="logout.php?logout">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="container">

        <div class="wrapper" style="margin-top:65px; width: 64%;">
            <div class="search-filter-container">
                <form action="pocetna.php" method="GET">
                    <input type="text" name="search" class ="inputLogRes" style="width:65%;" placeholder="Search by artist or artwork name">
                    
                    <select name="category" class="inputLogRes" style="width:20%;">
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
                    
                    <button type="submit" class ="startPageBttn" style="width:15%; padding: 10px 10px;">Search</button>
                </form>
            </div>
        </div>


        <?php
            $query = "SELECT * FROM artworks";
            $whereExists = 0;
            if ($_SESSION["user_type"] == 'artist') {
                $query .= " WHERE artist_id != {$_SESSION['user_id']}";
                $whereExists = 1;
            }
            
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $db->db->real_escape_string($_GET['search']);
                if($whereExists === 1){
                    $query .= " AND ";
                } else {
                    $query .= " WHERE ";
                    $whereExists = 1;
                }

                $query .= "(title LIKE '%$searchTerm%' OR artist_id IN (SELECT user_id FROM users WHERE CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'))";
            }
            
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $category = $db->db->real_escape_string($_GET['category']);

                if($whereExists === 1){
                    $query .= " AND ";
                } else {
                    $query .= " WHERE ";
                    $whereExists = 1;
                }

                $query .= "category_id = $category";
            }
            
            $resultArtworkQuery = $db->db->query($query);
            if($resultArtworkQuery->num_rows > 0){
                echo '<div class="container" style="width: 80%;">';
                echo '<div class="wrapper" style="margin-top: 10px; width: 80%;">';
                while($artwork = $resultArtworkQuery->fetch_assoc()){
                    $title = $artwork["title"];
                    $authorID = $artwork["artist_id"];
                    $author = $db->getUserByID($authorID);
                    $authorName = $author["firstname"]. " " .$author["lastname"];
                    $imageURL = $artwork["image_url"];
                    $redirekcija = "inspect_picture.php?id=".$authorID."-".$artwork['artwork_id'];
                    ?>
                    <div class="container" style="margin: 15px;">
                        <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                            <h2>Title: <?php echo $title; ?></h2>
                            <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                            <h3>Author's Name: <?php echo $authorName; ?></h3>
                            <?php 
                            if($_SESSION["user_type"] == 'admin'){
                                $redirekcijaAdmin = "inspect_picture.php?id=".$authorID."-".$artwork['artwork_id'];
                                ?>
                                <button type="button" onclick="location.href = '<?php echo $redirekcijaAdmin; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Admin actions</button>
                                <?php
                            } else {
                            ?>
                            <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Details</button>
                    <?php
                    }
                    echo "</div>
                    </div>";
                }
            } else {
                echo '<div class="wrapper" style="margin-top: 70px; box-shadow: 0 0px 20px 0 rgba(0,0,0,0.30), 0 0px 20px 0 rgba(0,0,0,0.30);"> <h1> No artworks to be shown. </h1> </div>';
            }
        ?>
        </div>
    </div>
</body>
</html>

<?php } ?>