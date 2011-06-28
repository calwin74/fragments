<?php
/*
 * This model handles hexangon map structure and everything shown on the map.
 *
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

global $session;

/* get character */
$character = new Character();

/* update resources */
$population = new Population();
$treasury = new Treasury();
$population->updateAllPopulation();
$treasury->updateAllTreasury();

/* process any actions */
$action = new Action();
$action->processActions();
$action->readActions($character->getName());

/* get lands */
$x = 0;
$y = 0;
$lands = new Lands($x, $y, $character->getName(), $action->isMove());
if (isset($_GET['mark_key'])) {
  $mark_key = $_GET['mark_key'];
  $lands->markLand($mark_key);
  $marked_land = $lands->getLand($mark_key);
  $marked_population = $marked_land->getPopulation();
  $marked_toxic = $marked_land->getToxic();
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

<!--markup for context menu1-->
<div class="contextMenu" id="myMenu1">
   <ul>
      <li id="move"> Move</li>
   </ul>
</div>

<!--markup for context menu2-->
<div class="contextMenu" id="myMenu2">
   <ul>
      <li id="clean"> Clean</li>
   </ul>
</div>

<?php

/* set menu */
menu1();
?>

<div id="map">

<?php

/*
   Loops that lays the heagon tiles:
   1. First row is always even.
   2. The first odd tile is special.
   3. NOTE: The x and y coordinates in the for-loops are there to set up the map
*/

$first_row = 1;
$is_odd = 0;

for ($y_pos = $y + Y_LOCAL_MAP_SIZE; $y_pos >= $y - Y_LOCAL_MAP_SIZE; $y_pos--){
  $is_first_odd = 1;
  $is_first_even = 1;
  $position = "";

  for ($x_pos = $x - X_LOCAL_MAP_SIZE; $x_pos <= $x + X_LOCAL_MAP_SIZE; $x_pos++){
    if ($first_row){
      $position = "first";
    }
    else{
      if ($is_odd){
        if ($is_first_odd){
          $is_first_odd = 0;
          $position = "br firstodd";           
        }
        else{
          $position = "odd";
        }
      }
      else{
        if ($is_first_even){
          $is_first_even = 0;
          $position = "br even";
        }
        else{
          $position = "even";
        }
      }
    }

    $key = createKey($x_pos, $y_pos);
    $land = $lands->getLand($key);
    $land_descr = $land->getDescr($lands->isMove());
    $classes = $land_descr["class"];

    /* ugly code to mark characters, should use css classes instead */
    if (strstr($classes, "gasmask")){
      $unit = "$";
    }
    else{
      $unit = "";
    }

    echo "<span class=\"$classes $position\" id=$key><p>$unit</p></span>";
  }
  
  if ($is_odd){
    $is_odd = 0;
  }
  else{
    $is_odd = 1;
  }

  if ($first_row){
    $first_row = 0;
  }
}

?>
</div>

<div id="player_info">
<b>Character: </b> <?php echo $character->getName(); ?>
<br>
<b>Time: </b>
<!-- clock -->
<clock class="jclock"></clock>
<br>
<b>Gold:</b>
<!-- gold count here -->
  <div id="gold">
  <?php echo $treasury->getGold($character->getName()) ?>
  </div>
<b>Total population:</b>
<!-- population count here -->
  <div id="population">
  <?php echo $population->getPopulation($character->getName()) ?>
  </div>
<b>Coordinates: </b>
   <div id="coordinates">
   </div>
</div>

<div id="hex_info">
<a>Marked land:</a> <?php echo $mark_key; ?>
<br>
<a>Population: </a> <?php echo $marked_population; ?>
<br>
<a>Toxic: </a> <?php echo $marked_toxic; ?>
</div>

<div id="character_info">
<a>Name:</a> <?php if(isset($marked_land) && $marked_land->getCharacter()) echo $character->getName(); ?>
<br>
<a>Soldiers: </a> <?php if(isset($marked_land) && $marked_land->getCharacter()) echo 0; ?>
<br>
<a>Civilians: </a> <?php if(isset($marked_land) && $marked_land->getCharacter()) echo 0; ?>
</div>

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

<?php
$html->html_footer();
?>
