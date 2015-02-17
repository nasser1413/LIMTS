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

	// Get something of type $type with id $id
	function get_x_with_id($conn, $type, $id) {
		$result = $conn->query("SELECT * 
					FROM  `$type` 
					WHERE `id` =$id;");
		$row = $result->fetch_row();
		$result->close();

		return $row;
	}

	// Get Rooms from DataBase IDentifierS
	function get_rooms_from_dbids($conn, $ids) {
		$rooms = array();
		foreach ($ids as $id) {
			/* From the database, we get ids as:
			 *	[0] => building, [1] => room
			 */
			$exploded_ids = explode("-", $id);
			
			$building = get_x_with_id($conn, "Building", $exploded_ids[0]);
			$room = get_x_with_id($conn, "Rooms", $exploded_ids[1]);
			
			array_push($rooms, $building[2] . "-" . $room[1]);
		}
		return $rooms;
	}

	class ValpoClass {
		public $name;
		public $credit_hours;
		public $contact_hours;
		public $title;
	
		public $sections = array();

		function add_section($section) {
			array_push($this->sections, $section);
		}
	}

	class ValpoSection {
		public $identifier;
		public $meeting_times;
		public $rooms;
		public $semester;
		public $week_style;
		public $capacity;
	}
?>
