<?php
session_start();
require "../connect.php";

// Checking whether all input variables are in place:
if(!is_numeric($_POST['cid']) || !isset($_POST['body']) || !isset($_POST['bg_color']))
die("0");

if(ini_get('magic_quotes_gpc'))
{
	// If magic_quotes setting is on, strip the leading slashes that are automatically added to the string:
	$_POST['body']=stripslashes($_POST['body']);
}

// Escaping the input data:

$cid = (int)$_POST['cid'];
$body = mysql_real_escape_string(strip_tags($_POST['body']));
$bg_color = mysql_real_escape_string(strip_tags($_POST['bg_color']));
$fg_color = mysql_real_escape_string(strip_tags($_POST['fg_color']));

/* Inserting a new record in the notes DB: */
mysql_query('UPDATE cards SET content="' . $body . '", background_color="' . $bg_color . '", foreground_color="' . $fg_color . '" WHERE id="' . $cid . '";');

if(mysql_affected_rows($link)==1)
{
	// Return the id of the inserted row:
	echo $cid;
}
else echo '0';

?>