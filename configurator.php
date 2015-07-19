<?php
  // Import the "Grab Bag"
  require("common.php");

  // Open an (OO) MySQL Connection
  $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

  // Check connection
  if ($conn->connect_error || !session_start()) {
    die("{\"response\": \"Connection failed: " . $conn->connect_error . "\"}");
  }

  // Get the values from the POST parameters
  $username   = $_POST["username"];
  $firstname  = $_POST["firstname"];
  $lastname   = $_POST["lastname"];
  $password   = $_POST["password"];

  if (!check_parameters($username, $firstname, $lastname)) {
    die("{\"response\": \"You must specify the username, firstname and lastname!\"}");
  }

  // If we are updating the password, change it accordingly
  if ($password && ($result = change_user_password($conn, $username, $password)) != "Successful") {
    die("{\"response\": \"$result\"}");
  }

  // Finally, update the other user information and die...
  $result = update_user_info($conn, $username, $firstname, $lastname);
  die("{\"response\": \"$result\"}");
?>
