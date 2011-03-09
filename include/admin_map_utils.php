<?php
/**
 * admin_map_utils.php
 * 
 * The admin_map_utils module contains utility functions for the admin map
 *
 * Written by: matkar01
 */
include_once("constants.php");

/**
 * admin_map_lnk - Create links and populate information fields for the onMouseOver command.
 * Note that two referenced counters are incremented here.
 */

function admin_map_lnk($data, $x, $y, &$i)
{
   // See database table for characters to understand how the array is structured.
   if (isset($data[$i][0])){
      if (($data[$i][0] == $x) && ($data[$i][1] == $y)){
         $terrain = $data[$i][2];
         $yield = $data[$i][3];

         if ($terrain == DIRT1 || $terrain == DIRT2 || $terrain == DIRT3 || $terrain == DIRT4){
            echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('Dirt (".$x.",".$y.")', '-', '-', ".$yield.")\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
         }
         else if ($terrain == DIRTSEA1 || $terrain == DIRTSEA2 || $terrain == DIRTSEA3 || $terrain == DIRTSEA4 || $terrain == DIRTSEA44 || $terrain == DIRTSEA55 || $terrain == DIRTSEA66 || $terrain == DIRTSEA77) {
             echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('Coast (".$x.",".$y.")', '-', '-', ".$yield.")\" onMouseOut=\"admin_desc('Description', '', '', '')\"";        
         }
         else if ($terrain == FOREST1 || $terrain == FOREST2 || $terrain == FOREST3 || $terrain == FOREST4){
            echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('Forest (".$x.",".$y.")', '-', '-', ".$yield.")\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
         }
         else if ($terrain == SEA1 || $terrain == SEA2){
            echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('Sea (".$x.",".$y.")', '-', '-', ".$yield.")\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
         }
         else if ($terrain == MOUNTAINS1 || $terrain == MOUNTAINS2) {
            echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('Mountains (".$x.",".$y.")', '-', '-', ".$yield.")\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
         }

         if ($i<count($data)-1){
            // increment counters
            $i++;
         }
         
      }
      else{
         echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('void', '-', '-', '-')\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
      }
  }
  else{
     echo "href='admin_map.php?x=".$x."&y=".$y."' onMouseOver=\"admin_desc('void', '-', '-', '-')\" onMouseOut=\"admin_desc('Description', '', '', '')\"";
  }
}
?>