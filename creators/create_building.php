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
    $name =  $conn->escape_string($_GET["name"]);
    $abbreviation = $_GET["abbreviation"];
    $database_id = $_GET["database_id"];

    // Check to make sure the required information is present
    if (!($name && $abbreviation)) {
        die("{\"response\": \"You must specify the name, and abbreviation!\"}");
    }

    if (!$database_id) {
        // Check to see if the Building already exists in the database
        $query = "SELECT *
                    FROM `Building`
                    WHERE `UserID`='$userId'
                    AND `Name`='$name'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
          die("{\"response\": \"Building already exists in database\"}");
        }
        $result->close();

        $result = $conn->query("SELECT *
                                FROM `Building`
                                WHERE `UserID` = $userId
                                AND `Abbreviation`='$abbreviation'");
        if ($result->num_rows > 0) {
          die("{\"response\": \"Building already exists in database\"}");
        }
        $result->close();

        // Everything seems ok at this point, so just add the Building
        //In Reality we'd also validate the dates to make sure EndDate > StartDate
        $result = $conn->query("INSERT INTO `Building` (Name, Abbreviation, UserID)
                                VALUES('$name', '$abbreviation', '$userId')");
    } else {
	    $query = "UPDATE `Building`
		            SET Name='$name', Abbreviation='$abbreviation'
		            WHERE id=$database_id";
		$result = $conn->query($query);
    }

    if (!$result) {
        die("{\"response\": \"Could not insert Building!\"}");
    }

    // Give a success response
    echo "{\"response\": \"Success\"}";

    // Finally, close the connection
    $conn->close();
?>
