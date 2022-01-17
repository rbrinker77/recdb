<?php
	$dbuser = 'rvuser';
	$dbpass = 'e4M28FoWLl0vki2cNHHQ';

	$dsn = 'mysql:dbname=reviews;host=mysqldb';

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
