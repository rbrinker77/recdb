<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$to      = 'rbrinker77@yahoo.com';
$subject = 'Cam Alert';
$message = 'Check Cam Video';
$headers = 'From: rbrinker77@gmail.com' . "\r\n" .
    'Reply-To: rbrinker77@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>
