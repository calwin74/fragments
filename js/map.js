/*
 * Functions used by the map representation. Using JQuery plugins.
 */

$(function($) {
   $('.jclock').jclock();
});

$(function() {
   var money = $("div.money");
   var production = parseInt(document.getElementById('production').innerHTML);
   var growth = parseFloat(document.getElementById('growth').innerHTML);

/*
       $.ajax({
          type: "GET",
          url: "map_update.php",
          dataType: "xml",
          success: function(xml){
             processLandXML(xml);
          },
          error: function(){
             alert("error GETTING map_update.php");
          }
       });

       function processLandXML(xml){
          var dataArray = $(xml).find("land");
          var dataArrayLen = dataArray.length;
          
          var key = $(dataArray[0]).attr("key");
          var class = $(dataArray[0]).attr("class");
          var toxic = $(dataArray[0]).attr("toxic");
          var desc = key + " " + class + " " + toxic;

          var new_html = "<land class=\"" + class + " first\" id=" + key + "><p>" + toxic + "</p></land>"; 
          
          alert(desc);
          alert(new_html);

          alert($("#" + key).html());
       }
*/
        
   $('span.move').contextMenu('myMenu1', {
      bindings: {
         'move': function(t) {
            var actionForm = document.forms["actionForm"];

            actionForm.elements["action"].value = 'move';
            actionForm.elements["key"].value = t.id;
            actionForm.submit();
         },
      }
   });
   $('span.clean').contextMenu('myMenu2', {
      bindings: {
         'clean': function(t) {
            var actionForm = document.forms["actionForm"];

            actionForm.elements["action"].value = 'clean';
            actionForm.elements["key"].value = t.id;
            actionForm.submit();
         },
      }
   });

   $(".controlled-interval", money).everyTime("1s", "controlled", function() {
      var production_int = 0;

      production += growth;
      production_int = Math.round(production);
                      
      document.getElementById('production').innerHTML = production_int;
   });

   $(".hex").mouseover(function() {
      var id = this.id
      document.getElementById('coordinates').innerHTML = id;
   });
});