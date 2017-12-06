<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

  if ( $_POST['jersey'] > 0 ) {
    $name=$_POST['name'].", #".$_POST['jersey'];
  }
  else {
    $name=$_POST['name'];
  }
  $twittername=$_POST['twitter'];
  $instaname=$_POST['instagram'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Volleyball feed for <?php echo $name; ?></title>
</head>
<body>
<?php

  $instaResult = file_get_contents("https://www.instagram.com/".$instaname."/?__a=1");
  $instas = json_decode($instaResult,true);
var_dump($instas);

  if ( $instaname <> "" || ) {
    echo "<h3>".$name."</h3>";
    echo "<p class='instapic'>
        <a target='_blank' href='".$instas['user']['external_url']."'><img src='".$instas['user']['profile_pic_url_hd']."' title='".$instas['user']['biography']."' alt='".$instaname." Profile Pic' /></a>
      </p>
      <br />
      <h2>Instagram</h2>";

    foreach ($instas['user']['media']['nodes'] as $insta) {
      $postdate = date("m-d-Y @ H:i", $insta['date']);
      echo "<a class='instapost' target='_blank' href='".$insta['thumbnail_src']."'><img src='".$insta['thumbnail_resources'][0]['src']."' title='".$insta['caption']."' alt='".$postdate." - ".$insta['caption']."' /></a>";
    }
  }

  if ( $twittername <> "" ) {
    echo "</br /></br /></br /><a class='twitter-timeline' data-theme='dark' target='_blank' href='https://twitter.com/".$twittername."?ref_src=twsrc%5Etfw'>".$twittername."</a> <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>";
  }
?>

</body>
</html>
