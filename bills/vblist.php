<!DOCTYPE html>
<html lang="en">
<head>
  <title>Volleyball Feeds</title>
<body>
  <?php

include("./DB/dbconnect.php");

$vbroster = "SELECT * FROM vb ORDER BY name ASC;";

echo "<h2>Volleyball Feeds</h2>";

foreach($dbConnection->query($vbroster) as $row)
{
	echo "<form action='./vbfeed.php' method='post'>
    <input type='submit' name='submit' value='".$row['name']."'>
    <input type='hidden' name='name' value='".$row['name']."'>
    <input type='hidden' name='jersey' value='".$row['jersey']."'>
    <input type='hidden' name='twitter' value='".$row['twitter']."'>
    <input type='hidden' name='instagram' value='".$row['instagram']."'>
    </form>";
}

$dbConnection = null;

?>
</body>
</html>
