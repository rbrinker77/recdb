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

  include("../DB/dbconnect.php");

  $vbroster = "SELECT * FROM vb ORDER BY name ASC;";

  foreach($dbConnection->query($vbroster) as $row)
  {
  	echo "<div class='thumbs'><form action='./index.php' method='post'>
      <input type='image' src='./Images/".$row['name'].".jpg' title='".$row['name']."' alt='".$row['name']." image'>
      <input type='hidden' name='name' value='".$row['name']."'>
      <input type='hidden' name='jersey' value='".$row['jersey']."'>
      <input type='hidden' name='twitter' value='".$row['twitter']."'>
      <input type='hidden' name='instagram' value='".$row['instagram']."'>
      </form>
      </div>";
  }

  $dbConnection = null;

  ?>
  </body>
  </html>
<?php
}
?>
