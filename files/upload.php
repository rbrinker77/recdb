<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
print_r($_FILES);
$targetDir = "/home/pbox/recs/web/files/uploads/";
echo "<script type='text/JavaScript'>document.getElementById('errors').innerHTML = 'Top Level';</script>"
//PHP code to upload file to server directory
if (!empty($_FILES)) {
	$temporaryFile = $_FILES['file']['tmp_name']; 
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    echo "<script type='text/JavaScript'>document.getElementById('errors').innerHTML = 'Up files';</script>"
	if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
        echo "<script type='text/JavaScript'>document.getElementById('errors').innerHTML = 'File errors';</script>"
	}
}
?>