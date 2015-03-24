<?php
  // Include the get_sections_by page w/ Output disabled
  $OUTPUT_DISABLED = true;
  include "get_sections_by.php";

  // Open an (OO) MySQL Connection
  $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get all of the "Parameters"
  $start = strtotime($_GET["start"]);
  $end = strtotime($_GET["end"]);

  // Validate the dates
  if ($start > $end) {
    die("Invalid date range");
  }

  // Grab all of the Semesters
  $semesters = array();
  $query = "SELECT *
            FROM `Semester`";
  $result = $conn->query($query);
  while ($row = $result->fetch_row()) {
    $semester_start = strtotime("+1 day", strtotime($row[SEMESTER_START]));
    $semester_end = strtotime("+1 day", strtotime($row[SEMESTER_END]));
    // If the semester is in the desired range add it to the list
    if ($semester_end >= $start) {
      array_push($semesters, array( SEMESTER_ID => $row[SEMESTER_ID],
                                    SEMESTER_START => $semester_start,
                                    SEMESTER_END => $semester_end ));
    }
  }
  $result->close();

  /* Right now this only handles classes that
   * meet every week. In other words it does not
   * properly handle ODD_WEEKS or EVEN_WEEKS classes
   */

  // For Each of the Semesters, load the sections
  $events = array();
  foreach ($semesters as $semester) {
    $semester_id = $semester[SEMESTER_ID];
    // $sections = get_all_sections_with_query($conn, PHP_EOL . "WHERE `Semester` = $semester_id");

    foreach ($filtered_sections as $section) {
      // Grab the relevant section information
      $meeting_times = parse_meeting_times($section->meeting_times);
      $name = $section->name;
      // Loop through the time range
      for ($time = $semester[SEMESTER_START]; ($time <= $end) && ($time <= $semester[SEMESTER_END]); $time = strtotime("+1 day", $time)) {
        // Get the day of the week (numbered 1-7)
        $day = date("N", $time);
        // If we meet today then...
        if ($meeting_times[$day] != NULL) {
          // Generate an event w/ the appropriate information
          $event = array();
          $event["title"] = $name;
          $event["allDay"] = false;
          $event["start"] = date(DATE_ATOM, strtotime("-1 day", $time + $meeting_times[$day][0]));
          $event["end"] = date(DATE_ATOM, strtotime("-1 day", $time + $meeting_times[$day][1]));
          // Add the event to the list
          array_push($events, $event);
        }
      }
    }
  }

  // Echo all of the classes as JSON
  echo json_encode($events);

  // Finally, close the connection
  $conn->close();
?>
