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
  <link rel="stylesheet" href="../CSS/vb.css" type="text/css">
</head>
<body>
<?php

  echo "<div class='instapic'><h3>".$name."</h3>";

  if ( $instaname <> "" ) {

    $instaResult = file_get_contents("https://www.instagram.com/".$instaname."/?__a=1");
    $instas = json_decode($instaResult,true);

    //$instas = json_decode($instaResult);var_dump($instas);die();
    echo "<div>";
    echo "<img src='".$instas['graphql']['user']['profile_pic_url_hd']."' title='".$instas['graphql']['user']['biography']."' alt='".$instaname." Profile Pic' /></a>
      </div>
      <div>";

    if ( $instas['graphql']['user']['external_url'] <> "" ) {
        echo "External: <a target='_blank' href='".$instas['graphql']['user']['external_url']."'>".$instas['graphql']['user']['external_url']."</a>";
    }

    echo "</div></div><br />";

    if ( count($instas['graphql']['user']['edges']) > 0 ) {
      echo "<div><h2><a href='https://www.instagram.com/".$instaname."'>Instagram</a></h2>";

      foreach ($instas['graphql']['user']['edges']['edges'] as $insta) {
        $postdate = date("m-d-Y @ H:i", $insta['date']);
        echo "<a class='instapost' target='_blank' href='".$insta['thumbnail_src']."'><img src='".$insta['thumbnail_resources'][0]['src']."' title='".$insta['caption']."' alt='".$postdate." - ".$insta['caption']."' /></a>";
      }

      echo "</div>";
    }
  }
  if ( $twittername <> "" ) {
    echo "</br /></br /></br /><div><a class='twitter-timeline' data-theme='dark' target='_blank' href='https://twitter.com/".$twittername."?ref_src=twsrc%5Etfw'>".$twittername."</a> <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script></div>";
  }
?>

</body>
</html>
