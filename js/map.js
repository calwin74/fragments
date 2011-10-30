/*
 * Functions used by the map representation. Using JQuery plugins.
 */

$(function($) {
   $('.jclock').jclock();
});

function timer(data, lnk)
{
	dat=document.getElementById(data);
	var time=(dat.innerHTML).split(":"); var done=0;
	if (time[2]>0) time[2]--;
	else
	{
		time[2]=59;
		if (time[1]>0) time[1]--;
		else
		{
			time[1]=59;
			if (time[0]>0) time[0]--;
			else { clearTimeout(id[data]); window.location.href=lnk; done=1;}
		}
	}
	if (!done)
	{
		dat.innerHTML=time[0]+":"+time[1]+":"+time[2];
		id[data]=setTimeout("timer('"+data+"', '"+lnk+"')", 1000);
	}
}

$(function() {
   //   $(".cyan,.character").click(function(t) {
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

   $("span.mark").click(function(t) {
      var actionForm = document.forms["actionForm"];

      actionForm.elements["action"].value = "mark";
      actionForm.elements["key"].value = this.id; 
      actionForm.submit();
   });

   $("span.unmark").click(function(t) {
      var actionForm = document.forms["actionForm"];

      actionForm.elements["action"].value = "unmark";
      actionForm.elements["key"].value = this.id; 
      actionForm.submit();
   });

   $('span.explore').contextMenu('myMenu2', {
      bindings: {
         'explore': function(t) {
            var actionForm = document.forms["actionForm"];

            actionForm.elements["action"].value = 'explore';
            actionForm.elements["key"].value = t.id;
            actionForm.submit();
         },         
      }
   });
   $('span.move_mark').contextMenu('myMenu3', {
      bindings: {
         'move': function(t) {
            var actionForm = document.forms["actionForm"];

            actionForm.elements["action"].value = 'move';
            actionForm.elements["key"].value = t.id;
            actionForm.submit();
         },
         'mark': function(t) {
            var actionForm = document.forms["actionForm"];

            actionForm.elements["action"].value = 'mark';
            actionForm.elements["key"].value = t.id;
            actionForm.submit();
         },
      }
   });
   $(".hex").mouseover(function() {
      var id = this.id
      document.getElementById('coordinates').innerHTML = id;
   });
/*
   $(".hex").click(function() {
      var actionForm = document.forms["actionForm"];

      actionForm.elements["action"].value = "mark";
      actionForm.elements["key"].value = this.id; 
      actionForm.submit();
   });
*/

});