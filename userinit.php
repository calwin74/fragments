<?php
/**
 * UserInit.php
 *
 * This page is for users to initialise the game: choose tribe and name and so on. 
 * This is done once at the first login.
 * Admin users should never have to initialise.
 *
 * Written by: matkar01
 */
include_once("include/session.php");
include_once("include/constants.php");
include_once("include/html.php");

global $session;
$database = $session->database; // Database connection
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo FRAGMENTS_TITLE ?></title>
<link rel="stylesheet" type="text/css" href="css/login_style.css"></link>
</head>

<body>

<div id="apDiv1"><a href="#"></a>
<?php
/**
 * User has already initialised, so go on to the game or admin center.
 */
if($session->isInitUser()){
   header("Location: board.php");
}
?>

<form action="process.php" method="POST">
<table align="left" border="0" cellspacing="0" cellpadding="3">

<tr><td>Name:</td><td><input type="text" name="charname" maxlength="30" value="<?php echo $form->value("charname"); ?>"></td><td><?php echo $form->error("charname"); ?></td></tr>
<?php
/* get fragment tribes */
$tribes = $database->getAllTribes();
?>
<tr><td>Fragment:</td><td>
<select name="tribe">
<?php
for($t = 0; $t < count($tribes); $t++) { ?>
<option value="<?php echo $tribes[$t]["name"]; ?>"><?php echo $tribes[$t]["name"]; ?>
<?php
}
?>
</select>

<tr><td colspan="2" align="left">
<font size="2">
<input type="hidden" name="subinit" value="1">
<input type="submit" value="Create"></td></tr>
</table>
</form>
</div>

</body>
</html>
