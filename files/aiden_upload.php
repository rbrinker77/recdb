<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targetDir = "uploads/Aiden/";

//PHP code to upload file to server directory
if (!empty($_FILES)) {
	$temporaryFile = $_FILES['file']['tmp_name']; 
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);

    if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	}
}
?>