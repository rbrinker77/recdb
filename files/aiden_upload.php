<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targetDir = $_SERVER['DOCUMENT_ROOT']."/files/uploads/Aiden";
$yearDir = $targetDir."/".date("Y");
$monthDir = $yearDir."/".date("n");
$buildyear = false;
$buildmonth = false;
$filetxt = "<?php include '/var/www/html/files/uploads/index.php'; ?>";

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
		$yearname = $yearDir."/index.php"
		$yearfile = fopen($yearname, "w") or die("Unable to open year file!");
		fwrite($yearfile, $filetxt);
		fclose($yearfile);
	}

	if($buildmonth) {
		$monthname = $monthDir."/index.php"
		$monthfile = fopen($monthname, "w") or die("Unable to open month file!");
		fwrite($monthfile, $filetxt);
		fclose($monthfile);
	}

    $targetFile = $monthDir . "/" . basename($_FILES["file"]["name"]);

    if(!move_uploaded_file($temporaryFile,$targetFile))  {
		echo "Error occurred while uploading the file to server!";
	}
}
?>