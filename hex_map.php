<?php
/*
 * This model handles the hexangon map structure
 */

include_once("include/session.php");
include_once("include/map_utils.php");
include_once("menu.php");
include_once("include/html.php");
include_once("include/land_descr.php");
include_once("include/land_utils.php");
include_once("include/lands.php");
include_once("include/utils.php");
include_once("include/character.php");
include_once("include/population.php");
include_once("include/treasury.php");
include_once("include/action.php");
include_once("include/buildings.php");


global $session;

/* check user name, guests not allowed for now */
if (!strcasecmp($session->username, GUEST_NAME)){
   header("Location: process.php");
   return;
}

/* process any actions */
$action = new Action();
$action->processActions();

/* process any builds */
$buildings = new Buildings();
$buildings->processBuilds();

/* get character */
$character = new Character();
$action->readActions($character->getName());

/* update resources */
$population = new Population();
$treasury = new Treasury($character->getName());
$population->updateAllCivilians();
$treasury->updateAllTreasury();

/* get lands */
$x = 0;
$y = 0;

/* get lands */
$lands = new Lands($x, $y, $character->getName(), $action->isAction());

/* get marked land */
if (isset($_GET['mark_key'])) {
  $mark_key = $_GET['mark_key'];
  $lands->markLand($mark_key);
  $marked_land = $lands->getLand($mark_key);
  $marked_civilians = $marked_land->getCivilians();
  $marked_toxic = $marked_land->getToxic();
  $current_buildings = $buildings->getBuildingsDone($marked_land->getX(), $marked_land->getY());
  $new_buildings = $buildings->getNewBuildings($marked_land->getX(), $marked_land->getY());
  $buildings->readBuilds($character->getName(), $marked_land->getX(), $marked_land->getY());
}

/* get land character stays in */
$character_land = $lands->getLand(createKey($character->getX(), $character->getY()));

$html = new Html;
$html->html_header(FRAGMENTS_TITLE);

$max = $character->getCivilians() + $character_land->getCivilians();
$min = $character->getCivilians() - (CIVILIANS_MAX - $character_land->getCivilians());
if ($min < 0){
  $min = 0;
}

?>
<script type='text/javascript'>
$(function() {
  /* spinners for input fields */
  $('#civilians').spinner({ min: <?php echo $min ?>, max: <?php echo $max ?> });
});

</script>
<?php

$html->html_end_header();
?>

<!--action form for hex map actions-->
<form action="action_process.php" id="actionForm" method="POST">
   <input name="subaction" value="1" type="hidden">
   <input name="action" value="" type="hidden">                        
   <input name="key" value="" type="hidden">
</form>

<!--markup for context menu1-->
<div class="contextMenu" id="myMenu1">
   <ul>
      <li id="move"> Move</li>
   </ul>
</div>

<!--markup for context menu2-->
<div class="contextMenu" id="myMenu2">
   <ul>
      <li id="colonize"> Colonize</li>
   </ul>
</div>

<div id="wrapper">
   <div id="header">
	   <?php menu1(); ?>
      <!-- clock -->
      Time: <clock class="jclock"></clock>
	</div>         
   <div id="overview">
      Overview data
      <br>Gold: <?php echo $treasury->getGold(); ?>
      | Total Population: <?php echo $population->getPopulation($character->getName()) + $character->getCivilians(); ?>
      | Tax: <?php echo $treasury->getTax(); ?>
      | Total Income: <?php echo "25" ?>
      | Total Cost: <?php echo "0" ?>
   </div>
   <div id="content">
	   <div id="map">
         <?php $lands->printMap($x, $y); ?>
         Coordinates: <div id="coordinates"></div>
      </div>
      <div id="land">
         Marked land 
         <br>Coordinate: <?php echo $mark_key; ?>
         | Civilians: <?php echo $marked_civilians; ?>
         | Toxic: <?php echo $marked_toxic; ?>
         <br>
         <?php if($mark_key){
         if(count($current_buildings)){         
         ?>
         Buildings:<?php for($b = 0; $b < count($current_buildings); $b++){echo $current_buildings[$b]["type"].",";}?>
         <br>
         <?php
         }
         $builds = $buildings->getBuilds();
         if(count($builds)){
            /* display build queue */
            echo "<div id=\"build_queue\">";

            for ($i = 0; $i < count($builds); $i++){
               $row = $builds[$i];
               $diff = $buildings->getDiff($row["due_time"]);
               $type = $row["type"];

               echo " ".$type." <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', 'hex_map.php');</script><br>";
            }
            echo "</div><br>";
         }
         else if(count($new_buildings)){
         ?>
         <form action="action_process.php" method="POST">
         <table align="left" border="0" cellspacing="0" cellpadding="3">
         <tr><td>Build:</td><td>
         <select name="type">
         <?php
         for($b = 0; $b < count($new_buildings); $b++) { ?>
         <option value="<?php echo $new_buildings[$b]["type"];?>"><?php echo $new_buildings[$b]["type"]; echo "(".$new_buildings[$b]["cost"].")"?>
         <?php
         }
         ?>
         </select>
         <tr><td colspan="2" align="left">
         <font size="2">
         <input type="hidden" name="subaction" value="1">
         <input type="hidden" name="action" value="build">
         <input type="hidden" name="key" value="<?php echo $marked_land->getName();?>">
         <input type="hidden" name="name" value="<?php echo $character->getName();?>">
         <input type="submit" value="Build"></td></tr>
         </table>
         </form>
         <?php 
         }
         }?>
 	   </div>

      <div id="character">
         Character name here ...
         <br>Civilians:
         <?php
         if ($character_land->getOwner() == I_OWN){
            ?>
         <form action="action_process.php" method="POST">
         <input type="text" id="civilians" name="civilians" value="<?php echo $character->getCivilians();?>" size="2">
         <input type="hidden" name="subaction" value="1">
         <input type="hidden" name="action" value="army">
         <input type="hidden" name="key" value="<?php echo $character_land->getName();?>">
         <input type="hidden" name="character" value="<?php echo $character->getCivilians();?>">
         <input type="hidden" name="land" value="<?php echo $character_land->getCivilians();?>">
         <input type="hidden" name="name" value="<?php echo $character->getName();?>">
         <input type="submit" value="Get Value">
         </form>
            <?php 
         }
         else{
            echo $character->getCivilians();
         }   
         ?>
         | Soldiers: | Colonizers: | Workers:
         <br>
      <?php
      /* display action queue */
      $actions = $action->getActions();
      if (count($actions)){
         echo "<div id=\"action_queue\">";

         for ($i = 0; $i < count($actions); $i++){
            $row = $actions[$i];
            $diff = $action->getDiff($row["due_time"]);
            $type = $action->typeToString($row["type"]);

            echo " ".$type." (".$row["x"]."|".$row["y"].") <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', 'hex_map.php');</script><br>";
         }
         echo "</div><br>";
      }
      ?>
	   </div>

      <div id="country">
         Country
         <?php
         $civilians = $population->getPopulation($character->getName()) + $character->getCivilians();
         ?>
         <br>Total Population: <?php echo $civilians; ?>
         (Civilians: <?php echo $civilians; ?>| Soldiers: 0| Colonizers: 0| Workers: 0)
         | Garrison soldiers: 0
         | Army soldiers: 0
	   </div>

      <div id="economy">
         Economy
         <br>
	   </div>

      <div id="content-main">
		   TEXT ELLER ANNAT
      </div>
   </div>
	<div id="footer">
   Footer
   </div>
<?php
$html->html_footer();
?>
