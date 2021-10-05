<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
?>
<!doctype html>
<html>
    <head>
        <link href="/CSS/styles.css" rel="stylesheet" type="text/css">
        <link rel="icon" type="image/png" href="/favicon.ico">
    </head>
    <body >
        <div>
            <button type="button" class="smallGreen"><a class="indexes" href="../">Up a level</a></button>
        </div>
        <div class="pageDiv">
            <div class="leftDiv">
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
        $modtime = date("M/j/y @ h:i a", filemtime($name));
        $arrayFiles[] = [ 'name'=>$name, 'modtime'=>$modtime ];
    }
}

sort($arrayDirs);
$imagesize = 200;
$textsize = .75;

foreach ($arrayDirs as $dir) {
    echo "<div>
            <div>
                <a href='./".$dir."'><img width='".$imagesize."' height='".$imagesize."' src='' /></a>
            </div>
            <div>
                <p style='font-size:.75em;'>".$dir."</p>
            </div>
        </div>";
}

foreach ($arrayFiles as $file) {
    echo "<div>
            <div>
                <a href='./".$file['name']."'><img width='".$imagesize."' height='".$imagesize."' src='".$file['name']."' title='".$file['name']."' /></a>
            </div>
            <div>
                <p style='font-size:.75em;'>".$file['modtime']."</p>
            </div>
        </div>";
}
?>          </div>
        </div>
    </body>
</html>