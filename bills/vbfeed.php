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
  if ( $instaname <> "" ) {
    $instaResult = file_get_contents("https://www.instagram.com/".$instaname."/?__a=1");
    $instas = json_decode($instaResult,true);
    //var_dump($insta);
    var_dump($instaname);
    echo "<p class='instapic'>
      <figure>
        <b><figcaption>".$name."</figcaption></b>
        <img src='".$instas['user']['profile_pic_url_hd']."' alt='".$instaname." Profile Pic' />
      </figure>
      </p>
      <br />
      <h2>Instagram</h2>";

    foreach ($instas['user']['media']['nodes'] as $insta) {
      $postdate = date("m-d-Y @ H:i", $insta['date']);
      echo "<br /><a class='instapost' href='".$insta['thumbnail_src']."'><img src='".$insta['thumbnail_resources'][0]['src']."' alt='".$postdate." - ".$insta['caption']."' /></a>";
    }
  }
  else {
    echo "<h3>".$name."</h3>";
  }

  if ( $twittername <> "" ) {
    echo "</br /><a class='twitter-timeline' href='https://twitter.com/".$twittername."?ref_src=twsrc%5Etfw'>Tweets by ".$twittername."</a> <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script>";
  }
?>

</body>
</html>
