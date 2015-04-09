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
    $title = $_GET["title"];
    $credithours=$_GET["credithours"];
    $contacthours=$_GET["contacthours"];

    // Check to make sure the required information is present
    if (!($name && $title && $credithours)) {
      die("{\"response\": \"You must specify the name, and title and credit hours!\"}");
    }

    // Check to see if the Building already exists in the database
    $result = $conn->query("SELECT *
                            FROM `Class`
                            WHERE `Name`='$name'");
    if ($result->num_rows > 0) {
      die("{\"response\": \"Building already exists in database\"}");
    }
    $result->close();

    $result = $conn->query("SELECT *
                            FROM `Class`
                            WHERE `title`='$title'");
    if ($result->num_rows > 0) {
      die("{\"response\": \"Building already exists in database\"}");
    }
    $result->close();

    // Everything seems ok at this point, so just add the Class
    //In Reality we'd also validate the dates to make sure EndDate > StartDate
    $result = $conn->query("INSERT INTO `Class` (Name, Title, CreditHours, ContactHours)
                            VALUES('$name', '$title' , '$credithours' , '$contacthours')");
    if (!$result) {
      die("{\"response\": \"Could not insert Class!\"}");
    }

    // Give a success response
    echo "{\"response\": \"Success\"}";

    // Finally, close the connection
    $conn->close();
?>