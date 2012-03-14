<?php
	class Html {
		function Html(){
  	 	}

		function header($title) {
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			echo '<html>';
			echo '<head>';
			echo "<title>".$title."</title>";

         /*
          * jquery-1.3.2.min.js
          * jquery library
          */

         echo '<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>';

         /*
          * taffy-min.js
          * TaffyDB library
          */
         echo '<script src="js/taffy-min.js" type="text/javascript"></script>';

         /*
          * map.js
          * Site specific javascript code included in the map description.
          */
         echo '<script src="js/map.js" type="text/javascript"></script>';

         /*
          * style.css
          * Site specific css. Note there are two files ...for now.
          */
			echo '<link rel="stylesheet" type="text/css" href="css/style.css"></link>';
         echo '<link rel="stylesheet" type="text/css" href="style.css"></link>';
		}

      function end_header() {
         echo '</head>';
      }

		function footer() {
			echo '</body>';
			echo '</html>';
		}
	}
?>