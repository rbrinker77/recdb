<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html>
    <head>
        <link href="/CSS/styles.css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/png" href="/favicon.ico">
    </head>
    <body >
        <div class="pageDiv">
            <div class="indexContainer">
                <button type="button" class="smallGreen"><a class="indexes" href="../">Up a level</a></button>
            </div>
        </div>
<?
$thisDir = dirname(__FILE__);
foreach (new DirectoryIterator($thisDir) as $fileInfo) {
    $name = $fileInfo->getFilename();
    $path = pathinfo($name);
    $extension = $path['extension'];
    if( $fileInfo->isDot() || $extension == "php" || $extension == "Trash-0" || $extension == "htaccess" ) continue;
    echo $name . "<br>\n";
    var_dump($extension);
}
?>
    </body>
</html>