<?
require('include/global_data.php');
?>

<?
function raise_error($err = '') {
  global $id;
  
  if ($err != _SUCCESS) $type = "&id=$id";

  if (!empty($err)) $err = "&error=$err";
  header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?content=search$type$err");
  die();
}

switch ($error) {
  case _FAIL_INSERT:
    $error = '<p class="msg">DB not updated</p>';
    break;
  case _SUCCESS:
    $error = '<p class="msg">Message sent!</p>';
    break;
  case _ERR_EMAIL:
    $error = '<p class="msg"><span class="red">Invalid email.</span></p>';
    break;
  case _ERR_MESSAGE_EMPTY:
    $error = '<p class="msg"><span class="red">Please enter a message.</span></p>';
    break;
  case _ERR_EMAIL_NOT_SENT:
    $error = '<p class="msg"><span class="red">Email not sent. Please try again. If problem presists contact administrator.</span></p>';
    break;
  default:
    break;
}

if ($_REQUEST[type] === 'browse') {
  if ($_GET[cat] == _HOUSING) {
    $request_type = '&amp;type=browse&amp;cat=5';
    require 'content/search_results_housing.php';
  } else {
    $request_type = '&amp;type=browse';
    require 'content/search_results.php';
  }
} elseif (isset($_REQUEST['id']) || (isset($_POST['submit']) && $error != _SUCCESS)) {
  require 'content/search_contact.php';
} else {
  $search_isbn = $isbn;
  $search_keywords = $keywords;
  require 'content/search_results.php';
}

?>
