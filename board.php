<?php
/*
 * This model sets the board, create the html for the DOM
 */

include_once("include/session.php");
include_once("include/map_utils.php"); //needed?
include_once("include/html.php");
include_once("include/land_descr.php");
include_once("include/land_utils.php");
include_once("include/lands.php");
include_once("include/utils.php"); //needed?
include_once("include/character.php");
include_once("include/map.php");

global $session;

$x = 0;
$y = 0;

$map = new Map();

$html = new Html;
?>

<?php
$html->header(FRAGMENTS_TITLE);
//$html->end_header();
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
</div>

<div id="right">
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
      <td height="310" align="left" valign="top"><img src="img/mark.png" width="35" height="15" /><br>INFO:<br>Id: <div id="board_id"></div>BClasses: <div id="bclasses"></div>FClasses: <div id="fclasses"></div> <br>Coord: <div id="coord"></div>  <br>Toxic: <div id="toxic"></div> </td>
    </tr>
  </table>
</div>

<div id="loudspeaker">
</div>

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
</div>

<div id="selected">
</div>

<div id="action">
</div>

<div id="footer">
</div>

</div>

<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>

<?php
$html->footer();
?>