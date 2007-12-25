<?
// ------ Get Posting Information ---------------
$query = 'SELECT * FROM '._CW_TABLE.' WHERE listid = %i AND remove != 1 LIMIT 1';
$db->query( $db->safesql($query, array($_REQUEST[id]) ) );

$row = $db->fetch_array();

if (empty($row)) {
	require('top.php');
	?>
		<div id="content" style="background-image:url(images/bg_search.jpg);">
			<h1>Contact Seller</h1>
			This listing is no longer available.
		</div>
	<?
	require('bottom.php');
	die();
}

$title = $row[title];
$price = $row[price];
$description = $row[description];
$time = date("F j, Y g:i a", $row['time']);

$title_label = 'Title';
$price_label = 'Price';

$category = $arrstrCW[$row[category]];

switch ($row[category]) {
  case _CW_COURSE_NOTES: case _CW_HAND_NOTES: case _CW_EXAM:
    $title_label = 'Term';
    $title = "$row[term] $row[year]";
  break;
  case _HOUSING:
	  $title_label = 'Address';
	  $price_label = 'Rent';
	  break;
}
// ----------------------------------------------

if (isset($_POST[submit])) {
  $replyemail = htmlspecialchars(stripslashes($_POST['replyemail']));

  setcookie('user_email', $replyemail, time()+3600);
  setcookie('seller_message', $message, time()+3600);

  require 'content/post_validate.php';

  if (!$message)
    raise_error(_ERR_MESSAGE_EMPTY);

  if (!validate_email($replyemail))
    raise_error(_ERR_EMAIL);

  $query = 'INSERT INTO '._CW_TABLE_CONTACT_SELLER." (listid, email, time, message) VALUES (%i, '%s', '".time()."', '%s')";
  $db->query( $db->safesql($query, array($_POST[id], $replyemail, $message)) );

  if ($db->affected_rows() < 1)
    raise_error(_FAIL_INSERT);

  $id = $db->insert_id();

  $query = 'SELECT email FROM '._CW_TABLE.', '._CW_TABLE_USERS
    .' WHERE '._CW_TABLE.'.uid = '._CW_TABLE_USERS.'.uid AND listid = %i LIMIT 1';

  $db->query( $db->safesql($query, array($_POST[id])) );
  
  $row = $db->fetch_array();
  $email = $row[email];
  
  // append the subject with title
  $title = html_entity_decode($title);
  $subject = "UWSUBE Listing #$_REQUEST[id] - $title";

  // compose email
  $message = "Category: $category\r\n$title_label: $title\r\n$price_label: $$price\r\n\r\n"."The following is a message sent to you via http://www.uwsube.com/ regarding your posting:\r\n\r\n".$message;

  require_once 'include/mailer.php';
  $mail = new mailer($email, $subject, $message);
  $mail->setFrom("<$replyemail>");

  // send email
  if (!$mail->send()) {
    $error = new mailer(_ERROR_EMAIL, 'Error with sending email', 'The following email to '.$email.' regarding password reset was not sent successfully.\r\n\r\n\r\n'.$message);
    $error->send();

    raise_error(_ERR_EMAIL_NOT_SENT);
  }

  $db->query('UPDATE '._CW_TABLE_CONTACT_SELLER." SET sent = 1 WHERE id = $id");

  setcookie('seller_message', '', time()-3600);
  raise_error(_SUCCESS);
} // form submit, email end


$id = htmlspecialchars(stripslashes($_GET['id']));

$html_title = 'Contact Seller';
$html_meta_robots = 'index,nofollow';
require('top.php');

// Give link to google maps for housing postings
if ($row[category] == _HOUSING)
	$map_link = '&nbsp;<a href="http://maps.google.ca/maps?q='.$title.'+Waterloo+Ontario" target="_blank">Map</a>';

?>

<div id="content" style="background-image:url(images/bg_search.jpg);">
<h1>Contact Seller</h1>
<?=$error?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<div class="formcenter">
<?=formEntry('Category:', $category ); ?>
<?=formEntry("$title_label:", "<strong>$title</strong>$map_link" );?>
<?=formEntry('Description:', $description ); ?>
<?=formEntry("$price_label:", "$$price" ); ?>
<?=formEntry("Time Posted:", $time ); ?>
<?=formEntry('Message:', '<textarea name="message" cols="30" rows="8">'.$_COOKIE[seller_message].'</textarea>'); ?>
<?=formEntry('Reply-Email:', '<input type="text" name="replyemail" style="width:160px;" value="'.$_COOKIE[user_email].'" />'); ?>
<br />
<input type="hidden" name="content" value="search"/>
<input type="hidden" name="id" value="<?=$id?>"/>
<div class="center"><input type="submit" name="submit" value="Send Message"/></div>

</div>
</form>
</div>

<? require('bottom.php'); ?>
