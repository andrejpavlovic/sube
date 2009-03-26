<?

function process_form() {
  global $db, $cid, $cnum;
  global $cat, $title, $desc, $cw_isbn, $price, $email, $pass, $term, $year, $vacancies;
  $time = time();
  $pass = sha1($pass);

  $query = 'SELECT uid FROM '._CW_TABLE_USERS." WHERE email = '%s' LIMIT 1";

  $db->query( $db->safesql($query, array($email)) );

  if ($db->num_rows() > 0) {
    $row = $db->fetch_array();
    $uid = $row['uid'];
  } else {
    $query = 'INSERT INTO '._CW_TABLE_USERS."(email, password) VALUES ('$email', '$pass')";
    $db->query($query);
    $uid = $db->insert_id();
  }

  // insert statement to be used for the new listing
  $query_string = 'INSERT INTO '._CW_TABLE.' (%l) VALUES (%q)';

  // columns and values to be inserted with the new record
  $columns = array('time', 'category', 'description', 'price', 'uid');
  $values = array($time, $cat, $desc, $price, $uid);

  // message that will be sent through email
  $message_info = 'Category: '.$GLOBALS['arrstrCW'][$cat]."\r\n";

  switch ($cat) {
    case _CW_BOOK:
      $message_info .= 'Title: '.$title."\r\n"
                     . 'ISBN: '.$cw_isbn."\r\n";
      array_push($columns, 'title', 'isbn');
      array_push($values, $title, $cw_isbn);
      break;
    case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
      $message_info .= 'Term: '.$term."\r\n"
                     . 'Year: '.$year."\r\n";
      array_push($columns, 'term', 'year');
      array_push($values, $term, $year);
      break;
    case _CW_OTHER:
      $message_info .= 'Title: '.$title."\r\n";
      array_push($columns, 'title');
      array_push($values, $title);
      break;
    case _HOUSING:      
      $message_info .= 'Address: '.$title."\r\n";
      $message_info .= 'Vacancies: '.$vacancies."\r\n";
      $message_info .= 'Available: '.$term.' '. $year . "\r\n";
      array_push($columns, 'title');        array_push($values, $title); // address field
      array_push($columns, 'vacancies');    array_push($values, $vacancies);      
      array_push($columns, 'term', 'year'); array_push($values, $term, $year);
      
      break;
    default:
      header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?content=post');
      break;
  }

  $message_info .= 'Description: '.html_entity_decode($desc)."\r\n"
                 . 'Price: $'.$price."\r\n";

	/* var_dump($columns); exit(); */
                 
  // insert new record
  $db->query( $db->safesql($query_string, array($columns, $values)) );

  // get the new posting id
  $listid = $db->insert_id();

  // insert the courses into a separate table
  for ($i=0; $i<3; $i++) {
    if ( !empty($cid[$i]) ) {
      $course_query_string = 'INSERT INTO '._CW_TABLE_COURSES.' (listid, course, number) VALUES (%q)';
      $course_values = array($listid, $cid[$i], $cnum[$i]);
      $db->query( $db->safesql($course_query_string, array($course_values)) );
    }
  }

  // compose email
  $subject = 'Listing posted';
  $message = 'Your listing has been added to UWSUBE. The posting will remain on our site for a period of '._POSTING_PERIOD.' months. After this period, you will be contacted again to confirm if you want your posting to remain for another '._POSTING_PERIOD." months. Below is some of the information regarding your posting.\r\n\r\n$message_info\r\nYour feedback is important. Please do not hesitate to contact us should you have any questions or concerns.\r\n\r\nKind Regards,\r\nUWSUBE Team\r\ninfo at uwsube dot com";

  require_once('include/mailer.php');
  $mail = new mailer($email, $subject, $message);
  $mail->setFrom('UWSUBE.com <help@uwsube.com>');
  
  // send email
  if (!$mail->send()) {
    $error = new mailer(_ERROR_EMAIL, 'Error with sending email', 'The following email to '.$email.' was not sent successfully.\r\n\r\n\r\n'.$message);
    $error->send();
  }

  header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?content=post&type=done');
  exit();
}


?>