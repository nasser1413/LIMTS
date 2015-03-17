<!--This is the html page that displays a form for adding a professor
    to the database -->
<!DOCTYPE html>
<?php

$page_title = 'ADD PROFESSOR';
include ('index.html');
?>

 <html>
<div id='section'>
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
      <option value="fullTimeProfessor">Full Time Professor</option>
      <option value="associateProfessor">Associate Professor</option>
      <option value="assistantProfessor">Assistant Professor</option>
      <option value="adjunctProfessor">Adjunct Professor</option>
      <option value="instructor">Instructor</option> 
      <option value="visitingProfessor">Visiting Professor </option>      
    </select>
   </p>
 
    <p><label for="maxCreditHours1">Max Credit Hours:</label> 
       <input type="number" id="maxCreditHours1" name="maxCreditHours" min="0">
     </p>
      
    <p><input type="submit" value="Submit"></p>
    </form>
   </section>
  </body>

</div>
</html>