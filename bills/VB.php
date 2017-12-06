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
<?php
  //Riley Bell
  $twittername="RileyBell22";
  //echo "<a class='twitter-timeline' href='https://twitter.com/".$twittername."?ref_src=twsrc%5Etfw'>Tweets by ".$twittername."</a> <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>";
  $instaname="rileybell2";
  $instaResult = file_get_contents("https://www.instagram.com/".$instaname."/?__a=1");
  $instas = json_decode($instaResult,true);
  //var_dump($insta);
  echo "<p class='insta-timeline'><img src='".$insta['user']['profile_pic_url_hd']."' alt='".$username." HD Profile Pic' /></p>";
  foreach ($instas['user']['nodes'] as $insta) {
    $postdate = date("m-d-Y @ H:i", $insta['date']);
    echo "<a class='instapost' href='".$insta['thumbnail_src']."'><img src='".$insta['thumbnail_resources'][0].['src']."' alt='".$postdate." - ".$insta['caption']."' /></a>";
  }
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
