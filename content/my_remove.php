<?
if (!$_COOKIE['login']) {
  raise_error();
}

define('_YES', 'Yes');
define('_NO', 'No');

if (isset($_POST['submit'])) {
  switch ($_POST['submit']) {
    case _YES:
      $query = 'UPDATE '._CW_TABLE.' SET remove = 1 WHERE listid = %i AND uid = '.$_COOKIE['login_uid'];
      $db->query( $db->safesql($query, array($_POST['id'])) );

      if ($db->affected_rows() < 1)
        raise_error(_FAIL_UPDATE, 'home');

    default:
      raise_error();
      break;
  }
}

/*$query = 'SELECT title, category, description, price, term, year, course, number, vacancies'
  .' FROM '._CW_TABLE.','._CW_TABLE_COURSES
  .' WHERE '._CW_TABLE.'.listid = '._CW_TABLE_COURSES.'.listid'
  .' AND uid = '.$_COOKIE['login_uid'].' AND '._CW_TABLE.'.listid = %i LIMIT 1';*/

// This improved query will still return CW records even without _COURSES records present.
$query = 'SELECT title, category, description, price, term, year, course, number, vacancies'
  .' FROM '._CW_TABLE
  .' LEFT JOIN '._CW_TABLE_COURSES.' ON '._CW_TABLE.'.listid = '._CW_TABLE_COURSES.'.listid'
  .' WHERE uid = '.$_COOKIE['login_uid'].' AND '._CW_TABLE.'.listid = %i LIMIT 1';


$db->query( $db->safesql($query, array($_GET['id'])) );

if ($db->num_rows() < 1)
  raise_error(_FAIL_SELECT, 'home');

$row = $db->fetch_array();

$posting = array('Category' => $arrstrCW[$row['category']]);

switch ($row['category']) {
  case _CW_BOOK: case _CW_OTHER:
    $posting['Title'] = $row['title'];
    $posting['Course'] = $row['course'].' '.$row['number'];
    break;
  case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
    $posting['Term'] = $row['term'];
    $posting['Year'] = $row['year'];
    $posting['Course'] = $row['course'].' '.$row['number'];
    break;
  case _HOUSING:
    $posting['Vacancies'] = $row['vacancies'];
    $posting['Address'] = $row['title'];
    break;
  default:
    break;
}

$posting = $posting + array('Description' => $row['description'], 'Price' => $row['price']);

foreach ($posting as $type=>$val)
  $details .= "$type: $val<br />";
?>

<p>Are you sure you want to remove the following posting?</p>
<?=$details?>
<br />
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="content" value="my"/>
<input type="hidden" name="type" value="home"/>
<input type="hidden" name="action" value="rem"/>
<input type="hidden" name="id" value="<?=$_GET['id']?>"/>
<input type="submit" name="submit" value="<?=_YES?>" style="width:60px" />
&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="<?=_NO?>" style="width:60px" />


</form>