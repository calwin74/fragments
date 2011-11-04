<?php
/**
 * login.php
 */

include_once("include/session.php");
include_once("include/constants.php");

global $session;
$database = $session->database;  //The database connection

/**
 * User has already logged in
 */
if($session->logged_in){
   if($session->isAdmin()){
      header('Location: admin.php');
   }
   else if(!$session->isInitUser()){
      header('Location: userinit.php');
   }
   else{
      header("Location: home.php");
   }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<style type="text/css">
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: x-small;
	color: #0F0;
}
body {
	background-color: #000;
	background-image: url(img/login.jpg);
	background-repeat: no-repeat;
}
#apDiv1 {
	position:absolute;
	left:452px;
	top:356px;
	width:364px;
	height:122px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left:452px;
	top:619px;
	width:364px;
	height:138px;
	z-index:3;
}
#updatetext {
	position:absolute;
	left:190px;
	top:27px;
	width:863px;
	height:162px;
	z-index:2;
}
a:link {
	color: #0F0;
	text-decoration: none;
}
a:visited {
	color: #0F0;
	text-decoration: none;
}
a:hover {
	color: #090;
	text-decoration: none;
}
a:active {
	color: #060;
	text-decoration: none;
}
</style>
</head>

<!-- This doesn't work on danielmadsen.se/fragments because of header problem -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
if ( ($.browser.msie) && ($.browser.version == '7.0') ){
  var txt = "IE 7 not supported";
  alert(txt);
}
</script>

<body>
<div id="apDiv1"><a href="#">Login</a>

<?php
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}

?>

<form action="process.php" method="POST">
<table align="left" border="0" cellspacing="0" cellpadding="3">
<tr><td>Username:</td><td><input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"></td><td><?php echo $form->error("user"); ?></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"></td><td><?php echo $form->error("pass"); ?></td></tr>
<tr><td colspan="2" align="left"><input type="checkbox" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>
<font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;
<input type="hidden" name="sublogin" value="1">
<input type="submit" value="Login"></td></tr>
<tr><td colspan="2" align="left"><br><font size="2">[<a href="forgotpass.php">Forgot Password?</a>]</font></td><td align="right"></td></tr>
<tr><td colspan="2" align="left"><br>Not registered? <a href="register.php">Sign-Up!</a></td></tr>
</table>
</form>

</div>

<div id="apDiv2">
<?php
/**
 * Just a little page footer, tells how many registered members
 * there are, how many users currently logged in and viewing site,
 * and how many guests viewing site. Active users are displayed,
 * with link to their user information.
 */
echo "</td></tr><tr><td align=\"center\"><br><br>";
echo "<b>Member Total:</b> ".$database->getNumMembers()."<br>";
echo "There are $database->num_active_users registered members and ";
echo "$database->num_active_guests guests viewing the site.<br><br>";

include("include/view_active.php");
?>
</div>

<div id="updatetext">
<b>Updated 2011 11 04</b>
<br>
<br>  
<b>New features:</b>
<br>
- updating all the time ...
<br>
</div>

</body>
</html>
