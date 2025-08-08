<?php
session_start();
require_once "database.php";

$db = new DB;

if(!$_SESSION){ echo 'Nisi ulogovan !'; 
                header("Location: guest.php"); } else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProdajemKupujem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class = "LoginRegisterPage">
    <div class="navbarWrapper">
        <nav class="navbar">
            <div class="navbarStatic">
                <a class="navItem" href="pocetna.php">Početna</a>
                <a class="navItem" href="about.php">O nama</a>
                <a class="navItem" href="contact.php">Kontaktirajte nas</a>
            </div>

            <div class="navbarElem">
                <ul class="navbarUl">
                    <?php
                    if($_SESSION["user_type"] != "guest"){
                        $user = $db->getUserByID($_SESSION['user_id']);

                        if ($user && isset($user['user_id'])) {
                            $userID = $user['user_id'];
                            echo "<li class='navItemWrapper'> Hello, " . $_SESSION['firstname'] . "</a></li>";
                                        
                            if ($user['role'] == "user") {
                                echo "<li class='navItemWrapper'><a class='navItem' href='create_oglas.php'>Kreiraj oglas</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='request_category.php'>Zatraži novu kategoriju</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='profile.php'>Profil</a></li>";
                            } else if ($user['role'] == "admin") {
                                echo "<li class='navItemWrapper'><a class='navItem' href='profile.php'>Profil</a></li>";
                                echo "<li class='navItemWrapper'><a class='navItem' href='kontrolnipanel.php'>Kontrolni Panel</a></li>";
                            }
                        } else {
                            echo "Error fetching user's ID.";
                        }
                    }
                    ?>
                    <li class="navItemWrapper"><a class="navItem" href="logout.php?logout">Izlogujte se</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="container" style="width:75%">

        <div class="wrapper" style="margin-top:65px; width: 93%;">
            <div class="search-filter-container">
                <form action="pocetna.php" method="GET">
                    <input type="text" name="search" class ="inputLogRes" style="width:65%; margin-right:10px" placeholder="Pretraži oglase po nazivu ili po oglašivaču">
                    
                    <select name="category" class="inputLogRes" style="width:15%;">
                        <option value="">Sve kategorije</option>
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
                    
                    <button type="submit" class ="startPageBttn" style="width:15%; padding: 10px 10px;">Pretraži</button>
                </form>
            </div>
        </div>


        <?php
            $query = "SELECT * FROM oglasi";
            $whereExists = 0;
            
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = $db->db->real_escape_string($_GET['search']);
                if($whereExists === 1){
                    $query .= " AND ";
                } else {
                    $query .= " WHERE ";
                    $whereExists = 1;
                }

                $query .= "(title LIKE '%$searchTerm%' OR user_id IN (SELECT user_id FROM users WHERE CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'))";
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
            
            $resultOglasQuery = $db->db->query($query);
            if($resultOglasQuery->num_rows > 0){
                echo '<div class="container" style="width: 80%;">';
                echo '<div class="wrapper" style="margin-top: 10px; width: 80%;">';
                while($oglas = $resultOglasQuery->fetch_assoc()){
                    $title = $oglas["title"];
                    $authorID = $oglas["user_id"];
                    $author = $db->getUserByID($authorID);
                    $authorName = $author["firstname"]. " " .$author["lastname"];
                    $imageURL = $oglas["image_url"];
                    $redirekcija = "inspect_oglas.php?id=".$authorID."-".$oglas['oglas_id'];
                    ?>
                    <div class="container" style="margin: 15px;">
                        <div class="wrapper" style="background-color: rgba(165, 191, 221, 0.1); box-shadow: 0 0px 20px 0 rgba(0,0,0,0.10), 0 0px 20px 0 rgba(0,0,0,0.10);">
                            <h2>Naslov: <?php echo $title; ?></h2>
                            <img src="<?php echo $imageURL ?>" alt="<?php echo $imageURL ?>" class="imageCard" style="max-width: 95%; max-height: 95%;">
                            <h3>Oglašavač: <?php echo $authorName; ?></h3>
                            <?php 
                            if($_SESSION["user_type"] == 'admin'){
                                $redirekcijaAdmin = "inspect_oglas.php?id=".$authorID."-".$oglas['oglas_id'];
                                ?>
                                <button type="button" onclick="location.href = '<?php echo $redirekcijaAdmin; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Admin akcije</button>
                                <?php
                            } else {
                            ?>
                            <button type="button" onclick="location.href = '<?php echo $redirekcija; ?>';" class="loginRegisterRedirectButton" style="width:100%;">Detaljnije</button>
                    <?php
                    }
                    echo "</div>
                    </div>";
                }
            } else {
                echo '<div class="wrapper" style="margin-top: 70px; box-shadow: 0 0px 20px 0 rgba(0,0,0,0.30), 0 0px 20px 0 rgba(0,0,0,0.30);"> <h1> Nema kreiranih oglasa. </h1> </div>';
            }
        ?>
        </div>
    </div>
</body>
</html>

<?php } ?>