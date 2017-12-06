<?php

include("./DB/dbconnect.php");

$vbroster = "SELECT * FROM vb ORDER BY jersey ASC;";

echo "<div class=\"roster\">
  <h2>Volleyball Feeds</h2>";

foreach($dbConnection->query($vbroster) as $row)
{
	echo "<form action='./vbfeed.php' type='post'>
    <a href=''>".$row['name']."</a>
    </form>";
}

echo "</div>";

$dbConnection = null;

?>
