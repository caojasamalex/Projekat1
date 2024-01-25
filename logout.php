<?php
session_start();

unset($_SESSION["username"]);
unset($_SESSION["firstname"]);
unset($_SESSION["user_type"]);
unset($_SESSION["user_id"]);
session_destroy();

header("Location: index.php");
exit();
?>