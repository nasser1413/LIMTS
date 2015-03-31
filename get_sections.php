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
	$section_ids = $_GET["id"];
	$professors = $_GET["professor"];
	$semesters = $_GET["semester"];
	$classes = $_GET["class"];
	$rooms = $_GET["room"];
	$buildings = $_GET["building"];

	// Initialize the Query to be a blank string
	$query = "";

	// If the sections id was provided for us
	if ($section_ids) {
		// Decode the JSON
		$section_ids = implode_parameters($section_ids);
		// And Append the Sections Query
		$query .= PHP_EOL . "AND `id` IN ( $section_ids )";
	}
	// If we were provided with a professors
	if ($professors) {
		// Decode the JSON
		$professors = implode_parameters($professors);
		// Append the professors Query
		$query .= PHP_EOL . "AND `Professor` IN ( $professors )";
	}
	// If we were provided with a semesters
	if ($semesters) {
		// Decode the JSON
		$semesters = implode_parameters($semesters);
		// Append the semesters Query
		$query .= PHP_EOL . "AND `Semester` IN ($semesters)";
	}
	// If we were provided with a classes
	if ($classes) {
		// Decode the JSON
		$classes = implode_parameters($classes);
		// Append the classes Query
		$query .= PHP_EOL . "AND `Class` IN ($classes)";
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
