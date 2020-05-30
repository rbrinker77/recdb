<?php
session_unset();

echo "<html>
	<head>
		<meta name=\"viewport\" content=\"width=device-width\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../CSS/styles.css\">
		<title>Recipe Portal - Search Recipes</title>
	</head>
	<body document.recSearch.reset(); \">
	<form name=\"recSearch\" method=\"post\" action=\"./results.php?p=1\" >
	<div class=\"headerDiv\">
		SEARCH RECIPES
	</div>
	<div class=\"buttons\">
		<input class=\"smallRed\" type=\"reset\" value=\"Reset\"  onclick=\"window.location.href='./search.php'\">
		<input class=\"smallGreen\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
		<input class=\"smallPurple\" name=\"viewByName\" type=\"submit\" value=\"By Name\" >
		<input class=\"smallPurple dateButton\" name=\"viewByNum\" type=\"submit\" value=\"By Rec Number\" >
	</div>
	<div class=\"pageDiv\">
<div>
	<div class=\"nameDiv\">
		Name <input autocorrect=\"off\" autocapitalize=\"off\" type=\"text\" name=\"recName\" id=\"recName\" maxlength=\"75\" />
		<input type=\"hidden\" name=\"page\" id=\"page\" value=\"1\" >
	</div>
</div>
<div>
	<div class=\"headerDiv\">
		INGREDIENTS THAT YOU WANT
	</div>";

$i = 0;

while ($i < 4)
{
	echo "<div class=\"ingredDiv\">
		<input autocorrect=\"off\" autocapitalize=\"off\" class=\"searchBox\" type=\"text\" name=\"ingred[]\" id=\"ingred[]\" maxlength=\"75\" />
		</div>";

	$i++;
}

echo "</div>
	<div>
	<div class=\"headerDiv\">
		INGREDIENTS THAT YOU DO NOT WANT
	</div>";

$i = 0;

while ($i < 4)
{
	echo "<div class=\"ingredDiv\">
		<input autocorrect=\"off\" autocapitalize=\"off\" class=\"searchBox\" type=\"text\" name=\"minusIngred[]\" id=\"minusIngred[]\" maxlength=\"75\" />
		</div>";

	$i++;
}

echo "</div>
	<div>
	<div class=\"headerDiv\">
		CATEGORIES
	</div>";

include("../DB/dbconnect.php");

$catLoop = "SELECT * FROM theboxli_Categories ORDER BY catName;";
$i = 1;

echo "<div class=\"catContainer\">";

$catCount = $dbConnection->query($catLoop)->rowCount();
$catRows = ceil($catCount / 4);
$c = 1;
$i = 1;

foreach($dbConnection->query($catLoop) as $row)
{
	echo "<div class=\"classSearchContainer\">
			<div class=\"catSearch\">
				<label><input type=\"checkbox\" value=\"".$row['catNumber']."\" name=\"cat[]\" id=\"cat[]\" onclick=\"setChecks(this)\" /> ".$row['catName']."</label>
			</div>
		</div>";
}

echo "</div>
	</div>";

$dbConnection = null;

echo "<div class=\"push\"></div>
	</div>
	<div class=\"footerDiv\">
		<div class=\"buttons\">
			<input class=\"smallRed\" type=\"reset\" value=\"Reset\"  onclick=\"window.location.href='./search.php'\">
			<input class=\"smallGreen\" type=\"button\" value=\"Home\"  onclick=\"window.location.href='./index.php'\">
			<input class=\"smallPurple\" name=\"viewByName\" type=\"submit\" value=\"By Name\" >
			<input class=\"smallPurple dateButton\" name=\"viewByNum\" type=\"submit\" value=\"By Rec Num\" >
		</div>
	</div>";

echo "
	</form>
	</body>
	</html>";
?>

<script type="text/javascript" src="../JS/functions.js"></script>
