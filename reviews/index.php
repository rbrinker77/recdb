<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Review System</title>
    <link rel="stylesheet" href="../CSS/styles.css" type="text/css">
  <body>
<?php
  echo "<div><h3>Reviews</h3></div>";

  include("../DB/rvconnect.php");

  $i=0;
  $typelist = "SELECT * FROM type ORDER BY id ASC;";

  foreach($dbConnection->query($typelist) as $row)
  {
    $eventlist[$i] = "SELECT * FROM events WHERE id='".$i."' ORDER BY date DESC;";
    echo "<p>".$eventlist[$i]."</p>";
    $i++;
  }

  echo "<div>";
//Roster loop
  foreach($dbConnection->query($typelist) as $row)
  {
  	echo "<div>
      <form action='./index.php' method='post'>
        <h3>".$row['name']."</h3>
        <input type='text' name='desc' spellcheck='true' size='50'/>
        <input type='hidden' name='id' value='".$row['id']."' />
        <input type='submit' name='submit' value='Send' />
      </form>
    </div>";
  }

  echo "</div>";

  $dbConnection = null;
?>
  </body>
  </html>
