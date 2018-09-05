<?php
//header('Content-Type: application/json');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

function get_string_between($string, $start, $end){
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}

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

    $instaResult = file_get_contents("https://www.instagram.com/".$instaname."/");
    $instaString = get_string_between($instaResult, '<script type="text/javascript">window._sharedData = ', ';</script>');

    $instas = json_decode($instaString,true);
    //$instas = json_encode($instas, JSON_PRETTY_PRINT);
    //echo $instas;die();

    echo "<div>";
    echo "<a href='https://www.instagram.com/".$instaname."/' target='_blank'><img  src='".$instas['entry_data']['ProfilePage']['0']['graphql']['user']['profile_pic_url_hd']."' title='".$instas['entry_data']['ProfilePage']['0']['graphql']['user']['biography']."' alt='".$instaname." Profile Pic' /></a>
      </div>
      <div>";

    if ( $instas['entry_data']['ProfilePage']['0']['graphql']['user']['external_url'] <> "" ) {
        echo "External: <a target='_blank' href='".$instas['entry_data']['ProfilePage']['0']['graphql']['user']['external_url']."'>".$instas['entry_data']['ProfilePage']['0']['graphql']['user']['external_url']."</a>";
    }

    echo "</div></div><br />";

    if ( count($instas['entry_data']['ProfilePage']['0']['graphql']['user']['edge_owner_to_timeline_media']['edges']) > 0 ) {
      echo "<div><h2><a href='https://www.instagram.com/".$instaname."'>Instagram</a></h2>";

      foreach ($instas['entry_data']['ProfilePage']['0']['graphql']['user']['edge_owner_to_timeline_media']['edges'] as $insta) {
        $postdate = date("m-d-Y @ H:i", $insta['node']['taken_at_timestamp']);
        echo "<a class='instapost' target='_blank' href='".$insta['node']['thumbnail_src']."'><img src='".$insta['node']['thumbnail_resources'][0]['src']."' title='".$insta['node']['edge_media_to_caption']['edges'][0]['node']['text']."' alt='".$postdate." - ".$insta['node']['edge_media_to_caption']['edges'][0]['node']['text']."' /></a>";
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
