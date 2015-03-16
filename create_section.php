<?php
	// Import the "Grab Bag"
	require("common.php");
	
	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get all of the "Parameters" (IRL don't forget to escape them to prevent SQLi!!!)
	$identifier = $_GET["identifier"];
	$rooms = $_GET["rooms"];
	$semester = $_GET["semester"];
	$class = $_GET["class"];
	$professor = $_GET["professor"];
	$meeting_times = $_GET["meeting_times"];
	$week_style = $_GET["week_style"];
	$max_capacity = $_GET["max_capacity"];

	// Check to make sure the required information is present
	if (!check_parameters($identifier, $rooms, $semester, $class, $professor, $meeting_times)) {
		die("You must specify the section id, rooms, semester, class, professor and meeting times.");
	}
	// Check to see if the section already exists in the database
	$result = $conn->query("SELECT *
				FROM `Section`
				WHERE `Identifier`='$identifier'
				AND `Class`='$class'");
	if ($result->num_rows > 0) {
		die("Section already exists in database");
	}
	$result->close();
	// Assume a default value for week_style
	if (!$week_style) {
		$week_style = 1;
	}
	// and for max_capacity
	if (!$max_capacity) {
		$max_capacity = "NULL";
	}
	// Everything seems ok at this point, so just add the semester
	//	In Reality, we should check all of these parameters against the database (but we don't have to worry a ton
	//	because we'll get 'em from our *own* UI which should validate them for us)
	$result = $conn->query("INSERT INTO `Section` (Identifier, Rooms, Semester, Class, Professor, MeetingTimes, WeekStyle, MaxCapacity)
				VALUES('$identifier', '$rooms', '$semester', '$class', '$professor', '$meeting_times', '$week_style', $max_capacity)");
	if (!$result) {
		die("Could not insert section!");
	}
	// Finally, close the connection
	$conn->close();
?>
