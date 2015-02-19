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
	$start_date = $_GET["start_date"];
	$end_date = $_GET["end_date"];
	$type = $_GET["type"];

	// Check to make sure the required information is present
	if (!($name && $start_date && $end_date)) {
		die("You must specify the name, start date and end date!");
	}
	// Assume a default value for type if it is null
	if (!$type) {
		$type = "Full";
	}
	// Check to see if the semester already exists in the database
	$result = $conn->query("SELECT *
				FROM `Semester`
				WHERE `Name`='$name'");
	if ($result->num_rows > 0) {
		die("Semester already exists in database");
	}
	$result->close();
	// Everything seems ok at this point, so just add the semester
	//	In Reality we'd also validate the dates to make sure EndDate > StartDate
	$result = $conn->query("INSERT INTO `Semester` (Name, Type, StartDate, EndDate)
				VALUES('$name', '$type', '$start_date', '$end_date')");
	if (!$result) {
		die("Could not insert semester!");
	}
	// Finally, close the connection
	$conn->close();
?>
