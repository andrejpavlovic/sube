<?

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
  $message = "Your new password is displayed below. You can change this password at any time by loging into My UWSUBE.\r\n\r\nEmail: $email\r\nPassword: $new_pass\r\n\r\nYour feedback is important. Please do not hesitate to contact us should you have any questions or concerns.\r\n\r\nKind Regards,\r\nUWSUBE Team\r\nhelp@uwsube.com";

  require_once 'include/mailer.php';
  $mail = new mailer($email, $subject, $message);
  $mail->setFrom('UWSUBE.com <help@uwsube.com>');
  
  // send email
  if (!$mail->send()) {
    $error = new mailer(_ERROR_EMAIL, 'Error with sending email', 'The following email to '.$email.' regarding password reset was not sent successfully.\r\n\r\n\r\n'.$message);
    $error->send();
  }

  raise_error(_SUCCESS);
}
?>

<p>Please enter your email address below. A new password will be sent to this email account. You can change your new password after loging into My UWSUBE.</p>

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
