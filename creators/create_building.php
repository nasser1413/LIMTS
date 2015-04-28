<?php
    // Import the "Grab Bag"
    require("../common.php");

    // Open an (OO) MySQL Connection
    $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Get all of the "Parameters"
    $name = $_GET["name"];
    $abbreviation = $_GET["abbreviation"];
    $database_id = $_GET["database_id"];

    // Check to make sure the required information is present
    if (!($name && $abbreviation)) {
        die("{\"response\": \"You must specify the name, and abbreviation!\"}");
    }


    if (!$database_id) {
        // Check to see if the Building already exists in the database
        $result = $conn->query("SELECT *
                                FROM `Building`
                                WHERE `Name`='$name'");
        if ($result->num_rows > 0) {
          die("{\"response\": \"Building already exists in database\"}");
        }
        $result->close();

        $result = $conn->query("SELECT *
                                FROM `Building`
                                WHERE `Abbreviation`='$abbreviation'");
        if ($result->num_rows > 0) {
          die("{\"response\": \"Building already exists in database\"}");
        }
        $result->close();

        // Everything seems ok at this point, so just add the Building
        //In Reality we'd also validate the dates to make sure EndDate > StartDate
        $result = $conn->query("INSERT INTO `Building` (Name, Abbreviation)
                                VALUES('$name', '$abbreviation')");
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
