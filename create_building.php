<?php
  // Import the "Grab Bag"
require("common.php");

// Open an (OO) MySQL Connection
$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get all of the "Parameters"
$name = $_GET["name"];
$abbreviation = $_GET["abbreviation"];
$rooms = $_GET["rooms"];

// Check to make sure the required information is present
if (!($name && $abbreviation )) {
  die("You must specify the name, and abbreviation!");
}
// Assume a default value for rooms if it is null
if (!$rooms) {
  $rooms = "none";
}
// Check to see if the Building already exists in the database
$result = $conn->query("SELECT *
FROM `Building`
WHERE `Name`='$name'");
if ($result->num_rows > 0) {
  die("Building already exists in database");
}
$result->close();
// Everything seems ok at this point, so just add the Building
//In Reality we'd also validate the dates to make sure EndDate > StartDate
$result = $conn->query("INSERT INTO `Building` (Name, Abbreviation)
VALUES('$name', '$abbreviation')");
if (!$result) {
  die("Could not insert Building!");
}
// Finally, close the connection
$conn->close();
?>
