<?php
    // Import the "Grab Bag"
    require("../common.php");

    // Open an (OO) MySQL Connection
    $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

    // Check connection
    if ($conn->connect_error || !session_start()) {
		die("Connection failed: " . $conn->connect_error);
	}

	$userId = $_SESSION[USER_ID];

    // Get all of the "Parameters"
    $name = $_GET["name"];
    $type = $_GET["type"];
    $start_date = $_GET["start_date"];
    $end_date = $_GET["end_date"];
    $database_id = $_GET["database_id"];

    // Check to make sure the required information is present
    if (!($name && $type && $start_date && $end_date)) {
      die("{\"response\": \"You must specify the all the information!\"}");
    }

    if (!$database_id) {
        // Check to see if the Building already exists in the database
        $result = $conn->query("SELECT *
                                FROM `Semester`
                                WHERE `UserID` = $userId
                                AND `Name`='$name'");
        if ($result->num_rows > 0) {
          die("{\"response\": \"Semester already exists in database\"}");
        }
        $result->close();

        // Everything seems ok at this point, so just add the Semester
        //In Reality we'd also validate the dates to make sure EndDate > StartDate
        $result = $conn->query("INSERT INTO `Semester` (Name, Type, StartDate, EndDate, UserID)
                                VALUES('$name', '$type' , '$start_date' , '$end_date', '$userId')");
    } else {
        $result = $conn->query("UPDATE `Semester`
                                SET `Name`='$name', `Type`='$type', `StartDate`='$start_date', `EndDate`='$end_date'
                                WHERE id=$database_id");
    }

    if (!$result) {
        die("{\"response\": \"Could not insert Semester!\"}");
    }

    // Give a success response
    echo "{\"response\": \"Success\"}";

    // Finally, close the connection
    $conn->close();
?>
