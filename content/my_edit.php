<?php
if (isset($_POST['cat'])) {
  require('content/post_validate.php');
  if ($form_error = validate_form(false)) {
    edit_posting($listid);
  } else {
    $time = time();

    // update the courseware data
    $query = 'UPDATE '._CW_TABLE.' SET '
      ."time = '$time', category = '%s', price = %f, description = '%s'"
      ."[,title = '%S'][,isbn = '%S'][,term = '%S'][,year = '%S'][,vacancies = %I]"
      .' WHERE listid = %i AND uid = '.$_COOKIE['login_uid'];
    $db->query( $db->safesql($query, array($cat, $price, $desc, $title, $cw_isbn, $term, $year, $vacancies, $listid)) );

    // remove all courses from the courses table
    $db->query( $db->safesql('DELETE FROM '._CW_TABLE_COURSES.' WHERE listid = %i', array($listid)) );

    // re-insert the new courses
    for ($i=0; $i<3; $i++) {
      if ( !empty($cid[$i]) ) {
        $course_query_string = 'INSERT INTO '._CW_TABLE_COURSES.' (listid, course, number) VALUES (%q)';
        $course_values = array($listid, $cid[$i], $cnum[$i]);
        $db->query( $db->safesql($course_query_string, array($course_values)) );
      }
    }

    raise_error();
  }

} else {

$query = 'SELECT title, category, description, isbn, price, term, year,vacancies FROM '._CW_TABLE
  .' WHERE listid = %i AND uid = '.$_COOKIE['login_uid'].' LIMIT 1';

$db->query( $db->safesql($query, array($_GET['id'])) );

if ($db->num_rows() < 1) {
  raise_error(_FAIL_SELECT, 'home');
}
$row = $db->fetch_array();

$cat = $row['category'];

switch ($row['category']) {
  case _CW_BOOK: case _CW_OTHER:
    $title = $row['title'];
    $cw_isbn = $row['isbn'];
    break;
  case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
    $term = $row['term'];
    $year = $row['year'];
    break;
  case _HOUSING:
    $term = $row['term'];
    $year = $row['year'];
    $title = $row['title'];
    $vacancies = $row['vacancies'];
    break;
  default:
    break;
}

$desc = $row['description'];
$price = $row['price'];

$db->query( $db->safesql('SELECT course, number FROM '._CW_TABLE_COURSES.' WHERE listid = %i', array($_GET['id'])) );

while ($row2 = $db->fetch_array()) {
  $cid[] = $row2['course'];
  $cnum[] = $row2['number'];
}

$_COOKIE['user_email'] = $_COOKIE['login_email'];

edit_posting($_GET['id']);
}

function edit_posting($listid) {
  extract($GLOBALS); // <-- ack!

  $content_form = 'my';
  $type_form = 'home';
  $submit_form = 'Save Changes';
  $val_pass = false;

  $action_form = '<input type="hidden" name="action" value="edit" /><input type="hidden" name="listid" value="'.$listid.'" />';

  require 'content/post_listing.php';
}

?>