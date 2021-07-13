<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targetDir = $_SERVER['DOCUMENT_ROOT']."/files/uploads/Aiden";
$yearDir = $targetDir."/".date("Y");
$monthDir = $yearDir."/".date("n");

//PHP code to upload file to server directory
if (!empty($_FILES)) {
	$temporaryFile = $_FILES['file']['tmp_name']; 

	if (!file_exists($yearDir)) {
		mkdir($yearDir);
		mkdir($monthDir);
	}
	else if (!file_exists($monthDir)) {
		mkdir($monthDir);
	}

    $targetFile = $monthDir . "/" . basename($_FILES["file"]["name"]);

    if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	}
	else {
		chown($targetDir,"pbox");
	}
}
?>