<?php
/*
 * This model handles the home command center
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
include_once("include/units.php");
include_once("include/garrison.php");
include_once("include/land_utils.php");

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

/* process any units */
$units = new Units();
$units->processBuilds();

/* get character */
$character = new Character();
$action->readActions($character->getName());

/* update resources */
$population = new Population($character->getName());
$treasury = new Treasury($character->getName());
$population->updateAllCivilians();
$treasury->updateAllTreasury();

/* garrison */
$garrison = new Garrison($character->getName());

/* get lands */
if(isset($_GET['focus_key'])) {
  $focus_key = $_GET['focus_key'];
  $x = getXfromKey($focus_key);
  $y = getYfromKey($focus_key);
}
else if ($character) {
  $x = $character->getHomeX();
  $y = $character->getHomeY();
  $focus_key = createKey($x, $y);
}
else {
  /* default */
  $x = 0;
  $y = 0;
  $focus_key = createKey($x, $y);

}

/* get marked land */
if(isset($_GET['mark_key'])) {
  $mark_key = $_GET['mark_key'];
}
else {
  $mark_key = null;
}

/* get lands */
$lands = new Lands($x, $y, $character->getName(), $action->isAction(), X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE,
                   $mark_key);

/* get marked land */
if ($mark_key) {
  $lands->markLand($mark_key);
  $marked_land = $lands->getLand($mark_key);

  if ($marked_land) {
    $marked_toxic = $marked_land->getToxic();
    if ($marked_land->getOwner() == I_OWN){
      /* buildings */
      $current_buildings = $buildings->getBuildingsDone($marked_land->getX(), $marked_land->getY());
      $new_buildings = $buildings->getNewBuildings($marked_land->getX(), $marked_land->getY());
      $buildings->readBuilds($character->getName(), $marked_land->getX(), $marked_land->getY());
      /* units */
      $new_units = $units->getAvailableUnits($current_buildings);
      $units->readUnitBuilds($character->getName(), $marked_land->getX(), $marked_land->getY());
    }
  }
}

/* create link */
$lnk = createLnk("home.php", $mark_key, $focus_key);

/* get land character stays in */
$character_land = $lands->getLand(createKey($character->getX(), $character->getY()));

$html = new Html;
$html->html_header(FRAGMENTS_TITLE);

?>

<link rel="stylesheet" type="text/css" href="style.css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fragments</title>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:339px;
	top:497px;
	width:322px;
	height:114px;
	z-index:1;
}
body {
	background-color: #000;
	background-image: url(img/interfacebg.jpg);
	text-align: left;
}
</style>
<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
</head>

<body onload="MM_preloadImages('img/arrowupon.png','img/arrowdownon.png','img/arrowlefton.png','img/arrowrighton.png')">

<?php

$civilians_max = $population->getCivilians();
$soldiers_max = $character->getSoldiers() + $garrison->getSoldiers();
$explorers_max = $character->getExplorers() + $population->getExplorers();

?>
<script type='text/javascript'>
$(function() {
  /* spinners for input fields */
  $('#civilians').spinner({ min: 0, max: <?php echo $civilians_max ?> });
  $('#soldiers').spinner({ min: 0, max: <?php echo $soldiers_max; ?> });
  $('#explorers').spinner({ min: 0, max: <?php echo $explorers_max;?> });
  $('#tax').spinner({ min: 0, max: 100 });
});

</script>
<?php
$html->html_end_header();
?>

<!--action form for hex map actions-->
<form action="action_process.php" id="actionForm" method="POST">
   <input name="subaction" value="1" type="hidden">
   <input name="action" value="" type="hidden">
   <input name="mark_key" value="<?php echo $mark_key;?>" type="hidden">
   <input name="focus_key" value="<?php echo $focus_key;?>" type="hidden">
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
      <li id="explore"> Explore</li>
   </ul>
</div>

<!--markup for context menu3-->
<div class="contextMenu" id="myMenu3">
   <ul>
      <li id="move"> Move</li>
      <li id="mark"> Mark</li>
   </ul>
</div>

<div id="wrapper">
<div id="head">
  <ul id="MenuBar1" class="MenuBarHorizontal">
    <li><a class="MenuBarItemSubmenu" href="#">Menu</a>
      <ul>
        <li><a href="userinfo.php?user=<?php echo $session->username ?>">My account</a></li>
        <li><a href="process.php">Logout</a></li>
        <li><a href="hex_editor.php">Map editor</a></li>
      </ul>
    </li>
  </ul>
<?php
$civilians = $population->getCivilians();
$explorers = $population->getExplorers() + $character->getExplorers();
?>
Population: <?php echo $civilians + $explorers + $garrison->getSoldiers() + $character->getSoldiers(); ?>
 (Civilians: <?php echo $civilians; ?>
| Explorers: <?php echo $explorers; ?>
| Soldiers: <?php echo $garrison->getSoldiers() + $character->getSoldiers(); ?>)
 Gold: <?php echo $treasury->getGold(); ?>
 Tax: <?php echo $treasury->getTax(); ?>%
 Income: <?php echo $treasury->getIncome(); ?>
 Upkeep: <?php echo $treasury->getCost(); ?>
 Time: <clock class="jclock"></clock>
</div>
<div id="headc">
</div>
<div id="speaker">
</div>
<div id="comcentral">
  <table width="115" height="373" border="0" align="left" cellpadding="0" cellspacing="9">
    <tr>
      <td height="30">&nbsp;</td>
    </tr>
    <tr>
      <td height="310" align="left" valign="top"><img src="img/mark.png" width="35" height="15" /> INFO:<br>Coord: <div id="coordinates"></div></td>
    </tr>
  </table>
</div>
<div id="loudspeaker">
</div>
<div id="map">
<?php $lands->printMap($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE); ?>
</div>

<?php
/* arrows used to move local map view */
?>
<div id="frametop"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Scroll-up','','img/arrowupon.png',1)"><img src="img/arrowupoff.png" alt="Scroll Up" name="Scroll-up" width="819" height="30" border="0" id="Scroll-up" /></a>
</div>
<div id="frameleft"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Scroll-Left','','img/arrowlefton.png',1)"><img src="img/arrowleftoff.png" alt="Scroll Left" name="Scroll-Left" width="30" height="292" border="0" id="Scroll-Left" /></a>
</div>
<div id="frameradar">
  <p>&nbsp;</p>
  <p><img src="img/light-on.png" width="32" height="32" alt="There Will be light!" /></p>
</div>
<div id="framebottom"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Scroll-Down','','img/arrowdownon.png',1)"><img src="img/arrowdownoff.png" alt="Scroll Down" name="Scroll-Down" width="756" height="30" border="0" id="Scroll-Down" /></a>
</div>
<div id="framecornerur">
</div>
<div id="framecornerbr">
</div>
<div id="frameright"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Scroll-Right','','img/arrowrighton.png',1)"><img src="img/arrowrightoff.png" alt="Scroll-Right" name="Scroll-Right" width="30" height="523" border="0" id="Scroll-Right" /></a>
</div>

<div id="minimap"><img src="img/Radar-frame.png" alt="MINIMAP" width="192" height="194" hspace="0" vspace="0" align="top" /></div>

<div id="army">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr valign="top">
   <td width="35%"><img src="img/mark.png" width="45" height="15" hspace="0" vspace="0" align="top" /></td>
   <td width="68%">
   <?php
   echo "<a id=\"army_home\" href=\"#\">".$character->getName()."</a>";
   if ($character_land && $character_land->getOwner() == I_OWN){
      ?>
      <form action="action_process.php" method="POST">
      <table align="left" border="0" cellspacing="0" cellpadding="3">
      <tr><td colspan="2" align="left">
      <font size="2">
      <div class="buttons">
      <input type="text" id="soldiers" name="soldiers" value="<?php echo $character->getSoldiers();?>" size="2">
      <input type="hidden" name="subaction" value="1">
      <input type="hidden" name="action" value="army">
      <input type="hidden" name="mark_key" value="<?php echo $mark_key;?>">
      <input type="hidden" name="focus_key" value="<?php echo $focus_key;?>">
      <input type="hidden" name="key" value="<?php echo $character_land->getName();?>">
      <input type="hidden" name="character" value="<?php echo $character->getSoldiers();?>">
      <input type="hidden" name="garrison" value="<?php echo $garrison->getSoldiers();?>">
      <input type="hidden" name="name" value="<?php echo $character->getName();?>">
      <button type="submit">Soldiers</button>
      </div>
      </table>
      </form>
      <?php 
   }
   else{
      echo " | soldiers: ".$character->getSoldiers();
   }

   if ($character_land && $character_land->getOwner() == I_OWN){
      ?>
      <form action="action_process.php" method="POST">
      <table align="left" border="0" cellspacing="0" cellpadding="3">
      <tr><td colspan="2" align="left">
      <font size="2">
      <div class="buttons">
      <input type="text" id="explorers" name="explorers" value="<?php echo $character->getExplorers();?>" size="2">
      <input type="hidden" name="subaction" value="1">
      <input type="hidden" name="action" value="army">
      <input type="hidden" name="mark_key" value="<?php echo $mark_key;?>">
      <input type="hidden" name="focus_key" value="<?php echo $focus_key;?>">
      <input type="hidden" name="key" value="<?php echo $character_land->getName();?>">
      <input type="hidden" name="character" value="<?php echo $character->getExplorers();?>">
      <input type="hidden" name="land" value="<?php echo $population->getExplorers();?>">
      <input type="hidden" name="name" value="<?php echo $character->getName();?>">
      <button type="submit">Explorers</button>
      </div>
      </table>
      </form>
      <?php 
   }
   else{
      echo " | explorers: ".$character->getExplorers();
   }                     
   ?>
   <?php
   /* display action queue */
   $actions = $action->getActions();
   if (count($actions)){
      echo "<div id=\"action_queue\">";
      for ($i = 0; $i < count($actions); $i++){
         $row = $actions[$i];
         $diff = $action->getDiff($row["due_time"]);
         $type = $action->typeToString($row["type"]);

      echo " ".$type." (".$row["x"]."|".$row["y"].") <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', '".$lnk."');</script><br>";
      }
      echo "</div><br>";
   }
?>
   </td>
   </tr>
</table>

</div>

<div id="selected"><img src="img/mark.png" width="35" height="15" />
Coordinate: <?php echo $mark_key; ?> <br>Toxic: <?php echo $marked_toxic; ?>

<?php
if($mark_key){
   if(count($current_buildings)){         
      ?>
      <br>Buildings:<?php for($b = 0; $b < count($current_buildings); $b++){echo $current_buildings[$b]["type"].",";}?>
      <br>
      <?php
   }
   /* buildings */
   $builds = $buildings->getBuilds();
   if(count($builds)){
      /* display build queue */
      echo "<div id=\"build_queue\">";

      for ($i = 0; $i < count($builds); $i++){
      $row = $builds[$i];
      $diff = $buildings->getDiff($row["due_time"]);
      $type = $row["type"];

      echo " ".$type." <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', '".$lnk."');</script><br>";
      }
      echo "</div><br>";
   }

   /* units */
   $unit_builds = $units->getBuilds();
   if(count($unit_builds)){
      /* display unit build queue */
      echo "<div id=\"unit_queue\">";

      for ($i = 0; $i < count($unit_builds); $i++){
         $row = $unit_builds[$i];
         $diff = $units->getDiff($row["due_time"]);
         $type = $row["type"];

         echo " ".$type." <span id='".$i."'>".$diff."</span> <script type='text/javascript'> var id=new Array(50); timer('".$i."', '".$lnk."');</script><br>";
      }
      echo "</div><br>";
   }
}
?>
</div>

<div id="action"><img src="img/mark.png" width="35" height="15">
<?php
if(count($new_buildings)){
   ?>
   <form action="action_process.php" method="POST">
      <table align="left" border="0" cellspacing="0" cellpadding="3">
      <tr><td>
      <select name="type">
      <?php
      for($b = 0; $b < count($new_buildings); $b++) { ?>
         <option value="<?php echo $new_buildings[$b]["type"];?>"><?php echo $new_buildings[$b]["type"]; echo "(".$new_buildings[$b]["cost"].")";
      }
      ?>
      </select>
      <tr><td colspan="2" align="left">
      <font size="2">
      <input type="hidden" name="subaction" value="1">
      <input type="hidden" name="action" value="build">
      <input type="hidden" name="mark_key" value="<?php echo $mark_key;?>">
      <input type="hidden" name="focus_key" value="<?php echo $focus_key;?>">      
      <input type="hidden" name="key" value="<?php echo $marked_land->getName();?>">
      <input type="hidden" name="name" value="<?php echo $character->getName();?>">
      <input type="submit" value="Build"></td></tr>
      </table>
   </form>
   <?php 
}

/* units */
if(count($new_units) && ($population->getCivilians() > 0)){
   ?>
   <form action="action_process.php" method="POST">
   <table align="left" border="0" cellspacing="0" cellpadding="3">
   <tr><td>
   <select name="type">
   <?php
   for($b = 0; $b < count($new_units); $b++) { ?>
      <option value="<?php echo $new_units[$b]["type"];?>"><?php echo $new_units[$b]["type"]; echo "(".$new_units[$b]["cost"].")";?>
      <?php
   }
   ?>
   </select>
   <tr><td colspan="2" align="left">
   <font size="2">
   <input type="hidden" name="subaction" value="1">
   <input type="hidden" name="action" value="train">
   <input type="hidden" name="mark_key" value="<?php echo $mark_key;?>">
   <input type="hidden" name="focus_key" value="<?php echo $focus_key;?>">
   <input type="hidden" name="key" value="<?php echo $marked_land->getName();?>">
   <input type="hidden" name="name" value="<?php echo $character->getName();?>">
   <input type="submit" value="Train"></td></tr>
   </table>
   </form>
   <?php
}
?>

</div>


<div id="footer">
</div>
</div>

<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>

</body>
</html>
