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

/*
 * Timer to handle delta updates
 */
var timer = null;
var interval = 5000;

// ---------------------------------------------------------------------------
// Code to run on data load

/*
 * Create XMLHttpRequest object for all browsers.
 */
var request = false;
var request_delta = false;

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

try {
   request_delta = new XMLHttpRequest();
} catch (trymicrosoft) {
   try {
      request_delta = new ActiveXObject("Msxml2.XMLHTTP");
   } catch (othermicrosoft) {
      try {
         request_delta = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (failed) {
         request_delta = false;
      }  
   }
}

if (!request_delta) {
   alert("Error initializing XMLHttpRequest! (request_delta)");
}

//Client initialization (sync)
initialize();
//Read board
loadMapBatch();
//Start delta updates
//timer = setInterval(function(){deltaMapFunction()}, interval);

// ---------------------------------------------------------------------------
// async functions

function deltaMapFunction() {
   // need a dedicated channel???
   var url = "map_delta_update.php?x_position=" + escape(x_position) + "&y_position=" + escape(y_position) + "&x_batch_size=" + escape(x_batch_size) + "&y_batch_size=" + escape(y_batch_size);;
   request_delta.open("GET", url, true);
   request_delta.onreadystatechange = handleDeltaMapUpdate;
   request_delta.send(null);
}

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

function handleDeltaMapUpdate() {
   if (request_delta.readyState == 4) {
      if (request_delta.status == 200) {
	      var response = request_delta.responseText;
	      //alert(response);

	      //This might not be secure. Use a JSON parser instead?
	      //For instance use $.parseJSON(response);
	      var units = eval('(' + response + ')');

	      //Reset armies from mapDB and DOM
	      mapDB({army:{is:"army"}}).update({army:null});
	      mapDB({army:{is:"army-enemy"}}).update({army:null});	  
	      $(".army").removeClass("army");
	      $(".army-enemy").removeClass("army-enemy");

	      //Set army in mapDB and DOM using delta updates
	      for (var i = 0; i < units.length; i++) {
	         var unit = units[i];
	         mapDB( {x:{is:unit.x}}, {y:{is:unit.y}} ).update( {army: unit.army} );
	         updateTile(unit);
	      }
     }
     else if (request_delta.status == 404) {
        alert("handleDeltaMapUpdate: Request URL does not exist");
     }
     else {
        alert("handleDeltaMapUpdate: Error - status code is " + request_delta.status);
     }
   }
}

function handleDefaultUpdate() {
   if (request.readyState == 4) {
      if (request.status == 200) {
	      //var response = request.responseText;
	      //alert (response);
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

	      // This might not be secure. Use a JSON parser instead?
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

function updateTile(record) {
   var class_coord = ".xy_" + record.x + "_" + record.y;
   var army_class = " " + record.army;
   $(class_coord).addClass(army_class);
}

function updateBoard(records) {
   var x = x_board_min;
   var y = y_board_max; 

   // loop through records and modify class attributes
   records.each(function (r){
      // ids
      var id = x + "_" + y;
      var a_id = "a" + id;
      var b_id = "b" + id;
      var c_id = "c" + id;
      var d_id = "d" + id;

      //current objects
      var old_a = $("#"+a_id);              
      //check classes
      var br = old_a.hasClass("br");
      var firstodd = old_a.hasClass("firstodd");
      var odd = old_a.hasClass("odd");
      var even = old_a.hasClass("even");

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

      var a_class = c + " " + r.bclasses;
      //var class_b = c + " " + r.bclasses;

      var b_class = c +  " " + r.classes;

      var army_class = "";
      if (r.army) {
         army_class = " " + r.army
      } 
      var c_class = c + " " + r.classes + army_class;

      // The xy coordinate and toxic classes must always be first in the class attribute list.
      var class_coord = "xy_" + r.x + "_" + r.y;
      var toxic_class = "toxic_" + r.toxic;
      var d_class = class_coord + " " + toxic_class + " " + c + " " + r.classes;

      // class attribute
      $("#"+a_id).attr("class", a_class);
      $("#"+b_id).attr("class", b_class);
      $("#"+c_id).attr("class", c_class);
      $("#"+d_id).attr("class", d_class);

      // src attribute for background tile
      /*
      var animation = r.bimage;
      if (animation) {
         $("#"+b_id).children("img").attr("src", animation);
      }
      */

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
   var len = road.length;

   while(tile = road[i++]) {
      var cartesian = hexToCartesian(tile);
      var coord = ".xy_" + cartesian[0] + "_" + cartesian[1];
      var id = $(coord).attr("id");

      setMark(getCoreId(id));
      if (i == len) {
         setWayPoint(getCoreId(id));         
      }   
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
   //Skip start position
   var i = 1;
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

function getNextStepEast(current_hex, stop_hex) {
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

function getNextStepWest(current_hex, stop_hex) {
    var distance = getDistance(current_hex, stop_hex);
    var coord;
    var tile_distance;

    //NorthWest
    coord = createCoord(parseInt(getHexX(current_hex)) - 1, getHexY(current_hex));
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
    //South
    coord = createCoord(getHexX(current_hex), parseInt(getHexY(current_hex)) - 1);
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
    //NorthEast
    coord = createCoord(parseInt(getHexX(current_hex)) + 1, parseInt(getHexY(current_hex)) + 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	    return coord;
    }
    //North
    coord = createCoord(getHexX(current_hex), parseInt(getHexY(current_hex)) + 1);
    tile_distance = getDistance(coord, stop_hex);
    if (tile_distance < distance) {
	    return coord;
    }
}

function getRoadMap(current, destination) {
    var road = new Array();
    var spin = 0; // 0 = east, 1 = west.
 
    //Transform to hex coordinates
    current_hex = cartesianToHex(current);
    destination_hex = cartesianToHex(destination);

    //include starting point for marks
    road.push(current_hex);
    
    while(!atDestination(current_hex, destination_hex)) {
	    var next_step;

	    //alert(current_hex);
       if (spin == 0) {
	       next_step = getNextStepEast(current_hex, destination_hex);
          spin = 1;
       }
       else {
          next_step = getNextStepWest(current_hex, destination_hex);
          spin = 0;
       }
	    road.push(next_step);
	    //alert(next_step);
	    current_hex = next_step;
    }

    return road;
}

function getCoreId(id) {
    var core_id = id.substring(1);
    return core_id;
}

function getCoordsFromClass(id) {
    var classes = $("#d"+id).attr("class");
    var parts = classes.split(" ");
    xy_coords = getXY(parts[0]);
    return xy_coords;
}

function clearMarks() {
   $(".marked_enemy").removeClass("marked_enemy").addClass("front");
   $(".marked_friend").removeClass("marked_friend").addClass("front");
   $(".marked_neutral").removeClass("marked_neutral").addClass("front");
}

function showDevView(id_x, id_y, core_id) {
   var str = "(".concat(id_x).concat("|").concat(id_y).concat(")");
   document.getElementById('board_id').innerHTML = str;

   var classes = $("#d"+core_id).attr("class");
   document.getElementById('dclasses').innerHTML = classes;

   classes = $("#c"+core_id).attr("class");
   document.getElementById('cclasses').innerHTML = classes;

   classes = $("#b"+core_id).attr("class");
   document.getElementById('bclasses').innerHTML = classes;

   classes = $("#a"+core_id).attr("class");
   document.getElementById('aclasses').innerHTML = classes;   
}

function moveMap(steps, y_axis) {
   if (y_axis) {
      y_position = y_position + steps;
   }
   else {
      x_position = x_position + steps;
   }
   checkMoveMap(x_position, y_position);
}

function getMark(core_id) {
   var army_id = "#c" + core_id;
   var building_id = "#a" + core_id;
   var mark = "marked_neutral";

   if ($(army_id).hasClass('army-enemy')) {
      mark = "marked_enemy";
   }
   else if ($(army_id).hasClass('army')) {
      mark = "marked_friend";
   }
   else if ($(building_id).hasClass('enemy')) {
      mark =  "marked_enemy";
   }
   else if ($(building_id).hasClass('friend')) {
      mark = "marked_friend";
   }
  
   return mark;
}

function setMark(core_id) {
   var mark = getMark(core_id);
   var mark_id = "#b" + core_id;

   $(mark_id).removeClass("front").addClass(mark);
}

function setWayPoint(core_id) {
   var mark_id = "#b" + core_id;
   $(mark_id).addClass("waypoint");
}

function handleArmyMovement(core_id) {
   var army_id = "#c" + core_id;
   
   if ($(army_id).hasClass('army')) {
	   if (army_selected) {
         //reset globals
	      army_selected = null;
	      road = null;
	   }
	   else {
	      army_selected = getCoordsFromClass(core_id);
	   }
   }
   else if (army_selected) {
	   //get road map
	   xy_coords = getCoordsFromClass(core_id);
	   road = getRoadMap(army_selected, xy_coords);
	   markRoadMap(road);
   }   
}

function handleRoad(core_id) {
   var mark_id = "#b" + core_id;
   var result = false;

   var waypoint = $(mark_id).hasClass('waypoint')

   if (waypoint) {
      var steps = formatWay(road);

      //setWalk(steps);

      //clear way and wayend tiles
      $(".wayend").removeClass("wayend").addClass("front");
      result = true;
   }
   
   return result;
}

// ---------------------------------------------------------------------------
// JQuery functions

$(function() {
   $(".front").mouseover(function() {
      var core_id = getCoreId(this.id);
      var id_xy = core_id.split("_");
      var id_x = id_xy[0];
      var id_y = id_xy[1];
      showDevView(id_x, id_y, core_id);
   });

   /* click on arrows to move map */
   $("#toparrow").click(function() {
      moveMap(2, 1);
   });
   $("#bottomarrow").click(function() {
      moveMap(-2, 1);
   });
   $("#leftarrow").click(function() {
      moveMap(-1, 0);      
   });
   $("#rightarrow").click(function() {
      moveMap(1, 0);      
   });

   /* click on tile */
   $(".front").live("click", function() {
      var core_id = getCoreId(this.id);

      //clear marks
      clearMarks();

      if (!handleRoad(core_id)) {
         // mark tile
         setMark(core_id);

         // army movement
         handleArmyMovement(core_id);
      }
   });
});
