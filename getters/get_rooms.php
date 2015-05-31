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
    $building = $_GET["building"];
    $id = $_GET["id"];

	$query = "SELECT *
              FROM  `Room`
			  WHERE `UserID` = $userId";

    if ($building) {
        $query .= PHP_EOL . "AND `Building`=$building";
    } else if ($id) {
        $query .= PHP_EOL . "AND `id`=$id";
	}

	$result = $conn->query($query);
	$rooms = array();
	while ($row = $result->fetch_row()) {
		if ($row[ROOM_ID]) {
			$building = get_x_with_id($conn, "Building", $row[ROOM_BLDG]);
			$name = $building[BUILDING_ABRV] . "-" . $row[ROOM_NMBR];

			array_push($rooms, array("id" => $row[ROOM_ID], "name" => $name, "building" => $row[ROOM_BLDG], "nmbr" => $row[ROOM_NMBR], "cap" => $row[ROOM_CAP], "handicap_accessible" => $row[HANDICAP_ACCESSIBLE]));
		}
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($rooms);

	// Finally, close the connection
	$conn->close();
?>
