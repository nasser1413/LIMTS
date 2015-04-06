<?php
    // Import the "Grab Bag"
    require("common.php");

    // Open an (OO) MySQL Connection
    $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Get all of the "Parameters"
    $name = $_GET["name"];
    $type = $_GET["type"];
    $startDate = $_GET["startDate"];
    $endDate = $_GET["endDate"];

    // Check to make sure the required information is present
    if (!($name && $type && $startDate && $endDate)) {
      die("{\"response\": \"You must specify the all the information!\"}");
    }

    // Check to see if the Building already exists in the database
    $result = $conn->query("SELECT *
                            FROM `Semester`
                            WHERE `Name`='$name'");
    if ($result->num_rows > 0) {
      die("{\"response\": \"Building already exists in database\"}");
    }
    $result->close();

    // Everything seems ok at this point, so just add the Semester
    //In Reality we'd also validate the dates to make sure EndDate > StartDate
    $result = $conn->query("INSERT INTO `Semester` (Name, Type, StartDate, EndDate)
                            VALUES('$name', '$type' , '$startDate' , '$endDate')");
    if (!$result) {
      die("{\"response\": \"Could not insert Semester!\"}");
    }

    // Give a success response
    echo "{\"response\": \"Success\"}";

    // Finally, close the connection
    $conn->close();
?>
