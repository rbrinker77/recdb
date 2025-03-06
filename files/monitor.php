<?php
// Define the servers and ports to check
$servers = [
    ['host' => 'recsite.ooguy.com', 'port' => 80, 'name' => 'HTTP'],      		// HTTP
    ['host' => 'recsite.ooguy.com', 'port' => 8989, 'name' => 'Sonarr'],  		// Sonarr
    ['host' => 'recsite.ooguy.com', 'port' => 8686, 'name' => 'Lidarr'], 		// Lidarr
    ['host' => 'recsite.ooguy.com', 'port' => 7878, 'name' => 'Radarr'], 		// Radarr
    ['host' => 'recsite.ooguy.com', 'port' => 9091, 'name' => 'Transmission'],  // Transmission
    ['host' => 'recsite.ooguy.com', 'port' => 8787, 'name' => 'Readarr'], 		// Readarr
    ['host' => 'recsite.ooguy.com', 'port' => 9696, 'name' => 'Prowlarr'], 		// Prowlarr
    ['host' => 'recsite.ooguy.com', 'port' => 8000, 'name' => 'OpenEats'],   	// OpenEats
    ['host' => 'recsite.ooguy.com', 'port' => 90, 'name' => 'Lychee'],     		// Lychee
    ['host' => 'recsite.ooguy.com', 'port' => 62341, 'name' => 'Plex'],  		// Plex
];

// Function to check port status
function checkPort($host, $port, $timeout = 5) {
    $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($connection) {
        fclose($connection);
        return true;
    } else {
        return false;
    }
}

// Monitor the ports and generate output
header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Port Checker</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>
    <h1>Port Check Results</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Port</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';
echo "<h2>Port Monitoring Status</h2>";
echo "<table border='1' cellpadding='10'><tr><th>Host</th><th>Port</th><th>Status</th></tr>";

foreach ($servers as $server) {
    $status = checkPort($server['host'], $server['port']) ? 'UP' : 'DOWN';
    $color = ($status === 'UP') ? 'green' : 'red';
    echo "<tr><td>{$server['host']}</td><td>{$server['name']}</td><td>{$server['port']}</td><td style='color: {$color}; font-weight: bold;'>$status</td></tr>";
}

echo "</table>";
?>
