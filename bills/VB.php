<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Display Twitter Feed On Website - LEVEL 2 - Live Demo</title>

        <!-- Bootstrap CSS -->
    <link href="libs/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" media="screen">
</head>
<body>
  <!-- //Riley Bell
  <a class="twitter-timeline" href="https://twitter.com/RileyBell22?ref_src=twsrc%5Etfw">Tweets by RileyBell22</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
 ––>
 <?php
  $username="rileybell2";
  $instaResult = file_get_contents("https://www.instagram.com/".$username."/?__a=1");
  $insta = json_decode($instaResult);
  var_dump($insta);
  echo "<p class='insta-timeline'><img src='.$insta['profile_pic_url_hd'].' alt='.$username.' HD Profile Pic' /></p>";
 ?>
<!-- jQuery library -->
<script src="libs/js/jquery.js"></script>

<!-- bootstrap JavaScript -->
<script src="libs/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="libs/js/bootstrap/docs-assets/js/holder.js"></script>

<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</body>
</html>
