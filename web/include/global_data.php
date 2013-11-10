<?php

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings.php';

// List of course ids
$arrstrCOURSE_ID = array('', 'ACC', 'ACTSC', 'AFM', 'AMATH', 'ANTH', 'ARCH', 'ARCHL', 'ARTS', 'BET', 'BIOL', 'BUS', 'CDNST', 'CEDEV', 'CHE', 'CHEM', 'CHINA', 'CIVE', 'CLAS', 'CM', 'CO', 'COGSCI', 'COMM', 'COMST', 'COOP', 'CROAT', 'CS', 'DAC', 'DANCE', 'DM', 'DRAMA', 'DUTCH', 'EARTH', 'EASIA', 'ECE', 'ECON', 'ELPE', 'ENGL', 'ENVE', 'ENVS', 'ERS', 'ESL', 'FILM', 'FINE', 'FR', 'FRCS', 'GENE', 'GEOE', 'GEOG', 'GER', 'GERON', 'GRK', 'GS', 'HIST', 'HLTH', 'HRM', 'HSG', 'HUMSC', 'INTEG', 'INTTS', 'IS', 'ISS', 'ITAL', 'ITALST', 'JAPAN', 'JS', 'KIN', 'KOREA', 'KPE', 'LAT', 'LED', 'LS', 'LSC', 'MATH', 'ME', 'MISC', 'MSCI', 'MTE', 'MTHEL', 'MUSIC', 'NATST', 'NE', 'NES', 'OPTOM', 'PACS', 'PDENG', 'PHIL', 'PHYS', 'PLAN', 'PMATH', 'POLSH', 'PORT', 'PSCI', 'PSYCH', 'REC', 'RELC', 'RS', 'RUSS', 'SCI', 'SE', 'SMF', 'SOC', 'SOCWK', 'SOCWL', 'SPAN', 'SPCOM', 'SPD', 'STAT', 'STV', 'SWREN', 'SYDE', 'TAX', 'TOUR', 'TPM', 'TPPE', 'UKRAN', 'UU', 'WHMIS', 'WKRPT', 'WS');

/* should only be in the postings section */
$arrstrYEAR = array(''); $arrstrYEARfuture = array(''); // array of years
$current_year = date('Y');
for($i = 0; $i<6; $i++) {
	array_push($arrstrYEAR, $current_year - $i);
	array_push($arrstrYEARfuture, $current_year + $i);
}
$arrstrTERM = array('', 'Spring', 'Fall', 'Winter'); // array of terms

// Define constants for courseware
define('_CW_OTHER', 0);
define('_CW_BOOK', 1);
define('_CW_COURSE_NOTES', 2);
define('_CW_HAND_NOTES', 3);
define('_HOUSING', 5);

// Define courseware names
$arrstrCW = array(
	_CW_OTHER => 'Other Courseware',
	_CW_BOOK => 'Books',
	_CW_COURSE_NOTES => 'Course Notes',
	_CW_HAND_NOTES => 'Handwritten Notes',
	_HOUSING => 'Housing'
);

// Define database connection object
require_once('include/db.php');
$db = new SQLLayer(_DB_HOST, _DB_USER, _DB_PASS, _DB_NAME);

// Define courseware tables
define('_CW_TABLE', 'courseware'); // also storing housing listings here
define('_CW_TABLE_COURSES', 'cw_courses');
define('_CW_TABLE_USERS', 'users');
define('_CW_TABLE_CATEGORY', 'cw_category');
define('_CW_TABLE_CONTACT_SELLER', 'contact_seller');


// Define successful return value for functions
define('_SUCCESS', 1); // success
define('_SUCCESS_EMAIL', 2); // email sent
define('_SUCCESS_LOGOUT', 3); // logged out

// Define a list of global user errors
define('_ERR_EMAIL', -10); // bad email
define('_ERR_PASS', -11); // bad password
define('_ERR_NOT_MATCH', -12); // does not match
define('_ERR_ILL_PASS', -13); // illegal password
define('_ERR_MESSAGE_EMPTY', -14); // empty message
define('_ERR_EMAIL_NOT_SENT', -15); // empty message
define('_ERR_INVALID_SUM', -16); // empty message

// Define a list of script and database errors
define('_FAIL_UPDATE', -30); // failed to update database
define('_FAIL_SELECT', -31); // failed to select recordset
define('_FAIL_INSERT', -32); // failed to insert record

// Define content
define('_INVALID_PASSWORD', 'You must enter a password between 4 and 10 characters in length.');
define('_POSTING_PERIOD', 6);

// Define constants for search results
define('_LIMIT_PER_PAGE', 30); // max number of search results per page

// Unique ID prefix
define('_UNIQUE_ID_PREFIX', 'a'); 

//$label - heading for the entry
//$input - should be an <input> tag with attributes
function formEntry($label, $input, $id = '', $style='') {
  if (preg_match('/<[a-z0-9]+[^>]*?id="([a-z0-9]+)"/i', $input, $match)) $for_id = $match[1];
  
  $return = '<div class="row"';

  if (!empty($id)) $return .= ' id="'.$id.'"';
  if (!empty($style)) $return .= ' style="'.$style.'"';
  $return .= '>';

  $return .= '<label';
  if (isset($for_id)) $return .= ' for="'.$for_id.'"';
  $return .= '>'.$label.'</label>';
  $return .= '<span class="formf">'.$input.'</span>';
  $return .= '<div class="clear"></div>';
  $return .= '</div>';

  return $return;
}

//$array - list of choices
//$select_open - the <select> tag with attributes
function formSelect($array, $select_open, $selected = '')  {
  ASSERT(is_array($array));
  $selected = "$selected";
  if (!in_array($selected, $array)) $selected = '';
  
  $return = $select_open;

  foreach ($array as $item) {
    $return .= '<option value="'.$item.'"';
    if (0 == strcmp($item,$selected)) $return .= ' selected="selected"';
    $return .= '>'.$item.'</option>';
  }

  $return .= '</select>';

  return $return;
}
