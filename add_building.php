<!-- This is the html page that displays a form for adding a building to the database -->
<?php

$page_title = 'ADD BUILDING';
include ('index.html');
?>
<!DOCTYPE html>
 <html>
 <div id='section'>
  <head>
   <meta charset="utf-8">
   <title>Add New Building</title>
  </head>

 
  <body>

  <header>
   <h1>Add Building</h1>
  </header>

  <section class="main"> 
   <form action = "" method = "POST">
    
    <p>
     <label for="buildingName1">Building Name:</label>
     <input type="text" id="buildingName1" name="buildingName">
    </p> 
    
    <p>
     <label for="abbreviation1">Building Abbreviation:</label>
     <input type="text" id="abbreviation1" name="abbreviation" placeholder="example GEM for Gellersen">
    </p>
  
    <p>
     <label for="room1">Room:</label>
     <input type="text" id="room1" name="room">
    </p>
   
    <p>
     <label for="roomNumber1">Room Number:</label>
     <input type="number" id="roomNumber1" name="roomNumber">
    </p>
 
    <p>
     <label for="roomCapacity1">Room Capacity:</label>
     <input type="number" id="roomCapacity1" name="roomCapacity">
    </p>

     <p>Handicap Accessible:<br>
      <label for="handicapAccessibleTrue">Yes</label>
      <input type="radio" id="handicapAccessibleTrue" name="handicapAccessible" value="Yes"><br>   <!--Exception to naming convention because of radio button-->
      <label for="handicapAccessibleFalse">No</label> 
      <input type="radio" id="handicapAccessibleFalse" name="handicapAccessible" value="No">  <!-- Exception to naming convention because of radio button-->
     </p>
     
    <p><input type="submit" value="Submit"></p> 
   </form>
  </section>
  </body>

</div>
</html>