<?php

date_default_timezone_set('America/Chicago');

//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

function savetoDB() {

//write records to database

		$recName = $_POST['recName'];
		$today = date('Ymd');

		$ingred[] = $_POST['ingred'];
		$instruct[] = $_POST['instruct'];
		$cat[] = @$_POST['cat'];
		$i = 0;

		if (@is_null($cat[0]))
		{
			$cat1 = 0;
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

		$badCharacters[] = array("\"", "'", "!", "@", "#", "$", "%", "^", "*", "+", "=", "[", "]", "{", "}", "\\", "|", ";", ":", "?", "<", ">", "`", "~", "     ", "    ", "   ", "  ");

		foreach($badCharacters as $badCharacter)
		{
			$recName = str_replace($badCharacter, " ", $recName);
		}

		for ($i=0; $i < 20; $i++)
		{
			foreach($badCharacters as $badCharacter)
			{
				$ingred[0][$i] = str_replace($badCharacter, " ", $ingred[0][$i]);
			}
		}

		for ($i=0; $i < 15; $i++)
		{
			foreach($badCharacters as $badCharacter)
			{
				$instruct[0][$i] = str_replace($badCharacter, " ", $instruct[0][$i]);
			}
		}

		include("./DB/dbconnect.php");

		$post = "INSERT INTO theboxli_Recipes \n"
			."(recNumber,recName,dateAdded,dateModified,\n"
			."ingred1,ingred2, ingred3,ingred4, \n"
			."ingred5,ingred6,ingred7,ingred8, \n"
			."ingred9, ingred10,ingred11,ingred12, \n"
			."ingred13,ingred14,ingred15, ingred16, \n"
			."ingred17,ingred18,ingred19,ingred20, \n"
			."instruct1,instruct2,instruct3,instruct4,instruct5, \n"
			."instruct6,instruct7,instruct8,instruct9,instruct10, \n"
			."instruct11,instruct12,instruct13,instruct14,instruct15, \n"
			."cat1,cat2,cat3,cat4,cat5) \n"
			."VALUES (DEFAULT,'".trim($recName)."','$today',DEFAULT, ";

		$i = 0;

		while ($i < 20)
		{
			$post .= "'".trim($ingred[0][$i])."',";

			$i++;
		}

		$i = 0;

		while ($i < 15)
		{
			$post .= "'".trim($instruct[0][$i])."',";

			$i++;
		}

		$post .= "'".$cat1."','".$cat2."','".$cat3."','".$cat4."','".$cat5."');";

		try
		{
    		$insertRec = $dbConnection->prepare($post);
			$insertRec->execute();
		}
		catch (PDOException $e)
		{
		    echo "<html><body>An error occurred while trying to add the record. Please <a href=\"javascript:history.back()\">GO BACK</a> and change the name.<br /><br />".$e->getMessage()."</body></html>";
			die();
		}

		echo "<script type=\"text/javascript\">
			if (confirm('Insert another recipe?')) {
		    	window.location = './newrec.php';
			}
			else
			{
				window.location = './index.php';
			}
		</script>";

		$dbConnection = null;
	}

if (!empty($_REQUEST['saveRec']))
{
	savetoDB();
}

echo "
	<html>
	<head>
		<meta name=\"viewport\" content=\"width=device-width\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./CSS/styles.css\">
		<script type=\"text/javascript\" src=\"./JS/functions.js\"></script>
		<title>Recipe Portal - Add Recipe</title>
	</head>
	<body setFocus(); document.recInsert.reset(); document.onkeypress = stopRKey; \">
	<div class=\"pageDiv\">
	<form name=\"recInsert\" id=\"insertRec\" method=\"post\" action=\"./newrec.php\" onsubmit='return validateRecipe(\"recInsert\");'>
	<div class=\"nameDiv\">
		Name <input autocorrect=\"off\" autocapitalize=\"off\" class=\"nameBox\" type=\"text\" name=\"recName\" id=\"recName\" maxlength=\"75\" onKeyPress=\"return limitchar(this, event)\" />
	</div>
	<div class=\"ingredBox\">
		<div class=\"headerDiv\">
			INGREDIENTS
		</div>";

$i = 1;

while ($i < 21)
{
	echo "<div class=\"ingredDiv\"><input autocorrect=\"off\" autocapitalize=\"off\" class=\"ingredBox\" type=\"text\" name=\"ingred[]\" id=\"ingred[]\" maxlength=\"75\" onKeyPress=\"return limitchar(this, event)\" /></div>";

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
	echo "<div class=\"instructDiv\"><textarea autocorrect=\"off\" autocapitalize=\"off\" class=\"instructBox\" name=\"instruct[]\" id=\"instruct[]\" maxlength=\"1000\" onKeyPress=\"return limitchar(this, event)\" ></textarea></div>";

	$i++;
}

echo "
		</div>
		<div class=\"categoryBox\">
			<div class=\"headerDiv\">
				CATEGORIES
			</div>";

		include("./DB/dbconnect.php");

		$catLoop = "SELECT * FROM theboxli_Categories ORDER BY catName;";

		foreach($dbConnection->query($catLoop) as $row)
		{
			echo "<div class=\"categoryDiv\">
				<label><input type=\"checkbox\" value=\"".$row['catNumber']."\" name=\"cat[]\" id=\"cat[]\" onclick=\"setChecks(this)\" /> ".$row['catName']."</label>
				</div>";
		}

		$dbConnection = null;

		echo "</div>
			</div>
			<div class=\"push\"></div>
		</div>
		<div class=\"footerDiv buttons\">
				<input class=\"smallRed\" type=\"reset\" value=\"Reset\"  onclick=\"window.location.href='./newrec.php\">
				<input class=\"smallPurple\" name=\"saveRec\" type=\"submit\" value=\"Submit\" >
				<input class=\"smallGreen\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		</div>
		</form>
	</body>
	</html>";
?>
