<?php
session_start(); 
require_once "database.php";

$db = new DB;

if ($_SESSION) {
    if ($_SESSION['user_type'] === "artist") {
        if (isset($_POST['submitCategoryRequest'])) {
            $userID = $_SESSION['user_id'];
            $categoryName = $db->db->real_escape_string($_POST['categoryName']);
            $description = $db->db->real_escape_string($_POST['description']);

            $queryIfRequested = "SELECT * FROM categoryrequests WHERE requested_category_name = '$categoryName'";
            $queryIfRequestedRes = $db->db->query($queryIfRequested);

            $queryIfExists = "SELECT * FROM categories WHERE category_name = '$categoryName'";
            $queryIfExistsRes = $db->db->query($queryIfExists);

            if (!$queryIfRequestedRes->num_rows && !$queryIfExistsRes->num_rows) {
                $queryInsertNewRequest = "INSERT INTO categoryrequests (requested_category_name, category_desc) VALUES ('$categoryName', '$description')";
                $queryInsertNewRequestRes = $db->db->query($queryInsertNewRequest);

                if ($queryInsertNewRequestRes) {
                    echo "Category request submitted successfully.";
                } else {
                    echo "Error submitting category request.";
                }
            } else {
                echo "Request for this category already exists or this category has already been added.";
            }
        }
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
    <div class="container">
        <div class="wrapper">
            <div class="loginRegisterRedirect" style="justify-content: center;">
                <button class="loginRegisterRedirectButton" onclick="location.href = 'pocetna.php';">Go back to the Main Page</button>
            </div>
            <form action="request_category.php" method="post" enctype="multipart/form-data">
                <input type="text" name="categoryName" id="categoryName" required placeholder="Name of the category" class="inputLogRes"><br>
                <textarea name="description" id="description" rows="4" required placeholder="Write something about the category" class="inputLogRes"></textarea><br>
                <input class="startPageBttn" type="submit" value="Submit" name="submitCategoryRequest" style="width:100%; padding: 10px 10px;">
            </form>
        </div>
    </div>
</body>
</html>

<?php
    } else if ($_SESSION['user_type'] === "admin") { 
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
    <div class="container" style="flex-direction:">
        <div class="wrapper" style="box-shadow: none; background-color: inherit;">
            <div class="loginRegisterRedirect" style="justify-content: center;">
                <button class ="loginRegisterRedirectButton" onclick="location.href = 'kontrolnipanel.php';">Go back</button>
            </div>
        <?php
        $queryRequests = "SELECT * FROM categoryrequests";
        $queryRequestsRes = $db->db->query($queryRequests);

        if($queryRequestsRes->num_rows){
            while($row = $queryRequestsRes->fetch_assoc()){ ?>
                <div class="wrapper">
                    <h4>Category: <?php echo $row['requested_category_name']; ?></h4>
                    <h4>Description: <?php echo $row['category_desc']; ?></h4>
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
                echo "<h1>No category requests.</h1>";
            echo '</div>';
        }

        if (isset($_POST['approveTheRequest'])) {
            $requestID = $db->db->real_escape_string($_POST['requestID']);

            $queryCategoryReq = "SELECT * FROM categoryrequests WHERE request_id = $requestID";
            $queryCategoryReqRes = $db->db->query($queryCategoryReq);

            if($queryCategoryReqRes->num_rows){
                $row = $queryCategoryReqRes->fetch_assoc();
                $queryApprove = "INSERT INTO categories (category_name) VALUES ('{$row['requested_category_name']}')";
                $queryApproveRes = $db->db->query($queryApprove);

                $queryDeleteRequest = "DELETE FROM categoryrequests WHERE requested_category_name = '{$row['requested_category_name']}'";
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
                $queryDeleteRequest = "DELETE FROM categoryrequests WHERE requested_category_name = '{$row['requested_category_name']}'";
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
    } else {
        echo 'Za malo -> Nisi umetnik a ni admin !';
    }
} else {
    echo 'Za malo -> Nisi ni ulogovan !';
}
?>
