<?php
	// Import the "Grab Bag"
	require("common.php");
	
	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get all of the "Parameters"
	$section_id = $_GET["id"] ? "[" . $_GET["id"] . "]" : $_GET["ids"];
	$professor = $_GET["professor"];
	$building = $_GET["building"];
	$class = $_GET["class"];
	$room = $_GET["room"];
	$semester = $_GET["semester"];

	// Initialize the Query to be a blank string
	$query = "";
	
	// If the sections id was provided for us
	if ($section_id) {
		// Decode the JSON
		$ids = json_decode($section_id);
		// Then "Implode" the IDs into a string
		$matches = implode(',', $ids);
		// And Append the Sections Query
		$query .= PHP_EOL . "AND `id` IN ( $matches )";
	}
	// If we were provided with a Professor
	if ($professor) {
		// Append the Professor Query
		$query .= PHP_EOL . "AND `Professor` = $professor";
	}
	// If we were provided with a Semester
	if ($semester) {
		// Append the Semester Query
		$query .= PHP_EOL . "AND `Semester` = $semester";
	}
	// You should get the idea by now
	if ($class) {
		$query .= PHP_EOL . "AND `Class` = $class";
	}

	// Now here's where we replace the first occurence of "AND" with "WHERE" so the query works
	$query = str_replace_first(PHP_EOL . "AND", PHP_EOL . "WHERE", $query);

	// Then, load the given Sections (or potentially all of them)
	$sections_pool = get_all_sections_with_query($conn, $query);

	$filtered_sections = array();
	foreach ($sections_pool as $section) {
		// This is what does all the room/building filtering, it (probably) could be simplified but I'm lazy
		if ($room || $building) {
			$rooms = json_decode($section[SECTION_ROOMS]);
			$skip = true;
			foreach ($rooms as $room_tmp) {
				if ($room && ($room == $room_tmp)) {
					$skip = false;
					break;
				} else if ($building && ($building == get_building_for_room($conn, $room_tmp))) {
					$skip = false;
					break;
				}
			}
			if ($skip) {
				continue;
			}
		}
	
		// Create a new ValpoSection and append it to the sections array
		array_push($filtered_sections, ValpoSection::new_from_db_row($conn, $section));
	}

	// Echo all of the classes as JSON
	echo json_encode($filtered_sections);

	// Finally, close the connection
	$conn->close();
?>
