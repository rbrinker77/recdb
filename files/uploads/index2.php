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
$arrayDirs = [];
$arrayFiles =[];

foreach (new DirectoryIterator($thisDir) as $fileInfo) {
    $name = $fileInfo->getFilename();
    $path = pathinfo($name);
    $extension = $path['extension'];
    if( $fileInfo->isDot() || $extension == "php" || $extension == "Trash-0" || $extension == "htaccess" ) continue;
    
    if(!$extension) {
        $arrayDirs[] = $name;
    } else {
        $modtime = filemtime($name);
        $arrayFiles[] = [ ['name', $name], ['modtime', $modtime] ];
    }
}

sort($arrayDirs);

foreach ($arrayDirs as $dir) {
    echo "<a class='indexes' href='./".$dir."'>".$dir."</a>";
}

foreach ($arrayFiles as $file) {
    echo "<a class='indexes' href='./".$file['name']."'><img src='".$file['name']."' title='Example Image Link' width='50' height='50' /></a>";
}
?>
    </body>
</html>