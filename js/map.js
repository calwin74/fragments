/*
 * Functions used by the map representation. Using JQuery plugins.
 */

// ---------------------------------------------------------------------------
// Global variables

/*
 * Initiate TaffyDB
 */
var mapDB = TAFFY();

/*
 * Middle position for board
 * This the middle of the board and board creation will be based on this point.
 */
var x_position = 0;
var y_position = 0;

/*
 * Size of board.
 * This should be alligned with the sizes in constants.php
 */
var x_board_max = 5;
var x_board_min = -5;
var y_board_max = 13;
var y_board_min = -13;

/*
 * Size of map data in client database
 */
var x_batch_size = 20;
var y_batch_size = 40;

/*
 * Boarder for loaded data in client database.
 */
var x_pos_boarder = 0;
var x_neg_boarder = 0;
var y_pos_boarder = 0;
var y_neg_boarder = 0;

/*
 * Army selected on board
 */
var army_selected = false;

// ---------------------------------------------------------------------------
// Code to run on data load

/*
 * Create XMLHttpRequest object for all browsers.
 */
var request = false;

try {
   request = new XMLHttpRequest();
} catch (trymicrosoft) {
   try {
      request = new ActiveXObject("Msxml2.XMLHTTP");
   } catch (othermicrosoft) {
      try {
         request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (failed) {
         request = false;
      }  
   }
}

if (!request) {
   alert("Error initializing XMLHttpRequest!");
}

loadMapBatch();

// ---------------------------------------------------------------------------
// Local functions

function loadMapBatch() {
   // set load status
   var url = "map_update.php?x_position=" + escape(x_position) + "&y_position=" + escape(y_position) + "&x_batch_size=" + escape(x_batch_size) + "&y_batch_size=" + escape(y_batch_size);
   request.open("GET", url, true);
   request.onreadystatechange = handleMapUpdate;
   request.send(null);

   updateBoarderXY(x_position, y_position);
}

function updateBoard(records) {
   var x = x_board_min;
   var y = y_board_max; 

   // loop through records and modify class attributes
   records.each(function (r){
      // ids
      var id = x + "_" + y;
      var b_id = "b" + id;
      //current objects
      var old_b = $("#"+b_id);              
      var old_f = $("#"+id);
      //check classes
      var br = old_b.hasClass("br");
      var firstodd = old_b.hasClass("firstodd");
      var odd = old_b.hasClass("odd");
      var even = old_b.hasClass("even");

      var c = "";

      //find position classes
      if (br) { 
         c = c + " br";
      }
      if (firstodd) {
         c = c + " firstodd";
      }
      if (odd) {
         c = c + " odd";
      }
      if (even) {
         c = c + " even";
      }

      var class_b = c + " " + r.bclasses;

      // The xy coordinate and toxic classes must always be first in the class attribute list.
      var class_coord = "xy_" + r.x + "_" + r.y;
      var toxic_class = "toxic_" + r.toxic;

      var class_f = class_coord + " " + toxic_class + " "  + c + " " + r.classes;      

      // class attribute
      $("#"+b_id).attr("class", class_b);
      $("#"+id).attr("class", class_f);

      // src attribute for background tile
      var animation = r.bimage;
      if (animation) {
         $("#"+b_id).children("img").attr("src", animation);
      }

      //edit x and y ...
      if (x >= x_board_max) {
         x = x_board_min;
         y--;
      }
      else {
         x++;
      }
   })
}

function getBoard() {
   var x_left = x_position - x_board_max;
   var x_right = x_position + x_board_max;
   var y_top = y_position + y_board_max;
   var y_bottom = y_position - y_board_max;

   var records = mapDB( {y:{lte:y_top}}, {y:{gte:y_bottom}}, {x:{gte:x_left}}, {x:{lte:x_right}} );
   
   return records;
}

function handleMapUpdate() {
   if (request.readyState == 4) {
      if (request.status == 200) {
         var response = request.responseText;

	 // remove all rows from database
	 mapDB().remove();
         // insert json response into database
         mapDB.insert(response);
         
         // create selection for map based on (x_position|y_position)
         records = getBoard();

         //update board
         updateBoard(records);
      }
      else if (request.status == 404) {
         alert("Request URL does not exist");
      }
      else {
         alert("Error: status code is " + request.status);
      }
   }
}

// Update boarders of client database map data.
function updateBoarderXY(x,y)
{
   x_pos_boarder = x + x_batch_size;
   x_neg_boarder = x - x_batch_size;
   y_pos_boarder = y + y_batch_size;
   y_neg_boarder = y - y_batch_size;
}

// Check if outside client database boarder.
function checkBoarder(x,y) {
  var outside = 0;

  if ( (x + x_board_max > x_pos_boarder) || (x + x_board_min < x_neg_boarder) ||
       (y + y_board_max > y_pos_boarder) || (y + y_board_min < y_neg_boarder))
  {
     outside = 1;
  }

  return outside;
}

// Check if a move is valid.
function checkMove(x,y) {
   if (checkBoarder(x, y)) {
      loadMapBatch();
   }
   else {
      var records = getBoard();
      updateBoard(records);
   }
}

// ---------------------------------------------------------------------------
// JQuery functions

$(function() {
   $(".front").mouseover(function() {
      var id = this.id;
      var id_coords = id.split("_");
      var id_x = id_coords[0];
      var id_y = id_coords[1];

      //id
      var str = "(".concat(id_x).concat("|").concat(id_y).concat(")");
      document.getElementById('board_id').innerHTML = str;
      //background class
      var bclasses = $("#b"+id).attr("class");
      document.getElementById('bclasses').innerHTML = bclasses;
      //foreground class
      var fclasses = $("#"+id).attr("class");
      document.getElementById('fclasses').innerHTML = fclasses;
      //get first part with hover information
      var hover_part = fclasses.split(" ");
      //(x|y) coordinates      
      var coords = hover_part[0].split("xy_");
      var xy_coords = coords[1].split("_");
      var x_coord = xy_coords[0];
      var y_coord = xy_coords[1];
      str = "(".concat(x_coord).concat("|").concat(y_coord).concat(")");
      document.getElementById('coord').innerHTML = str;
      //get toxic part
      var toxic_part = hover_part[1].split(" ");
      var toxic = toxic_part[0].split("_");
      var str = toxic[1];
      document.getElementById('toxic').innerHTML = str;
   });

   $(".front").click(function() {
      // find classes
      var army = $(this).hasClass('army');

      if (army == true) {
         //toggle army_selected
	 army_selected = !army_selected;
	 if (army_selected == true) {
	    alert("marked army");
	 }
	 else {
	    alert("unmarked army");
	 }
      }

      if (army_selected == true && army == false) {
	 //generate road map
	 alert("generate road map");
      }
   });
});

$(function() {
   $("#frametop").click(function() {
      // move 2 steps to keep board in line.
      y_position++;
      y_position++;
      checkMove(x_position, y_position);
   });
   $("#framebottom").click(function() {
      // move 2 steps to keep board in line.
      y_position--;
      y_position--;
      checkMove(x_position, y_position);
   });
   $("#frameleft").click(function() {
      x_position--;
      checkMove(x_position, y_position);
   });
   $("#frameright").click(function() {
      x_position++;
      checkMove(x_position, y_position);
   });
});
