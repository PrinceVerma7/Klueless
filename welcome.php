<?php
   include('session.php');
?>
<html">
   
   <head>
      <title>Welcome to LEVEL 1 or whatever</title>
   </head>
   
   <body>
      <h1>Welcome <?php echo $login_session; ?></h1> 
      <button><h2><a href = "logout.php">Sign Out</a></h2></button>
   </body>
   
</html>