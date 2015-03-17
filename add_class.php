<!--This is the html page that displays a form for adding a class
    to the database -->
<!DOCTYPE html>
<?php

$page_title = 'ADD CLASS';
include ('index.html');
?>
 <html>
 <div id='section'>
  <head>
   <meta charset="utf-8">
   <title>Add New Class</title>
  </head>

 
  <body>
   <header>
    <h1>Add Class</h1>
   </header>

<section class="main"> 
  <form action = "" method = "POST">
  
   <p> <label for="className1">Class Name:</label>
      <input type="text" id="className1" name="className">
   </p>

   <p>
     <label for="classTitle1">Class Title:</label>
     <input type="text"  id="classTitle1" name="classTitle">
   </p>
   
   <p>
     <label for="creditHours1">Credit Hours:</label>
     <input type="number" min="0" id="creditHours1" name="creditHours">
   </p>
   
  <p>
    <input type="submit" value="Submit">
 </p>
  
  </form>
 </section>
</body>
</div>

</html>