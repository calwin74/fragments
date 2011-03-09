<?php
/**
 * Agent.php
 *
 * This is the Agent Center page. Only agent users
 * are allowed to view this page. This page displays the
 * agents and the status of the work
 *
 * Written by: matkar01
 */
include_once("include/session.php");
include_once("include/constants.php");
include_once("menu.php");

global $session;

/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAgent()){
   header("Location: ../main.php");
}
else{
/**
 * Agent user is viewing page, so display all
 * forms.
 */
?>
<html>
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<!-- http://plugins.jquery.com/project/timers -->
<script src="js/jquery.timers-1.2.js" type="text/javascript"></script>
<!-- http://plugins.jquery.com/project/jclock -->
<script src="js/jquery.jclock-1.2.0.js" type="text/javascript"></script>

<script type="text/javascript">
$(function($) {
   $('.jclock').jclock();
});
</script>

<script type="text/javascript">
$(function() { 
   var resource_div = $("div.resources");
   var resource_active = false;

   var action_div = $("div.action");
   var action_active = false;

   $('.resource-interval', resource_div).find('.start').css("cursor", "pointer").click(function() {
      if (!resource_active) {
         resource_active = !resource_active;
         document.getElementById('resource_agent').innerHTML = "Running";
         $(this).parents("div").find('ul').everyTime("1s", "resource", function() {
            $.ajax({
               type: "POST",
               url: "agent_process.php",
               data: "type=resources",
               success: function(html){
                        $("#resource_response").html(html);
               }
            });
         });
      }
   }).end().find('.stop').css("cursor", "pointer").click(function() {
      if (resource_active) {
         resource_active = !resource_active;
         document.getElementById('resource_agent').innerHTML = "Not running";
         $(this).parents("div").find('ul').stopTime('resource');
       }
   });

   $('.action-interval', action_div).find('.start').css("cursor", "pointer").click(function() {
      if (!action_active) {
         action_active = !action_active;
         document.getElementById('action_agent').innerHTML = "Running";
         $(this).parents("div").find('ul').everyTime("1s", "action", function() {
            $.ajax({
               type: "POST",
               url: "agent_process.php",
               data: "type=actions",
               success: function(html){
                        $("#action_response").html(html);
               }
            });
         });
      }
   }).end().find('.stop').css("cursor", "pointer").click(function() {
      if (action_active) {
         action_active = !action_active;
         document.getElementById('action_agent').innerHTML = "Not running";
         $(this).parents("div").find('ul').stopTime("action");
       }
   });

});
</script>

<!-- clock -->
<div class="jclock"></div>

<title>Agent center <?php echo FRAGMENTS_TITLE; ?></title>
<body>
<?php menu1(); ?>

<h1>Agent Center</h1>
<font size="5" color="#ff0000">
<b>::::::::::::::::::::::::::::::::::::::::::::</b></font>
<font size="4">Logged in as <b><?php echo $session->username; ?></b></font><br><br>

<b>Resource Agent </b><br>

<!-- Resource agent status -->
<div id="resource_agent">
Not running
</div>

<div class="resources">
   <div class="resource-interval">
      <ul></ul>
      <p><span class="start">Start</span> | <span class="stop">Stop</span></p>
   </div>
</div>

<!-- AJAX resource response -->
Last agent event:
<div id="resource_response"></div>

<br><br>
<b>Action Agent </b><br>

<!-- Action agent status -->
<div id="action_agent">
Not running
</div>

<div class="action">
   <div class="action-interval">
      <ul></ul>
      <p><span class="start">Start</span> | <span class="stop">Stop</span></p>
   </div>
</div>

<!-- AJAX action response -->
Last agent event:
<div id="action_response"></div>


</body>
</html>
<?php
}
?>