<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include("./vbfeed.php");
}
else {
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Volleyball Feeds</title>
    <link rel="stylesheet" href="../CSS/vb.css" type="text/css">
  <body>
<?php

  echo "<div><a class='vblink' href='./vbnews.php'><h3>UW Volleyball news</h3></a></div>";

  include("../DB/dbconnect.php");

      $vbroster = "SELECT * FROM vb WHERE name NOT LIKE 'Badger%' AND active=1 ORDER BY name ASC;";
      $vbother = "SELECT * FROM vb WHERE name LIKE 'Badger%' AND active=1 ORDER BY name ASC;";

  echo "<div class='thumbs'>";
//Roster loop
  foreach($dbConnection->query($vbroster) as $row)
  {
    if ($row['jersey'] > 0)
    {
      $titleline = $row['name'].", ".$row['position'];
    }
    else
    {
      $titleline = $row['name'];
    }
  	echo "<div class='vbdiv'>
      <form action='./index.php' method='post'>
      <input type='image' class='vbimage' src='../Images/".$row['name'].".jpg' title='".$titleline."' alt='".$row['name']." image'>
      <input type='hidden' name='name' value='".$row['name']."'>
      <input type='hidden' name='jersey' value='".$row['jersey']."'>
      <input type='hidden' name='position' value='".$row['position']."'>
      <input type='hidden' name='twitter' value='".$row['twitter']."'>
      <input type='hidden' name='instagram' value='".$row['instagram']."'>
      </form>
    </div>";
  }
//Non-roster links
  foreach($dbConnection->query($vbother) as $row)
  {
    $titleline = $row['name'];
  	echo "<div class='vbdiv'>
      <form action='./index.php' method='post'>
      <input type='image' class='vbimage' src='../Images/".$row['name'].".jpg' title='".$titleline."' alt='".$row['name']." image'>
      <input type='hidden' name='name' value='".$row['name']."'>
      <input type='hidden' name='jersey' value='".$row['jersey']."'>
      <input type='hidden' name='position' value='".$row['position']."'>
      <input type='hidden' name='twitter' value='".$row['twitter']."'>
      <input type='hidden' name='instagram' value='".$row['instagram']."'>
      </form>
    </div>";
  }

  echo "</div>";

  $dbConnection = null;

?>
  </body>
  </html>
<?php
}
?>
