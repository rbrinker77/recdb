<!doctype html>
<html>
    <head>
        <link href="../CSS/styles.css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/png" href="/favicon.ico">
    </head>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$parentDir = dirname( dirname(__FILE__) );
echo $parentDir;

?>
    <body >
        <div class="pageDiv">
            <div class="indexContainer">
                <button type="button" class="smallGreen"><a class="indexes" href="<?echo $parentDir;?>">Up a level</a></button>
            </div>
        </div>
    </body>
</html>