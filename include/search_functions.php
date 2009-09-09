<?php
require_once 'include/global_data.php';

function format_results ($query_results) {
global $db;
require_once 'include/table.php';
$table = new TableResult();

  while ($row2 = $db->fetch_array($query_results)) {

    $db->query('SELECT title, category, description, isbn, price, term, year FROM '._CW_TABLE." WHERE listid = $row2[listid]");
    $row = $db->fetch_array();

    $db->query("SELECT course, number FROM "._CW_TABLE_COURSES." WHERE listid = $row2[listid] ORDER BY course");
	  while ($row3 = $db->fetch_array()) {
      if (!$row3[number]) $row3[number] = '';
      $row[courses] .= "$row3[course]&nbsp;$row3[number]<br />";
    }

    $row[listid] = $row2[listid];

    $table->addRow($row);
  }

return $table;
}

?>
