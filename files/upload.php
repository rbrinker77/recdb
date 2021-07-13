<?php
#error_reporting(E_ALL);
#ini_set('display_errors', 1);

if ($_GET['type'] != "Aiden" && $_GET['type'] != "Mine") {
	die("Go away");
}
else {
	$targetDir = $_SERVER['DOCUMENT_ROOT']."/files/uploads/".$_GET['type'];
}

$targetDir = $_SERVER['DOCUMENT_ROOT']."/files/uploads/";
$yearDir = $targetDir."/".date("Y");
$monthDir = $yearDir."/".date("n");

if ($_POST['subdir'] != "") {
	$subName = trim($_POST['subname']);
	$subDir = $monthDir."/".$subName; 
}
else {
	$subName = "none";
}

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

	if ($subName != "none") {
		if (!file_exists($subDir)) {
			mkdir($subDir);
		}
		$fileDir = $subDir;
	}
	else {
		$fileDir = $monthDir;
	}

    $targetFile = $fileDir . "/" . basename($_FILES["file"]["name"]);

    if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	}
}
?>