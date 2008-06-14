<?

require 'include/global_data.php';

//------------ Statistics ------------------

$books_sold_query = 'SELECT COUNT(*) number, SUM(price) `value` FROM courseware WHERE remove = 1 AND category != '._HOUSING.' AND `time` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))';
$books_posted_query = 'SELECT COUNT(*) number, SUM(price) `value` FROM courseware WHERE remove = 0 AND category != '._HOUSING;
$housing_sold_query = 'SELECT COUNT(*) number FROM courseware WHERE remove = 1 AND category = '._HOUSING.' AND `time` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR))';
$housing_posted_query = 'SELECT COUNT(*) number FROM courseware WHERE remove = 0 AND category = '._HOUSING;

$db->query($books_sold_query);
$books_sold = $db->fetch_array();

$db->query($books_posted_query);
$books_posted = $db->fetch_array();

$db->query($housing_sold_query);
$housing_sold = $db->fetch_array();

$db->query($housing_posted_query);
$housing_posted = $db->fetch_array();

//------------ End Statistics --------------


//------------ Five Latest Postings --------
$five_postings_query = 'SELECT listid FROM courseware WHERE category != '._HOUSING.' AND remove = 0 ORDER BY time DESC LIMIT 5';
require 'include/search_functions.php';
$five_postings_table = format_results ($db->query($five_postings_query));
//------------ End Five Latest Postings ----

require_once('include/global_data.php');
$html_title = 'Latest News';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_news.jpg);">

<h1>Latest News</h1>

<p>
	Students bought over <span class="style2"><?=number_format($books_sold[number], 0, '.', ',')?></span>
	courseware items (books, course notes, etc.) last year worth almost
	<span class="style2">$<?=number_format($books_sold[value], 2, '.', ',')?></span>!
	This year we hope to double that amount while constantly increasing the usability of our site.
	Founded and maintained by Univeristy of Waterloo students, UWSUBE allows you to buy/sell books,
	course notes, handwritten notes, exam papers, and housing absolutely <span class="style1">FREE</span>!
</p> 
<p>
	There are currently <span class="style2"><?=number_format($books_posted[number], 0, '.', ',')?></span>
	courseware items listed worth over <span class="style2">$<?=number_format($books_posted[value], 2, '.', ',')?></span>.
</p>

<p>
	<span class="style2">NO REGISTRATION NECESSARY</span>! UWSUBE is designed to make buying and selling your school
	items fast and painless. Just follow the 'Submit a Posting' link above.
</p>

<p>
	There are also over <span class="style2"><?=number_format($housing_posted[number], 0, '.', ',')?></span>
	off campus housing postings available for you to choose from.
	<span class="style2"><?=number_format($housing_sold[number], 0, '.', ',')?></span> have already been rented out in the last year!
</p>

<br />

<h1>Five Latest Postings</h1>

<br />

<?=$five_postings_table->printTable();?>

</div>

<?
require('bottom.php');
?>
