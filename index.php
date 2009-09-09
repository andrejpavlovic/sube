<?php
// If the web browser supports xhtml, then update the header
/*
if ( stristr($_SERVER[HTTP_ACCEPT],"application/xhtml+xml") )
  header("Content-type: application/xhtml+xml");
else
  header("Content-type: text/html");
*/


// For server side buffering.
// This is to avoid modifying headers after being already sent.
ob_start();

switch($_REQUEST['content']) {
  case 'search':
    require('content/search.php');
    break;
  case 'about':
    require('content/about.php');
    break;
  case 'contact':
    require('content/contact.php');
    break;
  case 'join':
    require('content/join.php');
    break;
  case 'my':
    require('content/my.php');
    break;
  case 'news':
    require('content/news.php');
    break;
  case 'post':
    require('content/post.php');
    break;
  case 'support':
    require('content/support.php');
    break;
  default:
    require('content/news.php');
    break;
}


// For server side buffering.
// This is to avoid modifying headers after being already sent.
ob_end_flush();
?>
