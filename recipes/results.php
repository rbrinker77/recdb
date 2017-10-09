<?php
session_start();
date_default_timezone_set('America/Chicago');

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$page = $_POST['page'];

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

		foreach ($ingred as $wantingred) {
			if ($wantingred <> "xxxxxxxxxxx") {
				$searchLoop .= " AND LOWER('".$wantingred."') IN (LOWER(ingred1),LOWER(ingred2),LOWER(ingred3),LOWER(ingred4),LOWER(ingred5),LOWER(ingred6),LOWER(ingred7),LOWER(ingred8),LOWER(ingred9),LOWER(ingred10),LOWER(ingred11),LOWER(ingred12),LOWER(ingred13),LOWER(ingred14),LOWER(ingred5),LOWER(ingred16),LOWER(ingred17),LOWER(ingred18),LOWER(ingred19),LOWER(ingred20)) ";
			}
		}
/*loop for wanted ingredients
		$searchIngredNum = 0;

		while ($searchIngredNum < 4)
		{
			$thisIngred = @$ingred[$searchIngredNum];

			$baseIngredNum = 1;

			while ($baseIngredNum < 21)
			{
				if ($thisIngred <> "" && $thisIngred <> " " && $thisIngred <> "xxxxxxxxxxx")
				{
					if ($baseIngredNum == 1)
					{
						$searchLoop .= " AND (";
					}

					$searchLoop .= "(LOWER(ingred".$baseIngredNum.") LIKE LOWER('%".$thisIngred."%')) ";

					if ($baseIngredNum <> 20)
					{
						$searchLoop .= " OR \n";
					}
					else
					{
						$searchLoop .= ") ";
					}
				}

				$baseIngredNum++;
			}

			$searchIngredNum++;
		}
*/
//loop for unwanted ingredients
		$searchMinusIngredNum = 0;

		while ($searchMinusIngredNum < 4)
		{
			$thisIngred = @$minusIngred[$searchMinusIngredNum];

			$baseIngredNum = 1;

			while ($baseIngredNum < 21)
			{
				if ($thisIngred <> "" && $thisIngred <> " " && $thisIngred <> "xxxxxxxxxxx")
				{
					if ($baseIngredNum == 1)
					{
						$searchLoop .= " AND (";
					}

					$searchLoop .= "(LOWER(ingred".$baseIngredNum.") NOT LIKE LOWER('%".$thisIngred."%')) ";

					if ($baseIngredNum <> 20)
					{
						$searchLoop .= " AND \n";
					}
					else
					{
						$searchLoop .= ") ";
					}
				}

				$baseIngredNum++;
			}

			$searchMinusIngredNum++;
		}

 		if ($cat1 > 0)
		{
			$searchLoop .= "AND \n"
				."((cat1 = '".$cat1."') or \n"
				."(cat2 = '".$cat1."') or \n"
				."(cat3 = '".$cat1."') or \n"
				."(cat4 = '".$cat1."') or \n"
				."(cat5 = '".$cat1."'))";
		}
		if ($cat2 > 0)
		{
			$searchLoop .= "AND \n"
				."((cat1 = '".$cat2."') or \n"
				."(cat2 = '".$cat2."') or \n"
				."(cat3 = '".$cat2."') or \n"
				."(cat4 = '".$cat2."') or \n"
				."(cat5 = '".$cat2."'))";
		}
		if ($cat3 > 0)
		{
			$searchLoop .= "AND \n"
				."((cat1 = '".$cat3."') or \n"
				."(cat2 = '".$cat3."') or \n"
				."(cat3 = '".$cat3."') or \n"
				."(cat4 = '".$cat3."') or \n"
				."(cat5 = '".$cat3."'))";
		}
		if ($cat4 > 0)
		{
			$searchLoop .= "AND \n"
				."((cat1 = '".$cat4."') or \n"
				."(cat2 = '".$cat4."') or \n"
				."(cat3 = '".$cat4."') or \n"
				."(cat4 = '".$cat4."') or \n"
				."(cat5 = '".$cat4."'))";
		}
		if ($cat5 > 0)
		{
			$searchLoop .= "AND \n"
				."((cat1 = '".$cat5."') or \n"
				."(cat2 = '".$cat5."') or \n"
				."(cat3 = '".$cat5."') or \n"
				."(cat4 = '".$cat5."') or \n"
				."(cat5 = '".$cat5."'))";
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

echo	"<div class=\"buttons\">
			<input class=\"smallRed twoButtons\" type=\"button\" value=\"New Search\"  onclick=\"window.location.href='./search.php'\">
			<input class=\"smallGreen twoButtons\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		</div>
		<div class=\"pageButtons\" >";

include("./DB/dbconnect.php");

$mysql_rows = $dbConnection->query($searchLoop)->rowCount();
$pages = ceil($mysql_rows / 100);
$i = 0;

while ($i < $pages)
{
	$i++;

	echo "<div class=\"pageButton\">
		<form name=\"topPage".$i."\" method=\"post\" action=\"./results.php\" >
			<input type=\"hidden\" name=\"page\" id=\"page\" value=\"".$i."\">";

	if (isset($_POST['searchRec']))
	{
		$searchRec = $_POST['searchRec'];
		echo "<input type=\"hidden\" name=\"searchRec\" id=\"searchRec\" value=\"".$searchRec."\">";
	}

	if ($i <> $page)
	{
		echo "<input class=\"greenButton\" type=\"submit\" name=\"newTopPage".$i."\" id=\"newTopPage".$i."\" value=\"".$i."\" >";
	}
	else
	{
		echo "<input class=\"blackButton\" type=\"submit\" name=\"newTopPage".$i."\" id=\"newTopPage".$i."\" value=\"".$i."\" >";
	}

	echo "<input type=\"hidden\" width=\"100%\">
		</form>
		</div>";
}

echo 	"</div>
		<div class=\"pageDiv pagePad\">
			<div class=\"recipeLine\"> </div>";

		$count = ($page * 100) - 100;

		$finalSearch = $searchLoop." LIMIT ".$count.",100;";

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

$dbConnection = null;

$i = 0;

echo "<div class=\"pageButtons\" >";

while ($i < $pages)
{
	$i++;

	echo "<div class=\"pageButton\" >
		<form name=\"bottomPage".$i."\" method=\"post\" action=\"./results.php\" >
			<input type=\"hidden\" name=\"page\" id=\"page\" value=\"".$i."\">";

	if (isset($_POST['searchRec']))
	{
		$searchRec = $_POST['searchRec'];
		echo "<input type=\"hidden\" name=\"searchRec\" id=\"searchRec\" value=\"".$searchRec."\">";
	}

	if ($i <> $page)
	{
		echo "<input class=\"greenButton\" type=\"submit\" name=\"newBottomPage".$i."\" id=\"newBottomPage".$i."\" value=\"".$i."\" >";
	}
	else
	{
		echo "<input class=\"blackButton\" type=\"submit\" name=\"newBottomPage".$i."\" id=\"newBottomPage".$i."\" value=\"".$i."\">";
	}

	echo "<input type=\"hidden\" width=\"100%\">
		</form>
		</div>";
}

echo 	"</div>
		<div class=\"buttons\">
			<input class=\"smallRed twoButtons\" type=\"button\" value=\"New Search\"  onclick=\"window.location.href='./search.php'\">
			<input class=\"smallGreen twoButtons\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		</div>
	</body>
	</html>";
?>

<script type="text/javascript" src="./JS/functions.js"></script>
