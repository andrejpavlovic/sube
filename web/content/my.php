<?php
function raise_error($err = '', $type = '') {
  if (!empty($type))
    $type = "&type=$type";

  if (!empty($err))
    $err = "&error=$err";

  header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?content=my$type$err");
  die();
}

require('include/global_data.php');
$html_title = 'My Account';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_my.jpg);">
<h1>My Account</h1>

<?php
switch ($_REQUEST['type']) {
  case 'login':
    $email = htmlspecialchars(stripslashes($_POST[email]));
    $pass = htmlspecialchars(stripslashes($_POST[pass]));
    
    setcookie('user_email', $email, time()+3600);

    // for validating email syntax
    require 'content/post_validate.php';

    if (validate_email($email)) {
      // check the password
      $query = 'SELECT password, uid, email FROM '._CW_TABLE_USERS." WHERE email = '%s' LIMIT 1";
      $db->query( $db->safesql($query, array($email)) );

      if ($db->num_rows() <= 0)
        raise_error(_ERR_EMAIL);

      $row = $db->fetch_array();
      
      $pass = sha1($pass);
      if ( 0 == strcmp($pass,$row[password]) ) {
      	$_SESSION['login'] = true;
      	$_SESSION['login_email'] = $row['email'];
      	$_SESSION['login_uid'] = $row['uid'];
        require('content/my_postings.php');
      } else {
        raise_error(_ERR_PASS);
      }
    } else {
      raise_error(_ERR_EMAIL);
    }
    break;
  case 'home':
    require('content/my_postings.php');
    break;
  case 'pass':
    require('content/my_reset_pass.php');
    break;
  case 'chg':
    require('content/my_chg_pass.php');
    break;
  case 'logout':
  	session_destroy();
    raise_error(_SUCCESS_LOGOUT);
    break;
  default:
    if ($_SESSION['login']) {
      require('content/my_postings.php');
    } else {
      ?>
      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <div class="formcenter" style="width:100%">
      <p>By logging in you can change your password, modify your existing postings, or remove your existing postings.</p>
      <br />
        <?php echo formEntry('Email:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="text" name="email" style="width:140px;" value="'.$user_email.'" />'); ?>
        <?php echo formEntry('Password:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="password" name="pass" style="width:140px;" /><br /><a href="'.$_SERVER['PHP_SELF'].'?content=my&amp;type=pass">Forgot your password</a>'); ?>
        <br />
        <div style="text-align:center"><input type="submit" value="Login" /></div>
      <input type="hidden" name="content" value="my"/>
      <input type="hidden" name="type" value="login"/>

      <br />
       
      <?php switch ($_GET['error']) {
        case _ERR_EMAIL:
          $msg = '<p class="msg"><span class="red">Invalid email.</span></p>';
          break;
        case _ERR_PASS:
          $msg = '<p class="msg"><span class="red">Incorrect password.</span></p>';
          break;
        case _SUCCESS_EMAIL:
          $msg = '<p class="msg">The email with the new password has been sent to you sucessfully.</p>';
          break;
        case _SUCCESS_LOGOUT:
          $msg = '<p class="msg">You have logged out sucessfully.</p>';
          break;
        default:
          break;
      } 
      echo $msg;
      
      ?>
      
      </div>
      </form>
    <?php }
    break;
} ?>

</div>

<?php require('bottom.php'); ?>
