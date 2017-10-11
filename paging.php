<?php
//show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

function get_paging_info($tot_rows,$pp,$curr_page)
{
    $pages = ceil($tot_rows / $pp); // calc pages

    $data = array(); // start out array
    $data['si']        = ($curr_page * $pp) - $pp; // what row to start at
    $data['pages']     = $pages;                   // add the pages
    $data['curr_page'] = $curr_page;               // Whats the current page

    return $data; //return the paging data
}

$count = mysql_fetch_assoc( mysql_query ( "SELECT COUNT( rows ) as count FROM table" ) ) ;
$count = $count[0]['count'];

//Call page function from above
$paging_info = get_paging_info($count,5,34);
$paging_info = get_paging_info(25,5,3);

echo "<p>";
//If the current page is more than 1, show the First and Previous links -->
if($paging_info['curr_page'] > 1) {
  echo "<a href='' title='Page 1'>First</a>
    <a href='' title='Page ".$paging_info['curr_page'] - 1)."'>Prev</a>";
}

//setup starting point
//$max is equal to number of links shown
$max = 7;
if($paging_info['curr_page'] < $max){
  $sp = 1;}
elseif($paging_info['curr_page'] >= ($paging_info['pages'] - floor($max / 2)) ){
  $sp = $paging_info['pages'] - $max + 1;}
elseif($paging_info['curr_page'] >= $max){
  $sp = $paging_info['curr_page']  - floor($max/2);}

//If the current page >= $max then show link to 1st page -->
if($paging_info['curr_page'] >= $max) {
  echo "<a href='' title='Page 1'>1</a>";}

//Loop though max number of pages shown and show links either side equal to $max / 2 -->
for($i = $sp; $i <= ($sp + $max -1);$i++) {
  if($i > $paging_info['pages']){
    continue;
    if($paging_info['curr_page'] == $i) {
      echo "<span class='bold'>".$i."</span>";}
    else {
      echo "<a href='' title='Page ".$i."'>".$i."</a>";}
  }
}

//If the current page is less than say the last page minus $max pages divided by 2-->
if($paging_info['curr_page'] < ($paging_info['pages'] - floor($max / 2))) {
  echo "<a href='' title='Page ".$paging_info['pages']."'>".$paging_info['pages']."</a>";}

//Show last two pages if we're not near them -->
if($paging_info['curr_page'] < $paging_info['pages']) {
  echo "<a href='".str_replace('/page'.$paging_info['curr_page'], '', $paging_info['curr_url']) . "/page".($paging_info['curr_page'] + 1)."' title='Page ".$paging_info['curr_page'] + 1)."'>Next</a>";}

  echo "<a href='".str_replace('/page'.$paging_info['curr_page'], '', $paging_info['curr_url']) . "/page".$paging_info['pages']."' title='Page ".$paging_info['pages']."'>Last</a>";}
}
echo "</p>";

?>
