<?
// If the web browser supports xhtml, then update the header
/*
if ( stristr($_SERVER[HTTP_ACCEPT],"application/xhtml+xml") )
  header("Content-type: application/xhtml+xml");
else
  header("Content-type: text/html");
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>UWSUBE - <?=$html_title?></title>
<?
if (!isset($html_meta_robots)) $html_meta_robots = 'index,follow';
if (!isset($html_meta_description)) $html_meta_description = 'UWSUBE is an online textbook and housing exchange for Univeristy of Waterloo students.';
if (!isset($html_meta_keywords)) $html_meta_keywords = 'books, course notes, UW, university, waterloo, students, exchange, buy, sell, listings, postings, UWSUBE, online, exchange, housing, courseware';
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="<?=$html_meta_robots?>" />
<meta name="description" content="<?=$html_meta_description?>" />
<meta name="keywords" content="<?=$html_meta_keywords?>" />

<link rel="shortcut icon" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="base.css" />
<script src="wch.js" type="text/javascript"></script>

</head>
<body<? if (isset($content)) echo ' id="'.$content.'"' // this little bit of code allows for page-specific styles ?>>

<div id="container">

<div id="top">

<div style="width:753px;height:71px">
<a href="index.php"><img alt="UWSUBE" src="images/banner_01.jpg" /></a>
</div>
<ul id="nav">
<li style="width:105px;height:37px"><a href="index.php?content=news"><img alt="News" src="images/banner_04.jpg" /></a></li>
<li style="width:128px;height:37px"><a href="index.php?content=my"><img alt="My UWSUBE" src="images/banner_05.jpg" /></a></li>
<li style="width:165px;height:37px" onmouseover="WCH.Apply('drop1')" onmouseout="WCH.Discard('drop1')"><a href="index.php?content=post"><img alt="Submit a Posting" src="images/banner_06.jpg" /></a>
		<ul style="width:165px" id='drop1'>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_BOOK?>"><?=$arrstrCW[_CW_BOOK]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_COURSE_NOTES?>"><?=$arrstrCW[_CW_COURSE_NOTES]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_HAND_NOTES?>"><?=$arrstrCW[_CW_HAND_NOTES]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_CW_OTHER?>"><?=$arrstrCW[_CW_OTHER]?></a></li>
      <li><a href="index.php?content=post&amp;cat=<?=_HOUSING?>"><?=$arrstrCW[_HOUSING]?></a></li>
		</ul>
</li>
<li style="width:107px;height:37px"><a href="index.php?content=join"><img alt="Join" src="images/banner_07.jpg" /></a></li>
<li style="width:128px;height:37px"><a href="index.php?content=about"><img alt="About Us" src="images/banner_08.jpg" /></a></li>
<li style="width:120px;height:37px"><a href="index.php?content=contact"><img alt="Contact" src="images/banner_09.jpg" /></a></li>
</ul>

</div>

<div id="divider">
  <div id="left">
  <? require("left.php"); ?>
  </div>
