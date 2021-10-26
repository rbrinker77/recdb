<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '/functions.php';

$targetDir = $_SERVER['DOCUMENT_ROOT']."/files/uploads/Mine";
$yearDir = $targetDir."/".date("Y");
$monthDir = $yearDir."/".date("n");
$buildyear = false;
$buildmonth = false;
$filename = '/var/www/html/files/uploads/index.php';
$width = 200;

//PHP code to upload file to server directory
if (!empty($_FILES)) {
	$temporaryFile = $_FILES['file']['tmp_name']; 

	if (!file_exists($yearDir)) {
		mkdir($yearDir);
		mkdir($monthDir);
		$buildyear = true;
		$buildmonth = true;
	}
	else if (!file_exists($monthDir)) {
		mkdir($monthDir);
		$buildmonth = true;
	}

	if($buildyear) {
		$yearname = $yearDir."/index.php";
		if (!copy($filename, $yearname)) {
			echo "Failed to copy $yearname...\n";
		}
	}

	if($buildmonth) {
		$monthname = $monthDir."/index.php";
		if (!copy($filename, $monthname)) {
			echo "Failed to copy $monthname...\n";
		}
	}

    $targetFile = $monthDir . "/" . basename($_FILES["file"]["name"]);
    $thumbFile = $monthDir . "/.tmb_" . basename($_FILES["file"]["name"]);

    if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	} else {
		if(!make_thumb($targetFile,$thumbFile,$width))  {
			echo "Error occurred while creating thumbnail!";
		}
	}
}
?>