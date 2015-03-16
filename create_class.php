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
$title = $_GET["title"];
$credit_hours = $_GET["credit_hours"];
$contact_hours = $_GET["contact_hours"];
$ascending = $_GET["ascending"];


// Check to make sure the required information is present
if (!($name && $title && $credit_hours && $contact_hours )) {
  die("You must specify the name,title,credit_hours,and contact_hours!");
}
// Assume a default value for ascending if it is null, 0 = false , 1= true
if (!$ascending) {
  $ascending = "0";
}
// Check to see if the Building already exists in the database
$result = $conn->query("SELECT *
FROM `Class`
WHERE `Name`='$name'");
if ($result->num_rows > 0) {
  die("Class already exists in database");
}
$result->close();
// Everything seems ok at this point, so just add the Class
//In Reality we'd also validate the dates to make sure EndDate > StartDate
$result = $conn->query("INSERT INTO `Class` (Name, Title, CreditHours, ContactHours)
VALUES('$name', '$title', '$credit_hours','$contact_hours')");
if (!$result) {
  die("Could not insert Class!");
}
// Finally, close the connection
$conn->close();
?>
