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
        <title>Artist's account request</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="LoginRegisterPage" style="height: 100vh;">
        <?php
        if (isset($_POST['submitAccountRequest'])){
            $userID = $_POST['userrequestID'];
            $description = $db->db->real_escape_string($_POST['description']);

            $queryIfExists = "SELECT * FROM userrequests WHERE user_id = $userID";
            $queryIfExistsRes = $db->db->query($queryIfExists);

            if(!$queryIfExistsRes->num_rows){
                $queryInsertNewRequest = "INSERT INTO userrequests (user_id, description) VALUES ($userID, '$description')";
                $queryInsertNewRequestRes = $db->db->query($queryInsertNewRequest);

                if ($queryInsertNewRequestRes) {
                    header("Location: pocetna.php");
                } else {
                    echo "Error submitting this request.";
                }
            } else {
                echo "<div class='containter'> <div class='wrapper'>";
                echo "You have already requested an artist role";
                echo "</div> </div>";
            }
        }
        ?>
        <div class="container">
            <div class="wrapper">
                    <div class="loginRegisterRedirect" style="justify-content: center;">
                        <button class ="loginRegisterRedirectButton" onclick="location.href = 'pocetna.php';">Go back to the Main Page</button>
                    </div>
                <form action="" method="post">
                    <textarea name="description" id="description" rows="4" required placeholder="Tell us why should we approve this request ?" class="inputLogRes"></textarea><br>
                    <input type="text" id="userrequestID" name="userrequestID" value="<?php echo $_SESSION['user_id']; ?>" hidden>
                    <input class="startPageBttn" type="submit" value="Submit the request" name="submitAccountRequest" style="width:100%; padding: 10px 10px;">
                </form>
            </div>
        </div>
    </body>
</html>

<?php
    } else if($_SESSION['user_type'] === "admin") { ?>


        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Artist requests</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body class="LoginRegisterPage" style="height: 100vh;">
            <div class="container" style="flex-direction:">
                <div class="wrapper" style="box-shadow: none; background-color: inherit;">
                    <div class="loginRegisterRedirect" style="justify-content: center;">
                        <button class ="loginRegisterRedirectButton" onclick="location.href = 'kontrolnipanel.php';">Go back</button>
                    </div>
                <?php
                $queryRequests = "SELECT * FROM userrequests";
                $queryRequestsRes = $db->db->query($queryRequests);
        
                if($queryRequestsRes->num_rows){
                    while($row = $queryRequestsRes->fetch_assoc()){
                    
                        $userQuery = "SELECT * FROM users WHERE user_id = {$row['user_id']}";
                        $userQueryRes = $db->db->query($userQuery);
                        
                        $user = $userQueryRes->fetch_assoc();

                        $userFullName = $user['firstname'] . " " . $user['lastname'];
                    ?>
                        <div class="wrapper">
                            <h4>User's name: <?php echo $userFullName; ?></h4>
                            <h4>Description: <?php echo $row['description']; ?></h4>
                            <form action="" method="post">
                                <input type="hidden" name="requestID" value="<?php echo $row['request_id']; ?>">
                                <input class="loginRegisterRedirectButton" type="submit" name="approveTheRequest" style="width: 48%;" value="Approve the request"></input>
                                <input class="loginRegisterRedirectButton" type="submit" name="deleteTheRequest" style="width: 48%;" value="Delete the request"></input>
                            </form>
                        </div>
                    <?php
                    }
                } else {
                    echo '<div class="wrapper">';
                        echo "<h1>No artist requests.</h1>";
                    echo '</div>';
                }
        
                if (isset($_POST['approveTheRequest'])) {
                    $requestID = $db->db->real_escape_string($_POST['requestID']);
        
                    $queryArtistReq = "SELECT * FROM userrequests WHERE request_id = $requestID";
                    $queryArtistReqRes = $db->db->query($queryArtistReq);
        
                    if($queryArtistReqRes->num_rows){
                        $row = $queryArtistReqRes->fetch_assoc();
                        $userID = $row['user_id'];

                        $queryUpdateRole = "UPDATE users SET role = 'artist' WHERE user_id = $userID";
                        $queryUpdateRoleRes = $db->db->query($queryUpdateRole);

                        $queryDeleteRequest = "DELETE FROM userrequests WHERE request_id = $requestID";
                        $queryDeleteRequestRes = $db->db->query($queryDeleteRequest);
        
                        header("Location: request_category.php");
                    }
                }
        
                if (isset($_POST['deleteTheRequest'])) {
                    $requestID = $db->db->real_escape_string($_POST['requestID']);
                    
                    $queryCategoryReq = "SELECT * FROM categoryrequests WHERE request_id = $requestID";
                    $queryCategoryReqRes = $db->db->query($queryCategoryReq);
        
                    if($queryCategoryReqRes->num_rows){
                        $row = $queryCategoryReqRes->fetch_assoc();

                        $queryDeleteRequest = "DELETE FROM categoryrequests WHERE request_id = {$row['request_id']}";
                        $queryDeleteRequestRes = $db->db->query($queryDeleteRequest);
        
                        header("Location: request_category.php");
                    }
                }
                ?>
        
                </div>
            </div>
        </body>
        </html>
        
<?php
    } else echo 'Za malo -> Nisi user a ni admin !';
} else echo 'Za malo -> Nisi ni ulogovan !'?>