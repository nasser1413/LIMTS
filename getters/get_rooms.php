<?php
	// Import the "Grab Bag"
	require "../common.php";

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    $building = $_GET["building"];
    $id = $_GET["id"];

	$query = "SELECT *
              FROM  `Room`";

    if ($building) {
        $query .= "WHERE `Building`=$building";
    } else if ($id) {
        $query .= "WHERE `id`=$id";
	}

	$result = $conn->query($query);
	$rooms = array();
	while ($row = $result->fetch_row()) {
		$building = get_x_with_id($conn, "Building", $row[ROOM_BLDG]);
		$name = $building[BUILDING_ABRV] . "-" . $row[ROOM_NMBR];

		array_push($rooms, array("id" => $row[ROOM_ID], "name" => $name, "nmbr" => $row[ROOM_NMBR], "cap" => $row[ROOM_CAP]));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($rooms);

	// Finally, close the connection
	$conn->close();
?>
