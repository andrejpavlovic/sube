<?
if (!$_COOKIE['login']) {
  raise_error();
}

switch ($action) {
  case 'edit':
    require 'content/my_edit.php';
    break;
  case 'rem':
    require 'content/my_remove.php';
    break;
  default:

$logout_link = '<a href="index.php?content=my&amp;type=logout">Logout</a>';

echo '<p>Welcome '.$_COOKIE['login_email'].'! ('.$logout_link.') (<a href="index.php?content=my&amp;type=chg">Change password</a>)</p>';

switch ($_GET['error']) {
  case _SUCCESS:
    echo '<p class="msg">Password changed sucessfully.</p>';
    break;
  case _FAIL_UPDATE:
    echo '<p class="msg"><span class="red">Failed to update database.</span></p>';
    break;
  default:
    echo '<br />';
    break;
}

// Display user's postings
require_once('include/table.php');

/* This may be a security problem, selecting on the contents of the cookie. Just have to make sure that 
   the logged-in state is being checked on every pageview. (we could be using $_SESSION also...) */
$rs_postings = $db->query('SELECT listid, title, category, description, isbn, price, term, vacancies, year FROM '._CW_TABLE." WHERE uid = ".$_COOKIE['login_uid'].' AND remove = 0');

if ($db->num_rows($rs_postings) > 0) {

  ?>Your current postings are displayed below. You may edit or remove them at your discretion.
  <br /><br /><?

  $table_other = $table_notes = $table_book = array('desc' => array('Description'), 'price' => array('Price'), 'actions' => array('Actions'));
  $table_housing = array('desc' => array('Description'), 'price' => array('Rent'), 'actions' => array('Actions'));

  while ($row = $db->fetch_array($rs_postings)) {

  $courses = '';
	
  $db->query("SELECT course, number FROM "._CW_TABLE_COURSES." WHERE listid = ".$row['listid']);

  while ($row2 = $db->fetch_array())
	  $courses .= $row2['course'].' '.$row2['number'].', ';
	$courses = substr($courses, 0, strlen($courses)-2); // remove extra comma

    if (strlen($row['description']) > 200)
		$row['description'] = substr($row['description'], 0, 200) . '...';
    $row['description'] = wordwrap($row[description], 50, ' ', 1);

    switch ($row['category']) {
      case _CW_BOOK:
        $table = &$table_book;
        array_push($table['desc'], '<h3>#'.$row['listid'].' '.$row['title'].'</h3><p class="desc">'.$row['description'].'</p><p class="details">ISBN: '.$row['isbn'].' &#8212; Courses: '.$courses.'</p>');
        break;
      case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
        $table = &$table_notes;
        array_push($table['desc'], '<h3>'.$row['term'].' '.$row['year'].' ('.$arrstrCW[$row['category']].')</h3><p class="desc">'.$row['description'].'</p><p class="details">Courses: '.$courses.'</p>');
        break;
      case _CW_OTHER:
        $table = &$table_other;
        array_push($table['desc'], '<h3>'.$row['title'].'</h3><p class="desc">'.$row['description'].'</p><p class="details">Courses: '.$courses.'</p>');
        break;
      case _HOUSING:        
		$table = &$table_housing;
		array_push($table['desc'], '<h3>'.$row['title'].'</h3><p class="desc">'.$row['description'].'</p><p class="details">Term: '.$row['term'].' '.$row['year'].' &#8212; Vacancies: '.$row['vacancies'].'</p>');
	    //array_push($table['price'], '$' . number_format($row['rent'], 0));
	    /*array_push($table['actions'], '<div class="mybutton"><a href="'.$_SERVER['PHP_SELF']
	      .'?content=my&amp;type=home&amp;action=edit_housing&amp;id='.$row['listid'].'">edit</a></div><div class="mybutton"><a href="'.$_SERVER['PHP_SELF']
	      .'?content=my&amp;type=home&amp;action=rem_housing&amp;id='.$row['listid'].'">remove</a></div>');*/
        break;
      default:
        break;
    }

    array_push($table['price'], '$' . number_format($row['price'], 2));
    array_push($table['actions'], '<div class="mybutton"><a href="'.$_SERVER['PHP_SELF']
      .'?content=my&amp;type=home&amp;action=edit&amp;id='.$row['listid'].'">edit</a></div><div class="mybutton"><a href="'.$_SERVER['PHP_SELF']
      .'?content=my&amp;type=home&amp;action=rem&amp;id='.$row['listid'].'">remove</a></div>');
  }

  echo '<h2 class="mycat">'.$arrstrCW[_CW_BOOK].'</h2>';
  echo createTable(array_values($table_book));
  echo '<br /><br />';

  echo '<h2 class="mycat">'.$arrstrCW[_CW_COURSE_NOTES].', '.$arrstrCW[_CW_HAND_NOTES].'</h2>';
  echo createTable(array_values($table_notes));
  echo '<br /><br />';

  echo '<h2 class="mycat">'.$arrstrCW[_CW_OTHER].'</h2>';
  echo createTable(array_values($table_other));
  echo '<br /><br />';
  
  echo '<h2 class="mycat">'.$arrstrCW[_HOUSING].'</h2>';
  echo createTable(array_values($table_housing));
  echo '<br /><br />';  
  

} else {
  ?>You have no postings currently.<?
}

}
