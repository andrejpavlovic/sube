<?

function validate_form($val_pass = true) {
  global $cid, $cnum;
  global $title, $desc, $cw_isbn, $price, $email, $pass, $term, $year, $vacancies;
  global $db;

  // protect our html code from user input
  $title = htmlspecialchars(stripslashes($title));
  $cw_isbn = htmlspecialchars(stripslashes($cw_isbn));
  $price = htmlspecialchars(stripslashes($price));
  $email = htmlspecialchars(stripslashes($email));
  $term = htmlspecialchars(stripslashes($term));
  $year = htmlspecialchars(stripslashes($year));
  $desc = htmlspecialchars(stripslashes($desc));
  $vacancies = intval($vacancies);

  
  // assuming that there is always the same number of courses and numbers
  for ($i=0; $i<count($cid); $i++) {
    $cid[$i] = htmlspecialchars(stripslashes($cid[$i]));
    $cnum[$i] = htmlspecialchars(stripslashes($cnum[$i]));
  }

  switch ($_POST['cat']) {
    case _CW_BOOK:
      // check if any entries are empty
      if (isEmptyTrim($cw_isbn)) return "You must enter an ISBN.";
      if (isEmptyTrim($title)) return "You must enter the title.";

      // check if ISBN is valid using the ISBNvalidator class
      require('include/isbntest.class.php');
      $validator = new isbntest();
      if ($validator->isISBN($cw_isbn)) $cw_isbn = $validator->isbn; else return "You have entered an invalid ISBN. Please check that you have entered it correctly.";
      unset($validator);

      $error = validate_courses(3);
      if (!empty($error)) return $error;

      break;

    case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
      // check if any entries are empty
      if (isEmptyTrim($term)) return "You must choose a term.";
      if (isEmptyTrim($year)) return "You must choose a year.";

      // check term in array
      if (!in_array($term, $GLOBALS['arrstrTERM'])) return "You've somehow chosen an invalid term";

      // check year in array
      if (!in_array($year, $GLOBALS['arrstrYEAR'])) return "You've somehow chosen an invalid term";

      $error = validate_courses(3);
      if (!empty($error)) return $error;

      break;
    case _CW_OTHER:
      // check if any entries are empty
      if (isEmptyTrim($title)) return "You must enter the title.";

      $error = validate_courses(3);
      if (!empty($error)) return $error;

      break;
    case _HOUSING:
      if (isEmptyTrim($title)) return "You must enter the address.";   
      if ($vacancies <= 0) return "You must specify the number of vacancies.";
      if (isEmptyTrim($term) || isEmptyTrim($year)) return "You must specify the term it's available.";      
      if (!empty($error)) return $error;
    
      break;
    default:
      header('location:http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?content=post');
      break;
  }

  // check price
  if ($temp = validate_price($price)) $price = $temp; else return "The price you have entered is invalid.";

  if ($val_pass) {
    // check email
    if (!validate_email($email)) return "You have entered an invalid email.";

    // check if this email has been used previously, the password corresponds to it
    $db->query('SELECT password FROM '._CW_TABLE_USERS." WHERE email = '$email' LIMIT 1");
    if ($db->num_rows() > 0) {
      $row = $db->fetch_array();
      if ( 0 != strcmp($row[password],sha1($pass)) ) {
        return 'The password you have entered does not correspond to this email account. If you have forgotten your password click <a href="'.$_SERVER[PHP_SELF].'?content=my&amp;type=pass">here</a> to get a new password sent to you.';
      }
    } else {
      // validate password syntax
      $error = validate_pass($pass);
      if (!empty($error)) return $error;
    }
  }
}

function isEmptyTrim(&$string) {
  $string = trim($string);
  if (0 == strcmp($string, '')) return true;
  return false;
}

// Check that password is not empty, has no spaces at beginning or end, 4-10 chars in length
function validate_pass($pass) {
  if (!preg_match('/^[0-9a-z]{4,10}$/i', $pass))
    return _INVALID_PASSWORD;
  else
    return '';
}

function validate_email($email) {
 // Create the syntactical validation regular expression
 $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})?$";

 // Presume that the email is invalid
 $valid = false;

 // Validate the syntax
 if (eregi($regexp, $email)) {
   //list($username,$domaintld) = split("@",$email);
   // Validate the domain
   //if (getmxrr($domaintld,$mxrecords))
   $valid = true;
 }

 return $valid;
}

function validate_price($price) {
  if (preg_match('/^(\$)?(\d{1,3}(\,\d{3})*|(\d+))(\.\d{1,2})?$/', $price)) {
    return (float)eregi_replace("[^0-9.]", null, $price);
  } else {
    return false;
  }
}

function validate_courses($num) {
  global $cid, $cnum;

  for ($i=0; $i<=$num; $i++) {
    // check course ids - should be in the array
    if (in_array($cid[$i], $GLOBALS['arrstrCOURSE_ID'])) {
      // check course number
      if (!isEmptyTrim($cid[$i])) {
        if (!preg_match('/^\d\d\d$/', $cnum[$i]))
          return "Invalid course number. Should be 3 digits.";                
      } else {
        $cnum[$i] = '';
      } 
    } else {
      return "You've somehow chosen an invalid course id.";
    }
  }

  // order the array of courses so that there are no empty entries
  $array_cid = array();
  $array_cnum = array();

  for ($i=0; $i<$num; $i++) {
    if (0 != strcmp($cnum[$i],'')) {
      array_push($array_cid, $cid[$i]);
      array_push($array_cnum, $cnum[$i]);
    }
  }
  $cid = $array_cid;
  $cnum = $array_cnum;

  if (isEmptyTrim($cid[0]) || isEmptyTrim($cnum[0]))
    return "You must select at least one course name and course number.";

  return '';
}

?>