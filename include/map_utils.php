<?php
/**
 * map_utils.php
 * 
 * The map_utils module contains utility functions for the map
 *
 * Written by: matkar01
 */
include_once("constants.php");

/**
 * map_lnk - Create links and populate information fields for the onMouseOver command.
 * Note that two referenced counters are incremented here.
 */

function map_lnk($data, $units, $x, $y, &$i, &$j)
{
   // See database table for characters to understand how the array is structured.
   if (isset($data[$i][0])){
      if (($data[$i][0] == $x) && ($data[$i][1] == $y)){
         $terrain = $data[$i][2];

         if (is_unit($units, $x, $y, $j)){
            echo "onMouseOver=\"desc('".$units[$j][2]."', '".$units[$j][0]."', '-', 'Not allied')\" onMouseOut=\"desc('Description', '', '', '')\" onClick=\"desc('".$units[$j][2]."', '".$units[$j][0]."', '-', 'clicked')\" onDblClick=\"desc('".$units[$j][2]."', '".$units[$j][0]."', '-', 'dbl clicked')\"";

            if ($j<count($units)-1){
               // increment counters
               $j++;
            }
         }
         else if ($terrain == DIRT1 || $terrain == DIRT2 || $terrain == DIRT3 || $terrain == DIRT4){
            echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('Dirt (".$x.",".$y.")', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
         }
         else if ($terrain == DIRTSEA1 || $terrain == DIRTSEA2 || $terrain == DIRTSEA3 || $terrain == DIRTSEA4 || $terrain == DIRTSEA44 || $terrain == DIRTSEA55 || $terrain == DIRTSEA66 || $terrain == DIRTSEA77) {
            echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('Coast (".$x.",".$y.")', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
         }
         else if ($terrain == FOREST1 || $terrain == FOREST2 || $terrain == FOREST3 || $terrain == FOREST4){
            echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('Forest (".$x.",".$y.")', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
         }
         else if ($terrain == SEA1 || $terrain == SEA2){
            echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('Sea (".$x.",".$y.")', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
         }
         else if ($terrain == MOUNTAINS1 || $terrain == MOUNTAINS2){
            echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('Mountains (".$x.",".$y.")', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
         }

         if ($i<count($data)-1){
            // increment counters
            $i++;
         }
         
      }
      else{
         echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('void', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
      }
  }
  else{
     echo "href='map_hexagon.php?x=".$x."&y=".$y."' onMouseOver=\"desc('void', '-', '-', '-')\" onMouseOut=\"desc('Description', '', '', '')\"";
  }
}

/**
 * x_alignment - Calculate alignment for a hexagon.
 */
function x_alignment($y, $x_coordinate, $alignment)
{
   if ($y%2){
      $x_coordinate = $x_coordinate + $alignment;
   }

   echo $x_coordinate;
}

/**
 * get_unit - Get unit image. Note that a referenced counter is incremented here.
 */
function get_unit_icon($units, $x, $y, &$j)
{
   // x and y are number 4 and 5 in the array; tribe is 2.
   // See database table for characters.

   if (($units[$j][4] == $x) && ($units[$j][5] == $y)){
      if ($units[$j][2] == "human"){
         echo "src='units/Sir-Lord-2.png' width=\"55\" height=\"82\"";
      }
      else if ($units[$j][2] == "undead"){
         echo "src='units/Sir-Lord-1.png' width=\"55\" height=\"82\"";
      }
      else if ($units[$j][2] == "orc"){
         echo "src='units/Sir-Dark-1.png' width=\"55\" height=\"82\"";
      }
      else{
         echo "src='units/Sir-Lord-2.png' width=\"55\" height=\"82\"";
      }
      if ($j <count($units)-1){
         $j++;
      }
   }
}

/**
 * is_unit - Returns 1 if a unit is present on coordinate.
 */
function is_unit($units, $x, $y, $j)
{
   // x and y are number 4 and 5 in the array; see database table for characters.
   if (($units[$j][4] == $x) && ($units[$j][5] == $y)){
      return 1;  
   }
   else{
      return 0;
   } 
}

/**
 * map_image - Get terraine image.
 * This function maps terrain types to class types
 */
function get_land_class($lands, $x, $y, $character, $i)
{
   $img = "void";

   /* get variables */
   $row = $lands[$i];
   $x_c = $row["x"];
   $y_c = $row["y"];
   $type = $row["type"];
   $owner = $row["owner"];

   if (isset($x_c)){
      if (($x_c == $x) && ($y_c == $y)){
         $owned = !strcmp($owner, $character);
         if ($type == DIRT){
            if ($owned){
              $img = "hex frame";
            }
            else{
              $img = "hex";
            }
         }
         else if ($type == SEA){
            $img = "hex blue";
         }
         else if ($type == FOREST){
            if ($owned){
              $img = "hex green_frame";
            }
            else{
              $img = "hex green";
            }
         }
      }
   }

   return $img;
}
?>