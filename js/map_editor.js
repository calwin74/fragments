/*
 * Functions used by the map representation. Using JQuery plugins.
 */

$(function($) {
   $('.jclock').jclock();
});

$(function() {
   $(".hex").click(function(t) {
      var actionForm = document.forms["actionForm"];

      actionForm.elements["action"].value = "edit_mark";
      actionForm.elements["key"].value = this.id; 
      actionForm.submit();
   });

   $(".hex").mouseover(function() {
      var id = this.id
      document.getElementById('coordinates').innerHTML = id;
   });
});