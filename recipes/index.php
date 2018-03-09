<?php
//***************************
//***Coded by Ryan Brinker***
//***************************
?>

<?php
//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="../CSS/styles.css">
		<title>Recipe Portal</title>
	</head>
	<body>
		<div class="headerDiv giant">
			<p>Recipe Portal</p>
		</div>
		<div class="indexContainer">
			<input class="indexButton" type="button" name="searchRec" id="searchRec" value="Search Recipes" onclick="window.location='./search.php'"/>
		</div>
		<div class="indexContainer">
			<input class="indexButton" type="button" name="addRec" id="addRec" value="Add Recipe" onclick="window.location='./newrec.php'"/>
		</div>
		<div class="indexContainer">
			<input class="indexButton" type="button" name="randRecM" id="randRecM" value="Random Meat Dish" onclick="window.location='./view.php?rdm=y&meat=y'"/>
		</div>
		<div class="indexContainer">
			<input class="indexButton" type="button" name="randRecV" id="randRecM" value="Random Veggie Dish" onclick="window.location='./view.php?rdm=y&meat=n'"/>
		</div>
	</body>
</html>
