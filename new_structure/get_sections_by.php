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
	$section_ids = $_GET["ids"];
	$professors = $_GET["professors"];
	$semesters = $_GET["semesters"];
	$classes = $_GET["classes"];
	$rooms = $_GET["rooms"];
	$buildings = $_GET["buildings"];

	// Initialize the Query to be a blank string
	$query = "";

	// If the sections id was provided for us
	if ($section_ids) {
		// Decode the JSON
		$section_ids = json_to_sql($section_ids);
		// And Append the Sections Query
		$query .= PHP_EOL . "AND `id` IN ( $section_ids )";
	}
	// If we were provided with a professors
	if ($professors) {
		// Decode the JSON
		$professors = json_to_sql($professors);
		// Append the professors Query
		$query .= PHP_EOL . "AND `Professor` IN ( $professors )";
	}
	// If we were provided with a semesters
	if ($semesters) {
		// Decode the JSON
		$semesters = json_to_sql($semesters);
		// Append the semesters Query
		$query .= PHP_EOL . "AND `Semester` IN ($semesters)";
	}
	// If we were provided with a classes
	if ($classes) {
		// Decode the JSON
		$classes = json_to_sql($classes);
		// Append the classes Query
		$query .= PHP_EOL . "AND `Class` IN ($classes)";
	}

	if ($rooms) {
		$rooms = json_decode($rooms);
		if (!is_array($rooms)) {
			$rooms = array($rooms);
		}
	}

	if ($buildings) {
		$buildings = json_decode($buildings);
		if (!is_array($buildings)) {
			$buildings = array($buildings);
		}
	}

	// Now here's where we replace the first occurence of "AND" with "WHERE" so the query works
	$query = str_replace_first(PHP_EOL . "AND", PHP_EOL . "WHERE", $query);

	// Then, load only the desired sections (which is potentially all of them)
	$sections_pool = get_all_sections_with_query($conn, $query);

	$filtered_sections = array();
	foreach ($sections_pool as $section) {
	  // This is what does all the room/building filtering, it (probably) could be simplified but I'm lazy
	  if ($rooms || $buildings) {
	    $section_rooms = json_decode($section[SECTION_ROOMS]);
	    $skip = true;
	    foreach ($section_rooms as $section_room) {
	      if ($rooms && in_array($section_room, $rooms)) {
	        $skip = false;
	        break;
	      } else if ($buildings && in_array(get_building_for_room($conn, $section_room), $buildings)) {
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

	if (!$OUTPUT_DISABLED) {
		// Echo all of the classes as JSON
		echo json_encode($filtered_sections);
	}

	// Finally, close the connection
	$conn->close();
?>
