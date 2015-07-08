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
	$id     = $_GET["id"];

	$query = "SELECT *
				  FROM  `Semester`
				  WHERE `UserID` = $userId";

	if ($id) {
	  $id = implode_parameters($id);
	  $query .= PHP_EOL . "AND `id` IN (" . $id . ")";
	}

	$result    = $conn->query($query);
	$semesters = array();
	while ($row = $result->fetch_row()) {
	  $semester_start = strtotime("+1 day", strtotime($row[SEMESTER_START]));
	  $semester_end   = strtotime("+1 day", strtotime($row[SEMESTER_END]));

	  array_push($semesters, array(
	    "id" => $row[SEMESTER_ID],
	    "name" => $row[SEMESTER_NAME],
	    "type" => $row[SEMESTER_TYPE],
	    "start" => $semester_start,
	    "end" => $semester_end
	  ));
	}
	$result->close();

	// Echo all of the classes as JSON
	echo json_encode($semesters);

	// Finally, close the connection
	$conn->close();
?>
