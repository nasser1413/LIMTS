<!--This is the html page that displays a form for adding a new kind
    of semester to the database -->

<!DOCTYPE html>
<?php

$page_title = 'ADD SEMESTER';
include ('index.html');
?>
 <html>
 <div id='section'> 
  <head>
   <meta charset="utf-8">
   <title>Add New Semester</title>
  </head>

 
  <body>

  <header>
    <h1>Add New Semester</h1>
  </header>

  <section class="main"> 
   <form action = "" method = "POST">
     
    <p>
      <label for="semesterName1">Semester Name:</label>
      <input type="text" id="semesterName1" name="semesterName">
    </p>

    <p> 
      <label for="semesterType1">Semester Type:</label>
      <input type="text" id="semesterType1" name="semesterType" placeholder="e.g. FirstSevenWeeks">
    </p> 
    
    <p>
      <label for="startDate1">Start Date:</label>
      <input type="date" id="startDate1" name="startDate">
    </p>
 
   <p>
     <label for="endDate1">End Date:</label>
     <input type="date" id="endDate1" name="endDate">
   </p>

    <p><input type="submit" value="Submit"></p>
   </form>
  </section>
  </body>

</div>
</html>