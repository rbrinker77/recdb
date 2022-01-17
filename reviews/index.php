<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../DB/rvconnect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty(trim($_POST['desc']))) {
    $addevent = "INSERT INTO events (type, description) VALUES (".$_POST['id'].", '".$_POST['desc']."');";
  }
  else {
    $addevent = "INSERT INTO events (type, description) VALUES (".$_POST['id'].", NULL);";
  }
  $newevent = $dbConnection->prepare ($addevent);
  $newevent->execute();
}
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Review System</title>
    <link rel="stylesheet" href="../CSS/styles.css" type="text/css">
    <script src="/JS/functions.js" type="text/javascript"></script>
  <body>
<?php
  echo "<div><h3>Reviews</h3></div>";

  $badcount=0;
  $goodcount=0;
  $typelist = "SELECT * FROM type ORDER BY id ASC;";

  echo "<div>";

  foreach($dbConnection->query($typelist) as $row)
  {
  	echo "<div class='entryform'>
      <form action='./index.php' method='post'>
        <h3>".$row['name']."<input type='button' value='Show' class='show".$row['name']."' id='show".$row['name']."' onclick=\"showtable('".$row['name']."')\" /><input type='button' value='Hide' style='visibility: hidden;' class='hide".$row['name']."' id='hide".$row['name']."' onclick=\"hidetable('".$row['name']."')\" /></h3>
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
    $countevents[$idnum] = "SELECT count(*) as num_rows FROM events WHERE type='".$idnum."';";

    $result = $dbConnection->query($countevents[$idnum]);
    $num_rows = $result->fetchColumn();

    if ($num_rows > 0) {
      if ($row['name'] == 'Positive') {
        $goodcount=$goodcount+$num_rows;
      }
      else {
        $badcount=$badcount+$num_rows;
      }
      echo "<div class='".$row['name']."Table' id='".$row['name']."Table' style='visibility: hidden;'><table><tr><td>".$row['name']."</td></tr>";
      echo "<table><tr><td>Date</td><td>Description</td></tr>";

      foreach ($dbConnection->query($eventlist[$idnum]) as $eventrow)
      {
        echo "<tr><td>".$eventrow['date']."</td><td>".$eventrow['description']."</td></tr>";
      }

      echo "</table></div>";
    }
  }

  $total = abs($goodcount-$badcount);
  if ($goodcount >= $badcount) {
    $style = 'green';
    $favor='POSTIVE';
  } else {
    $style = 'red';
    $favor='NEGATIVE';
  }

  echo "</div>";
  echo "<div class='banner'>
      <b style='color:".$style.";'>".$favor." - ".$total."</b>
    </div>";

  $dbConnection = null;
?>
  </body>
  </html>
