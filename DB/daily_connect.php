<?php
	$dbuser = 'dailyuser';
	$dbpass = 'VTG1nmn8xye9waeKrca';

	$dsn = 'mysql:dbname=dailychecks;host=mysqldb';

	try
	{
	    $dbConnection = new PDO($dsn, $dbuser, $dbpass);
	}
	catch (PDOException $e)
	{
	    echo 'Connection failed: ' . $e->getMessage();
	    die();
	}

	$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
