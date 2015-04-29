<?php
	// Import the "Grab Bag"
    require("../common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get all of the "Parameters"
	$name = $_GET["name"];
	$credit_hours = $_GET["creditHours"];
	$database_id = $_GET["id"];

	// Check to make sure the required information is present
	if (!($name && $credit_hours)) {
		die("{\"response\": \"You must specify the name and default credit hours!\"}");
	}

    if (!$database_id) {
    	// Check to see if the Type already exists in the database
    	$result = $conn->query("SELECT *
    							FROM `ProfessorType`
    							WHERE `Name`='$name'");
    	if ($result->num_rows > 0) {
    		die("{\"response\": \"Type already exists in database\"}");
    	}
    	$result->close();

    	// Everything seems ok at this point, so just add the room
    	$result = $conn->query("INSERT INTO `ProfessorType`(`Name`, `DefaultCreditHours`)
    							VALUES('$name', '$credit_hours')");
    } else {
        $query = "UPDATE `ProfessorType`
                    SET Name='$name', `DefaultCreditHours`='$credit_hours'
                    WHERE id=$database_id";
        $result = $conn->query($query);
    }

	if (!$result) {
		die("{\"response\": \"Could not insert Type!\"}");
	}

	// Give a success response
	echo "{\"response\": \"Success\"}";

	// Finally, close the connection
	$conn->close();
?>
