<?php
	// Import the "Grab Bag"
	require("common.php");
	
	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    $building = $_GET["building"];

    if ($building == NULL) {
        die("You must specify the building.");
    }

	$result = $conn->query("SELECT *
			                FROM  `Room`
                            WHERE `Building`=$building;");
	$rooms = array();
	while ($row = $result->fetch_row()) {
		array_push($rooms, array("id" => $row[ROOM_ID], "nmbr" => $row[ROOM_NMBR], "cap" => $row[ROOM_CAP]));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($rooms);

	// Finally, close the connection
	$conn->close();
?>
