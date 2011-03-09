<?php
/**
 * Header.php
 *
 * This module includes header functionality; e.g. "logout" and "edit account" links.
 *
 * Written by: matkar01
 */

/**
 * menu1 - Adds a menu to the page with common functionality like "Account", "logout" and so on.
 */
function menu1(){
   global $session; //The session object.

   if ($session->isAdmin()){
      echo "[<a href=\"admin.php\">Admin Center</a>] &nbsp;&nbsp;"
          //."[<a href=\"admin_map.php?user=$session->username\">Map</a>] &nbsp;&nbsp;"
          ."[<a href=\"userinfo.php?user=$session->username\">My Account</a>] &nbsp;&nbsp;"
          ."[<a href=\"process.php\">Logout</a>]";
   }
   /*
   else if (!$session->isInitUser()){
      echo "[<a href=\"process.php\">Logout</a>]";
   }
   */
   else{
      echo "<a href=\"userinfo.php?user=$session->username\">My Account</a> &nbsp;&nbsp;"
          ."<a href=\"process.php\">Logout</a>";
   }
}
?>