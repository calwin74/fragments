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
 * The variables are set in initialize.
 */
var x_board_max = 0;
var x_board_min = 0;
var y_board_max = 0;
var y_board_min = 0;

/*
 * Size of map data in client database
 * The variables are set in initialize.
 */
var x_batch_size = 0;
var y_batch_size = 0;

/*
 * Size of data in database, needed to make sure a line is odd or not.
 * The variables are set in initialize.
 */
var x_global = 0;
var y_global = 0;

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

/*
 * Road proposed for movement
 */
var road = null;


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

//Client initialization
initialize();
//Read board
loadMapBatch();

// ---------------------------------------------------------------------------
// async functions

function initialize() {
   // This is a sync call, need to get init data before any other data.
   var url = "initialize.php";
   request.open("GET", url, false);
   request.onreadystatechange = handleInitialize;
   request.send(null);
}

function loadMapBatch() {
   var url = "map_update.php?x_position=" + escape(x_position) + "&y_position=" + escape(y_position) + "&x_batch_size=" + escape(x_batch_size) + "&y_batch_size=" + escape(y_batch_size);
   request.open("GET", url, true);
   request.onreadystatechange = handleMapUpdate;
   request.send(null);

   updateBoarderXY(x_position, y_position);
}

function setWalk(steps) {
   var url = "walk_update.php?steps=" + escape(steps);
   request.open("GET", url, true);
   request.onreadystatechange = handleDefaultUpdate;
   request.send(null);
}

// ---------------------------------------------------------------------------
// callbacks for async functions

function handleDefaultUpdate() {
   if (request.readyState == 4) {
       if (request.status == 200) {
	  var response = request.responseText;
	  alert (response);
       }
       else if (request.status == 404) {
          alert("handleDefaultUpdate: Request URL does not exist");
       }
       else {
	  alert("handleDefaultUpdate: Error - status code is " + request.status);
       }
   }
}

function handleInitialize() {
   if (request.readyState == 4) {
      if (request.status == 200) {
         var response = request.responseText;

	 // This might not be secure. Use a Jaason parser instead?
	 var initObj = eval('(' + response + ')');

	 x_board_max = initObj.x_local_map_size;
	 x_board_min = -initObj.x_local_map_size;
	 y_board_max = initObj.y_local_map_size;
	 y_board_min = -initObj.y_local_map_size;
	 
	 x_global = initObj.x_global_map_size;
	 y_global = initObj.y_global_map_size;

	 x_batch_size = initObj.x_batch_map_size;
	 y_batch_size = initObj.y_batch_map_size;
      }
      else if (request.status == 404) {
         alert("Request URL does not exist");
      }
      else {
         alert("Error: status code is " + request.status);
      }
   }
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

// ---------------------------------------------------------------------------
// local functions

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

      //add coordinates to front tile
      //$("#"+id).children("p").attr("src", animation);
      
      //var coord = x + "|" + y;
      var coord_hex = r.x_hex + "|" + r.y_hex
      //var coord_hex = r.x + "|" + r.y;
      $("#"+id).children("p").html(coord_hex);

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


   var y_diff = y_global - y_top;
   if (y_diff % 2) {
       /* 
	* First line should be even, need to modify the y coordinates.
	* Increase by one.
        */
       y_top++;
       y_bottom++;
   }

   var records = mapDB( {y:{lte:y_top}}, {y:{gte:y_bottom}}, {x:{gte:x_left}}, {x:{lte:x_right}} );
   
   return records;
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

  if ( (x + x_board_max > x_global) || (x + x_board_min < -x_global) ||
       (y + y_board_max > y_global) || (y + y_board_min < -y_global))
  {
      outside = 2
  }
  else if ( (x + x_board_max > x_pos_boarder) || (x + x_board_min < x_neg_boarder) ||
       (y + y_board_max > y_pos_boarder) || (y + y_board_min < y_neg_boarder))
  {
      outside = 1;
  }

  return outside;
}

/*
 * Check if a move is valid
 */
function checkMoveMap(x,y) {
    var status = checkBoarder(x, y);
    
    if (status == 1) {
	// load more map data from database
	loadMapBatch();
    }
    else if (status == 2) {
	// totally out of map
	alert("Out of map");
    }
    else {
	// get map from cache
	var records = getBoard();
	updateBoard(records);
   }
}

/*
 * Check if current is same as stop
 */
function atDestination(current_hex, stop_hex) {
   var done = 0;
   if ((current_hex[0] == stop_hex[0]) && (current_hex[1] == stop_hex[1])) {
       done = 1;
   }

   return done;
}

function createCoord(x,y) {
   var coord = new Array();
   coord[0] = x;
   coord[1] = y;

   return coord;
}

function markRoadMap(road) {
   var i = 0;
   var tile = null;

   while(tile = road[i++]) {
       var cartesian = hexToCartesian(tile);
       var coord = ".xy_" + cartesian[0] + "_" + cartesian[1];
       $(coord).removeClass("front").addClass("way");
   }
}

function printRoad(road) {
   var i = 0;
   var tile = null;
   var desc = "road:";

   while(tile = road[i++]) {
      var cartesian = hexToCartesian(tile);
      desc = desc.concat("(", cartesian[0], "|", cartesian[1], ")");
   }

   return desc;
}

/*
 * fromat way using comma separated list
 * x|y,x|y, ...
 */
function formatWay(road) {
   var i = 0;
   var tile = null;
   var desc = "";

   while(tile = road[i++]) {
      if(i != 1) {
         desc = desc.concat(",");
      }
      var cartesian = hexToCartesian(tile);
      desc = desc.concat(cartesian[0], "|", cartesian[1]);
   }

   return desc;
}

/*
 * Get (x|y) coordinates in the form of an array.
 */
function getXY(coords_str) {
    var parts = coords_str.split("xy_");
    var xy_coords = parts[1].split("_");
    return xy_coords;
}

/*
 * Get one record from cache using cartesian coordinates.
 */
function getRecord(x, y) {
    var records = mapDB( {x:{is:x.toString()}}, {y:{is:y.toString()}} );
    var my_record = null;

    if (records.count() == 1) {
	records.each(function (r){
            my_record = r;
	})
    }

    return my_record;
}

/*
 * Get one record from cache using hex coordinates.
 */
function getRecordHex(x_hex, y_hex) {
    var records = mapDB( {x_hex:{is:x_hex.toString()}}, {y_hex:{is:y_hex.toString()}} );
    var my_record = null;

    if (records.count() == 1) {
	records.each(function (r){
	    my_record = r;
	})
    }

    return my_record;
}

function cartesianToHex(cartesian) {
    var hex = new Array();

    record = getRecord(cartesian[0], cartesian[1]);

    hex[0] = record.x_hex;
    hex[1] = record.y_hex;

    return hex;
}

function hexToCartesian(hex) {
    var cartesian = new Array();
    
    record = getRecordHex(hex[0], hex[1]);
    cartesian[0] = record.x;
    cartesian[1] = record.y;
    
    return cartesian;
}

function getHexX(hex) {
    return hex[0];
}

function getHexY(hex) {
    return hex[1];
}

/*
 * Get distance based on hex coordinates and delta distances
 */

function getDistance(start_hex, stop_hex) {
    var deltaX = getHexX(stop_hex) - getHexX(start_hex);
    var deltaY = getHexY(stop_hex) - getHexY(start_hex);
    var deltaXY = deltaX - deltaY;
    var absX = Math.abs(deltaX);
    var absY = Math.abs(deltaY);
    var absXY = Math.abs(deltaXY);    
    var distance = 0;

    if ( (absX >= absY) && (absX >= absXY) ) {
	distance = absX;
    }
    else if ( (absY >= absX) && (absY >= absXY) ) {
	distance = absY;
    }
    else {
	distance = absXY;
    }

    return distance;
}

function getNextStep(current_hex, stop_hex) {
    var distance = getDistance(current_hex, stop_hex);
    var coord;
    var tile_distance;

    //alert(distance);

    //North
    coord = createCoord(getHexX(current_hex), parseInt(getHexY(current_hex)) + 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
    //NorthEast
    coord = createCoord(parseInt(getHexX(current_hex)) + 1, parseInt(getHexY(current_hex)) + 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
    //SouthEast
    coord = createCoord(parseInt(getHexX(current_hex)) + 1, getHexY(current_hex));
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
    //South
    coord = createCoord(getHexX(current_hex), parseInt(getHexY(current_hex)) - 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
    //SouthWest
    coord = createCoord(parseInt(getHexX(current_hex)) - 1, parseInt(getHexY(current_hex)) - 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
    //NorthWest
    coord = createCoord(parseInt(getHexX(current_hex)) - 1, getHexY(current_hex));
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	return coord;
    }
}

function getRoadMap(current, destination) {
    var road = new Array();

    //Transform to hex coordinates
    current_hex = cartesianToHex(current);
    destination_hex = cartesianToHex(destination);
    
    while(!atDestination(current_hex, destination_hex)) {
	 var next_step;

	//alert(current_hex);
	next_step = getNextStep(current_hex, destination_hex);
	road.push(next_step);
	//alert(next_step);
	current_hex = next_step;
    }

    return road;
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

   /* click on arrows to move map */
   $("#frametop").click(function() {
      // move 2 steps to keep board in line.
      y_position++;
      y_position++;
      checkMoveMap(x_position, y_position);
   });
   $("#framebottom").click(function() {
      // move 2 steps to keep board in line.
      y_position--;
      y_position--;
      checkMoveMap(x_position, y_position);
   });
   $("#frameleft").click(function() {
      x_position--;
      checkMoveMap(x_position, y_position);
   });
   $("#frameright").click(function() {
      x_position++;
      checkMoveMap(x_position, y_position);
   });

   /* click on tile */
   $(".front, .way").live("click", function() {
      // remove marked tiles
      $(".marked").removeClass("marked").addClass("front");
      // mark tile
      $(this).removeClass("front").addClass("marked");
      // remove way tile
      $(".way").removeClass("way").addClass("front");      
      
      // find classes
      var army = $(this).hasClass('army');

      if (army == true) {
	  if (army_selected) {
	      army_selected = null;
	      road = null;
	  }
	  else {
	      var fclasses = $("#"+this.id).attr("class");
	      var parts = fclasses.split(" ");
	      xy_coords = getXY(parts[0]);
	      army_selected = xy_coords;
	  }
      }
      else if (army == false && army_selected) {
	  //generate road map
	  var fclasses = $("#"+this.id).attr("class");
	  var parts = fclasses.split(" ");
	  xy_coords = getXY(parts[0]);

	  road = getRoadMap(army_selected, xy_coords);
	  markRoadMap(road);
      }
   });

   $(".marked.way").live("click", function(){
      alert(printRoad(road));
      var steps = formatWay(road);
      setWalk(steps);
      //clear way tiles
      $(".way").removeClass("way").addClass("front");
   });

   $(".marked").live("click", function(){
      //remove marked tile if clicked
      $(this).removeClass("marked").addClass("front");
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
