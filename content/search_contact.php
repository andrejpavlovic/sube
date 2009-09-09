<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mailer.php';

// ------ Get Posting Information ---------------
$query = 'SELECT * FROM '._CW_TABLE.' WHERE listid = %i AND remove = 0 LIMIT 1';
$db->query( $db->safesql($query, array($_REQUEST[id]) ) );

$row = $db->fetch_array();

if (empty($row)) {
	require('top.php');
	?>
		<div id="content" style="background-image:url(images/bg_search.jpg);">
			<h1>Contact Seller</h1>
			This listing is no longer available.
		</div>
	<?php
	require('bottom.php');
	die();
}

// get posting course information
$db->query( $db->safesql('SELECT course, number FROM '._CW_TABLE_COURSES.' WHERE listid = %i', array($_REQUEST['id'])) );
while ($row2 = $db->fetch_array()) {
	$courses[] = array('course' => $row2['course'], 'number' => $row2['number']);
}

$title = $row[title];
$price = $row[price];
$description = $row[description];
$time = date("F j, Y g:i a", $row['time']);

$title_label = 'Title';
$price_label = 'Price';
$html_title = $row['title'];

$category = $arrstrCW[$row[category]];

switch ($row[category]) {
  case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
    $title_label = 'Term';
    $title = "$row[term] $row[year]";
    $tmp = array();
    for ($i=0; $i<sizeof($courses); $i++)
    {
    	$tmp[] = $courses[$i]['course'] . ' ' . $courses[$i]['number'];
    }
    $html_title = "$category - " . implode(', ', $tmp);
  break;
  case _HOUSING:
	  $title_label = 'Address';
	  $price_label = 'Rent';
	  break;
}
// ----------------------------------------------

if (isset($_POST['submit'])) {
  $replyemail = htmlspecialchars(stripslashes($_POST['replyemail']));
  $number1 = intval($_POST['number1']);
  $number2 = intval($_POST['number2']);
  $sum = intval($_POST['sum']);

  setcookie('user_email', $replyemail, time()+86400);
  setcookie('seller_message', $message, time()+86400);
  setcookie('number1', $number1, time()+86400);
  setcookie('number2', $number2, time()+86400);
  setcookie('sum', $sum, time()+86400);

  require 'content/post_validate.php';

  if (!$message)
    raise_error(_ERR_MESSAGE_EMPTY);

  if (!validate_email($replyemail))
    raise_error(_ERR_EMAIL);

  if ($number1 + $number2 != $sum)
    raise_error(_ERR_INVALID_SUM);

  $query = 'INSERT INTO '._CW_TABLE_CONTACT_SELLER." (listid, email, time, message) VALUES (%i, '%s', '".time()."', '%s')";
  $db->query( $db->safesql($query, array($_POST[id], $replyemail, $message)) );

  if ($db->affected_rows() < 1)
    raise_error(_FAIL_INSERT);

  $insert_id = $db->insert_id();

  $query = 'SELECT email FROM '._CW_TABLE.', '._CW_TABLE_USERS
    .' WHERE '._CW_TABLE.'.uid = '._CW_TABLE_USERS.'.uid AND listid = %i LIMIT 1';

  $db->query( $db->safesql($query, array($_POST[id])) );
  
  $row = $db->fetch_array();
  $email = $row[email];
  
  // append the subject with title
  $title = html_entity_decode($title);
  $subject = "UWSUBE Listing #$_REQUEST[id] - $title";

  // compose email
  $body = "Category: $category\r\n$title_label: $title\r\n$price_label: $$price\r\n\r\n"."The following is a message sent to you via UWSUBE regarding your posting:\r\n\r\n".$message;

	//Create the message
	$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom($replyemail)
		->setSender(array(_EMAIL_FROM_ADDRESS => _EMAIL_FROM_NAME))
		->setReturnPath(_EMAIL_ERROR_ADDRESS)
		->setTo($email)
		->setBody($body)
	;
	
	$mailer = MyMailer::getMailer();
	
	// send email
	if (!$mailer->send($message)) {
		$message = Swift_Message::newInstance()
			->setSubject('Error with sending email')
			->setFrom(array(_EMAIL_FROM_ADDRESS => _EMAIL_FROM_NAME))
			->setTo(_EMAIL_ERROR_ADDRESS)
			->setBody(sprintf("The following email to %s regarding contact seller was not sent successfully.\r\n\r\n\r\n%s", $email, $body))
		;
		$mailer->send($message);
		
		raise_error(_ERR_EMAIL_NOT_SENT);
	}

  $db->query('UPDATE '._CW_TABLE_CONTACT_SELLER." SET sent = 1 WHERE id = $insert_id");

  setcookie('seller_message', '', time()-86400);
  setcookie('number1', '', time()-86400);
  setcookie('number2', '', time()-86400);
  setcookie('sum', '', time()-86400);
  raise_error(_SUCCESS);
} // form submit, email end


$id = htmlspecialchars(stripslashes($_GET['id']));

$html_meta_robots = 'index,nofollow';
$html_meta_description = htmlentities(substr(html_entity_decode($description), 0, 180)) . '...';
require('top.php');

// Give link to google maps for housing postings
if ($row[category] == _HOUSING)
	$map_link = '&nbsp;<a href="http://maps.google.ca/maps?q='.$title.'+Waterloo+Ontario" target="_blank">Map</a>';

if (!isset($_COOKIE['number1']))
{
	$number1 = rand(1, 5);
	$number2 = rand(1, 5);
	$sum = '';
}
else
{
	$number1 = $_COOKIE['number1'];
	$number2 = $_COOKIE['number2'];
	$sum = $_COOKIE['sum'];
}
?>

<div id="content" style="background-image:url(images/bg_search.jpg);">
<h1>Contact Seller</h1>
<?php echo $error?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<div class="formcenter">
<?php echo formEntry('Category:', $category ); ?>
<?php echo formEntry("$title_label:", "<strong>$title</strong>$map_link" );?>
<?php echo formEntry('Description:', $description ); ?>
<?php echo formEntry("$price_label:", "$$price" ); ?>
<?php echo formEntry("Time Posted:", $time ); ?>
<?php echo formEntry('Message:', '<textarea name="message" cols="30" rows="8">'.$_COOKIE[seller_message].'</textarea>'); ?>
<?php echo formEntry('Your Email:', '<input type="text" name="replyemail" style="width:160px;" value="'.$_COOKIE[user_email].'" />'); ?>
<?php echo formEntry("$number1 + $number2 =", '<input type="text" name="sum" size="4" value="'.$sum.'" />'); ?>
<br />
<input type="hidden" name="number1" value="<?php echo $number1 ?>"/>
<input type="hidden" name="number2" value="<?php echo $number2 ?>"/>
<input type="hidden" name="content" value="search"/>
<input type="hidden" name="id" value="<?php echo $id?>"/>
<div class="center"><input type="submit" name="submit" value="Send Message"/></div>

</div>
</form>
</div>

<?php require('bottom.php'); ?>
