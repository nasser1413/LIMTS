<?php

	/* File for common/shared functions, classes and global variables (A grab bag - this should be distributed!)
	 *
	 * Created by: Justin Szaday on Tues. Feb 17, 2014
	 */

	require_once "dbconstants.php";

	/************************************************************
	*   "Getter" Functions						                *
	************************************************************/

	// Get something of type $type with id $id
	function get_x_with_id($conn, $type, $id) {
		$result = $conn->query("SELECT *
								FROM  `$type`
								WHERE `id` =$id;");
		if (!$result) {
			return NULL;
		} else {
			$row = $result->fetch_row();
			$result->close();
			return $row;
		}
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
		$result->close();
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

	// Parse meeting times into a more appropriate format
	function parse_meeting_times($section, $rooms, $buildings) {
		$meeting_times = $section->meeting_times;
		$parsed_times = array();

		for ($i = 0; $i < count($meeting_times); $i++) {
			$room_id = $section->room_ids[$i];

			if ($rooms && !in_array($room_id, $rooms)) {
				continue;
			}

			if ($buildings) {
				$room = get_x_with_id($conn, "Room", $room_id);

				if(!in_array($room[ROOM_BLDG], $buildings)) {
					continue;
				}
			}

			preg_match($GLOBALS["date_regex"], $meeting_times[$i], $matches);
			$days = $matches[1];
			$start = strtotime($matches[2] . "m", 0);
			$end = strtotime($matches[3] . "m", 0);
			$range = array($start, $end, $i);

			for ($j = 0; $j < strlen($days); $j++) {
				$parsed_times[$GLOBALS["day_abbreviations"][$days{$j}]] = $range;
			}
		}

		return $parsed_times;
	}

	/************************************************************
	*   Utility Functions						                						*
	************************************************************/

	// Replace the first occurence of something
	function str_replace_first($search, $replace, $subject) {
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}

	// Check a bunch o" parameters & make sure they ain"t null
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

	/************************************************************
	*   User Management Functions					            *
	************************************************************/

	// creates a 3 character sequence
	function create_salt()
	{
		$string = md5(uniqid(rand() , true));
		return substr($string, 0, 3);
	}

	// this is a security measure
	function validate_user($user_data)
	{
		session_regenerate_id();
		$_SESSION = array_merge($_SESSION, $user_data);
		$_SESSION[SESSION_VALID] = true;
		session_write_close();
	}

	// destroys all of the session variables
	function logout_user()
	{
		$_SESSION = array();
		session_destroy();
	}

	// returns true if the user is logged in
	function is_user_logged_in()
	{
		return isset($_SESSION[SESSION_VALID]) && $_SESSION[SESSION_VALID];
	}

	function update_user_info($conn, $username, $firstname, $lastname) {
		$query	 		= "UPDATE `Users`
	               	 SET `Username`='$username', `FirstName`='$firstname', `LastName`='$lastname'
	               	 WHERE `Username`='$username';";
		$user_data	= array(
			"Username" => $username,
			"FirstName" => $firstname,
			"LastName" => $lastname
		);
 		if ($conn->query($query)) {
			validate_user($user_data);
 			return "Successful";
 		} else {
 			return "Could not update user's information, " . $conn->error;
 		}
	}

	function change_user_password($conn, $username, $new_password) {
		$hash 	= hash("sha256", $new_password);
		$salt 	= create_salt();
		$hash		= hash("sha256", $salt . $hash);
		$query	= "UPDATE `Users`
               SET `Password`='$hash', `Salt`='$salt'
               WHERE `Username`='$username';";
		if ($conn->query($query)) {
			return "Successful";
		} else {
			return "Could not update user's password, " . $conn->error;
		}
	}

	// logs a user in, returns an error string if unsuccessful
	function login_user($username, $password)
	{
		$username = strtolower($username);
		$mysqli = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
		if (mysqli_connect_errno())
		{
			return mysqli_connect_error();
		}
		$username = $mysqli->real_escape_string($username);
		$query = "SELECT * FROM Users WHERE Username = '$username';";
		$result = $mysqli->query($query);
		if ($result->num_rows < 1)
		{
			$result->close();
			$mysqli->close();
			return "User \"$username\" not found!";
		}
		$user_data = $result->fetch_assoc();
		$result->close();
		$hash = hash("sha256", $user_data[USER_SALT] . hash("sha256", $password));
		if ($hash != $user_data[USER_PASSWORD])
		{
			$mysqli->close();
			return "Incorrect password for user \"$username\"!";
		}
		$mysqli->close();
		validate_user($user_data);
		return "Successful";
	}

	// registers a user, returns an error string if unsuccessful
	function register_user($username, $password, $firstname, $lastname)
	{
		$username = strtolower($username);
		$hash = hash("sha256", $password);
		$salt = create_salt();
		$hash = hash("sha256", $salt . $hash);
		$mysqli = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
		if (mysqli_connect_errno())
		{
			return mysqli_connect_error();
		}
		// sanitize username
		$username = $mysqli->real_escape_string($username);
		// check if user is registered
		$query = "SELECT * FROM Users WHERE Username = '$username';";
		$result = $mysqli->query($query);
		if ($result->num_rows > 0)
		{
			// if they are, close the connection and result and return
			$result->close();
			$mysqli->close();
			return "Username already registered!";
		}
		// close the result
		$result->close();
		// insert user into database
		$query = "INSERT INTO Users ( Username, FirstName, LastName, Password, Salt ) VALUES ( '$username' , '$firstname' , '$lastname', '$hash', '$salt' );";
		$result = $mysqli->query($query);
		if (!$result) {
			$mysqli->close();
			return "Could not insert user.";
		}
		// Close the connection
		$mysqli->close();
		// Return Successful
		return "Successful";
	}

	/************************************************************
	*   Misc. Classes							                *
	************************************************************/

	class ValpoSection {
		public $name;
		public $credit_hours;
		public $tl_credits;
		public $title;
		public $meeting_times;
		public $room_ids;
		public $rooms;
		public $semester;
		public $meeting_type;
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
			$section->title = $class[CLASS_TITLE];
			$section->semester = $semester[SEMESTER_NAME];
			$section->meeting_type = $db_row[SECTION_TYPE];
			$section->professor = $professor[PROFESSOR_NAME];
			$section->meeting_times = json_decode($db_row[SECTION_TIMES]);

			// Load the number of credit hours from the row at first
			$section->credit_hours = $db_row[SECTION_CRHR];
			// But if it is null
			if (is_null($section->credit_hours)) {
				// Grab them from the class instead
				$section->credit_hours = $class[CLASS_CREDITHOURS];
			}

			// Load the number of credit hours from the row at first
			$section->tl_credits = $db_row[SECTION_TLC];
			// But if it is null
			if (is_null($section->tl_credits)) {
				// Grab them from the class instead
				$section->tl_credits = $class[CLASS_CONTACTHOURS];
				// If it is still null, the number of tlc = credithours
				if (is_null($section->tl_credits)) {
					$section->tl_credits = $section->credit_hours;
				}
			}

			$section->rooms = array();
			$smallest_cap = PHP_INT_MAX;
			$section->room_ids = json_decode($db_row[SECTION_ROOMS]);
			foreach ($section->room_ids as $room_id) {
				$room = get_x_with_id($conn, "Room", $room_id);
				$building = get_x_with_id($conn, "Building", $room[ROOM_BLDG]);

				if ($room[ROOM_CAP] < $smallest_cap) {
					$smallest_cap = $room[ROOM_CAP];
				}

				array_push($section->rooms, $building[BUILDING_ABRV] . "-" . $room[ROOM_NMBR]);
			}

			$section->capacity = $db_row[SECTION_MCAP];
			// If the section does not provide a meeting size cap
			if (!$section->capacity) {
				// Assign to smallest capacity
				$section->capacity = $smallest_cap;
			}

			return $section;
		}
	}
?>
