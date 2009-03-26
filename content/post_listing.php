<form action="<?=$_SERVER['PHP_SELF']?>" method="post">

<div class="post_entry" style="padding-top:7px">&nbsp;
<?

if (!isset($content_form)) $content_form = 'post';
if (!isset($type_form)) $type_form = 'add';
if (!isset($submit_form)) $submit_form = 'Submit Posting';
if (!isset($val_pass)) $val_pass = true;

// number of course options
$course_table = 0;
$pricelabel = "Price:";
$sidebar = false;

// if user has already submitted an entry reuse his email address
if (isset($_COOKIE['user_email']) && !isset($form_error)) $email = $_COOKIE['user_email'];

switch ($cat) {
  case _CW_BOOK:
    $sidebar = course_table(3);
    echo formEntry('<span class="red">*</span>Title:', '<input type="text" name="title" style="width:105px;" value="'.$title.'" />');
    echo formEntry('<span class="red">*</span>ISBN:', '<input type="text" name="cw_isbn" maxlength="13" style="width:105px;" value="'.$cw_isbn.'" />');
    break;

  case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
    $sidebar = course_table(3);
    echo formEntry('<span class="red">*</span>Term:', formSelect($arrstrTERM, '<select name="term" style="width:110px;">', $term));
    echo formEntry('<span class="red">*</span>Year:', formSelect($arrstrYEAR, '<select name="year" style="width:110px;">', $year));
    break;

  case _CW_OTHER:
    $sidebar = course_table(3);
    echo formEntry('<span class="red">*</span>Title:', '<input type="text" name="title" style="width:105px;" value="'.$title.'" />');
    break;
    
  case _HOUSING:
 	$sidebar = '<h4 style="padding-bottom: 6px;">Available Beginning</h4>'.formEntry('<span class="red">*</span>Term:', formSelect($arrstrTERM, '<select name="term" style="width:110px;">', $term))
    . formEntry('<span class="red">*</span>Year:', formSelect($arrstrYEARfuture, '<select name="year" style="width:110px;">', $year));	
     
  	$pricelabel = "Rent:";  	
	echo formEntry('<span class="red">*</span>Address:', '<input type="text" name="title" style="width:105px;" value="'.$title.'" />');
	echo formEntry('<span class="red">*</span>Vacancies:', '<input type="text" name="vacancies" maxlength="15" style="width:105px;" value="'.$vacancies.'" />');
	break;

  default:
    header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?content=$content_form");
    break;
}

if (is_numeric($price)) $price = number_format($price, 2);

?>
  <?=formEntry('<span class="red">*</span>'.$pricelabel, '<input type="text" name="price" maxlength="15" style="width:105px;" value="'.$price.'" />'); ?>

</div>

<?php
function course_table($courses) {
	global $arrstrCOURSE_ID, $cid, $cnum;
	ob_start();
	?>
  <table summary="Specify the courses and course numbers to which this posting applies" style="text-align:center;margin: 0 auto">
  <thead><tr><td></td><td>Course</td><td>Number</td></tr></thead>
  <tbody>
  <? for ($i = 1; $i<=$courses; $i++) {
    $for_id1 = uniqid(_UNIQUE_ID_PREFIX); $for_id2 = uniqid(_UNIQUE_ID_PREFIX);
    ?>
    <tr>
    <td><? if($i==1){ ?><span class="red">*</span><?}?></td>
    <td><?=formSelect($arrstrCOURSE_ID, '<div class="hidden"><label for="'.$for_id1.'">Select a course id</label></div><select id="'.$for_id1.'" name="cid[]" style="width:80px;">', $cid[$i-1]); ?></td>
    <td><div class="hidden"><label for="<?=$for_id2?>">Input the course number</label></div>
    <input id="<?=$for_id2?>" type="text" name="cnum[]" maxlength="3" style="width:30px;" value="<?=$cnum[$i-1]?>" /></td>
    </tr>
  <? } ?>
  </tbody></table>
  
  <?php
  return ob_get_clean();
}

 if ($sidebar) { ?>
<div class="post_entry" style="text-align:center;">
	<?php echo $sidebar; ?>
</div>
<? } ?>



<div class="post_entry_desc">
  <?=formEntry('Description:', '<textarea name="desc" cols="35" rows="8">'.$desc.'</textarea>'); ?>
</div>

<div class="post_entry">

  <? if ($val_pass) {
   echo formEntry('<span class="red">*</span>Email:', '<input type="text" name="email" style="width:140px;" value="'.$email.'" />');
   echo formEntry('<span class="red">*</span>Password:', '<input type="password" name="pass" style="width:140px;" />');
  } ?>

  <br />
  <div class="center"><input type="submit" value="<?=$submit_form?>" style="margin:0 auto;" /></div>
  <input type="hidden" name="cat" value="<?=$cat?>" />
  <input type="hidden" name="content" value="<?=$content_form?>" />
  <input type="hidden" name="type" value="<?=$type_form?>" />
  <?=$action_form?>
</div>

<div class="post_entry" style="padding:0px 10px 0px 10px;text-align:center;width:45%">
<? if (isset($form_error)) { // print error from form validation ?>
<span class="red"><?=$form_error?></span>
<? } ?>
</div>

</form>