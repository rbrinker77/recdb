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

  foreach($dbConnection->query($typelist) as $row)
  {
    $idnum=$row['id'];
    $eventlist[$idnum] = "SELECT * FROM events WHERE type='".$idnum."' ORDER BY date DESC;";

    $result = $dbConnection->query($eventlist[$idnum]);
    $data =  $result->fetch_assoc();
    echo $data['total'];
    if ($data['total'] = 0) {
      echo "NONE";
    }

    echo "<div><table><tr><td>".$row['name']."</td></tr>";
    echo "<table><tr><td>Date</td><td>Description</td></tr>";

    foreach ($dbConnection->query($eventlist[$idnum]) as $eventrow)
    {
      echo "<tr><td>".$eventrow['date']."</td><td>".$eventrow['description']."</td></tr>";
    }

    echo "</table></div>";
  }

  echo "</div>";

  $dbConnection = null;
?>
  </body>
  </html>
