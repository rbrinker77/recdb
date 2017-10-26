<?php

date_default_timezone_set('America/Chicago');

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$recNum = $_GET['recNum'];

include("./DB/dbconnect.php");

$recQuery = "SELECT * FROM theboxli_Recipes WHERE recNumber = ".$recNum.";";

foreach($dbConnection->query($recQuery) as $row)
{
	echo "
		<html>
		<head>
			<meta name=\"viewport\" content=\"width=device-width\">
			<link rel=\"stylesheet\" type=\"text/css\" href=\"./CSS/styles.css\">
			<title>".$row['recName']."</title>
		</head>
		<body>
		<div class=\"pageDiv\">
			<div class=\"headerDiv big\">"
				.$row['recName']."
			</div>
			<div class=\"dateDiv\">";

		if ((int)$row['dateAdded'] > (int)$row['dateModified'])
		{
			$when = date("m-d-Y",strtotime($row['dateAdded']));
			echo "Added on: ".$when;
		}
		else
		{
			$when = date("m-d-Y @ h:m",strtotime($row['dateModified']));
			echo "Last Modified: ".$when;
		}

		echo "</div>
			<div class=\"buttons\">
				<input class=\"smallGreen\" type=\"button\" value=\"Home\" onclick=\"window.location.href='./index.php'\">
				<input class=\"smallBlue\" name=\"isMod\" type=\"button\" onclick=\"window.location.href='./modrec.php?recNum=".$recNum."'\" value=\"Modify\" \">
				<input class=\"smallRed\" type=\"button\" value=\"New Search\" onclick=\"window.location.href='./search.php'\">
			</div>
			<div class=\"category\">
				INGREDIENTS
			</div>
			<ul>";

	for ($i=1; $i < 21; $i++)
	{
		$ingredNum = "ingred".$i;

		if ($row[$ingredNum] <> "")
		{
			echo "<li class=\"ingredLine\">".$row[$ingredNum]."</li>";
		}
	}

	echo	"</ul><div class=\"category\">
				INSTRUCTIONS
			</div><ol>";

	for ($i=1; $i < 11; $i++)
	{
		$instructNum = "instruct".$i;

		if ($row[$instructNum] <> "")
		{
			echo "<li class=\"instructLine\"> ".$row[$instructNum]."</li>";
		}
	}
	echo "</ol>";
}

$dbConnection = null;

echo "
	</body>
	</html>";
?>

<script type="text/javascript" src="./JS/functions.js"></script>
