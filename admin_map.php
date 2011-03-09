<?php 
/**
 * admin_map.php
 * 
 * The admin_map module contains the generation and structure of the hexagon map
 * and and allows the admin to change it.
 *
 * Written by: matkar01
 */
include_once("include/session.php");
include_once("menu.php");
include_once("include/map_utils.php");
include_once("include/admin_map_utils.php");

global $session;
$database = $session->database;  //The database connection

if (isset($_GET["x"], $_GET["y"]))
{
  $x=$_GET["x"]; $y=$_GET["y"];
}
else if ((isset($_POST["x"])) && (isset($_POST["y"])))
{
  $x=$_POST["x"];
  $y=$_POST["y"];
}
else
{
  $x = 0;
  $y = 0;
}

$data = $database->map($x, $y, 5);
$i = 0;

$units = $database->units($x, $y, 5);
$j = 0;

menu1();
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<script src="utils.js" type="text/javascript"></script>
<title>Map <?php echo LAK_TITLE;?></title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	left:208px;
	top:145px;
	width:588px;
	height:332px;
	z-index:3;
	overflow: hidden;
	visibility: visible;
}
body,td,th {
	font-family: Georgia, Times New Roman, Times, serif;
	font-size: 12px;
	color: #333;
}
body {
	background-repeat: no-repeat;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#apDiv3 {
	position:absolute;
	left:167px;
	top:36px;
	width:756px;
	height:171px;
	z-index:1;
	overflow: visible;
	visibility: visible;
}
#apDiv4 {
	position:absolute;
	left:217px;
	top:145px;
	width:603px;
	height:354px;
	z-index:2;
	background-image: url(backgrounds/frame.png);
	visibility: visible;
}
a {
	font-family: Georgia, Times New Roman, Times, serif;
	font-size: 12px;
	color: #000;
}
a:visited {
	color: #000;
}
a:hover {
	color: #900;
}
a:active {
	color: #F00;
}
#apDiv5 {
	position:absolute;
	left:200px;
	top:137px;
	width:197px;
	height:116px;
	z-index:1;
	visibility: visible;
}
-->
</style>
</head>
<body onload="startTime()" class="q_body">

<div id="txt"></div>

<!-- the map -->
<div id="apDiv1">
<!-- land type and units -->

   <div style="position:relative; top:-21; left:-28; visibility: visible;z-index: 1;">

<!-- y = 5 -->
      <img style="position:absolute; left:<?php x_alignment($y+5, 0); ?>;  top:10;"  <?php map_img($data, $x-5, $y+5, $i); ?>>
      <?php if (is_unit($units, $x-5, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, -15); ?>; top:-50;" <?php get_unit_icon($units, $x-5, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 56); ?>;  top:10;"  <?php map_img($data, $x-4, $y+5, $i); ?>>
      <?php if (is_unit($units, $x-4, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 41); ?>; top:-50;" <?php get_unit_icon($units, $x-4, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 112); ?>; top:10;"  <?php map_img($data, $x-3, $y+5, $i); ?>>
      <?php if (is_unit($units, $x-3, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 93); ?>; top:-50;" <?php get_unit_icon($units, $x-3, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 168); ?>; top:10;"  <?php map_img($data, $x-2, $y+5, $i); ?>>
      <?php if (is_unit($units, $x-2, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 153); ?>; top:-50;" <?php get_unit_icon($units, $x-2, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 224); ?>; top:10;"  <?php map_img($data, $x-1, $y+5, $i); ?>>
      <?php if (is_unit($units, $x-1, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 209); ?>; top:-50;" <?php get_unit_icon($units, $x-1, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 280); ?>; top:10;"  <?php map_img($data, $x, $y+5, $i); ?>>
      <?php if (is_unit($units, $x, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 265); ?>; top:-50;" <?php get_unit_icon($units, $x, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 336); ?>; top:10;"  <?php map_img($data, $x+1, $y+5, $i); ?>>
      <?php if (is_unit($units, $x+1, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 321); ?>; top:-50;" <?php get_unit_icon($units, $x+1, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 392); ?>; top:10;"  <?php map_img($data, $x+2, $y+5, $i); ?>>
      <?php if (is_unit($units, $x+2, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 377); ?>; top:-50;" <?php get_unit_icon($units, $x+2, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 448); ?>; top:10;"  <?php map_img($data, $x+3, $y+5, $i); ?>>
      <?php if (is_unit($units, $x+3, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 433); ?>; top:-50;" <?php get_unit_icon($units, $x+3, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 504); ?>; top:10;"  <?php map_img($data, $x+4, $y+5, $i); ?>>
      <?php if (is_unit($units, $x+4, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 489); ?>; top:-50;" <?php get_unit_icon($units, $x+4, $y+5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+5, 560); ?>; top:10;"  <?php map_img($data, $x+5, $y+5, $i); ?>>
      <?php if (is_unit($units, $x+5, $y+5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+5, 545); ?>; top:-50;" <?php get_unit_icon($units, $x+5, $y+5, $j); ?>> <?php } ?>

<!-- y = 4 -->
      <img style="position:absolute; left:<?php x_alignment($y+4, 0); ?>;  top:41;"  <?php map_img($data, $x-5, $y+4, $i); ?>>
      <?php if (is_unit($units, $x-5, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, -15); ?>; top:-11;" <?php get_unit_icon($units, $x-5, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 56); ?>;  top:41;"  <?php map_img($data, $x-4, $y+4, $i); ?>>
      <?php if (is_unit($units, $x-4, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 41); ?>; top:-11;" <?php get_unit_icon($units, $x-4, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 112); ?>; top:41;"  <?php map_img($data, $x-3, $y+4, $i); ?>>
      <?php if (is_unit($units, $x-3, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 93); ?>; top:-11;" <?php get_unit_icon($units, $x-3, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 168); ?>; top:41;"  <?php map_img($data, $x-2, $y+4, $i); ?>>
      <?php if (is_unit($units, $x-2, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 153); ?>; top:-11;" <?php get_unit_icon($units, $x-2, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 224); ?>; top:41;"  <?php map_img($data, $x-1, $y+4, $i); ?>>
      <?php if (is_unit($units, $x-1, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 209); ?>; top:-11;" <?php get_unit_icon($units, $x-1, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 280); ?>; top:41;"  <?php map_img($data, $x, $y+4, $i); ?>>
      <?php if (is_unit($units, $x, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 265); ?>; top:-11;" <?php get_unit_icon($units, $x, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 336); ?>; top:41;"  <?php map_img($data, $x+1, $y+4, $i); ?>>
      <?php if (is_unit($units, $x+1, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 321); ?>; top:-11;" <?php get_unit_icon($units, $x+1, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 392); ?>; top:41;"  <?php map_img($data, $x+2, $y+4, $i); ?>>
      <?php if (is_unit($units, $x+2, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 377); ?>; top:-11;" <?php get_unit_icon($units, $x+2, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 448); ?>; top:41;"  <?php map_img($data, $x+3, $y+4, $i); ?>>
      <?php if (is_unit($units, $x+3, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 433); ?>; top:-11;" <?php get_unit_icon($units, $x+3, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 504); ?>; top:41;"  <?php map_img($data, $x+4, $y+4, $i); ?>>
      <?php if (is_unit($units, $x+4, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 489); ?>; top:-11;" <?php get_unit_icon($units, $x+4, $y+4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+4, 560); ?>; top:41;"  <?php map_img($data, $x+5, $y+4, $i); ?>>
      <?php if (is_unit($units, $x+5, $y+4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+4, 545); ?>; top:-11;" <?php get_unit_icon($units, $x+5, $y+4, $j); ?>> <?php } ?>

<!-- y = 3 -->
      <img style="position:absolute; left:<?php x_alignment($y+3, 0); ?>;   top:72;"  <?php map_img($data, $x-5, $y+3, $i); ?>>
      <?php if (is_unit($units, $x-5, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, -15); ?>; top:18;" <?php get_unit_icon($units, $x-5, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 56); ?>;  top:72;"  <?php map_img($data, $x-4, $y+3, $i); ?>>
      <?php if (is_unit($units, $x-4, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 41); ?>; top:18;" <?php get_unit_icon($units, $x-4, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 112); ?>; top:72;"  <?php map_img($data, $x-3, $y+3, $i); ?>>
      <?php if (is_unit($units, $x-3, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 93); ?>; top:18;" <?php get_unit_icon($units, $x-3, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 168); ?>; top:72;"  <?php map_img($data, $x-2, $y+3, $i); ?>>
      <?php if (is_unit($units, $x-2, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 153); ?>; top:18;" <?php get_unit_icon($units, $x-2, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 224); ?>; top:72;"  <?php map_img($data, $x-1, $y+3, $i); ?>>
      <?php if (is_unit($units, $x-1, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 209); ?>; top:18;" <?php get_unit_icon($units, $x-1, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 280); ?>; top:72;"  <?php map_img($data, $x, $y+3, $i); ?>>
      <?php if (is_unit($units, $x, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 265); ?>; top:18;" <?php get_unit_icon($units, $x, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 336); ?>; top:72;"  <?php map_img($data, $x+1, $y+3, $i); ?>>
      <?php if (is_unit($units, $x+1, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 321); ?>; top:18;" <?php get_unit_icon($units, $x+1, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 392); ?>; top:72;"  <?php map_img($data, $x+2, $y+3, $i); ?>>
      <?php if (is_unit($units, $x+2, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 377); ?>; top:18;" <?php get_unit_icon($units, $x+2, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 448); ?>; top:72;"  <?php map_img($data, $x+3, $y+3, $i); ?>>
      <?php if (is_unit($units, $x+3, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 433); ?>; top:18;" <?php get_unit_icon($units, $x+3, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 504); ?>; top:72;"  <?php map_img($data, $x+4, $y+3, $i); ?>>
      <?php if (is_unit($units, $x+4, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 489); ?>; top:18;" <?php get_unit_icon($units, $x+4, $y+3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+3, 560); ?>; top:72;"  <?php map_img($data, $x+5, $y+3, $i); ?>>
      <?php if (is_unit($units, $x+5, $y+3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+3, 545); ?>; top:18;" <?php get_unit_icon($units, $x+5, $y+3, $j); ?>> <?php } ?>

<!-- y = 2 -->
      <img style="position:absolute; left:<?php x_alignment($y+2, 0); ?>;  top:103;" <?php map_img($data, $x-5, $y+2, $i); ?>>
      <?php if (is_unit($units, $x-5, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, -15); ?>; top:53;" <?php get_unit_icon($units, $x-5, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 56); ?>;  top:103;" <?php map_img($data, $x-4, $y+2, $i); ?>>
      <?php if (is_unit($units, $x-4, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 41); ?>; top:53;" <?php get_unit_icon($units, $x-4, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 112); ?>; top:103;" <?php map_img($data, $x-3, $y+2, $i); ?>>
      <?php if (is_unit($units, $x-3, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 93); ?>; top:53;" <?php get_unit_icon($units, $x-3, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 168); ?>; top:103;" <?php map_img($data, $x-2, $y+2, $i); ?>>
      <?php if (is_unit($units, $x-2, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 153); ?>; top:53;" <?php get_unit_icon($units, $x-2, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 224); ?>; top:103;" <?php map_img($data, $x-1, $y+2, $i); ?>>
      <?php if (is_unit($units, $x-1, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 209); ?>; top:53;" <?php get_unit_icon($units, $x-1, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 280); ?>; top:103;" <?php map_img($data, $x, $y+2, $i); ?>>
      <?php if (is_unit($units, $x, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 265); ?>; top:53;" <?php get_unit_icon($units, $x, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 336); ?>; top:103;" <?php map_img($data, $x+1, $y+2, $i); ?>>
      <?php if (is_unit($units, $x+1, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 321); ?>; top:53;" <?php get_unit_icon($units, $x+1, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 392); ?>; top:103;" <?php map_img($data, $x+2, $y+2, $i); ?>>
      <?php if (is_unit($units, $x+2, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 377); ?>; top:53;" <?php get_unit_icon($units, $x+2, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 448); ?>; top:103;" <?php map_img($data, $x+3, $y+2, $i); ?>>
      <?php if (is_unit($units, $x+3, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 433); ?>; top:53;" <?php get_unit_icon($units, $x+3, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 504); ?>; top:103;" <?php map_img($data, $x+4, $y+2, $i); ?>>
      <?php if (is_unit($units, $x+4, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 489); ?>; top:53;" <?php get_unit_icon($units, $x+4, $y+2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+2, 560); ?>; top:103;" <?php map_img($data, $x+5, $y+2, $i); ?>>
      <?php if (is_unit($units, $x+5, $y+2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+2, 545); ?>; top:53;" <?php get_unit_icon($units, $x+5, $y+2, $j); ?>> <?php } ?>

<!-- y = 1 -->
      <img style="position:absolute; left:<?php x_alignment($y+1, 0); ?>;   top:134;" <?php map_img($data, $x-5, $y+1, $i); ?>>
      <?php if (is_unit($units, $x-5, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, -15); ?>; top:84;" <?php get_unit_icon($units, $x-5, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 56); ?>;  top:134;" <?php map_img($data, $x-4, $y+1, $i); ?>>
      <?php if (is_unit($units, $x-4, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 41); ?>; top:84;" <?php get_unit_icon($units, $x-4, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 112); ?>; top:134;" <?php map_img($data, $x-3, $y+1, $i); ?>>
      <?php if (is_unit($units, $x-3, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 93); ?>; top:84;" <?php get_unit_icon($units, $x-3, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 168); ?>; top:134;" <?php map_img($data, $x-2, $y+1, $i); ?>>
      <?php if (is_unit($units, $x-2, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 153); ?>; top:84;" <?php get_unit_icon($units, $x-2, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 224); ?>; top:134;" <?php map_img($data, $x-1, $y+1, $i); ?>>
      <?php if (is_unit($units, $x-1, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 209); ?>; top:84;" <?php get_unit_icon($units, $x-1, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 280); ?>; top:134;" <?php map_img($data, $x, $y+1, $i); ?>>
      <?php if (is_unit($units, $x, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 265); ?>; top:84;" <?php get_unit_icon($units, $x, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 336); ?>; top:134;" <?php map_img($data, $x+1, $y+1, $i); ?>>
      <?php if (is_unit($units, $x+1, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 321); ?>; top:84;" <?php get_unit_icon($units, $x+1, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 392); ?>; top:134;" <?php map_img($data, $x+2, $y+1, $i); ?>>
      <?php if (is_unit($units, $x+2, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 377); ?>; top:84;" <?php get_unit_icon($units, $x+2, $y+1, $j); ?>> <?php } ?>      
      <img style="position:absolute; left:<?php x_alignment($y+1, 448); ?>; top:134;" <?php map_img($data, $x+3, $y+1, $i); ?>>
      <?php if (is_unit($units, $x+3, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 433); ?>; top:84;" <?php get_unit_icon($units, $x+3, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 504); ?>; top:134;" <?php map_img($data, $x+4, $y+1, $i); ?>>
      <?php if (is_unit($units, $x+4, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 489); ?>; top:84;" <?php get_unit_icon($units, $x+4, $y+1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y+1, 560); ?>; top:134;" <?php map_img($data, $x+5, $y+1, $i); ?>>
      <?php if (is_unit($units, $x+5, $y+1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y+1, 545); ?>; top:84;" <?php get_unit_icon($units, $x+5, $y+1, $j); ?>> <?php } ?>

<!-- y = 0 -->
      <img style="position:absolute; left:<?php x_alignment($y, 0); ?>;  top:165;" <?php map_img($data, $x-5, $y, $i); ?>>
      <?php if (is_unit($units, $x-5, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, -15); ?>; top:115;" <?php get_unit_icon($units, $x-5, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 56); ?>;  top:165;" <?php map_img($data, $x-4, $y, $i); ?>>
      <?php if (is_unit($units, $x-4, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 41); ?>; top:115;" <?php get_unit_icon($units, $x-4, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 112); ?>; top:165;" <?php map_img($data, $x-3, $y, $i); ?>>
      <?php if (is_unit($units, $x-3, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 93); ?>; top:115;" <?php get_unit_icon($units, $x-3, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 168); ?>; top:165;" <?php map_img($data, $x-2, $y, $i); ?>>
      <?php if (is_unit($units, $x-2, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 153); ?>; top:115;" <?php get_unit_icon($units, $x-2, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 224); ?>; top:165;" <?php map_img($data, $x-1, $y, $i); ?>>
      <?php if (is_unit($units, $x-1, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 209); ?>; top:115;" <?php get_unit_icon($units, $x-1, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 280); ?>; top:165;" <?php map_img($data, $x, $y, $i); ?>>
      <?php if (is_unit($units, $x, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 265); ?>; top:115;" <?php get_unit_icon($units, $x, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 336); ?>; top:165;" <?php map_img($data, $x+1, $y, $i); ?>>
      <?php if (is_unit($units, $x+1, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 321); ?>; top:115;" <?php get_unit_icon($units, $x+1, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 392); ?>; top:165;" <?php map_img($data, $x+2, $y, $i); ?>>
      <?php if (is_unit($units, $x+2, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 377); ?>; top:115;" <?php get_unit_icon($units, $x+2, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 448); ?>; top:165;" <?php map_img($data, $x+3, $y, $i); ?>>
      <?php if (is_unit($units, $x+3, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 433); ?>; top:115;" <?php get_unit_icon($units, $x+3, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 504); ?>; top:165;" <?php map_img($data, $x+4, $y, $i); ?>>
      <?php if (is_unit($units, $x+4, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 489); ?>; top:115;" <?php get_unit_icon($units, $x+4, $y, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y, 560); ?>; top:165;" <?php map_img($data, $x+5, $y, $i); ?>>
      <?php if (is_unit($units, $x+5, $y, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y, 545); ?>; top:115;" <?php get_unit_icon($units, $x+5, $y, $j); ?>> <?php } ?>

<!-- y = -1 -->
      <img style="position:absolute; left:<?php x_alignment($y-1, 0); ?>;  top:196;" <?php map_img($data, $x-5, $y-1, $i); ?>>
      <?php if (is_unit($units, $x-5, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, -15); ?>; top:146;" <?php get_unit_icon($units, $x-5, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 56); ?>;  top:196;" <?php map_img($data, $x-4, $y-1, $i); ?>>
      <?php if (is_unit($units, $x-4, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 41); ?>; top:146;" <?php get_unit_icon($units, $x-4, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 112); ?>; top:196;" <?php map_img($data, $x-3, $y-1, $i); ?>>
      <?php if (is_unit($units, $x-3, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 93); ?>; top:146;" <?php get_unit_icon($units, $x-3, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 168); ?>; top:196;" <?php map_img($data, $x-2, $y-1, $i); ?>>
      <?php if (is_unit($units, $x-2, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 153); ?>; top:146;" <?php get_unit_icon($units, $x-2, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 224); ?>; top:196;" <?php map_img($data, $x-1, $y-1, $i); ?>>
      <?php if (is_unit($units, $x-1, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 209); ?>; top:146;" <?php get_unit_icon($units, $x-1, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 280); ?>; top:196;" <?php map_img($data, $x, $y-1, $i); ?>>
      <?php if (is_unit($units, $x, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 265); ?>; top:146;" <?php get_unit_icon($units, $x, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 336); ?>; top:196;" <?php map_img($data, $x+1, $y-1, $i); ?>>
      <?php if (is_unit($units, $x+1, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 321); ?>; top:146;" <?php get_unit_icon($units, $x+1, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 392); ?>; top:196;" <?php map_img($data, $x+2, $y-1, $i); ?>>
      <?php if (is_unit($units, $x+2, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 377); ?>; top:146;" <?php get_unit_icon($units, $x+2, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 448); ?>; top:196;" <?php map_img($data, $x+3, $y-1, $i); ?>>
      <?php if (is_unit($units, $x+3, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 433); ?>; top:146;" <?php get_unit_icon($units, $x+3, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 504); ?>; top:196;" <?php map_img($data, $x+4, $y-1, $i); ?>>
      <?php if (is_unit($units, $x+4, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 489); ?>; top:146;" <?php get_unit_icon($units, $x+4, $y-1, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-1, 560); ?>; top:196;" <?php map_img($data, $x+5, $y-1, $i); ?>>
      <?php if (is_unit($units, $x+5, $y-1, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-1, 545); ?>; top:146;" <?php get_unit_icon($units, $x+5, $y-1, $j); ?>> <?php } ?>

<!-- y = -2 -->
      <img style="position:absolute; left:<?php x_alignment($y-2, 0); ?>;  top:227;" <?php map_img($data, $x-5, $y-2, $i); ?>>
      <?php if (is_unit($units, $x-5, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, -15); ?>; top:177;" <?php get_unit_icon($units, $x-5, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 56); ?>;  top:227;" <?php map_img($data, $x-4, $y-2, $i); ?>>
      <?php if (is_unit($units, $x-4, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 41); ?>; top:177;" <?php get_unit_icon($units, $x-4, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 112); ?>; top:227;" <?php map_img($data, $x-3, $y-2, $i); ?>>
      <?php if (is_unit($units, $x-3, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 93); ?>; top:177;" <?php get_unit_icon($units, $x-3, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 168); ?>; top:227;" <?php map_img($data, $x-2, $y-2, $i); ?>>
      <?php if (is_unit($units, $x-2, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 153); ?>; top:177;" <?php get_unit_icon($units, $x-2, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 224); ?>; top:227;" <?php map_img($data, $x-1, $y-2, $i); ?>>
      <?php if (is_unit($units, $x-1, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 209); ?>; top:177;" <?php get_unit_icon($units, $x-1, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 280); ?>; top:227;" <?php map_img($data, $x, $y-2, $i); ?>>
      <?php if (is_unit($units, $x, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 265); ?>; top:177;" <?php get_unit_icon($units, $x, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 336); ?>; top:227;" <?php map_img($data, $x+1, $y-2, $i); ?>>
      <?php if (is_unit($units, $x+1, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 321); ?>; top:177;" <?php get_unit_icon($units, $x+1, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 392); ?>; top:227;" <?php map_img($data, $x+2, $y-2, $i); ?>>
      <?php if (is_unit($units, $x+2, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 377); ?>; top:177;" <?php get_unit_icon($units, $x+2, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 448); ?>; top:227;" <?php map_img($data, $x+3, $y-2, $i); ?>>
      <?php if (is_unit($units, $x+3, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 433); ?>; top:177;" <?php get_unit_icon($units, $x+3, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 504); ?>; top:227;" <?php map_img($data, $x+4, $y-2, $i); ?>>
      <?php if (is_unit($units, $x+4, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 489); ?>; top:177;" <?php get_unit_icon($units, $x+4, $y-2, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-2, 560); ?>; top:227;" <?php map_img($data, $x+5, $y-2, $i); ?>>
      <?php if (is_unit($units, $x+5, $y-2, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-2, 545); ?>; top:177;" <?php get_unit_icon($units, $x+5, $y-2, $j); ?>> <?php } ?>

<!-- y = -3 -->
      <img style="position:absolute; left:<?php x_alignment($y-3, 0); ?>;   top:258;" <?php map_img($data, $x-5, $y-3, $i); ?>>
      <?php if (is_unit($units, $x-5, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, -15); ?>; top:208;" <?php get_unit_icon($units, $x-5, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 56); ?>;  top:258;" <?php map_img($data, $x-4, $y-3, $i); ?>>
      <?php if (is_unit($units, $x-4, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 41); ?>; top:208;" <?php get_unit_icon($units, $x-4, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 112); ?>; top:258;" <?php map_img($data, $x-3, $y-3, $i); ?>>
      <?php if (is_unit($units, $x-3, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 93); ?>; top:208;" <?php get_unit_icon($units, $x-3, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 168); ?>; top:258;" <?php map_img($data, $x-2, $y-3, $i); ?>>
      <?php if (is_unit($units, $x-2, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 153); ?>; top:208;" <?php get_unit_icon($units, $x-2, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 224); ?>; top:258;" <?php map_img($data, $x-1, $y-3, $i); ?>>
      <?php if (is_unit($units, $x-1, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 207); ?>; top:208;" <?php get_unit_icon($units, $x-1, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 280); ?>; top:258;" <?php map_img($data, $x, $y-3, $i); ?>>
      <?php if (is_unit($units, $x, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 265); ?>; top:208;" <?php get_unit_icon($units, $x, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 336); ?>; top:258;" <?php map_img($data, $x+1, $y-3, $i); ?>>
      <?php if (is_unit($units, $x+1, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 321); ?>; top:208;" <?php get_unit_icon($units, $x+1, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 392); ?>; top:258;" <?php map_img($data, $x+2, $y-3, $i); ?>>
      <?php if (is_unit($units, $x+2, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 377); ?>; top:208;" <?php get_unit_icon($units, $x+2, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 448); ?>; top:258;" <?php map_img($data, $x+3, $y-3, $i); ?>>
      <?php if (is_unit($units, $x+3, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 433); ?>; top:208;" <?php get_unit_icon($units, $x+3, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 504); ?>; top:258;" <?php map_img($data, $x+4, $y-3, $i); ?>>
      <?php if (is_unit($units, $x+4, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 489); ?>; top:208;" <?php get_unit_icon($units, $x+4, $y-3, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-3, 560); ?>; top:258;" <?php map_img($data, $x+5, $y-3, $i); ?>>
      <?php if (is_unit($units, $x+5, $y-3, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-3, 545); ?>; top:208;" <?php get_unit_icon($units, $x+5, $y-3, $j); ?>> <?php } ?>

<!-- y = -4 -->
      <img style="position:absolute; left:<?php x_alignment($y-4, 0); ?>;   top:289;" <?php map_img($data, $x-5, $y-4, $i); ?>>
      <?php if (is_unit($units, $x-5, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, -15); ?>; top:239;" <?php get_unit_icon($units, $x-5, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 56); ?>;  top:289;" <?php map_img($data, $x-4, $y-4, $i); ?>>
      <?php if (is_unit($units, $x-4, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 41); ?>; top:239;" <?php get_unit_icon($units, $x-4, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 112); ?>; top:289;" <?php map_img($data, $x-3, $y-4, $i); ?>>
      <?php if (is_unit($units, $x-3, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 93); ?>; top:239;" <?php get_unit_icon($units, $x-3, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 168); ?>; top:289;" <?php map_img($data, $x-2, $y-4, $i); ?>>
      <?php if (is_unit($units, $x-2, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 153); ?>; top:239;" <?php get_unit_icon($units, $x-2, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 224); ?>; top:289;" <?php map_img($data, $x-1, $y-4, $i); ?>>
      <?php if (is_unit($units, $x-1, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 209); ?>; top:239;" <?php get_unit_icon($units, $x-1, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 280); ?>; top:289;" <?php map_img($data, $x, $y-4, $i); ?>>
      <?php if (is_unit($units, $x, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 265); ?>; top:239;" <?php get_unit_icon($units, $x, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 336); ?>; top:289;" <?php map_img($data, $x+1, $y-4, $i); ?>>
      <?php if (is_unit($units, $x+1, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 321); ?>; top:239;" <?php get_unit_icon($units, $x+1, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 392); ?>; top:289;" <?php map_img($data, $x+2, $y-4, $i); ?>>
      <?php if (is_unit($units, $x+2, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 377); ?>; top:239;" <?php get_unit_icon($units, $x+2, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 448); ?>; top:289;" <?php map_img($data, $x+3, $y-4, $i); ?>>
      <?php if (is_unit($units, $x+3, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 433); ?>; top:239;" <?php get_unit_icon($units, $x+3, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 504); ?>; top:289;" <?php map_img($data, $x+4, $y-4, $i); ?>>
      <?php if (is_unit($units, $x+4, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 489); ?>; top:239;" <?php get_unit_icon($units, $x+4, $y-4, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-4, 560); ?>; top:289;" <?php map_img($data, $x+5, $y-4, $i); ?>>
      <?php if (is_unit($units, $x+5, $y-4, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-4, 545); ?>; top:239;" <?php get_unit_icon($units, $x+5, $y-4, $j); ?>> <?php } ?>

<!-- y = -5 -->
      <img style="position:absolute; left:<?php x_alignment($y-5, 0); ?>;   top:320;" <?php map_img($data, $x-5, $y-5, $i); ?>>
      <?php if (is_unit($units, $x-5, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, -15); ?>; top:270;" <?php get_unit_icon($units, $x-5, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 56); ?>;  top:320;" <?php map_img($data, $x-4, $y-5, $i); ?>>
      <?php if (is_unit($units, $x-4, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 41); ?>; top:270;" <?php get_unit_icon($units, $x-4, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 112); ?>; top:320;" <?php map_img($data, $x-3, $y-5, $i); ?>>
      <?php if (is_unit($units, $x-3, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 93); ?>; top:270;" <?php get_unit_icon($units, $x-3, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 168); ?>; top:320;" <?php map_img($data, $x-2, $y-5, $i); ?>>
      <?php if (is_unit($units, $x-2, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 153); ?>; top:270;" <?php get_unit_icon($units, $x-2, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 224); ?>; top:320;" <?php map_img($data, $x-1, $y-5, $i); ?>>
      <?php if (is_unit($units, $x-1, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 209); ?>; top:270;" <?php get_unit_icon($units, $x-1, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 280); ?>; top:320;" <?php map_img($data, $x, $y-5, $i); ?>>
      <?php if (is_unit($units, $x, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 265); ?>; top:270;" <?php get_unit_icon($units, $x, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 336); ?>; top:320;" <?php map_img($data, $x+1, $y-5, $i); ?>>
      <?php if (is_unit($units, $x+1, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 321); ?>; top:270;" <?php get_unit_icon($units, $x+1, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 392); ?>; top:320;" <?php map_img($data, $x+2, $y-5, $i); ?>>
      <?php if (is_unit($units, $x+2, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 377); ?>; top:270;" <?php get_unit_icon($units, $x+2, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 448); ?>; top:320;" <?php map_img($data, $x+3, $y-5, $i); ?>>
      <?php if (is_unit($units, $x+3, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 433); ?>; top:270;" <?php get_unit_icon($units, $x+3, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 504); ?>; top:320;" <?php map_img($data, $x+4, $y-5, $i); ?>>
      <?php if (is_unit($units, $x+4, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 489); ?>; top:270;" <?php get_unit_icon($units, $x+4, $y-5, $j); ?>> <?php } ?>
      <img style="position:absolute; left:<?php x_alignment($y-5, 560); ?>; top:320;" <?php map_img($data, $x+5, $y-5, $i); ?>>
      <?php if (is_unit($units, $x+5, $y-5, $j)){ ?> <img style="position:absolute; left:<?php x_alignment($y-5, 545); ?>; top:270;" <?php get_unit_icon($units, $x+5, $y-5, $j); ?>> <?php } ?>

    <img src="backgrounds/trans_bg.gif" usemap="#Map" style="position: absolute; top: 9px; visibility: visible; z-index: 3; width: 628px; height: 362px; left: 1px; background-image: url(backgrounds/frame2.png); layer-background-image: url(backgrounds/frame2.png); border: 1px none #000000;" border="0" height="350" width="644">
   <map name="Map" id="Map">

	<?php
      // reset $i;
      $i = 0;
      $j = 0;
   ?>

<!-- links -->

<!-- y = 5 -->
        <area shape="poly" coords="<?php x_alignment($y+5, 28); ?>,0,  <?php x_alignment($y+5, 56); ?>,10  <?php x_alignment($y+5, 56); ?>,31  <?php x_alignment($y+5, 28); ?>,43  <?php x_alignment($y+5, 0); ?>,31   <?php x_alignment($y+5, 0); ?>,10" <?php admin_map_lnk($data, $x-5, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 84); ?>,0,  <?php x_alignment($y+5, 112); ?>,10 <?php x_alignment($y+5, 112); ?>,31 <?php x_alignment($y+5, 84); ?>,43  <?php x_alignment($y+5, 56); ?>,31  <?php x_alignment($y+5, 56); ?>,10" <?php admin_map_lnk($data, $x-4, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 140); ?>,0, <?php x_alignment($y+5, 168); ?>,10 <?php x_alignment($y+5, 168); ?>,31 <?php x_alignment($y+5, 140); ?>,43 <?php x_alignment($y+5, 112); ?>,31  <?php x_alignment($y+5, 112); ?>,10" <?php admin_map_lnk($data, $x-3, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 196); ?>,0, <?php x_alignment($y+5, 224); ?>,10 <?php x_alignment($y+5, 224); ?>,31 <?php x_alignment($y+5, 196); ?>,43 <?php x_alignment($y+5, 168); ?>,31 <?php x_alignment($y+5, 168); ?>,10" <?php admin_map_lnk($data, $x-2, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 252); ?>,0, <?php x_alignment($y+5, 280); ?>,10 <?php x_alignment($y+5, 280); ?>,31 <?php x_alignment($y+5, 252); ?>,43 <?php x_alignment($y+5, 224); ?>,31 <?php x_alignment($y+5, 224); ?>,10" <?php admin_map_lnk($data, $x-1, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 308); ?>,0, <?php x_alignment($y+5, 336); ?>,10 <?php x_alignment($y+5, 336); ?>,31 <?php x_alignment($y+5, 308); ?>,43 <?php x_alignment($y+5, 280); ?>,31 <?php x_alignment($y+5, 280); ?>,10" <?php admin_map_lnk($data, $x, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 364); ?>,0, <?php x_alignment($y+5, 392); ?>,10 <?php x_alignment($y+5, 392); ?>,31 <?php x_alignment($y+5, 362); ?>,43 <?php x_alignment($y+5, 336); ?>,31 <?php x_alignment($y+5, 336); ?>,10" <?php admin_map_lnk($data, $x+1, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 420); ?>,0, <?php x_alignment($y+5, 448); ?>,10 <?php x_alignment($y+5, 448); ?>,31 <?php x_alignment($y+5, 420); ?>,43 <?php x_alignment($y+5, 392); ?>,31 <?php x_alignment($y+5, 392); ?>,10" <?php admin_map_lnk($data, $x+2, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 476); ?>,0, <?php x_alignment($y+5, 504); ?>,10 <?php x_alignment($y+5, 504); ?>,31 <?php x_alignment($y+5, 476); ?>,43 <?php x_alignment($y+5, 448); ?>,31 <?php x_alignment($y+5, 448); ?>,10" <?php admin_map_lnk($data, $x+3, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 532); ?>,0, <?php x_alignment($y+5, 560); ?>,10 <?php x_alignment($y+5, 560); ?>,31 <?php x_alignment($y+5, 532); ?>,43 <?php x_alignment($y+5, 504); ?>,31 <?php x_alignment($y+5, 504); ?>,10" <?php admin_map_lnk($data, $x+4, $y+5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+5, 588); ?>,0, <?php x_alignment($y+5, 616); ?>,10 <?php x_alignment($y+5, 616); ?>,31 <?php x_alignment($y+5, 588); ?>,43 <?php x_alignment($y+5, 560); ?>,31 <?php x_alignment($y+5, 560); ?>,10" <?php admin_map_lnk($data, $x+5, $y+5, $i); ?>>

<!-- y = 4 -->
        <area shape="poly" coords="<?php x_alignment($y+4, 28); ?>,31, <?php x_alignment($y+4, 56); ?>,43  <?php x_alignment($y+4, 56); ?>,64  <?php x_alignment($y+4, 28); ?>,74  <?php x_alignment($y+4, 0); ?>,64   <?php x_alignment($y+4, 0); ?>,43" <?php admin_map_lnk($data, $x-5, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 84); ?>,31,  <?php x_alignment($y+4, 112); ?>,43 <?php x_alignment($y+4, 112); ?>,64 <?php x_alignment($y+4, 84); ?>,74  <?php x_alignment($y+4, 56); ?>,64  <?php x_alignment($y+4, 56); ?>,43" <?php admin_map_lnk($data, $x-4, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 140); ?>,31, <?php x_alignment($y+4, 168); ?>,43 <?php x_alignment($y+4, 168); ?>,64 <?php x_alignment($y+4, 140); ?>,74 <?php x_alignment($y+4, 112); ?>,64  <?php x_alignment($y+4, 112); ?>,43" <?php admin_map_lnk($data, $x-3, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 196); ?>,31, <?php x_alignment($y+4, 224); ?>,43 <?php x_alignment($y+4, 224); ?>,64 <?php x_alignment($y+4, 196); ?>,74 <?php x_alignment($y+4, 168); ?>,64 <?php x_alignment($y+4, 168); ?>,43" <?php admin_map_lnk($data, $x-2, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 252); ?>,31, <?php x_alignment($y+4, 280); ?>,43 <?php x_alignment($y+4, 280); ?>,64 <?php x_alignment($y+4, 252); ?>,74 <?php x_alignment($y+4, 224); ?>,64 <?php x_alignment($y+4, 224); ?>,43" <?php admin_map_lnk($data, $x-1, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 308); ?>,31, <?php x_alignment($y+4, 336); ?>,43 <?php x_alignment($y+4, 336); ?>,64 <?php x_alignment($y+4, 308); ?>,74 <?php x_alignment($y+4, 280); ?>,64 <?php x_alignment($y+4, 280); ?>,43" <?php admin_map_lnk($data, $x, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 364); ?>,31, <?php x_alignment($y+4, 392); ?>,43 <?php x_alignment($y+4, 392); ?>,64 <?php x_alignment($y+4, 364); ?>,74 <?php x_alignment($y+4, 336); ?>,64 <?php x_alignment($y+4, 336); ?>,43" <?php admin_map_lnk($data, $x+1, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 420); ?>,31, <?php x_alignment($y+4, 448); ?>,43 <?php x_alignment($y+4, 448); ?>,64 <?php x_alignment($y+4, 420); ?>,74 <?php x_alignment($y+4, 392); ?>,64 <?php x_alignment($y+4, 392); ?>,43" <?php admin_map_lnk($data, $x+2, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 476); ?>,31, <?php x_alignment($y+4, 504); ?>,43 <?php x_alignment($y+4, 504); ?>,64 <?php x_alignment($y+4, 476); ?>,74 <?php x_alignment($y+4, 448); ?>,64 <?php x_alignment($y+4, 448); ?>,43" <?php admin_map_lnk($data, $x+3, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 532); ?>,0, <?php x_alignment($y+4, 560); ?>,43 <?php x_alignment($y+4, 560); ?>,64 <?php x_alignment($y+4, 532); ?>,74 <?php x_alignment($y+4, 504); ?>,64 <?php x_alignment($y+4, 504); ?>,43" <?php admin_map_lnk($data, $x+4, $y+4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+4, 588); ?>,0, <?php x_alignment($y+4, 616); ?>,43 <?php x_alignment($y+4, 616); ?>,64 <?php x_alignment($y+4, 588); ?>,74 <?php x_alignment($y+4, 560); ?>,64 <?php x_alignment($y+4, 560); ?>,43" <?php admin_map_lnk($data, $x+5, $y+4, $i); ?>>

<!-- y = 3 -->
        <area shape="poly" coords="<?php x_alignment($y+3, 28); ?>,63,  <?php x_alignment($y+3, 56); ?>,73  <?php x_alignment($y+3, 56); ?>,94  <?php x_alignment($y+3, 28); ?>,106  <?php x_alignment($y+3, 0); ?>,94   <?php x_alignment($y+3, 0); ?>,73" <?php admin_map_lnk($data, $x-5, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 84); ?>,63,  <?php x_alignment($y+3, 112); ?>,73 <?php x_alignment($y+3, 112); ?>,94 <?php x_alignment($y+3, 84); ?>,106  <?php x_alignment($y+3,56); ?>,94  <?php x_alignment($y+3, 56); ?>,73" <?php admin_map_lnk($data, $x-4, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 140); ?>,63, <?php x_alignment($y+3, 168); ?>,73 <?php x_alignment($y+3, 168); ?>,94 <?php x_alignment($y+3, 140); ?>,106 <?php x_alignment($y+3, 112); ?>,94  <?php x_alignment($y+3, 112); ?>,73" <?php admin_map_lnk($data, $x-3, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 196); ?>,63, <?php x_alignment($y+3, 224); ?>,73 <?php x_alignment($y+3, 224); ?>,94 <?php x_alignment($y+3, 196); ?>,106 <?php x_alignment($y+3, 168); ?>,94 <?php x_alignment($y+3, 168); ?>,73" <?php admin_map_lnk($data, $x-2, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 252); ?>,63, <?php x_alignment($y+3, 280); ?>,73 <?php x_alignment($y+3, 280); ?>,94 <?php x_alignment($y+3, 252); ?>,106 <?php x_alignment($y+3, 224); ?>,94 <?php x_alignment($y+3, 224); ?>,73" <?php admin_map_lnk($data, $x-1, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 308); ?>,63, <?php x_alignment($y+3, 336); ?>,73 <?php x_alignment($y+3, 336); ?>,94 <?php x_alignment($y+3, 308); ?>,106 <?php x_alignment($y+3, 280); ?>,94 <?php x_alignment($y+3, 280); ?>,73" <?php admin_map_lnk($data, $x, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 364); ?>,63, <?php x_alignment($y+3, 392); ?>,73 <?php x_alignment($y+3, 392); ?>,94 <?php x_alignment($y+3, 364); ?>,106 <?php x_alignment($y+3, 336); ?>,94 <?php x_alignment($y+3, 336); ?>,73" <?php admin_map_lnk($data, $x+1, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 420); ?>,63, <?php x_alignment($y+3, 448); ?>,73 <?php x_alignment($y+3, 448); ?>,94 <?php x_alignment($y+3, 420); ?>,106 <?php x_alignment($y+3, 392); ?>,94 <?php x_alignment($y+3, 392); ?>,73" <?php admin_map_lnk($data, $x+2, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 476); ?>,63, <?php x_alignment($y+3, 504); ?>,73 <?php x_alignment($y+3, 504); ?>,94 <?php x_alignment($y+3, 476); ?>,106 <?php x_alignment($y+3, 448); ?>,94 <?php x_alignment($y+3, 448); ?>,73" <?php admin_map_lnk($data, $x+3, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 532); ?>,63, <?php x_alignment($y+3, 560); ?>,73 <?php x_alignment($y+3, 560); ?>,94 <?php x_alignment($y+3, 532); ?>,106 <?php x_alignment($y+3, 504); ?>,94 <?php x_alignment($y+3, 504); ?>,73" <?php admin_map_lnk($data, $x+4, $y+3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+3, 588); ?>,63, <?php x_alignment($y+3, 616); ?>,73 <?php x_alignment($y+3, 616); ?>,94 <?php x_alignment($y+3, 588); ?>,106 <?php x_alignment($y+3, 560); ?>,94 <?php x_alignment($y+3, 560); ?>,73" <?php admin_map_lnk($data, $x+5, $y+3, $i); ?>>

<!-- y = 2 -->
        <area shape="poly" coords="<?php x_alignment($y+2, 28); ?>,94,  <?php x_alignment($y+2, 56); ?>,106  <?php x_alignment($y+2, 56); ?>,127  <?php x_alignment($y+2, 28); ?>,137  <?php x_alignment($y+2, 0); ?>,127   <?php x_alignment($y+2, 0); ?>,106" <?php admin_map_lnk($data, $x-5, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 84); ?>,94,  <?php x_alignment($y+2, 112); ?>,106 <?php x_alignment($y+2, 112); ?>,127 <?php x_alignment($y+2, 84); ?>,137  <?php x_alignment($y+2, 56); ?>,127  <?php x_alignment($y+2, 56); ?>,106" <?php admin_map_lnk($data, $x-4, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 140); ?>,94, <?php x_alignment($y+2, 168); ?>,106 <?php x_alignment($y+2, 168); ?>,127 <?php x_alignment($y+2, 140); ?>,137 <?php x_alignment($y+2, 112); ?>,127  <?php x_alignment($y+2, 112); ?>,106" <?php admin_map_lnk($data, $x-3, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 196); ?>,94, <?php x_alignment($y+2, 224); ?>,106 <?php x_alignment($y+2, 224); ?>,127 <?php x_alignment($y+2, 196); ?>,137 <?php x_alignment($y+2, 168); ?>,127 <?php x_alignment($y+2, 168); ?>,106" <?php admin_map_lnk($data, $x-2, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 252); ?>,94, <?php x_alignment($y+2, 280); ?>,106 <?php x_alignment($y+2, 280); ?>,127 <?php x_alignment($y+2, 252); ?>,137 <?php x_alignment($y+2, 224); ?>,127 <?php x_alignment($y+2, 224); ?>,106" <?php admin_map_lnk($data, $x-1, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 308); ?>,94, <?php x_alignment($y+2, 336); ?>,106 <?php x_alignment($y+2, 336); ?>,127 <?php x_alignment($y+2, 308); ?>,137 <?php x_alignment($y+2, 280); ?>,127 <?php x_alignment($y+2, 280); ?>,106" <?php admin_map_lnk($data, $x, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 364); ?>,94, <?php x_alignment($y+2, 392); ?>,106 <?php x_alignment($y+2, 392); ?>,127 <?php x_alignment($y+2, 364); ?>,137 <?php x_alignment($y+2, 336); ?>,127 <?php x_alignment($y+2, 336); ?>,106" <?php admin_map_lnk($data, $x+1, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 420); ?>,94, <?php x_alignment($y+2, 448); ?>,106 <?php x_alignment($y+2, 448); ?>,127 <?php x_alignment($y+2, 420); ?>,137 <?php x_alignment($y+2, 392); ?>,127 <?php x_alignment($y+2, 392); ?>,106" <?php admin_map_lnk($data, $x+2, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 476); ?>,94, <?php x_alignment($y+2, 504); ?>,106 <?php x_alignment($y+2, 504); ?>,127 <?php x_alignment($y+2, 476); ?>,137 <?php x_alignment($y+2, 448); ?>,127 <?php x_alignment($y+2, 448); ?>,106" <?php admin_map_lnk($data, $x+3, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 532); ?>,94, <?php x_alignment($y+2, 560); ?>,106 <?php x_alignment($y+2, 560); ?>,127 <?php x_alignment($y+2, 532); ?>,137 <?php x_alignment($y+2, 504); ?>,127 <?php x_alignment($y+2, 504); ?>,106" <?php admin_map_lnk($data, $x+4, $y+2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+2, 588); ?>,94, <?php x_alignment($y+2, 616); ?>,106 <?php x_alignment($y+2, 616); ?>,127 <?php x_alignment($y+2, 588); ?>,137 <?php x_alignment($y+2, 560); ?>,127 <?php x_alignment($y+2, 560); ?>,106" <?php admin_map_lnk($data, $x+5, $y+2, $i); ?>>

<!-- y = 1 -->
        <area shape="poly" coords="<?php x_alignment($y+1, 28); ?>,125,  <?php x_alignment($y+1, 56); ?>,135  <?php x_alignment($y+1, 56); ?>,156  <?php x_alignment($y+1, 28); ?>,168  <?php x_alignment($y+1, 0); ?>,156   <?php x_alignment($y+1, 0); ?>,135" <?php admin_map_lnk($data, $x-5, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 84); ?>,125,  <?php x_alignment($y+1, 112); ?>,135 <?php x_alignment($y+1, 112); ?>,156 <?php x_alignment($y+1, 84); ?>,168  <?php x_alignment($y+1, 56); ?>,156  <?php x_alignment($y+1, 56); ?>,135" <?php admin_map_lnk($data, $x-4, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 140); ?>,125, <?php x_alignment($y+1, 168); ?>,135 <?php x_alignment($y+1, 169); ?>,156 <?php x_alignment($y+1, 140); ?>,168 <?php x_alignment($y+1, 112); ?>,156  <?php x_alignment($y+1, 112); ?>,135" <?php admin_map_lnk($data, $x-3, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 196); ?>,125, <?php x_alignment($y+1, 224); ?>,135 <?php x_alignment($y+1, 224); ?>,156 <?php x_alignment($y+1, 196); ?>,168 <?php x_alignment($y+1, 168); ?>,156 <?php x_alignment($y+1, 168); ?>,135" <?php admin_map_lnk($data, $x-2, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 252); ?>,125, <?php x_alignment($y+1, 280); ?>,135 <?php x_alignment($y+1, 280); ?>,156 <?php x_alignment($y+1, 252); ?>,168 <?php x_alignment($y+1, 224); ?>,156 <?php x_alignment($y+1, 224); ?>,135" <?php admin_map_lnk($data, $x-1, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 308); ?>,125, <?php x_alignment($y+1, 336); ?>,135 <?php x_alignment($y+1, 336); ?>,156 <?php x_alignment($y+1, 308); ?>,168 <?php x_alignment($y+1, 280); ?>,156 <?php x_alignment($y+1, 280); ?>,135" <?php admin_map_lnk($data, $x, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 364); ?>,125, <?php x_alignment($y+1, 392); ?>,135 <?php x_alignment($y+1, 392); ?>,156 <?php x_alignment($y+1, 364); ?>,168 <?php x_alignment($y+1, 336); ?>,156 <?php x_alignment($y+1, 336); ?>,135" <?php admin_map_lnk($data, $x+1, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 420); ?>,125, <?php x_alignment($y+1, 448); ?>,135 <?php x_alignment($y+1, 448); ?>,156 <?php x_alignment($y+1, 420); ?>,168 <?php x_alignment($y+1, 392); ?>,156 <?php x_alignment($y+1, 392); ?>,135" <?php admin_map_lnk($data, $x+2, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 476); ?>,125, <?php x_alignment($y+1, 504); ?>,135 <?php x_alignment($y+1, 504); ?>,156 <?php x_alignment($y+1, 476); ?>,168 <?php x_alignment($y+1, 448); ?>,156 <?php x_alignment($y+1, 448); ?>,135" <?php admin_map_lnk($data, $x+3, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 532); ?>,125, <?php x_alignment($y+1, 560); ?>,135 <?php x_alignment($y+1, 560); ?>,156 <?php x_alignment($y+1, 532); ?>,168 <?php x_alignment($y+1, 504); ?>,156 <?php x_alignment($y+1, 504); ?>,135" <?php admin_map_lnk($data, $x+4, $y+1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y+1, 588); ?>,125, <?php x_alignment($y+1, 616); ?>,135 <?php x_alignment($y+1, 616); ?>,156 <?php x_alignment($y+1, 588); ?>,168 <?php x_alignment($y+1, 560); ?>,156 <?php x_alignment($y+1, 560); ?>,135" <?php admin_map_lnk($data, $x+5, $y+1, $i); ?>>

<!-- y = 0 -->
        <area shape="poly" coords="<?php x_alignment($y, 28); ?>,155,  <?php x_alignment($y, 56); ?>,167  <?php x_alignment($y, 56); ?>,188  <?php x_alignment($y, 28); ?>,198  <?php x_alignment($y, 0); ?>,188   <?php x_alignment($y, 0); ?>,168" <?php admin_map_lnk($data, $x-5, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 84); ?>,155,  <?php x_alignment($y, 112); ?>,167 <?php x_alignment($y, 112); ?>,188 <?php x_alignment($y, 84); ?>,198  <?php x_alignment($y, 56); ?>,188  <?php x_alignment($y, 56); ?>,168" <?php admin_map_lnk($data, $x-4, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 140); ?>,155, <?php x_alignment($y, 168); ?>,167 <?php x_alignment($y, 168); ?>,188 <?php x_alignment($y, 140); ?>,198 <?php x_alignment($y, 112); ?>,188  <?php x_alignment($y, 112); ?>,168" <?php admin_map_lnk($data, $x-3, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 196); ?>,155, <?php x_alignment($y, 224); ?>,167 <?php x_alignment($y, 224); ?>,188 <?php x_alignment($y, 196); ?>,198 <?php x_alignment($y, 168); ?>,188 <?php x_alignment($y, 168); ?>,168" <?php admin_map_lnk($data, $x-2, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 252); ?>,155, <?php x_alignment($y, 280); ?>,167 <?php x_alignment($y, 280); ?>,188 <?php x_alignment($y, 252); ?>,198 <?php x_alignment($y, 224); ?>,188 <?php x_alignment($y, 224); ?>,168" <?php admin_map_lnk($data, $x-1, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 308); ?>,155, <?php x_alignment($y, 336); ?>,167 <?php x_alignment($y, 336); ?>,188 <?php x_alignment($y, 308); ?>,198 <?php x_alignment($y, 280); ?>,188 <?php x_alignment($y, 280); ?>,168" <?php admin_map_lnk($data, $x, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 364); ?>,155, <?php x_alignment($y, 392); ?>,167 <?php x_alignment($y, 392); ?>,188 <?php x_alignment($y, 364); ?>,198 <?php x_alignment($y, 336); ?>,188 <?php x_alignment($y, 336); ?>,168" <?php admin_map_lnk($data, $x+1, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 420); ?>,155, <?php x_alignment($y, 448); ?>,167 <?php x_alignment($y, 448); ?>,188 <?php x_alignment($y, 420); ?>,198 <?php x_alignment($y, 392); ?>,188 <?php x_alignment($y, 392); ?>,168" <?php admin_map_lnk($data, $x+2, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 476); ?>,155, <?php x_alignment($y, 504); ?>,167 <?php x_alignment($y, 504); ?>,188 <?php x_alignment($y, 476); ?>,198 <?php x_alignment($y, 448); ?>,188 <?php x_alignment($y, 448); ?>,168" <?php admin_map_lnk($data, $x+3, $y, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y, 532); ?>,155, <?php x_alignment($y, 560); ?>,167 <?php x_alignment($y, 560); ?>,188 <?php x_alignment($y, 532); ?>,198 <?php x_alignment($y, 504); ?>,188 <?php x_alignment($y, 504); ?>,168" <?php admin_map_lnk($data, $x+4, $y, $i); ?>>
       <area shape="poly" coords="<?php x_alignment($y, 588); ?>,155, <?php x_alignment($y, 616); ?>,167 <?php x_alignment($y, 616); ?>,188 <?php x_alignment($y, 588); ?>,198 <?php x_alignment($y, 560); ?>,188 <?php x_alignment($y, 560); ?>,168" <?php admin_map_lnk($data, $x+5, $y, $i); ?>>

<!-- y = -1 -->
        <area shape="poly" coords="<?php x_alignment($y-1, 28); ?>,187,  <?php x_alignment($y-1, 56); ?>,197  <?php x_alignment($y-1, 56); ?>,218  <?php x_alignment($y-1, 28); ?>,230  <?php x_alignment($y-1, 0); ?>,218   <?php x_alignment($y-1, 0); ?>,198" <?php admin_map_lnk($data, $x-5, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 84); ?>,187,  <?php x_alignment($y-1, 112); ?>,197 <?php x_alignment($y-1, 112); ?>,218 <?php x_alignment($y-1, 84); ?>,230  <?php x_alignment($y-1, 56); ?>,218  <?php x_alignment($y-1, 56); ?>,198" <?php admin_map_lnk($data, $x-4, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 140); ?>,187, <?php x_alignment($y-1, 168); ?>,197 <?php x_alignment($y-1, 168); ?>,218 <?php x_alignment($y-1, 140); ?>,230 <?php x_alignment($y-1, 112); ?>,218  <?php x_alignment($y-1, 112); ?>,198" <?php admin_map_lnk($data, $x-3, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 196); ?>,187, <?php x_alignment($y-1, 224); ?>,197 <?php x_alignment($y-1, 224); ?>,218 <?php x_alignment($y-1, 196); ?>,230 <?php x_alignment($y-1, 168); ?>,218 <?php x_alignment($y-1, 168); ?>,198" <?php admin_map_lnk($data, $x-2, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 252); ?>,187, <?php x_alignment($y-1, 280); ?>,197 <?php x_alignment($y-1, 280); ?>,218 <?php x_alignment($y-1, 252); ?>,230 <?php x_alignment($y-1, 224); ?>,218 <?php x_alignment($y-1, 224); ?>,198" <?php admin_map_lnk($data, $x-1, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 308); ?>,187, <?php x_alignment($y-1, 336); ?>,197 <?php x_alignment($y-1, 336); ?>,218 <?php x_alignment($y-1, 308); ?>,230 <?php x_alignment($y-1, 280); ?>,218 <?php x_alignment($y-1, 280); ?>,198" <?php admin_map_lnk($data, $x, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 364); ?>,187, <?php x_alignment($y-1, 392); ?>,197 <?php x_alignment($y-1, 392); ?>,218 <?php x_alignment($y-1, 364); ?>,230 <?php x_alignment($y-1, 336); ?>,218 <?php x_alignment($y-1, 336); ?>,198" <?php admin_map_lnk($data, $x+1, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 420); ?>,187, <?php x_alignment($y-1, 448); ?>,197 <?php x_alignment($y-1, 448); ?>,218 <?php x_alignment($y-1, 420); ?>,230 <?php x_alignment($y-1, 392); ?>,218 <?php x_alignment($y-1, 392); ?>,198" <?php admin_map_lnk($data, $x+2, $y-1, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-1, 476); ?>,187, <?php x_alignment($y-1, 504); ?>,197 <?php x_alignment($y-1, 504); ?>,218 <?php x_alignment($y-1, 476); ?>,230 <?php x_alignment($y-1, 448); ?>,218 <?php x_alignment($y-1, 448); ?>,198" <?php admin_map_lnk($data, $x+3, $y-1, $i); ?>>        
        <area shape="poly" coords="<?php x_alignment($y-1, 532); ?>,187, <?php x_alignment($y-1, 560); ?>,197 <?php x_alignment($y-1, 560); ?>,218 <?php x_alignment($y-1, 532); ?>,230 <?php x_alignment($y-1, 504); ?>,218 <?php x_alignment($y-1, 504); ?>,198" <?php admin_map_lnk($data, $x+4, $y-1, $i); ?>>        
        <area shape="poly" coords="<?php x_alignment($y-1, 588); ?>,187, <?php x_alignment($y-1, 616); ?>,197 <?php x_alignment($y-1, 616); ?>,218 <?php x_alignment($y-1, 588); ?>,230 <?php x_alignment($y-1, 560); ?>,218 <?php x_alignment($y-1, 560); ?>,198" <?php admin_map_lnk($data, $x+5, $y-1, $i); ?>>

<!-- y = -2 -->
        <area shape="poly" coords="<?php x_alignment($y-2, 28); ?>,216,  <?php x_alignment($y-2, 56); ?>,228  <?php x_alignment($y-2, 56); ?>,249  <?php x_alignment($y-2, 28); ?>,261  <?php x_alignment($y-2, 0); ?>,249   <?php x_alignment($y-2, 0); ?>,230" <?php admin_map_lnk($data, $x-5, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 84); ?>,216,  <?php x_alignment($y-2, 112); ?>,228 <?php x_alignment($y-2, 112); ?>,249 <?php x_alignment($y-2, 84); ?>,261  <?php x_alignment($y-2, 56); ?>,249  <?php x_alignment($y-2, 56); ?>,230" <?php admin_map_lnk($data, $x-4, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 140); ?>,216, <?php x_alignment($y-2, 168); ?>,228 <?php x_alignment($y-2, 168); ?>,249 <?php x_alignment($y-2, 140); ?>,261 <?php x_alignment($y-2, 112); ?>,249  <?php x_alignment($y-2, 112); ?>,230" <?php admin_map_lnk($data, $x-3, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 196); ?>,216, <?php x_alignment($y-2, 224); ?>,228 <?php x_alignment($y-2, 224); ?>,249 <?php x_alignment($y-2, 196); ?>,261 <?php x_alignment($y-2, 168); ?>,249 <?php x_alignment($y-2, 168); ?>,230" <?php admin_map_lnk($data, $x-2, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 252); ?>,216, <?php x_alignment($y-2, 280); ?>,228 <?php x_alignment($y-2, 280); ?>,249 <?php x_alignment($y-2, 252); ?>,261 <?php x_alignment($y-2, 224); ?>,249 <?php x_alignment($y-2, 224); ?>,230" <?php admin_map_lnk($data, $x-1, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 308); ?>,216, <?php x_alignment($y-2, 336); ?>,228 <?php x_alignment($y-2, 336); ?>,249 <?php x_alignment($y-2, 308); ?>,261 <?php x_alignment($y-2, 280); ?>,249 <?php x_alignment($y-2, 280); ?>,230" <?php admin_map_lnk($data, $x, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 364); ?>,216, <?php x_alignment($y-2, 392); ?>,228 <?php x_alignment($y-2, 392); ?>,249 <?php x_alignment($y-2, 364); ?>,261 <?php x_alignment($y-2, 336); ?>,249 <?php x_alignment($y-2, 336); ?>,230" <?php admin_map_lnk($data, $x+1, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 420); ?>,216, <?php x_alignment($y-2, 448); ?>,228 <?php x_alignment($y-2, 448); ?>,249 <?php x_alignment($y-2, 420); ?>,261 <?php x_alignment($y-2, 392); ?>,249 <?php x_alignment($y-2, 392); ?>,230" <?php admin_map_lnk($data, $x+2, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 476); ?>,216, <?php x_alignment($y-2, 504); ?>,228 <?php x_alignment($y-2, 504); ?>,249 <?php x_alignment($y-2, 476); ?>,261 <?php x_alignment($y-2, 448); ?>,249 <?php x_alignment($y-2, 448); ?>,230" <?php admin_map_lnk($data, $x+3, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 532); ?>,216, <?php x_alignment($y-2, 560); ?>,228 <?php x_alignment($y-2, 560); ?>,249 <?php x_alignment($y-2, 532); ?>,261 <?php x_alignment($y-2, 504); ?>,249 <?php x_alignment($y-2, 504); ?>,230" <?php admin_map_lnk($data, $x+4, $y-2, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-2, 588); ?>,216, <?php x_alignment($y-2, 616); ?>,228 <?php x_alignment($y-2, 616); ?>,249 <?php x_alignment($y-2, 588); ?>,261 <?php x_alignment($y-2, 560); ?>,249 <?php x_alignment($y-2, 560); ?>,230" <?php admin_map_lnk($data, $x+5, $y-2, $i); ?>>

<!-- y = -3 -->
        <area shape="poly" coords="<?php x_alignment($y-3, 28); ?>,249,  <?php x_alignment($y-3, 56); ?>,260  <?php x_alignment($y-3, 56); ?>,281  <?php x_alignment($y-3, 28); ?>,291  <?php x_alignment($y-3, 0); ?>,281   <?php x_alignment($y-3, 0); ?>,260" <?php admin_map_lnk($data, $x-5, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 84); ?>,249,  <?php x_alignment($y-3, 112); ?>,260 <?php x_alignment($y-3, 112); ?>,281 <?php x_alignment($y-3, 84); ?>,291  <?php x_alignment($y-3, 56); ?>,281  <?php x_alignment($y-3, 56); ?>,260" <?php admin_map_lnk($data, $x-4, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 140); ?>,249, <?php x_alignment($y-3, 168); ?>,260 <?php x_alignment($y-3, 168); ?>,281 <?php x_alignment($y-3, 140); ?>,291 <?php x_alignment($y-3, 112); ?>,281  <?php x_alignment($y-3, 112); ?>,260" <?php admin_map_lnk($data, $x-3, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 196); ?>,249, <?php x_alignment($y-3, 224); ?>,260 <?php x_alignment($y-3, 224); ?>,281 <?php x_alignment($y-3, 196); ?>,291 <?php x_alignment($y-3, 168); ?>,281 <?php x_alignment($y-3, 168); ?>,260" <?php admin_map_lnk($data, $x-2, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 252); ?>,249, <?php x_alignment($y-3, 280); ?>,260 <?php x_alignment($y-3, 280); ?>,281 <?php x_alignment($y-3, 252); ?>,291 <?php x_alignment($y-3, 224); ?>,281 <?php x_alignment($y-3, 224); ?>,260" <?php admin_map_lnk($data, $x-1, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 308); ?>,249, <?php x_alignment($y-3, 336); ?>,260 <?php x_alignment($y-3, 336); ?>,281 <?php x_alignment($y-3, 308); ?>,291 <?php x_alignment($y-3, 280); ?>,281 <?php x_alignment($y-3, 280); ?>,260" <?php admin_map_lnk($data, $x, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 364); ?>,249, <?php x_alignment($y-3, 392); ?>,260 <?php x_alignment($y-3, 392); ?>,281 <?php x_alignment($y-3, 364); ?>,291 <?php x_alignment($y-3, 336); ?>,281 <?php x_alignment($y-3, 336); ?>,260" <?php admin_map_lnk($data, $x+1, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 420); ?>,249, <?php x_alignment($y-3, 448); ?>,260 <?php x_alignment($y-3, 448); ?>,281 <?php x_alignment($y-3, 420); ?>,291 <?php x_alignment($y-3, 392); ?>,281 <?php x_alignment($y-3, 392); ?>,260" <?php admin_map_lnk($data, $x+2, $y-3, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-3, 476); ?>,249, <?php x_alignment($y-3, 504); ?>,260 <?php x_alignment($y-3, 504); ?>,281 <?php x_alignment($y-3, 476); ?>,291 <?php x_alignment($y-3, 448); ?>,281 <?php x_alignment($y-3, 448); ?>,260" <?php admin_map_lnk($data, $x+3, $y-3, $i); ?>>

         <area shape="poly" coords="<?php x_alignment($y-3, 532); ?>,249, <?php x_alignment($y-3, 560); ?>,260 <?php x_alignment($y-3, 560); ?>,281 <?php x_alignment($y-3, 532); ?>,291 <?php x_alignment($y-3, 504); ?>,281 <?php x_alignment($y-3, 504); ?>,260" <?php admin_map_lnk($data, $x+4, $y-3, $i); ?>>
         <area shape="poly" coords="<?php x_alignment($y-3, 588); ?>,249, <?php x_alignment($y-3, 616); ?>,260 <?php x_alignment($y-3, 616); ?>,281 <?php x_alignment($y-3, 588); ?>,291 <?php x_alignment($y-3, 560); ?>,281 <?php x_alignment($y-3, 560); ?>,260" <?php admin_map_lnk($data, $x+5, $y-3, $i); ?>>

<!-- y = -4 -->
        <area shape="poly" coords="<?php x_alignment($y-4, 28); ?>,282,  <?php x_alignment($y-4, 56); ?>,292  <?php x_alignment($y-4, 56); ?>,313  <?php x_alignment($y-4, 28); ?>,321  <?php x_alignment($y-4, 0); ?>,313   <?php x_alignment($y-4, 0); ?>,290" <?php admin_map_lnk($data, $x-5, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 84); ?>,282,  <?php x_alignment($y-4, 112); ?>,292 <?php x_alignment($y-4, 112); ?>,313 <?php x_alignment($y-4, 84); ?>,321  <?php x_alignment($y-4, 56); ?>,313  <?php x_alignment($y-4, 56); ?>,290" <?php admin_map_lnk($data, $x-4, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 140); ?>,282, <?php x_alignment($y-4, 168); ?>,292 <?php x_alignment($y-4, 168); ?>,313 <?php x_alignment($y-4, 140); ?>,321 <?php x_alignment($y-4, 112); ?>,313  <?php x_alignment($y-4, 112); ?>,290" <?php admin_map_lnk($data, $x-3, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 196); ?>,282, <?php x_alignment($y-4, 224); ?>,292 <?php x_alignment($y-4, 224); ?>,313 <?php x_alignment($y-4, 196); ?>,321 <?php x_alignment($y-4, 168); ?>,313 <?php x_alignment($y-4, 168); ?>,260" <?php admin_map_lnk($data, $x-2, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 252); ?>,282, <?php x_alignment($y-4, 280); ?>,292 <?php x_alignment($y-4, 280); ?>,313 <?php x_alignment($y-4, 252); ?>,321 <?php x_alignment($y-4, 224); ?>,313 <?php x_alignment($y-4, 224); ?>,290" <?php admin_map_lnk($data, $x-1, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 308); ?>,282, <?php x_alignment($y-4, 336); ?>,292 <?php x_alignment($y-4, 336); ?>,313 <?php x_alignment($y-4, 308); ?>,321 <?php x_alignment($y-4, 280); ?>,313 <?php x_alignment($y-4, 280); ?>,290" <?php admin_map_lnk($data, $x, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 364); ?>,282, <?php x_alignment($y-4, 392); ?>,292 <?php x_alignment($y-4, 392); ?>,313 <?php x_alignment($y-4, 364); ?>,321 <?php x_alignment($y-4, 336); ?>,313 <?php x_alignment($y-4, 336); ?>,290" <?php admin_map_lnk($data, $x+1, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 420); ?>,282, <?php x_alignment($y-4, 448); ?>,292 <?php x_alignment($y-4, 448); ?>,313 <?php x_alignment($y-4, 420); ?>,321 <?php x_alignment($y-4, 392); ?>,313 <?php x_alignment($y-4, 392); ?>,290" <?php admin_map_lnk($data, $x+2, $y-4, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-4, 476); ?>,282, <?php x_alignment($y-4, 504); ?>,292 <?php x_alignment($y-4, 504); ?>,313 <?php x_alignment($y-4, 476); ?>,321 <?php x_alignment($y-4, 448); ?>,313 <?php x_alignment($y-4, 448); ?>,290" <?php admin_map_lnk($data, $x+3, $y-4, $i); ?>>

         <area shape="poly" coords="<?php x_alignment($y-4, 532); ?>,282, <?php x_alignment($y-4, 560); ?>,292 <?php x_alignment($y-4, 560); ?>,313 <?php x_alignment($y-4, 532); ?>,321 <?php x_alignment($y-4, 504); ?>,313 <?php x_alignment($y-4, 504); ?>,290" <?php admin_map_lnk($data, $x+4, $y-4, $i); ?>>
         <area shape="poly" coords="<?php x_alignment($y-4, 588); ?>,282, <?php x_alignment($y-4, 616); ?>,292 <?php x_alignment($y-4, 616); ?>,313 <?php x_alignment($y-4, 588); ?>,321 <?php x_alignment($y-4, 560); ?>,313 <?php x_alignment($y-4, 560); ?>,290" <?php admin_map_lnk($data, $x+5, $y-4, $i); ?>>

<!-- y = -5 -->
        <area shape="poly" coords="<?php x_alignment($y-5, 28); ?>,315,  <?php x_alignment($y-5, 56); ?>,324  <?php x_alignment($y-5, 56); ?>,345  <?php x_alignment($y-5, 28); ?>,351  <?php x_alignment($y-5, 0); ?>,345   <?php x_alignment($y-5, 0); ?>,320" <?php admin_map_lnk($data, $x-5, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 84); ?>,315,  <?php x_alignment($y-5, 112); ?>,324 <?php x_alignment($y-5, 112); ?>,345 <?php x_alignment($y-5, 84); ?>,351  <?php x_alignment($y-5, 56); ?>,345  <?php x_alignment($y-5, 56); ?>,320" <?php admin_map_lnk($data, $x-4, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 140); ?>,315, <?php x_alignment($y-5, 168); ?>,324 <?php x_alignment($y-5, 168); ?>,345 <?php x_alignment($y-5, 140); ?>,351 <?php x_alignment($y-5, 112); ?>,345  <?php x_alignment($y-5, 112); ?>,320" <?php admin_map_lnk($data, $x-3, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 196); ?>,315, <?php x_alignment($y-5, 224); ?>,324 <?php x_alignment($y-5, 224); ?>,345 <?php x_alignment($y-5, 196); ?>,351 <?php x_alignment($y-5, 168); ?>,345 <?php x_alignment($y-5, 168); ?>,260" <?php admin_map_lnk($data, $x-2, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 252); ?>,315, <?php x_alignment($y-5, 280); ?>,324 <?php x_alignment($y-5, 280); ?>,345 <?php x_alignment($y-5, 252); ?>,351 <?php x_alignment($y-5, 224); ?>,345 <?php x_alignment($y-5, 224); ?>,320" <?php admin_map_lnk($data, $x-1, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 308); ?>,315, <?php x_alignment($y-5, 336); ?>,324 <?php x_alignment($y-5, 336); ?>,345 <?php x_alignment($y-5, 308); ?>,351 <?php x_alignment($y-5, 280); ?>,345 <?php x_alignment($y-5, 280); ?>,320" <?php admin_map_lnk($data, $x, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 364); ?>,315, <?php x_alignment($y-5, 392); ?>,324 <?php x_alignment($y-5, 392); ?>,345 <?php x_alignment($y-5, 364); ?>,351 <?php x_alignment($y-5, 336); ?>,345 <?php x_alignment($y-5, 336); ?>,320" <?php admin_map_lnk($data, $x+1, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 420); ?>,315, <?php x_alignment($y-5, 448); ?>,324 <?php x_alignment($y-5, 448); ?>,345 <?php x_alignment($y-5, 420); ?>,351 <?php x_alignment($y-5, 392); ?>,345 <?php x_alignment($y-5, 392); ?>,320" <?php admin_map_lnk($data, $x+2, $y-5, $i); ?>>
        <area shape="poly" coords="<?php x_alignment($y-5, 476); ?>,315, <?php x_alignment($y-5, 504); ?>,324 <?php x_alignment($y-5, 504); ?>,345 <?php x_alignment($y-5, 476); ?>,351 <?php x_alignment($y-5, 448); ?>,345 <?php x_alignment($y-5, 448); ?>,320" <?php admin_map_lnk($data, $x+3, $y-5, $i); ?>>

         <area shape="poly" coords="<?php x_alignment($y-5, 532); ?>,315, <?php x_alignment($y-5, 560); ?>,324 <?php x_alignment($y-5, 560); ?>,345 <?php x_alignment($y-5, 532); ?>,351 <?php x_alignment($y-5, 504); ?>,345 <?php x_alignment($y-5, 504); ?>,320" <?php admin_map_lnk($data, $x+4, $y-5, $i); ?>>
         <area shape="poly" coords="<?php x_alignment($y-5, 588); ?>,315, <?php x_alignment($y-5, 616); ?>,324 <?php x_alignment($y-5, 616); ?>,345 <?php x_alignment($y-5, 588); ?>,351 <?php x_alignment($y-5, 560); ?>,345 <?php x_alignment($y-5, 560); ?>,320" <?php admin_map_lnk($data, $x+5, $y-5, $i); ?>>

      </map>
   </div>
</div>

<!-- Description box -->
   <div id="admin_descriptor" style="position:relative; top:150; left:260;">
      <table class="q_table_desc" style="border-collapse: collapse" align="center" border="0" width="180">
         <tbody>
            <tr>
               <td colspan="2" align="center">Description</td>
            </tr>
            <tr>
               <td width="117" align="center">Player<td></td>
            </tr>
            <tr>
               <td width="117" align="center">Population<td></td>
            </tr>
            <tr>
               <td width="117" align="center">Yield<td></td>
            </tr>
         </tbody>
      </table>
   </div>
</div>

<div id="apDiv3">
   <div align="center">
      <table class="q_table" style="border-collapse: collapse;" width="806" border="0" cellSpacing="0" cellPadding="0" width=806 align="center">
         <tr>
            <td width="600" height="540" align="left" valign="top">
               <form name="form1" method="post" action="adminprocess.php">
                  <label style="position:relative; top:500; left:392;">
                     <input name="x" type="text" id="x" size="2" value="<?php echo $x; ?>">
                  </label>
                  <label style="position:relative; top:500; left:392;">
                     <input name="y" type="text" id="y" size="2" value="<?php echo $y; ?>">
                  </label>
                  <label style="position:relative; top:500; left:392;">
                     <input type="submit" name= "go" id="go" value="Go">
                  </label>
                  <input type="hidden" name="subhoover" value="1">

                  <!-- x axis -->
                  <div style="position:relative; top:100; left:100;z-index: 1;">
                  <div style="position:absolute; top:348; left:-32; width:30;"><?php echo $x - 5; ?></div>
                  <div style="position:absolute; top:348; left:24 ; width:30;"><?php echo $x - 4; ?></div>
                  <div style="position:absolute; top:348; left:80 ; width:30;"><?php echo $x - 3; ?></div>
                  <div style="position:absolute; top:348; left:136; width:30;"><?php echo $x - 2; ?></div>
                  <div style="position:absolute; top:348; left:192; width:30;"><?php echo $x - 1; ?></div>
                  <div style="position:absolute; top:348; left:248; width:30;"><?php echo $x; ?></div>
                  <div style="position:absolute; top:348; left:304; width:30;"><?php echo $x + 1; ?></div>
                  <div style="position:absolute; top:348; left:360; width:30;"><?php echo $x + 2; ?></div>
                  <div style="position:absolute; top:348; left:416; width:30;"><?php echo $x + 3; ?></div>
                  <div style="position:absolute; top:348; left:472; width:30;"><?php echo $x + 4; ?></div>
                  <div style="position:absolute; top:348; left:528; width:30;"><?php echo $x + 5; ?></div>
                  <!-- y axis -->
                  <div style="position:absolute; top:-10; left:-85; width:30;"><?php echo $y + 5; ?></div>
                  <div style="position:absolute; top:21;  left:-85; width:30;"><?php echo $y + 4; ?></div>
                  <div style="position:absolute; top:52;  left:-85; width:30;"><?php echo $y + 3; ?></div>
                  <div style="position:absolute; top:82;  left:-85; width:30;"><?php echo $y + 2; ?></div>
                  <div style="position:absolute; top:113; left:-85; width:30;"><?php echo $y + 1; ?></div>
                  <div style="position:absolute; top:144; left:-85; width:30;"><?php echo $y; ?></div>
                  <div style="position:absolute; top:175; left:-85; width:30;"><?php echo $y - 1; ?></div>
                  <div style="position:absolute; top:206; left:-85; width:30;"><?php echo $y - 2; ?></div>
                  <div style="position:absolute; top:237; left:-85; width:30;"><?php echo $y - 3; ?></div>
                  <div style="position:absolute; top:268; left:-85; width:30;"><?php echo $y - 4; ?></div>
                  <div style="position:absolute; top:299; left:-85; width:30;"><?php echo $y - 5; ?></div>
               </div>
            </form>
         </tr>
      </table>
   </div>
</div>

<div style="position:relative; top:260; left:480;">
<form action="adminprocess.php" method="POST">
<table align="center" border="0" cellspacing="0" cellpadding="3">

<tr><td>Terrain:</td><td><select name="terrain">
<option value="1">dirt1
<option value="2">dirt2
<option value="3">dirt3
<option value="4">dirt4
<option value="21">dirtsea1
<option value="22">dirtsea2
<option value="23">dirtsea3
<option value="24">dirtsea4
<option value="25">dirtsea44
<option value="26">dirtsea55
<option value="27">dirtsea66
<option value="28">dirtsea77
<option value="101">forest1
<option value="102">forest2
<option value="103">forest3
<option value="104">forest4
<option value="201">sea1
<option value="202">sea2
<option value="301">mount1
<option value="302">mount2
</select>

<tr><td>Yield:</td><td><select name="yield">
<option value="1">1
<option value="2">2
<option value="3">3
<option value="4">4
<option value="5">5
<option value="6">6
<option value="7">7
<option value="8">8
<option value="9">9
</select>

<tr><td colspan="2" align="left">
<font size="2">
<input type="hidden" name="subterrain" value="1">
<input type="hidden" name="x" value="<?php echo $x; ?>">
<input type="hidden" name="y" value="<?php echo $y; ?>">
<input type="submit" value="Change"></td></tr>
</table>
</form>
</div>

<div id="apDiv5"><img src="backgrounds/frame.png" width="608" height="357"></div>

</body>
</html>