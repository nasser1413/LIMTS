<?php
    echo "{\"sections\":";
    // Import get sections routines
	include "get_sections.php";
    echo ",\"columns\":";
    $columns = array();
    foreach ($filtered_sections[0] as $key => $value) {
        if (!($key == "database_id" || $key == "meeting_type")) {
            array_push($columns, $key);
        }
    }
    echo json_encode($columns) . "}";
?>
