<?php
include_once("session.php");

/**
 * character.php
 * This module handle the character
 */

class Character
{
   private $my_name;           //character name
   private $my_x;              //character x coordinate
   private $my_y;              //character y coordinate
   private $my_civilians;      //civilians in army

   /* Class constructor */
   public function Character(){
     global $session;

     $database = $session->database;

     $character = $database->getCharacter($session->username);
     $this->my_name = $character["name"];
     
     $this->my_x = $character["x"];     
     $this->my_y = $character["y"];     

     $this->my_civilians = $character["civilians"];
   }

   public function getName(){
     return $this->my_name;
   }

   public function getX(){
     return $this->my_x;
   }

   public function getY(){
     return $this->my_y;
   }
   
   public function getCivilians(){
     return $this->my_civilians;
   }
}
?>