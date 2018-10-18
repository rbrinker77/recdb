<?php

date_default_timezone_set('America/Chicago');

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

$recNum = $_GET['recNum'];

function updateDB() {

//write records to database
		$recNum = $_GET['recNum'];
		$recName = $_POST['recName'];
		$servings = preg_replace("/[^0-9]/", "", $_POST['servings']);

		if ($servings == "") {
			$servings = "NULL";
		}

		$ingred[] = $_POST['ingred'];
		$instruct[] = $_POST['instruct'];
		$cat[] = @$_POST['cat'];
		$ingredLen = 0;
		$instructLen = 0;
		$i = 0;

		if ($recName == "")
		{
			echo "<html><body>You must enter a NAME. Please <a href=\"javascript:history.back()\">GO BACK</a> and correct this.</body></html>";
			die();
		}

		while ($i < 24)
		{
			$ingredLen = $ingredLen + strlen(trim($ingred[0][$i]));
			$i++;
		}

		if ($ingredLen == 0)
		{
			echo "<html><body>You must enter at least one INGREDIENT. Please <a href=\"javascript:history.back()\">GO BACK</a> and correct this.</body></html>";
			die();
		}

		$i = 0;

		while ($i < 15)
		{
			$instructLen = $instructLen + strlen(trim($instruct[0][$i]));
			$i++;
		}

		if ($instructLen == 0)
		{
			echo "<html><body>You must enter at least one INSTRUCTION. Please <a href=\"javascript:history.back()\">GO BACK</a> and correct this.</body></html>";
			die();
		}

		if (@is_null($cat[0]))
		{
			echo "<html><body>You must select at least one CATEGORY. Please <a href=\"javascript:history.back()\">GO BACK</a> and correct this.</body></html>";
			die();
		}
		else
		{
			$cat1 = $cat[0][0];
		}

		if (@is_null($cat[0][1]))
		{
			$cat2 = 0;
		}
		else
		{
			$cat2 = $cat[0][1];
		}

		if (@is_null($cat[0][2]))
		{
			$cat3 = 0;
		}
		else
		{
			$cat3 = $cat[0][2];
		}

		if (@is_null($cat[0][3]))
		{
			$cat4 = 0;
		}
		else
		{
			$cat4 = $cat[0][3];
		}

		if (@is_null($cat[0][4]))
		{
			$cat5 = 0;
		}
		else
		{
			$cat5 = $cat[0][4];
		}

		include("../DB/dbconnect.php");

		$post = "UPDATE theboxli_Recipes \n"
			."SET \n"
			."recName = '".trim(htmlentities($recName, ENT_QUOTES))."', "
			."servings = '".$servings."', ";

		$i = 0;
		$count = 1;

		while ($i < 24)
		{
			$post .= "ingred".$count." = '".trim(htmlentities($ingred[0][$i], ENT_QUOTES))."', ";

			$i++;
			$count++;
		}

		$i = 0;
		$count = 1;

		while ($i < 15)
		{
			$post .= "instruct".$count." = '".trim(htmlentities($instruct[0][$i], ENT_QUOTES))."', ";

			$i++;
			$count++;
		}

		$post .= "cat1 = '".$cat1."', \n"
			."cat2 = '".$cat2."', \n"
			."cat3 = '".$cat3."', \n"
			."cat4 = '".$cat4."', \n"
			."cat5 = '".$cat5."' \n"
			."WHERE recNumber = $recNum;";

		try
		{
    		$updateRec = $dbConnection->prepare($post);
			$updateRec->execute();
		}
		catch (PDOException $e)
		{
		    echo "<html><body>An error occurred while trying to add the record. Please <a href=\"javascript:history.back()\">GO BACK</a> and change the name.<br /><br />".$e->getMessage()."</body></html>";
			die();
		}

		$dbConnection = null;
	}

function deleteRec()
{
	$recNum = $_GET['recNum'];

	include("../DB/dbconnect.php");

	$deleteQuery="DELETE FROM theboxli_Recipes WHERE recNumber = ".$recNum;
	$deleteRec = $dbConnection->prepare($deleteQuery);
	$deleteRec->execute();

	$dbConnection = null;

	echo "<script type=\"text/javascript\">
			if (confirm('The recipe has been deleted!\\nReturn home?')) {
		    	window.location = './index.php';
			}
			else
			{
				close();
			}
		</script>";
}

if (!empty($_REQUEST['modifyRec']))
{
	updateDB();
}

if (!empty($_REQUEST['isDelete']))
{
	deleteRec();
}

include("../DB/dbconnect.php");

$recQuery = "SELECT * FROM theboxli_Recipes WHERE recNumber = ".$recNum.";";
$result = $dbConnection->prepare($recQuery);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

$dbConnection = null;

echo "
	<html>
	<head>
		<meta name=\"viewport\" content=\"width=device-width\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../CSS/styles.css\">
		<script type=\"text/javascript\" src=\"../JS/functions.js\"></script>
		<title>Modify ".html_entity_decode($row['recName'], ENT_QUOTES)."</title>
	</head>
	<body document.onkeypress = stopRKey;\">
	<div class=\"headerDiv\">
		MODIFY RECIPE
	</div>
	<div class=\"buttons\">
		<form class=\"floatForm\" name=\"deleteRecipe\" method=\"post\" action=\"./modrec.php?recNum=".$recNum."\" onsubmit='return confirmDelete();'>
			<input class=\"smallBlue twoButtons\" name=\"isView\" type=\"button\" onclick=\"window.location.href='./view.php?recNum=".$recNum."'\" value=\"View\" \">
			<input class=\"smallRed twoButtons\" name=\"isDelete\" type=\"submit\" value=\"DELETE?\" \">
		</form>
	</div>
	<div class=\"pageDiv\">
	<form name=\"recModify\" method=\"post\" action=\"./modrec.php?recNum=".$recNum."\" onsubmit='return validateRecipe(\"recModify\");'>
	<div class=\"nameDiv\">
		Name <input class=\"nameBox\" type=\"text\" name=\"recName\" id=\"recName\" maxlength=\"110\" value=\"".$row['recName']."\" /></td>
	</div>
	<div class=\"servingsDiv\">
		Servings <input class=\"servingsBox\" type=\"text\" name=\"servings\" id=\"servings\" maxlength=\"2\" value=\"".$row['servings']."\" /></td>
	</div>
	<div class=\"ingredBox\">
		<div class=\"headerDiv\">
			INGREDIENTS
		</div>";

$i = 1;

while ($i < 25)
{
	$ingredNum = "ingred".$i;

	echo "<div class=\"ingredDiv\"><input autocorrect=\"off\" autocapitalize=\"off\" type=\"text\" name=\"ingred[]\" id=\"ingred[]\" maxlength=\"110\" value=\"".$row[$ingredNum]."\" /></div>";

	$i++;
}

echo "</div>
	<div class=\"lowerContainer\">
		<div class=\"instructBox\">
			<div class=\"headerDiv\">
				INSTRUCTIONS
			</div>";

$i = 1;

while ($i < 16)
{
	$instructNum = "instruct".$i;

	echo "<div class=\"instructDiv\"><textarea autocorrect=\"off\" autocapitalize=\"off\" name=\"instruct[]\" id=\"instruct[]\" maxlength=\"800\" >".$row[$instructNum]."</textarea></div>";

	$i++;
}

echo "
		</div>
		<div class=\"categoryBox\">
			<div class=\"headerDiv\">
				CATEGORIES
			</div>";

		include("../DB/dbconnect.php");

		$catLoop = "SELECT * FROM theboxli_Categories ORDER BY catName;";

		foreach($dbConnection->query($catLoop) as $row2)
		{
			if ($row2['catNumber'] == $row['cat1'] || $row2['catNumber'] == $row['cat2'] || $row2['catNumber'] == $row['cat3'] || $row2['catNumber'] == $row['cat4'] || $row2['catNumber'] == $row['cat5'])
			{
				$checked = "checked";
				echo "<SCRIPT TYPE=\"text/javascript\">checkCount=checkCount+1</script>";
			}
			else
			{
				$checked = "";
			}
			echo "<div class=\"categoryDiv\">
				<label><input type=\"checkbox\" value=\"".$row2['catNumber']."\" name=\"cat[]\" id=\"cat[]\" onclick=\"setChecks(this)\" ".$checked."/> ".$row2['catName']."</label>
				</div>";
		}

		$dbConnection = null;

		echo "</div>
		</div>
			<div class=\"push\"></div>
		</div>
		<div class=\"footerDiv\">
			<div class=\"buttons\">
				<input class=\"smallPurple twoButtons\" name=\"modifyRec\" type=\"submit\" value=\"Update\" >
				<input class=\"smallGreen twoButtons\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
				</form>
			</div>
		</div>
		</form>
	</body>
	</html>";
?>
