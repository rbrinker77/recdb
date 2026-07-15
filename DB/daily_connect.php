<?php
$host = 'mysqldb';       // Docker container service name
$user = 'dailyuser';
$password = 'VTG1nmn8xye9waeKrca';
$dbname = 'daily_tracker';

try {
    // Establish connection to the MySQL container
    $conn = new mysqli($host, $user, $password, $dbname);
    
    // Set charset to match the UTF-8 handling in your index.php
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    // Production note: Log this error securely instead of echoing it to screen.
    die("Database connection failed: " . $e->getMessage());
}
?><?php
	$dbuser = 'dailyuser';
	$dbpass = 'VTG1nmn8xye9waeKrca';

	$dsn = 'mysql:dbname=daily_tracker;host=mysqldb';

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
