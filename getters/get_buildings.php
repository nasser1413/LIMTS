<?php
	// Import the "Grab Bag"
	require_once "../dbconstants.php";

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error || !session_start()) {
		die("Connection failed: " . $conn->connect_error);
	}

	$userId = $_SESSION[USER_ID];
    $query = "SELECT *
			  FROM  `Building`
		      WHERE `UserID` = $userId";

    $id = $_GET["id"];
    if ($id) {
        $query .= PHP_EOL . "AND Id = $id";
    }

	$result = $conn->query($query);
	$buildings = array();
	while ($row = $result->fetch_row()) {
		if ($row[BUILDING_ID]) {
			array_push($buildings, array("id" => $row[BUILDING_ID], "abbr" => $row[BUILDING_ABRV], "description" => $row[BUILDING_DESC]));
		}
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($buildings);

	// Finally, close the connection
	$conn->close();
?>
