<?php
   // test spinner
?>

<html>
<head>

<title>jQuery UI Spinner Example</title>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/ui.spinner.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="http://jqueryui.com/themeroller/themeswitchertool/"></script>

<script type="text/javascript">
jQuery().ready(function($) {
$('#spinner').spinner({ min: -100, max: 100 });
});
</script>

</head>
<body>

<div id="example">
<p>This is a simple example/test page for my jQuery UI 1.7 Spinner Widget v1.10. You can find the current release version online at <a href="http://github.com/btburnett3/jquery.ui.spinner">GitHub</a>.</p>

<p>Here is an example of a spinner based editor: <input type="text" id="spinner" value="0" /> <input type="button" id="GetValue" value="Get Value" /> <input type="button" id="destroy" value="Destroy" /></p>

</div>

</body>
</html>