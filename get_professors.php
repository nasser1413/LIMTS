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
			  FROM  `Professor`";

	if ($id) {
		$id = implode_parameters($id);
		$query .= PHP_EOL . "WHERE `id` IN (" . $id . ")";
	}

	$result = $conn->query($query);
	$professors = array();
	while ($row = $result->fetch_row()) {
        $credit_hours = $row[PROFESSOR_MAXHRS];

        if (!$credit_hours) {
            /* We should consider making this a cached map so we don't have to query
             * the database so often
             */
            $type = get_x_with_id($conn, "ProfessorType", $row[PROFESSOR_TYPE]);
            $credit_hours = $type[PROFESSORTYPE_CRHR];
        }

		array_push($professors, array(  "id" => $row[PROFESSOR_ID],
                                        "name" => $row[PROFESSOR_NAME],
                                        "max_credit_hours" => $credit_hours ));
	}
    $result->close();

	// Echo all of the classes as JSON
	echo json_encode($professors);

	// Finally, close the connection
	$conn->close();
?>
