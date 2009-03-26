<?
require('include/global_data.php');
$html_title = 'Submit a Posting';
if (isset($cat)) $html_title .= " - $arrstrCW[$cat]";
require('top.php');
?>

<div id="content" style="background-image:url(images/bg_post.jpg);">
<h1>Submit a Posting</h1>

<?if (isset($cat)) { ?>
  <h2 class="sub"><?=$arrstrCW[$cat]?></h2><?
} ?>

<? switch ($_REQUEST['type']) {
  case 'add':
    require('content/post_validate.php');
    if ($form_error = validate_form()) {
      require('content/post_listing.php');
    } else {
      setcookie('user_email', $email, time()+3600);
      // could add a confirmation page in the future for user to doublecheck before submitting
      require('content/post_listing_add.php');
      process_form();
    }
    break;
    
        
  case 'done':
    echo '<p class="msg">Your listing has been posted and an email has been sent to you with information about your listing. You may review or update all your listings by logging into <a href="'.$_SERVER['PHP_SELF'].'?content=my">My UWSUBE</a>.</p>';
  // Fall-through here.
    
  default: 
    if (isset($_GET['cat']))
    {
      require('content/post_listing.php');
    }
    else 
    {
  ?>
    <p>Choose which one of the following items you would like to advertise on the website:</p>
    Course Related
    <ul>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_BOOK?>"><?=$arrstrCW[_CW_BOOK]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_COURSE_NOTES?>"><?=$arrstrCW[_CW_COURSE_NOTES]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_HAND_NOTES?>"><?=$arrstrCW[_CW_HAND_NOTES]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_OTHER?>"><?=$arrstrCW[_CW_OTHER]?></a></li>
    </ul>
    <p><a href="index.php?content=post&amp;cat=<?=_HOUSING?>"><?=$arrstrCW[_HOUSING]?></a></p>
  <? } break;
} ?>

</div>

<? require('bottom.php'); ?>
