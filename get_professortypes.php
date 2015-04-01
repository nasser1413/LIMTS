<?php
	// Import the "Grab Bag"
	require("common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$id = $_GET["id"];

	$query = "SELECT *
			  FROM  `ProfessorType`";

	if ($id) {
		$id = implode_parameters($id);
		$query .= PHP_EOL . "WHERE `id` IN (" . $id . ")";
	}

	$result = $conn->query($query);
	$professor_types = array();
	while ($row = $result->fetch_row()) {
		array_push($professor_types, array(  "id" => $row[PROFESSORTYPE_ID],
                                            "name" => $row[PROFESSORTYPE_NAME] ));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($professor_types);

	// Finally, close the connection
	$conn->close();
?>
