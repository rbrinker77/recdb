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

	if ($meat == 'n') {
		$flag2 = $cat2." IN (cat1,cat2,cat3,cat4,cat5) ";
	} else {
		$flag2 = "1=1";
	}

	$randQuery = "SELECT r.recNumber "
		."FROM theboxli_Recipes AS r "
		."WHERE ".$flag1." "
		."AND ".$flag2." "
		."ORDER BY RAND() "
		."LIMIT 1;";

	include("../DB/dbconnect.php");

	foreach($dbConnection->query($randQuery) as $row) {
		$recNum = $row['recNumber'];
	}
	return $recNum;
}

if (@$_GET['rdm'] == 'y') {
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
		  <div>
				<input class='cpbutton' name='cpLink' type='button' onclick=\"copyLink('twolinks')\" value='Copy Links' />
				<textarea rows='2' type='text' class='cplink' readonly='readonly' id='twolinks'>http://recsite.ooguy.com/recipes/view.php?recNum=".$recNum."&#13;http://192.168.87.106/recipes/view.php?recNum=".$recNum."</textarea>
			</div>
			<p></p>
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
			$ingredient = html_entity_decode($row[$ingredNum], ENT_QUOTES);
			if(strpos($ingredient, "##") !== FALSE) {
				$ingredientparts = (explode("##",$ingredient));

				$ingredient = $ingredientparts[0];
				$ingredient .= "<a class='reclink' href='./view.php?recNum=".$ingredientparts[1]."' target='_blank'>";
				$ingredient .= $ingredientparts[2]."</a>";
				$ingredient .= $ingredientparts[3];
			}
			if (strpos(strtolower($ingredient), "sugar") !== FALSE || strpos(strtolower($ingredient), "potato") !== FALSE || strpos(strtolower($ingredient), "rice") !== FALSE || strpos(strtolower($ingredient), "tortilla") !== FALSE) {
				echo "<li class=\"ingredLineWarning\">".$ingredient."</li>";
			}
			else {
				echo "<li class=\"ingredLine\">".$ingredient."</li>";
			}
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
			$instruction = html_entity_decode($row[$instructNum], ENT_QUOTES);
			if(strpos($instruction, "##") !== FALSE) {
				$instructionparts = (explode("##",$instruction));

				$instruction = $instructionparts[0];
				$instruction .= "<a class='reclink' href='./view.php?recNum=".$instructionparts[1]."' target='_blank'>";
				$instruction .= $instructionparts[2]."</a>";
				$instruction .= $instructionparts[3];
			}
			echo "<li class=\"instructLine\">".$instruction."</li>";
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
