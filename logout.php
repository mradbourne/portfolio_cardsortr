<? 
session_start();
session_destroy();
header("location:index.php?msgid=3"); //"Successfully logged out";
?>

