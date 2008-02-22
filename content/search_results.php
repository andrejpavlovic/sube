<?

if ($_REQUEST[type] === 'browse') {
  $title = 'Browse All Postings';
  $html_title = 'Browse All Courseware Postings';
} else {
  $title = 'Search Results';
  $html_title = 'Search Results';
}

$html_meta_robots = 'noindex,follow';

require('top.php');
?>

<div id="content" style="background-image:url(images/bg_search.jpg);">
<h1><?=$title?></h1>

<?
echo $error;

if ($_REQUEST[type] === 'browse') {
  setcookie('cookie_isbn', '', time()-3600);
  setcookie('cookie_keywords', '', time()-3600);
} else if (isset($_REQUEST[page]))  {
  $search_isbn = $_COOKIE[cookie_isbn];
  $search_keywords = $_COOKIE[cookie_keywords];
} else {
  setcookie('cookie_isbn', $search_isbn, time()+3600);
  setcookie('cookie_keywords', $search_keywords, time()+3600);
}

// validate isbn
if (!empty($search_isbn)) {
  $search_isbn = validate_isbn($search_isbn);
  if (!$search_isbn) {
    echo '<p class="msg"><span class="red">The ISBN you have entered is invalid and was not included in the search.</span></p>';
    $search_isbn = '';
  }
}

// parse the keywords and put them into an array
$search_keywords = search_split_terms(html_entity_decode($search_keywords));

if (count($search_keywords) > 0) {
  foreach($search_keywords as $term){
    $term = $db->safesql('%s', array($term));
    $search_all[] = '('._CW_TABLE.".listid LIKE '%$term%'
                    OR title LIKE '%$term%'
                    OR isbn LIKE '%$term%'
                    OR description LIKE '%$term%'
                    OR category_name LIKE '%$term%'
                    OR course LIKE '%$term%'
                    OR number LIKE '%$term%'
                    OR term LIKE '%$term%'
                    OR year LIKE '%$term%'".')';
  }
  $all_search_parts = 'AND ('.implode(' AND ', $search_all).')';
} else {
  $all_search_parts = '';
}

$query = 'SELECT count(DISTINCT '._CW_TABLE.'.listid) FROM '._CW_TABLE_CATEGORY.','._CW_TABLE.','._CW_TABLE_COURSES
  .' WHERE '._CW_TABLE_CATEGORY.'.code = '._CW_TABLE.'.category'
  .' AND '._CW_TABLE.'.listid = '._CW_TABLE_COURSES.'.listid'
  .' AND remove = 0'
  ." [ AND isbn  = '%S' ]";

$query = $db->safesql($query, array($search_isbn)) . " $all_search_parts";

$rs = $db->query($query);
$row = $db->fetch_array();
$num_results = $row[0];

if (!isset($_GET[page])) $_GET[page] = 1; // default page value

$page = $_GET[page];
$next_page = $page + 1;
$prev_page = $page - 1;

// print the result statistics

if ($num_results) {
  $max_pages = intval($num_results/_LIMIT_PER_PAGE); // maximum pages to browse through
  // get the right number of pages
  if ($num_results % _LIMIT_PER_PAGE) $max_pages++;
  $start_result = ($page-1)*_LIMIT_PER_PAGE+1;
  if ($page == $max_pages) {
    $end_result = $num_results;
  } else {
    $end_result = $page * _LIMIT_PER_PAGE;
  }
  // print page list at top 
  print_page_list($start_result, $end_result, $num_results, $max_pages, $request_type);

  $query = 'SELECT DISTINCT '._CW_TABLE.'.listid FROM '._CW_TABLE_CATEGORY.','._CW_TABLE.','._CW_TABLE_COURSES
  .' WHERE '._CW_TABLE_CATEGORY.'.code = '._CW_TABLE.'.category'
  .' AND '._CW_TABLE.'.listid = '._CW_TABLE_COURSES.'.listid'
  .' AND remove = 0'
  ." [ AND isbn  = '%S' ]";

  $query = $db->safesql($query, array($search_isbn));
  $limit = $db->safesql(' LIMIT %i,'._LIMIT_PER_PAGE, array(($page-1) * _LIMIT_PER_PAGE));
  $query = $query . " $all_search_parts" .' ORDER BY time DESC'. $limit;

  $rs = $db->query( $query );

  require_once 'include/search_functions.php';
  $table = format_results($rs);
  // print results
  echo $table->printTable();

  // print page list at bottom
  print_page_list($start_result, $end_result, $num_results, $max_pages, $request_type);

} else {
    echo '<p class="msg"><span class="red">Sorry, there are no results that match the given criteria.</span></p>';
}

echo '</div>';
require('bottom.php');


function validate_isbn($isbn) {
  require 'include/isbntest.class.php';
  // check if ISBN is valid using the ISBNvalidator class
  $validator = new isbntest();
  if ($validIsbn = $validator->isISBN($isbn)) {
    $isbn = $validator->isbn;
  } else {
    $isbn = '';
  }
  unset($validator);
  return $isbn;
}

function validate_courseid($cid) {
  if (in_array($cid, $GLOBALS['arrstrCOURSE_ID']))
    return true;
  else
    return false;
}

function validate_number($cid) {
  if (preg_match('/^\d\d\d$/', $cid))
    return true;
  else
    return false;
}

// return an array of terms where each term is either a word or a list of words that was in quotes
function search_split_terms($terms) {
  $terms = preg_replace('/[^a-z0-9" ]/i', '', $terms);
  $terms = preg_replace("/\s+/", ' ', $terms);
  preg_match_all('/"(.*?)"/', $terms, $matches, PREG_PATTERN_ORDER);
  $return = array();
  if (count($matches) > 0) {
    foreach ($matches[1] as $val) {
      if (trim($val) !== '') $return[] = trim($val);
      $terms = preg_replace("/\"$val\"/", ' ', $terms, 1);
    }
  }
  $terms = preg_replace('/["]/', ' ', trim($terms));
  $terms = preg_replace("/\s+/", ' ', $terms);

  $terms = explode(' ', $terms);
  foreach ($terms as $val)
    $return[] = $val;

  return $return;
}

function print_page_list($start_result, $end_result, $num_results, $max_pages, $request_type) {
  $page = $_GET[page];
  $next_page = $page + 1;
  $prev_page = $page - 1;

  echo "Results $start_result - $end_result of $num_results";

  if ($max_pages > 1)
  {
    if ($page != 1) echo "\n".'<a href="'.$SERVER[PHP_SELF]."?content=search$request_type&amp;page=$prev_page\">Prev</a>";

    if ($page != $max_pages)  echo "\n".'<a href="'.$_SERVER[PHP_SELF]."?content=search$request_type&amp;page=$next_page\">Next</a>";
  
    echo "<br />Page: ";
  
    for ($i=1; $i<=$max_pages; $i++) {
      if ($i != $page)  echo "\n".'<a href="'.$_SERVER[PHP_SELF]."?content=search$request_type&amp;page=$i\">$i</a>";
      else echo "\n$i";
    }
  }
}

?>
