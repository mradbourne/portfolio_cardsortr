<?php
session_start();
require "../connect.php";


// Checking whether all input variables are in place:
if (!is_numeric($_POST['cid'])) die("0");

// Escaping the input data:
$cid = (int)$_POST['cid'];



mysql_query('DELETE FROM cards WHERE id="' . $cid . '"');

if (mysql_affected_rows() == 1) {
	// Return the id of the deleted row:
	echo $cid;
}
else echo '0';



?>