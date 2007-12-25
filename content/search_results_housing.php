<?
$html_title = 'Browse All Housing Postings';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_search.jpg);">
<h1>Browse All Postings</h1>

<?
echo $error;

$query = 'SELECT count('._CW_TABLE.'.listid) FROM '._CW_TABLE_CATEGORY.','._CW_TABLE
  .' WHERE '._CW_TABLE_CATEGORY.'.code = '._CW_TABLE.'.category'
  .' AND remove = 0'
  .' AND '._CW_TABLE.'.category = '._HOUSING;

$rs = $db->query($query);
$row = $db->fetch_array();
$num_results = $row[0];

if (!isset($_GET[page])) $_GET[page] = 1; // default page value
$page = $_GET[page];

// print the result statistics
if ($num_results) {
  $max_pages = intval($num_results/_LIMIT_PER_PAGE); // maximum pages to browse through
  if ($num_results % _LIMIT_PER_PAGE) $max_pages++;
  $start_result = ($page-1)*_LIMIT_PER_PAGE+1;
  if ($page == $max_pages) {
    $end_result = $num_results;
  } else {
    $end_result = $page * _LIMIT_PER_PAGE;
  }
  echo "Results $start_result - $end_result of $num_results<br />";
  echo "Page: ";
  for ($i=1; $i<=$max_pages; $i++) {
    if ($i != $page)
      echo "\n".'<a href="'.$_SERVER[PHP_SELF]."?content=search$request_type&amp;page=$i\">$i</a>";
    else
      echo "\n$i";
  }
  echo '<br /><br />';
} else {
  echo '<p class="msg">Your search did not match any listing.</p>';
}

if ($num_results) {
$query = 'SELECT * FROM '._CW_TABLE_CATEGORY.','._CW_TABLE
  .' WHERE '._CW_TABLE_CATEGORY.'.code = '._CW_TABLE.'.category'
  .' AND remove = 0'
  .' AND '._CW_TABLE.'.category = '._HOUSING
  .' ORDER BY time DESC';

$limit = $db->safesql(' LIMIT %i,'._LIMIT_PER_PAGE, array(($page-1) * _LIMIT_PER_PAGE));
$query = $query . $limit;

$rs = $db->query( $query );

$table = array('desc' => array('Description'), 'price' => array('Rent'), 'actions' => array('Actions'));

  while ($row = $db->fetch_array($rs)) {

  $row['description'] = wordwrap($row[description], 50, ' ', 1);

		array_push($table['desc'], '<h3>'.$row['title'].'</h3><p class="desc">'.$row['description'].'</p><p class="details">Term: '.$row['term'].' '.$row['year'].' &#8212; Vacancies: '.$row['vacancies'].'</p>');

    array_push($table['price'], '$' . number_format($row['price'], 2));
    array_push($table['actions'], '<div class="mybutton"><a href="'.$_SERVER[PHP_SELF]
      .'?content=search&amp;id='.$row[listid].'">Contact Seller<br />#'.$row[listid].'</a></div>');
  }

  // Display user's postings
  require_once('include/table.php');

  echo '<h2 class="mycat">'.$arrstrCW[_HOUSING].'</h2>';
  echo createTable(array_values($table));
  echo '<br /><br />';  
}

echo '</div>';
require('bottom.php');

?>
