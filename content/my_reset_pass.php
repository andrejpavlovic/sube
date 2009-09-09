<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'mailer.php';

function get_random_id() {
  //set the random id length 
  $random_id_length = 8; 

  //generate a random id encrypt it and store it in $rnd_id 
  $rnd_id = crypt(uniqid(rand(),1)); 

  //to remove any slashes that might have come 
  $rnd_id = strip_tags(stripslashes($rnd_id)); 

  //Removing any . or / and reversing the string 
  $rnd_id = str_replace(".","",$rnd_id); 
  $rnd_id = strrev(str_replace("/","",$rnd_id)); 

  //finally I take the first 10 characters from the $rnd_id 
  return substr($rnd_id,0,$random_id_length); 
}

if (isset($_POST['email'])) {
  $email = htmlspecialchars(stripslashes($_POST['email']));

  setcookie('user_email', $email, time()+3600);

  require 'content/post_validate.php';

  if (!validate_email($email))
    raise_error(_ERR_EMAIL, 'pass');
  
  $new_pass = get_random_id();

  $db->query('UPDATE '._CW_TABLE_USERS." SET password = '".sha1($new_pass)."' WHERE email = '$email'");

  if ($db->affected_rows() < 1)
    raise_error(_ERR_EMAIL, 'pass');
      
  // compose email
  $subject = 'Password reset';
  $body = "Your new password is displayed below. You can change this password at any time by logging into My UWSUBE.\r\n\r\nEmail: $email\r\nPassword: $new_pass\r\n\r\nYour feedback is important. Please do not hesitate to contact us should you have any questions or concerns.\r\n\r\nKind Regards,\r\nUWSUBE Team\r\n" . _EMAIL_SUPPORT_ADDRESS;

	//Create the message
	$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array(_EMAIL_FROM_ADDRESS => _EMAIL_FROM_NAME))
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
			->setBody(sprintf("The following email to %s regarding password reset was not sent successfully.\r\n\r\n\r\n%s", $email, $body))
		;
		$mailer->send($message);
	}

  raise_error(_SUCCESS);
}
?>

<p>Please enter your email address below. A new password will be sent to this email account. You can change your new password after logging into My UWSUBE.</p>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
<div class="formcenter">

<?=formEntry('Email:', '<input type="text" name="email" style="width:140px;" value="'.$user_email.'" />'); ?>
<br />
<input type="hidden" name="content" value="my"/>
<input type="hidden" name="type" value="pass"/>
<div class="center"><input type="submit" value="Send me a new password"/></div>

</div>
</form>
<br />

<? switch ($_GET['error']) {
  case _ERR_EMAIL:
    echo '<p class="msg"><span class="red">Invalid email.</span></p>';
    break;
  default:
    break;
} ?>
