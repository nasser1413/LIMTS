<?php
include "common.php";

session_start();

logout_user();

header("location: index.php");

exit;
?>
