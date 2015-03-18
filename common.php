<?php

	/* File for common/shared functions, classes and global variables (A grab bag - this should be distributed!)
	 *
	 * Created by: Justin Szaday on Tues. Feb 17, 2014
	 */

	// Connection information (host, uname, pwd)
	$dbhost = "localhost";
	$dbuser = "scheduler";
	$dbpass = "358dbpass";
	$dbname = "test";

	$day_abbreviations = array(	"U" => 1,
					"M" => 2,
					"T" => 3,
					"W" => 4,
					"R" => 5,
					"F" => 6,
					"S" => 7 );
	$date_regex = "/([A-Z]+) *(\d+:\d+[ap])-(\d+:\d+[ap])/";

	// Section Column information
	define("SECTION_ID", 1);
	define("SECTION_ROOMS", 2);
	define("SECTION_SEM", 3);
	define("SECTION_CLASS", 4);
	define("SECTION_PROF", 5);
	define("SECTION_TIMES", 6);
	define("SECTION_WEEKS", 7);
	define("SECTION_MCAP", 8);
	// Room Column information
	define("ROOM_BLDG", 1);
	define("ROOM_NMBR", 2);
	define("ROOM_CAP", 3);
	// Professor Information
	define("PROFESSOR_NAME", 1);
	// Class Information
	define("CLASS_NAME", 1);
	define("CLASS_CRHR", 2);
	define("CLASS_TITLE", 4);
	// Semester Information
	define("SEMESTER_ID", 0);
	define("SEMESTER_NAME", 1);
	define("SEMESTER_TYPE", 2);
	define("SEMESTER_START", 3);
	define("SEMESTER_END", 4);
	// Building Information
	define("BUILDING_ABRV", 2);
	// Semester Types
	define("EVERY_WEEK", 1);
	define("EVEN_WEEKS", 2);
	define("ODD_WEEKS", 3);

	// Get something of type $type with id $id
	function get_x_with_id($conn, $type, $id) {
		$result = $conn->query("SELECT *
					FROM  `$type`
					WHERE `id` =$id;");
		$row = $result->fetch_row();
		$result->close();

		return $row;
	}

	// Get all sections with an additional query
	function get_all_sections_with_query($conn, $query) {
		$db_query ="SELECT * FROM `Section`" . $query;
		$sections = array();
		$result = $conn->query($db_query);
		// $sections = $result->fetch_all();
		while ($row = $result->fetch_row()) {
			array_push($sections, $row);
		}
		$result->close;
		return $sections;
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

	function parse_meeting_times($section) {
		$parsed_times = array();
		$meeting_times = json_decode($section[SECTION_TIMES]);
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

		public static function new_from_db_row($conn, $db_row) {
			$section = new ValpoSection();
			$class = get_x_with_id($conn, "Class", $db_row[SECTION_CLASS]);
			$professor = get_x_with_id($conn, "Professor", $db_row[SECTION_PROF]);
			$semester = get_x_with_id($conn, "Semester", $db_row[SECTION_SEM]);

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
