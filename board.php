<?php
/*
 * This model sets the board, create the html for the DOM
 */

include_once("include/session.php");
include_once("include/html.php");
include_once("include/land_descr.php");
include_once("include/land_utils.php");
include_once("include/lands.php");
include_once("include/map.php");
include_once("include/character.php");
include_once("include/population.php");
include_once("include/treasury.php");

global $session;

/* get character */
$character = new Character();

/* get resources */
$population = new Population($character->getName());
$treasury = new Treasury($character->getName());

$x = 0;
$y = 0;

$map = new Map();

$html = new Html;
$html->header(FRAGMENTS_TITLE);
?>

<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr;
for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0;
i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document;
if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++)
x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++)
x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array;
for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc)
x.oSrc=x.src; x.src=a[i+2];}
}
</script>

<?php

$html->end_header();
?>

<body onload="MM_preloadImages('attackh.png','soldierh.png','defend2.png','stoph.png','img/attackh.png','img/soldierh.png','img/defend2.png','img/stoph.png','img/leftarrowh.png','img/uparrowh.png','img/downarrowh.png','img/rightarrowh.png')">

<div id="container">

<div id="map1">
<?php
$map->printMap($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE, 1);
?>
</div>

<div id="map2">
<?php 
$map->printMap($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE, 2);
?>
</div>

<div id="map3">
<?php 
$map->printMap($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE, 3);
?>
</div>

<div id="map4">
<?php 
$map->printMap($x, $y, X_LOCAL_MAP_SIZE, Y_LOCAL_MAP_SIZE, 4);
?>
</div>

<div id="topframe">
  <table width="100%" border="0" cellspacing="3" cellpadding="3">
    <tr>
       <td>MENU  | Gold: <?php echo $treasury->getGold();?> 
                 | Total Civilians: <?php echo $population->getCivilians();?>
                 | Tax: <?php echo $treasury->getTax(); ?>%
                 | Total Income: <?php echo $treasury->getIncome(); ?>
                 | Total Cost: Not done 
       </td>
    </tr>
  </table>
</div>

<div id="leftframe">
</div>
<div id="rightframe">
</div>
<div id="bottomframe">
</div>
<div id="topleftcorner">
</div>
<div id="toprightcorner">
</div>
<div id="bottomleftcorner">
<?php
if (DEV_VIEW) {
echo 'Dev view<br><div id="board_id"></div><div id="dclasses"></div><div id="cclasses"></div><div id="bclasses"></div><div id="aclasses"></div>';
}
?>
</div>
<div id="bottomrightcorner">
</div>

<div id="leftarrow"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image27','','img/leftarrowh.png',1)"><img src="img/leftarrow.png" alt="Left" name="Image27" width="24" height="134" border="0" id="Image27" /></a>
</div>

<div id="rightarrow"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('rightarrow','','img/rightarrowh.png',1)"><img src="img/rightarrow.png" alt="Right" name="rightarrow" width="24" height="134" border="0" id="rightarrow2" /></a>
</div> 
<a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('rightarrow','','img/rightarrowh.png',1)"></a>

<div id="toparrow"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('toparrow','','img/uparrowh.png',1)"><img src="img/uparrow.png" alt="Up" name="toparrow" width="134" height="24" border="0" id="toparrow2" /></a>
</div>

<div id="bottomarrow"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('downarrow','','img/downarrowh.png',1)"><img src="img/downarrow.png" alt="down" name="downarrow" width="134" height="24" border="0" id="downarrow" /></a>
</div>

<div id="actions">

<div id="bottomleft"><img src="img/wmarkarmy2.png" width="255" height="7" alt="ARMY" />
</div>

<div id="bottomcenter">
  <img src="img/wmarkselected2.png" alt="SELECTED" width="296" height="7" align="top" />
    <table width="100%" border="0" cellspacing="5" cellpadding="5">
      <tr>
        <td> Selected data goes here ... </td>
      </tr>
    </table>
  <p>&nbsp;</p>
</div>

<div id="bottomright"><img src="img/wmarkcommand2.png" width="296" height="7" alt="COMMAND" />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100" border="0" align="left" cellpadding="0" cellspacing="4" bordercolor="#000000" id="ACTIONS">
          <tr>
            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('attack','','img/attackh.png',1)"><img src="img/attack.png" alt="Attack" name="attack" width="44" height="44" border="0" id="attack" /></a></td>
            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Soldier','','img/soldierh.png',1)"><img src="img/soldier.png" alt="Soldier" name="Soldier" width="44" height="44" border="0" id="Soldier" /></a></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="ing/empty.png" alt="...X..." width="44" height="44" /></td>
            </tr>
          <tr>
            <td><img src="img/empty.png" width="44" height="44" alt="...X..." /></td>
            <td><img src="img/empty.png" width="44" height="44" alt="...X..." /></td>
            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Defend','','img/defendh.png',1)"><img src="img/defend.png" alt="Defend" name="Defend" width="44" height="44" border="0" id="Defend" /></a></td>
            <td><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Stop','','img/stoph.png',1)"><img src="img/stop.png" alt="cancel" name="Stop" width="44" height="44" border="0" id="Stop" /></a></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            <td><img src="img/empty.png" alt="...X..." width="44" height="44" /></td>
            </tr>
        </table></td>
      </tr>
    </table>
   </div
</div>  	

</div>        

<?php
$html->footer();
?>
