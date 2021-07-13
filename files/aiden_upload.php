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
$dir_path = "../files/uploads";

if (is_dir($dir_path)) {
    if ($dir_handler = opendir($dir_path)) {
        while (($file = readdir($dir_handler)) !== false) {
            echo "filename: $file : filetype: " . filetype($dir_path . $file) . "\n";
        }
        closedir($dir_handler);
    }
}

#$myfile = fopen($targetDir."newfile.txt", "w") or die("Unable to open file!");
#$txt = "John Doe\n";
#fwrite($myfile, $txt);
#$txt = "Jane Doe\n";
#fwrite($myfile, $txt);
#fclose($myfile);
?>