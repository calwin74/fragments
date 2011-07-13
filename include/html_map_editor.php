<?php
	class Html {
		function Html(){
  	 	}

		function html_header($title) {
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
			echo '<html>';
			echo '<head>';
			echo "<title>".$title."</title>";

         echo '<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>';
                        
         /* 
          * Clock
          * http://www.techiegyan.com/2008/11/16/jquery-clock-plugin-jclock/
          */
         echo '<script src="js/jquery.jclock-1.2.0.js" type="text/javascript"></script>';

         /*
          * map_editor.js
          * Site specific javascript code included in the map description.
          */
         echo '<script src="js/map_editor.js" type="text/javascript"></script>';

         /*
          * style.css
          * Site specific css.
          */
			echo '<link rel="stylesheet" type="text/css" href="css/style.css"></link>';
		}

      function html_end_header() {
         echo '</head>';
			echo '<body>';
      }

		function html_footer() {
			echo '</body>';
			echo '</html>';
		}
	}
?>