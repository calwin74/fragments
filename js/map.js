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
 * Selected army position on board
 * NULL och coordinate array
 */
var army_selected = null;

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

/*
 * Check if a move is valid
 */
function checkMove(x,y) {
   if (checkBoarder(x, y)) {
      loadMapBatch();
   }
   else {
      var records = getBoard();
      updateBoard(records);
   }
}

/*
 * Check if current is same as stop
 */
function atDestination(current, stop) {
   var done = 0;
   if ((current[0] == stop[0]) && (current[1] == stop[1])) {
	   done = 1;
   }

   return done;
}

/*
 * Check if movement is pure vertical from now on
 * This means that x_current equals x_stop
 */
function checkVertical(current, stop) {
   var ok = 0;

   if (current[0] == stop[0]) {
	   ok = 1;
   }

   return ok;
}

function createCoord(x,y) {
   var coord = new Array();
   coord[0] = x;
   coord[1] = y;

   return coord;
}

function walkVertical(current, stop, road) {
   var x = current[0];
   var yc = current[1];
   var ys = stop[1];

   road.push(current);

   if (yc > ys) {
      //walk down two steps
      alert("walk down");
      yc--;
      yc--;
      var coord = createCoord(x, yc);
      walkVertical(coord, stop, road);
   }
   else if (yc < ys) {
      //walk up 2 steps
      alert("walk up");
      yc++;
      yc++;
      var coord = createCoord(x, yc);
      walkVertical(coord, stop, road);
   }
   else {
      alert("done walking vertical");
   }
}

/*
 * String representation of road
 */
function roadToString(road) {
   var i = 0;
   var str = "|";
   var tile = null;

   while(tile = road[i++]) {
	   str = str.concat(tile).concat("|");
   }

   return str;
}

/*
 * Get an array of coordinates representing the road map from start to
 * destination. This is just one of the shortest ways.
 */
function getRoadMap(current, stop, road) {
   var str = "generate road map from " + current + " to " + stop;
   alert(str);

   //road.push(current);

   if (atDestination(current, stop)) {
	   alert("at destination");
	   str = roadToString(road);
	   alert(str);
   }
   //xs == xd -> just go up or down
   if (checkVertical(current, stop)) {
	   walkVertical(current, stop, road);
      str = roadToString(road);
      alert(str);
   }
}

function markRoadMap(road) {
   var i = 0;
   var tile = null;
   
   while(tile = road[i++]) {
       var coord = ".xy_" + tile[0] + "_" + tile[1];
       alert(coord);
       $(coord).removeClass("front").addClass("marked");
   }
}

function isBoarder(current, stop) {
   var xc = parseInt(current[0]);
   var yc = parseInt(current[1]);
   var xs = parseInt(stop[0]);
   var ys = parseInt(stop[1]);
}

function getBoarders(x,y) {
   var boarders = new Array();

   str = "getBoarders " + x + " " + y;
   alert(str);

   if (y % 2) {
      //y is odd
      
	   //x,y+2
	   boarders.push(createCoord(x,y+2));
	   //x-1,y+1
	   boarders.push(createCoord(x-1,y+1));
	   //x-1,y-1
	   boarders.push(createCoord(x-1,y-1));
	   //x,y-2
	   boarders.push(createCoord(x,y-2));
	   //x,y-1
	   boarders.push(createCoord(x,y-1));	
	   //x,y+1
	   boarders.push(createCoord(x,y+1));
   }
   else {
	   //y is even
	
	   //x,y+2
      boarders.push(createCoord(x,y+2));
   	//x,y+1
      boarders.push(createCoord(x,y+1));
	   //x,y-1
      boarders.push(createCoord(x,y-1));
	   //x,y-2
      boarders.push(createCoord(x,y-2));
	   //x+1,y-1
      boarders.push(createCoord(x+1,y-1));
	   //x+1,y+1
      boarders.push(createCoord(x+1,y+1));
   }
   
   alert(roadToString(boarders));
}

/*
 * Get (x|y) coordinates
 */
function getXY(coords_str) {
    var parts = coords_str.split("xy_");
    var xy_coords = parts[1].split("_");
    return xy_coords;
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

      var parts = fclasses.split(" ");

      //(x|y)
      xy_coords = getXY(parts[0]);
      str = "(" + xy_coords[0] + "|" + xy_coords[1] + ")";
      document.getElementById('coord').innerHTML = str;

      //toxic
      var toxic_part = parts[1].split(" ");
      var toxic = toxic_part[0].split("_");
      var str = toxic[1];
      document.getElementById('toxic').innerHTML = str;
   });

   $(".front").click(function() {
      // find classes
      var army = $(this).hasClass('army');

      if (army == true) {
	      if (army_selected) {
	         army_selected = null;
	         alert("unmark army");
	      }
	      else {
	         var fclasses = $("#"+this.id).attr("class");
	         var parts = fclasses.split(" ");
	         xy_coords = getXY(parts[0]);
	         army_selected = xy_coords;
	         alert("mark army");
	      }
      }
      else if (army == false && army_selected) {
	      //generate road map
	      var fclasses = $("#"+this.id).attr("class");
	      var parts = fclasses.split(" ");
	      xy_coords = getXY(parts[0]);

         //getBoarders(parseInt(xy_coords[0]), parseInt(xy_coords[1]));

	      //var road = new Array();
	      //getRoadMap(army_selected, xy_coords, road);
	      //markRoadMap(road);
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

$(document).ready(function() {
    $('.smoke').cycle({
/*
      fx: 'shuffle',
      shuffle: {
         top: -23,
         left: 23
      },
      speed: 1000,
      timeout: 1
*/
		fx: 'fade',
      timeout:375,
      speed:33
	});
});
