<?php
    require_once "../dbconstants.php";

    // Open an (OO) MySQL Connection
    $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

    // Check connection
    if ($conn->connect_error || !session_start()) {
		die("Connection failed: " . $conn->connect_error);
	}

    // Get all of the "Parameters"
	$userId = $_SESSION[USER_ID];
    $start = strtotime($_GET["start"]);
    $end = strtotime($_GET["end"]);

    // Validate the dates
    if ($start > $end) {
      die("Invalid date range");
    }

    // Grab all of the Semesters
    $semesters_in_range = array();
    $query = "SELECT *
              FROM `Semester`
              WHERE `UserID` = $userId";
    $result = $conn->query($query);
    while ($row = $result->fetch_row()) {
      // Convert the times into an appropriate format
      $semester_start = strtotime("+1 day", strtotime($row[SEMESTER_START]));
      $semester_end   = strtotime("+1 day", strtotime($row[SEMESTER_END]));

      // If the semester is in the desired range add it to the lists
      if (($semester_end >= $start) && ($semester_start <= $end)) {
        array_push($semesters_in_range, array(  "id" => $row[SEMESTER_ID],
                                                "name" => $row[SEMESTER_NAME],
                                                "start" => $semester_start,
                                                "end" => $semester_end ));
      }
    }
    $result->close();

    if (!$OUTPUT_DISABLED) {
        echo json_encode($semesters_in_range);
    }

    // Finally, close the connection
    $conn->close();
?>
