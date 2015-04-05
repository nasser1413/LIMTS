<?php
  // Disable output in included pages whenever possible
  $OUTPUT_DISABLED = true;

  // Include the get_semesters_in_range page
  include "get_semesters_in_range.php";

  echo "{\"semesters\":" . json_encode($semesters_in_range);

  // TODO: Add a method to merge GET semesters w/ the semesters in range

  // Include the get_sections_by page w/ Output disabled
  include "get_sections.php";

  /* TODO: Right now this only handles classes that
   * meet every week. In other words it does not
   * properly handle ODD_WEEKS or EVEN_WEEKS classes
   */

  // For Each of the Semesters, load the sections
  $events = array();
  foreach ($semesters_in_range as $semester) {
    $semester_id = $semester[SEMESTER_ID];

    foreach ($filtered_sections as $section) {
      // If the section isn't in our semester we need to skip it
      if ($section->semester != $semester["name"]) {
        continue;
      }
      // Grab the relevant section information
      $meeting_times = parse_meeting_times($section->meeting_times);
      $name = $section->name;
      // Loop through the time range
      for ($time = $semester["start"]; ($time <= $end) && ($time <= $semester["end"]); $time = strtotime("+1 day", $time)) {
        // Get the day of the week (numbered 1-7)
        $day = date("N", $time);
        // If we meet today then...
        if ($meeting_times[$day] != NULL) {
          // Generate an event w/ the appropriate information
          $event = array();
          $event["title"] = $name;
          $event["id"] = $section->database_id;
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
  echo ",\"events\":" . json_encode($events) . "}";
?>
