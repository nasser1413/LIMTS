<?php
	// Import the "Grab Bag"
    require("../common.php");

	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

	// Check connection
	if ($conn->connect_error) {
		die("{\"response\": \"Connection failed: " . $conn->connect_error . "\"}");
	}

	// Get all of the "Parameters" (IRL don't forget to escape them to prevent SQLi!!!)
	$class = $_GET["class"];
	$credit_hours = $_GET["credit_hours"];
	$database_id = $_GET["database_id"];
	$identifier = $_GET["identifier"];
	$max_capacity = $_GET["max_capacity"];
	$meeting_times = $_GET["meeting_times"];
	$meeting_type = $_GET["meeting_type"];
	$professor = $_GET["professor"];
	$rooms = $_GET["rooms"];
	$semester = $_GET["semester"];

    switch($meeting_type) {
        case SECTION_TYPE_TBA:
            $meeting_times = "[\"TBA\"]";
	        $rooms = "[0]";
            break;
        case SECTION_TYPE_ONLINE:
            $meeting_times = "[\"ONLINE\"]";
	        $rooms = "[0]";
            break;
        case SECTION_TYPE_ODD:
        case SECTION_TYPE_EVEN:
            break;
        default:
            $meeting_type = SECTION_TYPE_NORMAL;
            break;
    }

	// Check to make sure the required information is present
	if (!check_parameters($identifier, $rooms, $semester, $class, $professor, $meeting_times)) {
		die("{\"response\": \"You must specify the section id, rooms, semester, class, professor and meeting times.\"}");
	}

	// and for max_capacity
	if (!$max_capacity) {
		$max_capacity = "NULL";
	}

    if (!$credit_hours) {
        $credit_hours = "NULL";
    }

	// Check to see if the section already exists in the database
	if (!$database_id) {
		$result = $conn->query("SELECT *
					FROM `Section`
					WHERE `Identifier`='$identifier'
					AND `Class`='$class'
                    AND `Semester`='$semester'");
		if ($result->num_rows > 0) {
			die("{\"response\": \"Section already exists in database\"}");
		}
		$result->close();

		// Everything seems ok at this point, so just add the semester
		//	In Reality, we should check all of these parameters against the database (but we don't have to worry a ton
		//	because we'll get 'em from our *own* UI which should validate them for us)
		$result = $conn->query("INSERT INTO `Section` (Identifier, Rooms, Semester, Class, Professor, MeetingTimes, MeetingType, MaxCapacity, CreditHours)
					VALUES('$identifier', '$rooms', '$semester', '$class', '$professor', '$meeting_times', '$meeting_type', $max_capacity, $credit_hours)");
	} else {
		$query = "UPDATE `Section`
					SET Identifier='$identifier', Rooms='$rooms', Semester='$semester', Class='$class', Professor='$professor', MeetingTimes='$meeting_times', MeetingType='$meeting_type', MaxCapacity='$max_capacity', CreditHours='$credit_hours'
					WHERE id=$database_id";
		$result = $conn->query($query);
	}

	if (!$result) {
		die("{\"response\": \"Could not insert section!\"}");
	}

	echo "{\"response\": \"Success\"}";
	// Finally, close the connection
	$conn->close();
?>
