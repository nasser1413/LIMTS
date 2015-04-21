<?php
	// Import the "Grab Bag"
	require("../common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$result = $conn->query("SELECT *
			                FROM  `Class`;");
	$classes = array();
	while ($row = $result->fetch_row()) {
		array_push($classes, array(
            "id" => $row[CLASS_ID],
            "name" => $row[CLASS_NAME],
            "credithours" => $row[CLASS_CREDITHOURS],
            "contacthours" => $row[CLASS_CONTACTHOURS],
            "title" => $row[CLASS_TITLE]
        ));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($classes);

	// Finally, close the connection
	$conn->close();
?>
