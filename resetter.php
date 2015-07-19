<?php
  // Import the "Grab Bag"
  require("common.php");

  // Open an (OO) MySQL Connection
  $conn = new mysqli($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"]);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the values from the POST parameters
  $username       = $_GET['username'];
  $new_password   = $_GET['password'];
  $recovery       = $_GET['email'];
  $reset_hash     = $_GET['hash'];

  // Get the other user data
  $query  = "SELECT * FROM Users WHERE Username = '$username';";
  $result = $conn->query($query);

  if ($result->num_rows < 1) {
    die("User \"$username\" not found!");
  }

  $user_data  = $result->fetch_assoc();

  // And use it to generate a hash
  $firstname  = $user_data["FirstName"];
  $lastname   = $user_data["LastName"];
  $hash       = hash('ripemd160', "$firstname $lastname $password");

  // If we were given a valid hash and new password
  if ($reset_hash == $hash && $new_password) {
    // change the password
    die(change_user_password($conn, $username, $new_password));
  } else if ($reset_hash) {
    die("Cannot change password for user $username.");
  }

  // Otherwise, send a recovery email
  $text = "We have received your request to reset your password, please follow this link to proceed:
            http://baker.valpo.edu/scheduler/change-password?username=$username&hash=$hash";
  $text = str_replace("\n.", "\n..", $text);
  if (mail($recovery, "Recover Your LIMTS Password", $text)) {
    die("Successfully sent recovery message to $recovery.");
  } else {
    die("Uknown Failure.");
  }
?>
