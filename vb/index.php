<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include("./vbfeed.php");
}
else {
?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Volleyball Feeds</title>
    <link rel="stylesheet" href="../CSS/vb.css" type="text/css">
  <body>
  <?php

  include("../DB/dbconnect.php");

  $vbroster = "SELECT * FROM vb ORDER BY name ASC;";

  foreach($dbConnection->query($vbroster) as $row)
  {
  	echo "<div class='thumbs'><form action='./index.php' method='post'>
      <input type='image' src='../Images/".$row['name'].".jpg' title='".$row['name']."' alt='".$row['name']." image'>
      <input type='hidden' name='name' value='".$row['name']."'>
      <input type='hidden' name='jersey' value='".$row['jersey']."'>
      <input type='hidden' name='twitter' value='".$row['twitter']."'>
      <input type='hidden' name='instagram' value='".$row['instagram']."'>
      </form>
      </div>";
  }

  $dbConnection = null;
//
  $rss = new DOMDocument();
  $rss->load('http://host.madison.com/search/?f=rss&t=article&c=sports/college&l=25&s=start_time&sd=desc'); // Set the blog RSS feed url here

  $feed = array();
  foreach ($rss->getElementsByTagName('item') as $node) {
  	$item = array (
  		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
  		'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
  		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
  		);
  	array_push($feed, $item);
  }


  $limit = 10; // Set the number of articles to load here
  for($n=0;$x<$limit;$n++) {
  	$title = str_replace(' & ', ' &amp; ', $feed[$n]['title']);
  	$link = $feed[$n]['link'];
  	$description = $feed[$n]['desc'];
  	echo '<p><b><a href="'.$link.'" title="'.$title.'">'.$title.'</a></b><br />';
  	echo '<p>'.$description.'</p>';
  }
//
  ?>
  </body>
  </html>
<?php
}
?>
