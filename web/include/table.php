<?php

function createTable($array, $css_class = 'mypost') {

if (!is_array($array))
  die('Not an array');

//flip the array...
$temp = array();
for ($j=0; $j<count($array[0]); $j++) {
  ${'array$i'} = array();
  for ($i=0; $i<count($array); $i++) {
    array_push(${'array$i'}, $array[$i][$j]);
  }
  array_push($temp, ${'array$i'});
}
$array = $temp;

//begin table
$htmltable = '<table class="'.$css_class.'">';

// make sure to start at first row
reset($array);

// first row is table header
$i = true;

foreach($array as $row) {
  if ($i) {
    $thead_s = '<thead>';
    $thead_e = '</thead>';
    $i = false;
  } else {
    $thead_s = '';
    $thead_e = '';
  }
  //insert row
  $htmltable = "$htmltable  $thead_s<tr>\n";

  // make sure to start at first entry
  reset($row);
  foreach($row as $entry) {
    //insert entry in row
    $htmltable = "$htmltable    <td>$entry</td>\n";
    }

  //end row
  $htmltable = "$htmltable  </tr>$thead_e\n";
  }

//end table
$htmltable = "$htmltable</table>\n";

//return HTML table
return $htmltable;
}

class TableResult {
  var $table;

  function TableResult() {
    $this->table = array('cat' => array('Type'), 'price' => array('Price'), 'courses' => array('Courses'), 'desc' => array('Description'), 'contact' => array('Action'));
  }

  function addRow($row) {
    global $arrstrCW;
    $this->table[courses][] = substr($row[courses], 0, strlen($row[courses])-6); // remove extra comma and <br />
    //$this->table[listid][] = $row[listid];
    $this->table[price][] = '$' . number_format($row[price], 2);
    $this->table[contact][] = '<div class="mybutton"><a href="'.$_SERVER[PHP_SELF]
      .'?content=search&amp;id='.$row[listid].'">Contact Seller<br />#'.$row[listid].'</a></div>';
  
    $row[description] = wordwrap($row[description], 28, ' ', 1);
  
    switch ($row['category']) {
      case _CW_BOOK:
        $this->table[desc][] = '<h3>'.$row[title].'</h3><p class="desc">'.$row[description].'</p><p class="details">ISBN: '.htmlspecialchars($row[isbn]).'</p>';
        
        $this->table[cat][] = (!_AMAZON_ENABLED)
          ? htmlspecialchars('Books')
          : sprintf('<img src="book_cover.php?isbn=%s" alt="Books" />', htmlspecialchars($row['isbn']));
        break;
      case _CW_COURSE_NOTES: case _CW_HAND_NOTES:
        $this->table[desc][] = '<h3><span style="font-size:10px">from</span> '.htmlspecialchars($row[term]).' '.htmlspecialchars($row[year]).'</h3><p class="desc">'.$row[description].'</p>';
        $this->table[cat][] = htmlspecialchars($arrstrCW[$row['category']]);
        break;
      case _CW_OTHER:
        $this->table[desc][] = '<h3>'.$row[title].'</h3><p class="desc">'.$row[description].'</p>';
        $this->table[cat][] = htmlspecialchars($arrstrCW[$row['category']]);
        break;
      default:
        break;
    }    
  }

  function printTable() {
    $t = $this->table;
    return createTable(array_values($t), 'search');
  }
}

?>