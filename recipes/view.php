<?php
date_default_timezone_set('America/Chicago');

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

function randomRec($meat) {
	//Main dish
	$cat1	= 1;

	//Meatless
	$cat2 = 14;

	$flag1 = $cat1." IN (cat1,cat2,cat3,cat4,cat5) ";

	if ($meat == 'y') {
		$flag2 = $cat2." IN (cat1,cat2,cat3,cat4,cat5) ";
	} else {
		$flag2 = "1=1";
	}
	if ($flag1 > 0) {
		$searchLoop .= " AND ".$cat1." IN (cat1,cat2,cat3,cat4,cat5) ";
	}

	$randQuery = "SELECT r.recNumber "
		."FROM theboxli_Recipes AS r "
		."WHERE ".$flag1." "
		."AND ".$flag2." "
		."ORDER BY RAND() "
		."LIMIT 1;";
echo $randQuery;
	return $recNum;
}

if ($_GET['rdm'] == 'y') {
	$recNum = randomRec($_GET['meat']);
} else {
	$recNum = $_GET['recNum'];
}

include("../DB/dbconnect.php");

$recQuery = "SELECT * FROM theboxli_Recipes WHERE recNumber = ".$recNum.";";

foreach($dbConnection->query($recQuery) as $row)
{
	echo "
		<html>
		<head>
			<meta name=\"viewport\" content=\"width=device-width\">
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../CSS/styles.css\">
			<title>".html_entity_decode($row['recName'], ENT_QUOTES)."</title>
		</head>
		<body>
		<div class=\"pageDiv\">
			<div class=\"headerDiv big\">"
				.html_entity_decode($row['recName'], ENT_QUOTES)."
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
			<div>
			<ul>";

	for ($i=1; $i < 25; $i++)
	{
		$ingredNum = "ingred".$i;

		if ($row[$ingredNum] <> "")
		{
			echo "<li class=\"ingredLine\">".html_entity_decode($row[$ingredNum], ENT_QUOTES)."</li>";
		}
	}

	echo	"</ul></div><div class=\"category\">
				INSTRUCTIONS
			</div><div><ol>";

	for ($i=1; $i < 16; $i++)
	{
		$instructNum = "instruct".$i;

		if ($row[$instructNum] <> "")
		{
			echo "<li class=\"instructLine\">".html_entity_decode($row[$instructNum], ENT_QUOTES)."</li>";
		}
	}
	echo "</ol></div>";
}

$dbConnection = null;

echo "
	</body>
	</html>";
?>

<script type="text/javascript" src="../JS/functions.js"></script>
