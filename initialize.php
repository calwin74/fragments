<?php
/*
 * This model creates JSON for initialization of the client
 */

include_once("include/session.php");

/* generate json response */
$descr = array();

//LOCAL_MAP_SIZE
$descr["x_local_map_size"] = X_LOCAL_MAP_SIZE;
$descr["y_local_map_size"] = Y_LOCAL_MAP_SIZE;

//GLOBAL_MAP_SIZE
$descr["x_global_map_size"] = X_GLOBAL_MAP_SIZE;
$descr["y_global_map_size"] = Y_GLOBAL_MAP_SIZE;

$descr["x_batch_map_size"] = X_BATCH_MAP_SIZE;
$descr["y_batch_map_size"] = Y_BATCH_MAP_SIZE;

$json = json_encode($descr);
echo $json;

?>