<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Volleyball Feeds</title>
  <link rel="stylesheet" href="../CSS/vb.css" type="text/css">
<body>

<?php
  $rss = new DOMDocument();
  $rss->load('http://host.madison.com/search/?f=rss&t=article&c=sports/college/volleyball&l=25&s=start_time&sd=desc'); // Set the blog RSS feed url here

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
  for($n=0;$n<$limit;$n++) {
  	$title = str_replace(' & ', ' &amp; ', $feed[$n]['title']);
  	$link = $feed[$n]['link'];
  	$description = $feed[$n]['desc'];
  	echo '<p><b><a href="'.$link.'" title="'.$title.'">'.$title.'</a></b><br />';
  	echo '<p>'.$description.'</p>';
  }
?>
</body>
</html>
