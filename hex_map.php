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

global $session;

$database = $session->database;  //The database connection

/* fix this ... */
$lands = new Lands();

$x = 0;
$y = 0;

$land_rows = $database->map($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE);

$html = new Html;
$html->html_header(FRAGMENTS_TITLE);

?>

<script type="text/javascript">
$(function($) {
    $('.jclock').jclock();
});
</script>
 
<script type="text/javascript">
   $(document).ready(function() {
       var money = $("div.money");
       var production = parseInt(document.getElementById('production').innerHTML);
       var growth = parseFloat(document.getElementById('growth').innerHTML);
       
       $('move').contextMenu('myMenu1', {
          bindings: {
             'move': function(t) {
                 var actionForm = document.forms["actionForm"];

                 actionForm.elements["action"].value = 'move';
                 actionForm.elements["key"].value = t.id;
                 actionForm.submit();
             },
          }
       });
       $('clean').contextMenu('myMenu2', {
          bindings: {
             'clean': function(t) {
                 var actionForm = document.forms["actionForm"];

                 actionForm.elements["action"].value = 'clean';
                 actionForm.elements["key"].value = t.id;
                 actionForm.submit();
             },
          }
       });

       $(".controlled-interval", money).everyTime("1s", "controlled", function() {
            var production_int = 0;

            production += growth;
            production_int = Math.round(production);
                      
            document.getElementById('production').innerHTML = production_int;
       });

       $(".hex").mouseover(function() {
          var id = this.id
          document.getElementById('coordinates').innerHTML = id;
       });
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
      <li id="clean"> Clean</li>
   </ul>
</div>

<div class="money">
   <div class="controlled-interval">
   </div>
</div>

<?php

/* set menu */
menu1();

$character = $database->getCharacter($session->username);
$characterName = $character["name"];

/*
if ($character){
   $char_x = $character["x"];
   $char_y = $character["y"];
   $key = createKey($char_x, $char_y);
   $land = $lands->getLand($key);
   $land->setCharacter(1);
}
*/

/* get resources */
$resources = $database->getResources($characterName);
$production = $resources["production"];
$growth = $resources["production_growth"];

foreach ($land_rows as $row){
   $land = new Land;
   $land->init($row["x"], $row["y"], $row["type"], $row["toxic"]);
   /* handle land ownership */
   if ($row["owner"]){
      if (strcmp($characterName, $row["owner"]) == 0){
         $land->setOwner(I_OWN);
      }
      else{
         $land->setOwner(YOU_OWN);
      }
   }
   else{
      $land->setOwner(NOT_OWNED);
   }
   $lands->addLand($land);
}

/* handle available lands */
$lands->fixAvailableLands();


?>

<div id="map">

<?php

/*
   Lay out the tiles:
   1. First row is always even.
   2. The first odd tile is special.
   3. NOTE: The x and y coordinates in the for-loops are there to set up the map
*/

$first_row = 1;
$is_odd = 0;

for ($y_pos = $y + Y_LOCAL_MAP_SIZE; $y_pos >= $y - Y_LOCAL_MAP_SIZE; $y_pos--){
  $is_first_odd = 1;
  $is_first_even = 1;

  for ($x_pos = $x - X_LOCAL_MAP_SIZE; $x_pos <= $x + X_LOCAL_MAP_SIZE; $x_pos++){
    $key = createKey($x_pos, $y_pos);
    $land = $lands->getLand($key);

    if ($first_row){
      echo $land->getDescr("first");
    }
    else{
      if ($is_odd){
        if ($is_first_odd){
          echo $land->getDescr("br firstodd");
          $is_first_odd = 0;           
        }
        else{
          echo $land->getDescr("odd");
        }
      }
      else{
        if ($is_first_even){
          echo $land->getDescr("br even");
          $is_first_even = 0;
        }
        else{
          echo $land->getDescr("even");
        }
      }
    }
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

<div id="character">
<b>Character: </b> <?php echo $characterName; ?>
<br>
<b>Time: </b>
<!-- clock -->
<clock class="jclock"></clock>
<br>
<b>Production:</b>
<!-- production count here -->
  <div id="production">
  <?php echo $production ?>
  </div>
<b>Growth:</b>
  <div id="growth">
  <?php echo round($growth, 2) ?>/sec
  </div>
</div>

<?php
/* display action queue */
$actions = $database->getActions($characterName);
if (count($actions)){
   echo "<div id=\"action_queue\">";
   for ($i = 0; $i < count($actions); $i++) {
      $action = $actions[$i];
      $diff = $database->getTimeDiff($action["due_time"], getNow(0));

      switch($action["type"]){
         case 1: $what = "clean"; break;
         case 2: $what = "move"; break;
      }

      echo " ".$what." (".$action["x"]."|".$action["y"].") <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', 'hex_map.php');</script><br>";
   }
   echo "</div><br>";
}

?>

<div id="hex_info">
<b>Coordinates: </b>
   <div id="coordinates">
   </div>
</div>

<?php
$html->html_footer();
?>
