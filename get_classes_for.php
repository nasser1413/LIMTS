<?php
	/* Grab a Professor's Classes from the Database
	 * Created By: Justin Szaday
	 */

	// Import the "Grab Bag"
	require('common.php');
	
	// Open an (OO) MySQL Connection
	$conn = new mysqli($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['dbname']);
	
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Grab the Professor as a parameter
	$professor = $_GET["professor"];
	if ($professor == NULL) {
		die("No professor specified");	
	}

	// Select the professor from the table
	$result = $conn->query("SELECT * 
				FROM  `Professor` 
				WHERE `id` =$professor;");
	$row = $result->fetch_row();
	$result->close();

	// Decode the classes from the JSON stored in the table
	$classes = json_decode($row[4]);
	$classes_arr = array();

	foreach ($classes as &$class) {
		/* From the database, we get ids as:
		 *	[0] => class, [1] => section
		 */
		$ids = explode('-', $class);

		/* TODO: There needs to be some more complex logic here
		 *	 for handling when a professor teaches multiple
		 *	 sections of the same class.
		 */
		$class_container = new ValpoClass();
		
		// Select the class from the table
		$result = $conn->query("SELECT * 
					FROM  `Class` 
					WHERE `id` =$ids[0];");
		$row = $result->fetch_row();
		$result->close();
		
		// Populate the Class with information from the database
		$class_container->name = $row[1];
		$class_container->credit_hours = $row[2];
		$class_container->contact_hours = $row[3];
		$class_container->title = $row[4];

		/* TODO: More complicated logic for Sections would be here
		 *	 but in the name of demo code I am omitting it (for 
		 *	 now only)
		 */
		$section_container = new ValpoSection();

		// Select the section from the table
		$result = $conn->query("SELECT * 
					FROM  `Sections` 
					WHERE `id` =$ids[1];");
		$row = $result->fetch_row();
		$result->close();

		$section_container->identifier = $row[1];
		$section_container->meeting_times = json_decode($row[2]);
		$section_container->rooms = get_rooms_from_dbids($conn, json_decode($row[3]));
		$section_container->semester = get_x_with_id($conn, "Semester", $row[4]);
		$section_container->week_style = $row[5];
		$section_container->capacity = $row[6];
		
		// Add the section to the class & put the class in the array
		$class_container->add_section($section_container);
		array_push($classes_arr, $class_container);
	}

	// Echo all of the classes as JSON
	echo json_encode($classes_arr);

	// Finally, close the connection
	$conn->close();
?>
