<?php
	// Import the "Grab Bag"
	require "../common.php";

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error || !session_start()) {
		die("Connection failed: " . $conn->connect_error);
	}

	$userId = $_SESSION[USER_ID];
	$id = $_GET["id"];

	$query = "SELECT *
			  FROM  `ProfessorType`
			  WHERE `UserID` = $userId";

	if ($id) {
		$id = implode_parameters($id);
		$query .= PHP_EOL . "AND `id` IN (" . $id . ")";
	}

	$result = $conn->query($query);
	$professor_types = array();
	while ($row = $result->fetch_row()) {
		array_push($professor_types, array(  "id" => $row[PROFESSORTYPE_ID],
                                            "name" => $row[PROFESSORTYPE_NAME],
                                            "hours" => $row[PROFESSORTYPE_CRHR] ));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($professor_types);

	// Finally, close the connection
	$conn->close();
?>
