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

  $typelist = "SELECT * FROM type ORDER BY id ASC;";

  foreach($dbConnection->query($typelist) as $row)
  {
    $idnum=$row['id'];
    $eventlist[$idnum] = "SELECT * FROM events WHERE type='".$idnum."' ORDER BY date DESC;";

    echo "<table><tr><td>".$row['type']."</td></tr>";
    echo "<table><tr><td>Date</td><td>Description</td></tr>";

    foreach ($eventlist[$idnum] as $eventrow)
    {
      echo "<td>".$eventrow['date']."</td><td>".$eventrow['description']."</td>";
    }
  }

  echo "<div>";

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
