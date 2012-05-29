<?php
/*
 * This model is a test module
 *
 */

/*
$data1 = $_GET['test1'];
$data2 = $_GET['test2'];
echo "echo:".$data1." and ".$data2;
*/

/* json answer */

$arr = array();
$arr["hej"] = "da";
$arr["good"] = "bye";

$json = json_encode($arr);

echo $json;
 
?>