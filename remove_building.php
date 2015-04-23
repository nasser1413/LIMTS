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
    $id = $_GET["id"];

// Check to make sure the required information is present
    if (!($id)) {
      die("{\"response\": \"fail to get id!\"}");
    }


  //   Check to see if the Building  not exists in the database
    $result = $conn->query("SELECT *
                            FROM `Building`
                            WHERE `id`='$id'");
    if ($result->num_rows > 0) {
 		$result->close();

	// ready to delete 
		$result = $conn->query("DELETE FROM `Building`
                            WHERE `id`='$id'");

	  if (!$result) {
      die("{\"response\": \"Could not remove the Building!\"}");
    }
    
    }
	else{
 die("{\"response\": \"Building  does not exists in database\"}");
}
   	


  // Give a success response
    echo "{\"response\": \"Success\"}";

 // Finally, close the connection
    $conn->close();

?>
