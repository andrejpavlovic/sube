<?
function raise_error($err = '', $type = '') {
  if (!empty($type))
    $type = "&type=$type";

  if (!empty($err))
    $err = "&error=$err";

  header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?content=my$type$err");
  die();
}

require('include/global_data.php');
$html_title = 'My UWSUBE';
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_my.jpg);">
<h1>My UWSUBE</h1>

<?
switch ($_REQUEST['type']) {
  case 'login':
    $email = htmlspecialchars(stripslashes($_POST[email]));
    $pass = htmlspecialchars(stripslashes($_POST[pass]));
    
    setcookie('user_email', $email, time()+3600);

    // for validating email syntax
    require 'content/post_validate.php';

    if (validate_email($email)) {
      // check the password
      $query = 'SELECT password, uid FROM '._CW_TABLE_USERS." WHERE email = '%s' LIMIT 1";
      $db->query( $db->safesql($query, array($email)) );

      if ($db->num_rows() <= 0)
        raise_error(_ERR_EMAIL);

      $row = $db->fetch_array();
      
      $pass = sha1($pass);
      if ( 0 == strcmp($pass,$row[password]) ) {
        setcookie('login', true, time()+86400);
        setcookie('login_email', $email, time()+86400);
        setcookie('login_uid', $row['uid'], time()+86400);
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
    setcookie('login', false, 0);
    setcookie('login_email', '', 0);
    setcookie('login_uid', '', 0);
    raise_error(_SUCCESS_LOGOUT);
    break;
  default:
    if ($_COOKIE['login']) {
      require('content/my_postings.php');
    } else {
      ?>
      <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
      <div class="formcenter" style="width:100%">
      <p>By logging in you can change your password, modify your existing postings, or remove your existing postings.</p>
      <br />
        <?=formEntry('Email:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="text" name="email" style="width:140px;" value="'.$user_email.'" />'); ?>
        <?=formEntry('Password:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="password" name="pass" style="width:140px;" /><br /><a href="'.$_SERVER['PHP_SELF'].'?content=my&amp;type=pass">Forgot your password</a>'); ?>
        <br />
        <div style="text-align:center"><input type="submit" value="Login" /></div>
      <input type="hidden" name="content" value="my"/>
      <input type="hidden" name="type" value="login"/>

      <br />
       
      <? switch ($_GET['error']) {
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
    <? }
    break;
} ?>

</div>

<? require('bottom.php'); ?>
