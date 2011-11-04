<?php
include_once("session.php");

/**
 * generate_js.php
 * This module generate javascript code based on input.
 */

class GenerateJS {
   public function GenerateJS(){
      /* empty for now */
  	}

   /**
    * bindArrowEvents - Bind event handling for map view movement.
    * Only bind event if map view can't move out of the global map.
    */
   public function bindArrowEvents($x, $y) {
      echo "\n<script language=\"javascript\" type=\"text/javascript\">\n";      
      echo "$(function() {\n";

      if ($y < (Y_GLOBAL_MAP_SIZE - Y_LOCAL_MAP_SIZE )) {
         echo "   $('#frametop').click(function() {\n";
         echo "      var actionForm = document.forms['actionForm'];\n";
         echo "      actionForm.elements['action'].value = 'up';\n";
         echo "      actionForm.submit();\n";
         echo "   });\n";
      }

      if ($y > -(Y_GLOBAL_MAP_SIZE - Y_LOCAL_MAP_SIZE )) {      
         echo "   $('#framebottom').click(function() {\n";
         echo "      var actionForm = document.forms['actionForm'];\n";
         echo "      actionForm.elements['action'].value = 'down';\n";
         echo "      actionForm.submit();\n";
         echo "   });\n";
      }

      if ($x < (X_GLOBAL_MAP_SIZE - X_LOCAL_MAP_SIZE )) {
         echo "   $('#frameright').click(function() {\n";
         echo "      var actionForm = document.forms['actionForm'];\n";
         echo "      actionForm.elements['action'].value = 'right';\n";
         echo "      actionForm.submit();\n";
         echo "   });\n";
      }

      if ($x > -(X_GLOBAL_MAP_SIZE - X_LOCAL_MAP_SIZE )) {      
         echo "   $('#frameleft').click(function() {\n";
         echo "      var actionForm = document.forms['actionForm'];\n";
         echo "      actionForm.elements['action'].value = 'left';\n";
         echo "      actionForm.submit();\n";
         echo "   });\n";
      }

      echo "});\n";
      echo "</script>\n";
   }

   /**
    * markHomeEvent - Bind event handling for moving focus to marked tile.
    * Only bind event if focus can't move so map view moves out of global map.
    */
   public function bindMarkHomeEvent($x, $y) {
      global $session;

      $q = $x."_".$y;

      $session->logger->LogInfo($q);

      if ( ($y < (Y_GLOBAL_MAP_SIZE - Y_LOCAL_MAP_SIZE)) &&
           ($y > (Y_GLOBAL_MAP_SIZE - Y_LOCAL_MAP_SIZE) * (-1)) &&
           ($x < (X_GLOBAL_MAP_SIZE - X_LOCAL_MAP_SIZE)) &&
           ($x > (X_GLOBAL_MAP_SIZE - X_LOCAL_MAP_SIZE) * (-1)) ) {

         echo "\n<script language=\"javascript\" type=\"text/javascript\">\n";
         echo "$(function() {\n";      

         echo "   $('#mark_home').click(function() {\n";
         echo "      var actionForm = document.forms['actionForm'];\n";
         echo "      actionForm.elements['action'].value = 'mark_home';\n";
         echo "      actionForm.submit();\n";
         echo "   });\n";

         echo "});\n";
         echo "</script>\n";
      }
   }
}
?>