<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once($path);

$targetDir = "/media/uploads/Mine";
$yearDir = $targetDir."/".date("Y");
$monthDir = $yearDir."/".date("F");
$buildyear = false;
$buildmonth = false;
$width = 200;

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
	$mimeType = mime_content_type($_FILES["file"]["name"]);
	$fileType = explode('/', $mimeType)[0]; // video|image

	if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	}
}
?>
