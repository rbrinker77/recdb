<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<!doctype html>
<html>
    <head>
        <link href="../CSS/dropzone.css" rel="stylesheet" type="text/css">
        <script src="../JS/dropzone.js" type="text/javascript"></script>
    </head>
    <body >
        <div class="container" >
            <div class='content'>
            <form action="upload.php" class="dropzone" id="dropzonewidget">
                <button type="submit" name="filesup">Send Files</button>
            </form>  
            </div> 
        </div>
    </body>
</html>