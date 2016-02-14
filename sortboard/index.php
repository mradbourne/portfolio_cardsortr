<?php 
session_start();
if (!isset($_SESSION["myusername"])) {
	header("location:../index.php?msgid=2");
}

require "connect.php";
require "functions.php";

//Populate projects
$myprojects_query = mysql_query("SELECT projects.project_id, projects.updated
FROM project_access 
INNER JOIN projects
ON project_access.project_id=projects.project_id 
WHERE project_access.member_id='" .  mysql_real_escape_string($_SESSION["myid"]) . "'  
ORDER BY projects.updated DESC");
$_SESSION["myprojects"] = array();
while($row=mysql_fetch_assoc($myprojects_query)) {
	array_push($_SESSION["myprojects"], $row["project_id"]);
}


//Deal with project query string
if (!is_numeric($_GET["pid"])) { //Check to see if its set and safe
	$cp_query = mysql_query("SELECT current_project FROM members WHERE member_id = '" . mysql_real_escape_string($_SESSION["myid"]) . "'");
	$current_pid = mysql_result($cp_query,0);
	header("location:" . queryString("pid", $current_pid));
} else {

	if (mysql_num_rows(mysql_query("SELECT project_id FROM projects WHERE project_id = '" . mysql_real_escape_string($_GET["pid"]) . "'"))) {
		if (mysql_num_rows(mysql_query("SELECT * FROM project_access WHERE project_id = '" . mysql_real_escape_string($_GET["pid"]) . "' AND member_id = '" . mysql_real_escape_string($_SESSION["myid"]) . "'"))) {
			mysql_query("UPDATE members SET current_project='" . mysql_real_escape_string($_GET["pid"]) . "' WHERE member_id='" . mysql_real_escape_string($_SESSION["myid"]) . "'");
			$_SESSION["current_project"] = $_GET["pid"];
		} else {
			if (isset($_SESSION["myusername"])) {
				header("location:../index.php?msgid=5"); //Not authorised to view project
			}
		}
	} else {
		header("location:../index.php?msgid=4"); //Project does not exist
	}

	
}



//Render the project
$pr_query = mysql_query("SELECT * FROM cards WHERE project_id='" . $_SESSION["current_project"] . "' ORDER BY id DESC");

$cards = '';
$left='';
$top='';
$zindex='';

while($row=mysql_fetch_assoc($pr_query))
{
	list($left,$top,$zindex) = explode('-',$row['xyz']);

	$cards.= '
	<div class="card" data-cardcreator="'.htmlspecialchars($row['creator']).'" data-cardid="'.$row['id'].'" style="background-color:'.$row['background_color'].'; color:'.$row['foreground_color'].'; left:'.$left.'px; top:'.$top.'px; z-index:'.$zindex.'">'.htmlspecialchars($row['content']).'</div>';
}




?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CardSortR</title>

<link rel="apple-touch-icon" href="/path/to/icon.png" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<link rel="stylesheet" href="../css/reset.css" type="text/css" />
<link rel="stylesheet" href="../css/gui-style.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" href="farbtastic/farbtastic.css" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css"/>

<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="farbtastic/farbtastic.js"></script>
</head>

<body>
		<div class="gt-hd clearfix">
			<div class="gt-nav">
				<ul>
					<li><a href="../index.php">Home</a></li>
					<li class="current"><a href="index.php">Sort board</a></li>
				</ul>
			</div>
			
			<div id="projecttools">
				<label for="project-select">Project:</label>
				<select name="project-select" onchange="location = '?pid=' + this.options[this.selectedIndex].value;">
					<?php		
					foreach ($_SESSION['myprojects'] as $i) {
						$iq = mysql_query("SELECT project_name FROM projects WHERE project_id='" . $i . "'");
						if ($i == $_SESSION["current_project"]) {
							echo '<option selected="selected" value="' . $i . '">' . mysql_result($iq,0) . '</option>';
						} else {
							echo '<option value="' . $i . '">' . mysql_result($iq,0) . '</option>';
						}
					}
					?>
				</select>
			</div>

			<div id="usertools">You are <?php echo $_SESSION["myusername"] ?> (<a href="../logout.php">Logout</a>)</div>

		</div>


<section id="cardsort">
<menu id="toolbar">
<h1><a href="../index.php"><img src="images/logo.png" width="163" height="159" alt="CardSortR" /></a></h1>

<!-- Edit card form -->
<div id="cardtools">
<form action="" method="post" class="card-form">

<label for="card-body">Text on the card</label>
<textarea name="card-body" id="card-body" cols="10" rows="2"></textarea>

<label for="card-color">Colour:</label><input type="text" id="card-color" name="card-color" value="#fffddd" />
<div id="picker"></div>

<button id="card-update">Update card</button>
<a id="card-submit"><img src="images/add.png" alt="add" width="82" height="83" /></a>
<a id="card-remove"><img src="images/delete.png" alt="delete" width="82" height="81" /></a>

</form>
</div>
</menu>

<div id="main">
	<div id="sortcanvas"></div>
		<?php echo $cards; ?>

</div>

</section>
</body>
</html>
