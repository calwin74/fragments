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
include_once("menu.php");
include_once("include/html.php");

global $session;
$database = $session->database; // Database connection

/**
 * User has already initialised, so go on to the game or admin center.
 */
if($session->isInitUser()){
   header("Location: hex_map.php");
}

$html = new Html;
$html->html_header(FRAGMENTS_TITLE);
$html->html_end_header();

menu1();

?>
<h2>Add information about your character</h2>
<?php
/**
 * User not initialised, display the initialisation form.
 * If user has already tried to initialise, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>
<form action="process.php" method="POST">
<table align="left" border="0" cellspacing="0" cellpadding="3">

<tr><td>Name:</td><td><input type="text" name="charname" maxlength="30" value="<?php echo $form->value("charname"); ?>"></td><td><?php echo $form->error("charname"); ?></td></tr>
<?php
/* get fragment tribes */
$tribes = $database->getAllTribes();
?>
<tr><td>Tribe:</td><td>
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

<?php
$html->html_footer();
?>