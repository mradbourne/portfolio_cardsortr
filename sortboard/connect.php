<?php

$db_host		= 'internal-db.s95910.gridserver.com';
$db_user		= 'db95910_cardsort';
$db_pass		= 'mattgibson';
$db_database	= 'db95910_cardsortr';

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
/* mysql_query("SET names UTF8"); */

?>