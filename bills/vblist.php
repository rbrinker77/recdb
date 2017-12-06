<?php

include("./DB/dbconnect.php");

$vbroster = "SELECT * FROM vb ORDER BY jersey ASC;";

echo "<div class=\"roster\">";

foreach($dbConnection->query($vbroster) as $row)
{
	echo "<form action='./vbfeed.php' type='post'>
    <p><a href=''>".$row['name']."</a></p>
    </form>";
}

$dbConnection = null;

?>
