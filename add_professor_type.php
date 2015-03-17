<!--This is the html page that displays a form for adding a professor type
    to the database -->

<!DOCTYPE html>
<?php

$page_title = 'ADD PROFESSOR TYPE';
include ('index.html');
?>
 <html>
 <div id='section'>
  <head>
   <meta charset="utf-8">
   <title>Add New Professor Category</title>
  </head>

 
  <body>
  <header>
    <h1>Add New Professor Type</h1>
  </header>
  
  <section class="main"> 
   <form action = "" method = "POST">
    
    <p>
      <label for="professorType1">Name:</label>
      <input type="text" id="professorType1" name="professorType" placeholder="e.g. Adjunct Professor">
     </p>
      
    <p>
     <label for="defaultCreditHours1">Default Credit Hours:</label>
     <input type="number" id="defaultCreditHours1" name="defaultCreditHours" min="0">
    </p>
   
     <p><input type="submit" value="submit"></p>

    </form>
   </section>
  </body>

</div>
</html>