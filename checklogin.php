<?php
ob_start(); //Keep it on the server
$host="internal-db.s00000.gridserver.com"; // Host name 
$username="db00000_cardsort"; // Mysql username 
$password="passwordhere"; // Mysql password 
$db_name="db00000_cardsortr"; // Database name 

// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

// Define $myusername and $mypassword 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM members WHERE username='$myusername' and password='$mypassword'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){
session_start();
//Find projects
$myid_query = mysql_query("SELECT member_id FROM members WHERE username='$myusername' and password='$mypassword'");
$_SESSION["myid"] = mysql_result($myid_query, 0);

// Register $myusername, $mypassword and redirect to file "login_success.php"
$_SESSION["myusername"] = $myusername;
$_SESSION["mypassword"] = $mypassword;

mysql_query("UPDATE members SET lastloggedin=NOW() WHERE member_id='" . $_SESSION["myid"] . "'");
header("location:sortboard/index.php");
}
else {
header("location:index.php?msgid=1"); //"Incorrect username or password";
}

ob_end_flush();
?>