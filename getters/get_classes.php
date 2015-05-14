<?php
	// Import the "Grab Bag"
	require("../common.php");

	// Compare function for usort
	function cmp($a, $b) {
		// strcasecmp is case-insensitive...
		return strcasecmp($a["name"], $b["name"]);
	}

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Default Query
    $query = "SELECT *
			  FROM  `Class`";

	// With additional parameters if needed
    $id = $_GET["id"];
    if ($id) {
        $query .= PHP_EOL . "WHERE Id = $id";
    }

	// For each of the results
	$result = $conn->query($query);
	$classes = array();
	while ($row = $result->fetch_row()) {
		// Add them to the array
		array_push($classes, array(
            "id" => $row[CLASS_ID],
            "name" => $row[CLASS_NAME],
            "credithours" => $row[CLASS_CREDITHOURS],
            "contacthours" => $row[CLASS_CONTACTHOURS],
            "title" => $row[CLASS_TITLE]
        ));
	}
    $result->close();

	// Sort the classes array
	usort($classes, "cmp");

	// Echo all of the classes as JSON
	echo json_encode($classes);

	// Finally, close the connection
	$conn->close();
?>
