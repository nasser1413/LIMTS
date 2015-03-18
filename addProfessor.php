<!--This is the html page that displays a form for adding a professor
    to the database -->
<!DOCTYPE html>
 <html>
  <head>
   <meta charset="utf-8">
   <title>Add New Professor</title>
  </head>

 
  <body>
   <header>
     <h1>Add Professor</h1>
   </header>

  <section class="main">
   <form action = "" method = "POST">

   <p> <label for="fullName1">Full Name:</label>
       <input type="text" id="fullName1" name="fullName">
    </p>


   <p>
    <label for="professorType1">Professor Type:</label>
     <select id="professorType1" name="professorType">
      
      <?php
	require("common.php");
	// Open a (OO) MySQL Connection
	$conn = mysqli_connect($GLOBALS["dbhost"], $GLOBALS["dbuser"], $GLOBALS["dbpass"], $GLOBALS["dbname"])
        or die('Error connecting to the MYSQL Server');
        mysqli_select_db($conn, "[databaseContainingRoomInfo]");
        $query1 = "SELECT [roomName] FROM [databaseContainingRoomInfo]"; //CHANGE
        $result = mysqli_query($conn, $query1);
        $defaultCreditHrs = 0; // max set to 0 initially 
        if (mysqli_num_rows($result) > 0) {
           while ($row = mysqli_fetch_assoc($result)){
           // <option value="fullTimeProfessor">Full Time Professor</option>
             echo '<option value="' . $row[SUPPLY PROFESSOR TYPE] . '">' . $row[SUPPLY PROFESSOR TYPE]
                   . $row[SUPPLY PROFESSOR TYPE] . '</option>';
             $defaultCreditHrs = $row[SUPPLY DEFAULT HRS];
           }
             echo '<p><label for="maxCreditHours1">Max Credit Hours:</label> 
                  <input type="number" id="maxCreditHours1" name="maxCreditHours"  min="0" val="'
                   . $defaultCreditHrs . '"></p>';
                   
        }
        mysqli_close($conn);
       ?>     
    </select>
   </p>
 
         
    <p><input type="submit" value="Submit"></p>
    </form>
   </section>
  </body>


</html>