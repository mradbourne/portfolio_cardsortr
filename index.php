<?php
session_start();
if(session_is_registered(myusername)){
	$loginbox = '<div id="loginbox">
					<div id="chalkboard-blank">
						<h2>You are logged in</h2>
						<a href="logout.php">Click here to log out</a>
					</div>
				</div>';
} else {
	$loginbox = '<div id="loginbox">
					<div id="chalkboard-login">
						<form name="form1" method="post" action="checklogin.php">
							<input id="login-user" name="myusername" type="text" id="myusername">
							<input id="login-pass" name="mypassword" type="password" id="mypassword">
							<input id="login-submit" type="submit" name="Submit" value="Login">
						</form>
					</div>
				</div>';
}

if (isset($_GET['msgid'])) {
	switch ($_GET['msgid']) {
	case 1: $notification = '<p class="gt-error">Invalid username or password</p>'; break;
	case 2: $notification = '<p class="gt-error">Whoops! You need to be logged in to do that.</p>'; break;
	case 3: $notification = '<p class="gt-success">You have successfully logged out.</p>'; break;
	case 4: $notification = '<p class="gt-error">The project you are trying to access does not exist.</p>'; break;
	case 5: $notification = '<p class="gt-error">You are not authorised to view the project you are trying to access.</p>'; break;
	} 
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>CardSortR</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/reset.css" type="text/css" />
		<link rel="stylesheet" href="css/gui-style.css" type="text/css" />
		<link rel="stylesheet" href="css/cec.css" type="text/css" />
	</head>
	<body>
		<div class="gt-hd clearfix">
			<div class="gt-nav">
				<ul>
					<li class="current"><a href="index.php">Home</a></li>
					<li><a href="sortboard/index.php">Sort board</a></li>
				</ul>
			</div>
		</div>

		<div class="gt-bd gt-cols clearfix">
			<div class="gt-content">		
				<div id="notification-main"><?php echo $notification ?></div>		
				
				<!-- Coffee shop visuals -->
				<div id="logo">
					<img src="images/cec/logo.png" width="163" height="159" alt="CardSortR logo" />
				</div>
				
				<div id="paper_plane_logo">
					<img src="images/cec/paper_plane.png" width="542" height="88" alt="Paper Plane" />
				</div>
				<div id="companytext"><img src="images/cec/company_text.png" width="427" height="100" alt="Company Text"></div>
				
				<?php echo $loginbox; ?>
			</div>
		</div>
	</body>
</html>