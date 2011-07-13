<?php
/*
 * This model is a map editor
 */

include_once("include/session.php");
include_once("menu.php");
include_once("include/html_map_editor.php");
include_once("include/lands.php");

global $session;

/* check user name, guests not allowed for now */
if (!strcasecmp($session->username, GUEST_NAME)){
   header("Location: process.php");
   return;
}

/* get lands */
$x = 0;
$y = 0;

/* get lands */
$lands = new Lands($x, $y, NULL, 0);

/* get marked land */
if (isset($_GET['mark_key'])) {
  $mark_key = $_GET['mark_key'];
  $lands->markLand($mark_key);
  $marked_land = $lands->getLand($mark_key);
}

$html = new Html;
$html->html_header(FRAGMENTS_TITLE);

$html->html_end_header();
?>

<!--action form for hex map actions-->
<form action="action_process.php" id="actionForm" method="POST">
   <input name="subaction" value="1" type="hidden">
   <input name="action" value="" type="hidden">                        
   <input name="key" value="" type="hidden">
</form>

<div id="wrapper">
   <div id="header">
	   <?php menu1(); ?>
      <!-- clock -->
      Time: <clock class="jclock"></clock>
	</div>         
   <div id="content">
	   <div id="map">
         <?php $lands->printMap($x, $y); ?>
         Coordinates: <div id="coordinates"></div>
      </div>
      <div id="land">
         <?php if($mark_key){
            ?>
            <div style="position:relative; top:260; left:480;">
            <form action="action_process.php" method="POST">
            <select name="terrain">
               <option value="<?php echo DIRT1 ?>">dirt1
               <option value="<?php echo DIRT2 ?>">dirt2
               <option value="<?php echo DIRT3 ?>">dirt3
               <option value="<?php echo DIRT4 ?>">dirt4
               <option value="<?php echo DIRT5 ?>">dirt5
               <option value="<?php echo DIRTVEG1 ?>">dirtveg1
               <option value="<?php echo DIRTVEG2 ?>">dirtveg2
               <option value="<?php echo DIRTVEG3 ?>">dirtveg3
               <option value="<?php echo DIRTVEG4 ?>">dirtveg4
               <option value="<?php echo DIRTVEG5 ?>">dirtveg5
               <option value="<?php echo URBAN1 ?>">urban1
               <option value="<?php echo URBAN2 ?>">urban2
               <option value="<?php echo URBAN3 ?>">urban3
               <option value="<?php echo URBAN4 ?>">urban4
               <option value="<?php echo URBAN5 ?>">urban5
               <option value="<?php echo VEG1 ?>">veg1
               <option value="<?php echo VEG2 ?>">veg2
               <option value="<?php echo VEG3 ?>">veg3
               <option value="<?php echo VEG4 ?>">veg4
               <option value="<?php echo VEG5 ?>">veg5
               <option value="<?php echo SEA ?>">sea
            </select>
            <font size="2">
            <input type="hidden" name="subadmin" value="1">
            <input name="action" value="terrain" type="hidden">
            <input type="hidden" name="key" value="<?php echo $mark_key; ?>">
            <input type="submit" value="Change"></td></tr>
            </form>
            </div>
            <?php
         }
         else{
            echo "click a tile to change terrain type";
         }
         ?>
 	   </div>
</div>
<?php
$html->html_footer();
?>
