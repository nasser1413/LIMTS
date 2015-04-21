<?php
	// Import the "Grab Bag"
    require("../common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("{\"response\": \"Connection failed: \"}" . $conn->connect_error);
	}

	// Get all of the "Parameters"
	$name = $_GET["name"];
	$max_credit_hours = $_GET["MaxCreditHours"];
	$professor_type = $_GET["ProfessorType"];
	$valpo_id = $_GET["ValpoId"];

	// Check to make sure the required information is present
	if (!($name && $max_credit_hours && $valpo_id)) {
		die("{\"response\": \"You must specify the name, start date and end date!\"}");
	}
	// Assume a default value for ProfessorType if it is null
	if (!$type) {
		$type = "1";
	}
	// Check to see if the Professor already exist in the database
	$result = $conn->query("SELECT *
				FROM `Professor`
				WHERE `ValpoId`='$valpo_id'");
	if ($result->num_rows > 0) {
		die("{\"response\": \"Professor already exists in database\"}");
	}
	$result->close();
	// Everything seems ok at this point, so just add the professor
	$result = $conn->query("INSERT INTO `Professor` (Name, MaxCreditHours, ProfessorType, ValpoId)
				VALUES('$name', '$max_credit_hours', '$professor_type', '$valpo_id')");
	if (!$result) {
		die("{\"response\": \"Could not insert professor!\"}");
	}

	 // Give a success response
    	echo "{\"response\": \"Success\"}";

	// Finally, close the connection
	$conn->close();
?>
