<?php
if (!$_COOKIE[login]) {
  raise_error();
}

if (isset($oldpass)) {
	// check the password
	$query = sprintf("SELECT uid FROM %s WHERE email = '%s' AND password = '%s' LIMIT 1"
		,_CW_TABLE_USERS
		,$db->escape($_COOKIE['login_email'])
		,$db->escape(sha1($oldpass))
	);
  $db->query( $query );

  if ($db->num_rows() <= 0)
    raise_error(_ERR_PASS, 'chg');

  if (0 != strcmp($pass[0], $pass[1]))
    raise_error(_ERR_NOT_MATCH, 'chg');

  require_once 'content/post_validate.php';

  $error = validate_pass($pass[0]);
  if (!empty($error)) raise_error(_ERR_ILL_PASS, 'chg');

  $row = $db->fetch_array();
  $query = 'UPDATE '._CW_TABLE_USERS." SET password = '".sha1($pass[0])."' WHERE uid = ".$row[uid];
  $db->query( $query );

  if ($db->affected_rows() < 1 && 0 != strcmp($oldpass, $pass[0]))
    raise_error(_FAIL_UPDATE, 'chg');
  else
    raise_error(_SUCCESS, 'home');
}
?>

<p>Fill out the form below to change your password.</p>
<form action="<?php echo $_SERVER[PHP_SELF]?>" method="post">

<div class="formcenter">

<?php echo formEntry('Email:', $_COOKIE[login_email]); ?>
<?php echo formEntry('Old password:', '<input type="password" name="oldpass" style="width:140px;" />'); ?>
<?php echo formEntry('New password:', '<input type="password" name="pass[]" style="width:140px;" />'); ?>
<?php echo formEntry('New password:<br/>(again)', '<input type="password" name="pass[]" style="width:140px;" />'); ?>

<input type="hidden" name="content" value="my"/>
<input type="hidden" name="type" value="chg"/>
<div class="center"><input type="submit" value="Change Password"/></div>

</div>
</form>
<br />
<?php switch ($_GET['error']) {
  case _ERR_PASS:
    echo '<span class="red">Invalid old password.</span>';
    break;
  case _ERR_NOT_MATCH:
    echo '<span class="red">The new passwords do not match.</span>';
    break;
  case _ERR_ILL_PASS:
    echo '<span class="red">New password: '._INVALID_PASSWORD.'</span>';
    break;
  case _FAIL_UPDATE:
    echo '<span class="red">Filed to updated database. Please report to admin.</span>';
    break;
  case _SUCCESS:
    echo 'Password changed sucessfully.';
    break;
  default:
    break;
} ?>
