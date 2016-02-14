<?php
session_start();
require "connect.php";

// Checking whether all input variables are in place:
if(!is_numeric($_POST['zindex']) || !isset($_POST['body']) || !isset($_POST['bg_color']))
die("0");

if(ini_get('magic_quotes_gpc'))
{
	// If magic_quotes setting is on, strip the leading slashes that are automatically added to the string:
	$_POST['body']=stripslashes($_POST['body']);
}

// Escaping the input data:

$project_id = mysql_real_escape_string($_SESSION['current_project']);
$creator = mysql_real_escape_string(strip_tags($_SESSION['myusername']));
$body = mysql_real_escape_string(strip_tags($_POST['body']));
$bg_color = mysql_real_escape_string(strip_tags($_POST['bg_color']));
$fg_color = mysql_real_escape_string(strip_tags($_POST['fg_color']));
$zindex = (int)$_POST['zindex'];


/* Inserting a new record in the notes DB: */
mysql_query('INSERT INTO cards (project_id,content,creator,background_color,foreground_color,xyz) VALUES ("'.$project_id.'","'.$body.'","'.$creator.'","'.$bg_color.'","'.$fg_color.'","0-0-'.$zindex.'")');

if(mysql_affected_rows($link)==1)
{
	// Return the id of the inserted row:
	echo mysql_insert_id($link);
}
else echo '0';

?>