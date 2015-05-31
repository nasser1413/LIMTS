<?php
    include "common.php";

    $username = $_POST["username"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $password = $_POST["password"];

    if (check_parameters($username, $first_name, $last_name, $password)) {
        echo register_user($username, $password, $first_name, $last_name);
    } else {
        echo "Missing parameters. Contact your sysadmin.";
    }
?>
