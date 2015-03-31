<?php

	/* File for common/shared functions, classes and global variables (A grab bag - this should be distributed!)
	 *
	 * Created by: Justin Szaday on Tues. Feb 17, 2014
	 */

	require_once "dbconstants.php";

	// Get something of type $type with id $id
	function get_x_with_id($conn, $type, $id) {
		$result = $conn->query("SELECT *
					FROM  `$type`
					WHERE `id` =$id;");
		$row = $result->fetch_row();
		$result->close();

		return $row;
	}

	function get_all_x_with_query($conn, $x, $query, $desired_field = -1) {
		$db_query ="SELECT * FROM `$x`" . $query;
		$sections = array();
		$result = $conn->query($db_query);
		// $sections = $result->fetch_all();
		while ($row = $result->fetch_row()) {
			if ($desired_field != -1) {
				array_push($sections, $row[$desired_field]);
			} else {
				array_push($sections, $row);
			}
		}
		$result->close;
		return $sections;
	}

	// Get all sections with an additional query
	function get_all_sections_with_query($conn, $query) {
		return get_all_x_with_query($conn, "Section", $query);
	}

	// Get the Building ID given a Room ID
	function get_building_for_room($conn, $room_id) {
		$room = get_x_with_id($conn, "Room", $room_id);
		$building = $room[ROOM_BLDG];
		return $building;
	}

	// Replace the first occurence of something
	function str_replace_first($search, $replace, $subject) {
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}

	// Check a bunch o' parameters & make sure they ain't null
	function check_parameters() {
		$params = func_get_args();
		foreach ($params as $param) {
			if (!$param) {
				return false;
			}
		}
		return true;
	}

    function json_to_sql($json) {
        $parameter = json_decode($json);

        if (is_array($parameter)) {
            return implode(",", $parameter);
        } else {
            return $parameter;
        }
    }

	function implode_parameters($parameter) {
		if (is_array($parameter)) {
			return implode(",", $parameter);
		} else {
			return $parameter;
		}
	}

	function parse_meeting_times($meeting_times) {
		$parsed_times = array();
		foreach ($meeting_times as $meeting_time) {
			preg_match($GLOBALS["date_regex"], $meeting_time, $matches);
			$days = $matches[1];
			$start = strtotime($matches[2] . "m", 0);
			$end = strtotime($matches[3] . "m", 0);
			$range = array($start, $end);

			for ($i = 0; $i < strlen($days); $i++) {
				$parsed_times[$GLOBALS["day_abbreviations"][$days{$i}]] = $range;
			}
		}
		return $parsed_times;
	}

	class ValpoSection {
		public $name;
		public $credit_hours;
		public $title;
		public $meeting_times;
		public $rooms;
		public $semester;
		public $week_style;
		public $professor;
		public $capacity;
		public $database_id;

		public static function new_from_db_row($conn, $db_row) {
			$section = new ValpoSection();
			$class = get_x_with_id($conn, "Class", $db_row[SECTION_CLASS]);
			$professor = get_x_with_id($conn, "Professor", $db_row[SECTION_PROF]);
			$semester = get_x_with_id($conn, "Semester", $db_row[SECTION_SEM]);

			$section->database_id = $db_row[SECTION_DBID];
			$section->name = $class[CLASS_NAME] . "-" . $db_row[SECTION_ID];
			$section->credit_hours = $class[CLASS_CRHR];
			$section->title = $class[CLASS_TITLE];
			$section->semester = $semester[SEMESTER_NAME];
			$section->week_style = $db_row[SECTION_WEEKS];
			$section->professor = $professor[PROFESSOR_NAME];
			$section->meeting_times = json_decode($db_row[SECTION_TIMES]);

			$section->rooms = array();
			$smallest_cap = PHP_INT_MAX;
			$room_ids = json_decode($db_row[SECTION_ROOMS]);
			foreach ($room_ids as $room_id) {
				$room = get_x_with_id($conn, "Room", $room_id);
				$building = get_x_with_id($conn, "Building", $room[ROOM_BLDG]);

				if ($room[ROOM_CAP] < $smallest_cap) {
					$smallest_cap = $room[ROOM_CAP];
				}

				array_push($section->rooms, $building[BUILDING_ABRV] . "-" . $room[ROOM_NMBR]);
			}

			if ($db_row[SECTION_MCAP]) {
				$section->capacity = $db_row[SECTION_MCAP];
			} else {
				// Assign to smallest capacity
				$section->capacity = $smallest_cap;
			}

			return $section;
		}
	}
?>
