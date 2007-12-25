<?
if ($_POST['content'] === 'search') {
  $courseid = htmlspecialchars(stripslashes(trim($_POST['courseid'])));
  $number = htmlspecialchars(stripslashes(trim($_POST['number'])));
  $isbn = htmlspecialchars(stripslashes(trim($_POST['isbn'])));
  $keywords = htmlspecialchars(stripslashes(trim($_POST['keywords'])));
}
?>
<form id="search_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">

<h1 class="menu_title">Quick Search</h1>

<div class="menu_sub">Courseware</div>
<div style="text-align:center;padding-bottom:10px">
(<a href="<?=$_SERVER['PHP_SELF']?>?content=search&amp;type=browse">Browse All Postings</a>)
</div>
<div style="padding-bottom:10px;text-align:center;">Search by any field(s):</div>

<div id="left_courseware">

  <?=formEntry('Keywords:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="text" name="keywords" style="width:105px;" value="'.$keywords.'" />') ?>
  <?=formEntry('ISBN:', '<input id="'.uniqid(_UNIQUE_ID_PREFIX).'" type="text" name="isbn" maxlength="13" style="width:105px;" value="'.$isbn.'" />') ?>

</div>

<div style="text-align:center;padding-top:3px;padding-bottom:20px">
<input type="hidden" name="content" value="search"/>
<input type="submit" value="Search" /><br />
</div>
</form>

<div class="menu_sub">Off Campus Housing</div>

<div style="text-align:center">
(<a href="<?=$_SERVER['PHP_SELF']?>?content=search&amp;type=browse&amp;cat=<?=_HOUSING ?>">Browse All Postings</a>)
</div>

<br />
<br />

<div style="text-align:center">
<script type="text/javascript"><!--
google_ad_client = "pub-8161022511847042";
//160x600, created 11/14/07
google_ad_slot = "8319243890";
google_ad_width = 160;
google_ad_height = 600;
//--></script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
