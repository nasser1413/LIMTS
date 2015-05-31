<?php
include "common.php";

session_start();

if(isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $_SESSION[SESSION_MESSAGE] = login_user($username, $password);
}

header("location: index.php");

exit;
?>
