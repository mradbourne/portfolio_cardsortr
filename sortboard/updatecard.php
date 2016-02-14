<?php

require "connect.php";

// Validating the input data:
if(!is_numeric($_GET['id']) || !is_numeric($_GET['x']) || !is_numeric($_GET['y']) || !is_numeric($_GET['z']))
die("0");

// Escaping:
$id = (int)$_GET['id'];
$x = (int)$_GET['x'];
$y = (int)$_GET['y'];
$z = (int)$_GET['z'];

// Saving the position and z-index of the note:
mysql_query("UPDATE cards SET xyz='".$x."-".$y."-".$z."' WHERE id=".$id);

//Save project data
$project_id = 1;
$project_timezone = 7;
mysql_query("UPDATE projects SET updated=DATE_ADD(NOW(), INTERVAL". $project_timezone ."HOUR) WHERE id=".$project_id);

echo "1";
?>