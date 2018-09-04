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
    function fetchData($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
    }

    $result = fetchData("https://api.instagram.com/v1/users/".$instaname."/media/recent/?access_token=ACCES TOKEN HERE&count=14");


    $result = json_decode($result);
    foreach ($result->data as $post) {
       if(empty($post->caption->text)) {
         // Do Nothing
       }
       else {
          echo '<a class="instagram-unit" target="blank" href="'.$post->link.'">
          <img src="'.$post->images->low_resolution->url.'" alt="'.$post->caption->text.'" width="100%" height="auto" />
          <div class="instagram-desc">'.htmlentities($post->caption->text).' | '.htmlentities(date("F j, Y, g:i a", $post->caption->created_time)).'</div></a>';
       }

    }
  }
  if ( $twittername <> "" ) {
    echo "</br /></br /></br /><div><a class='twitter-timeline' data-theme='dark' target='_blank' href='https://twitter.com/".$twittername."?ref_src=twsrc%5Etfw'>".$twittername."</a> <script async src='https://platform.twitter.com/widgets.js' charset='utf-8'></script></div>";
  }
?>

</body>
</html>
