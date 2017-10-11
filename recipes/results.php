<?php

function get_paging_info($tot_rows,$pp)
{
    $pages = ceil($tot_rows / $pp); // calc pages

    $data = array(); // start out array
    $data['si']        = ($curr_page * $pp) - $pp; // what row to start at
    $data['pages']     = $pages;                   // add the pages
    $data['curr_page'] = $_GET['p'];               // Whats the current page

    return $data; //return the paging data
}

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Chicago');
session_start();

$pp = 30;

echo "
	<html>
	<head>
		<meta name=\"viewport\" content=\"width=device-width\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./CSS/styles.css\">
		<title>Recipe Portal - Search Results</title>
	</head>
	<body>
		<div class=\"headerDiv\">
			SEARCH RESULTS
		</div>";

	if (isset($_POST['viewByName']) || isset($_POST['viewByDate']))
	{
		unset($_SESSION["compiledQuery"]);

		$recName = $_POST['recName'];
		$ingred = array_values(array_filter($_POST['ingred']));
		$ingred1 = @$ingred[0];
		$ingred2 = @$ingred[1];
		$ingred3 = @$ingred[2];
		$ingred4 = @$ingred[3];
		$minusIngred = array_values(array_filter($_POST['minusIngred']));
		$minusIngred1 = @$minusIngred[0];
		$minusIngred2 = @$minusIngred[1];
		$minusIngred3 = @$minusIngred[2];
		$minusIngred4 = @$minusIngred[3];
		$cat1 = @$_POST['cat'][0];
		$cat2 = @$_POST['cat'][1];
		$cat3 = @$_POST['cat'][2];
		$cat4 = @$_POST['cat'][3];
		$cat5 = @$_POST['cat'][4];

		if ($minusIngred1 == "" OR $minusIngred1 == " " OR is_null($minusIngred1))
		{
			$minusIngred1 = "xxxxxxxxxxx";
		}

		if ($minusIngred2 == "" OR $minusIngred2 == " " OR is_null($minusIngred2))
		{
			$minusIngred2 = "xxxxxxxxxxx";
		}

		if ($minusIngred3 == "" OR $minusIngred3 == " " OR is_null($minusIngred3))
		{
			$minusIngred3 = "xxxxxxxxxxx";
		}

		if ($minusIngred4 == "" OR $minusIngred4 == " " OR is_null($minusIngred4))
		{
			$minusIngred4 = "xxxxxxxxxxx";
		}

		$searchLoop = "SELECT recNumber,recName";

		$searchLoop .= ",IF(dateModified IS NULL, dateAdded, dateModified) as dateChanged";

		$searchLoop .= "\n FROM theboxli_Recipes \n"
			."WHERE \n"
				."LOWER(recName) LIKE LOWER('%".$recName."%') ";

//loop for wanted ingredients
		foreach ($ingred as $wantingred) {
			if ($wantingred <> "xxxxxxxxxxx") {
				$searchLoop .= " AND CONCAT_WS(',',LOWER(ingred1),LOWER(ingred2),LOWER(ingred3),LOWER(ingred4),LOWER(ingred5),LOWER(ingred6),LOWER(ingred7),LOWER(ingred8),LOWER(ingred9),LOWER(ingred10),LOWER(ingred11),LOWER(ingred12),LOWER(ingred13),LOWER(ingred14),LOWER(ingred5),LOWER(ingred16),LOWER(ingred17),LOWER(ingred18),LOWER(ingred19),LOWER(ingred20)) LIKE LOWER('%".$wantingred."%') ";
			}
		}

//loop for unwanted ingredients
		foreach ($minusIngred as $notingred) {
			if ($notingred <> "xxxxxxxxxxx") {
				$searchLoop .= " AND CONCAT_WS(',',LOWER(ingred1),LOWER(ingred2),LOWER(ingred3),LOWER(ingred4),LOWER(ingred5),LOWER(ingred6),LOWER(ingred7),LOWER(ingred8),LOWER(ingred9),LOWER(ingred10),LOWER(ingred11),LOWER(ingred12),LOWER(ingred13),LOWER(ingred14),LOWER(ingred5),LOWER(ingred16),LOWER(ingred17),LOWER(ingred18),LOWER(ingred19),LOWER(ingred20)) NOT LIKE LOWER('%".$notingred."%') ";
			}
		}

//add logic for categories
		if ($cat1 > 0) {
			$searchLoop .= " AND ".$cat1." IN (cat1,cat2,cat3,cat4,cat5) ";
		}

		if ($cat2 > 0) {
			$searchLoop .= " AND ".$cat2." IN (cat1,cat2,cat3,cat4,cat5) ";
		}

		if ($cat3 > 0) {
			$searchLoop .= " AND ".$cat3." IN (cat1,cat2,cat3,cat4,cat5) ";
		}

		if ($cat4 > 0) {
			$searchLoop .= " AND ".$cat4." IN (cat1,cat2,cat3,cat4,cat5) ";
		}

		if ($cat5 > 0) {
			$searchLoop .= " AND ".$cat5." IN (cat1,cat2,cat3,cat4,cat5) ";
		}

		if (!isset($_POST['viewByName']))
		{
			$searchLoop .= " ORDER BY dateChanged DESC, recName";
			$_SESSION["searchView"] = 'byDate';
		}
		else
		{
			$searchLoop .= " ORDER BY recName";
			$_SESSION["searchView"] = 'byName';
		}

		$_SESSION["compiledQuery"] = $searchLoop;
	}
	else
	{
		$searchLoop = $_SESSION["compiledQuery"];
	}

//build results page after query is ready
echo	"<div class=\"buttons\">
			<input class=\"smallRed twoButtons\" type=\"button\" value=\"New Search\"  onclick=\"window.location.href='./search.php'\">
			<input class=\"smallGreen twoButtons\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		</div>
		<div class=\"pageButtons\" >";

include("./DB/dbconnect.php");
include("./paging.php");

echo 	"</div>
		<div class=\"pageDiv pagePad\">
			<div class=\"recipeLine\"> </div>";

		$count = ($paging_info['si'];

		$finalSearch = $searchLoop." LIMIT ".$count.",".$pp.";";

		foreach($dbConnection->query($finalSearch) as $row)
		{
			echo "<div class=\"recipeLine\">
				<div class='viewLink'>
					<a href=\"./view.php?recNum=".$row['recNumber']."\" target=\"_blank\">"
						.$row['recName']."
					</a>
				</div>
			</div>";

			echo "<div class=\"recipeLine\">
				<span>";

			$dateChanged = date('m-d-y',strtotime((string)$row['dateChanged']));
			echo "<div class=\"dates\">".$dateChanged."</div>";

			echo "<div class='modLink'>
						<form name=\"modLink".$i."\" method=\"post\" action=\"./modrec.php?recNum=".$row['recNumber']."\" target=\"_blank\">
							<input class=\"modButton\" type='submit' value='MODIFY'>
						</form>
					</div>";

			echo "</span>
				</div>";
		}

		echo "<div class=\"push\"></div>
			</div>";

include("./paging.php");
$dbConnection = null;

echo 	"</div>
		<div class=\"buttons\">
			<input class=\"smallRed twoButtons\" type=\"button\" value=\"New Search\"  onclick=\"window.location.href='./search.php'\">
			<input class=\"smallGreen twoButtons\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		</div>
	</body>
	</html>";
?>

<script type="text/javascript" src="./JS/functions.js"></script>
