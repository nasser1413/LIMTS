<?php
    // Import the "Grab Bag"
    require_once("common.php");

    // Open an (OO) MySQL Connection
    $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get all of the "Parameters"
    $id = $_GET["id"];

    // Check to make sure the required information is present
    if (!($id)) {
        die("{\"response\": \"fail to get id!\"}");
    }


    // Check to see if the Building  not exists in the database
    $result = $conn->query("SELECT *
                            FROM `Professor`
                            WHERE `id`='$id'");
    $professor_exists = $result->num_rows > 0;
    // Close the Result
    $result->close();

    if ($professor_exists) {
        // ready to delete
        $result = $conn->query("DELETE FROM `Professor`
                                WHERE `id`='$id'");

        if (!$result) {
            die("{\"response\": \"Could not remove the Professor!\"}");
        }
    } else {
        die("{\"response\": \"Professor does not exists in database\"}");
    }

    // Give a success response
    echo "{\"response\": \"Success\"}";

    // Finally, close the connection
    $conn->close();
?>
