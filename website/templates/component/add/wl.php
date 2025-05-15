<?php
$mac = $_POST['mac'];
$name = $_POST['name'];
$type = $_POST['type'];

$wl_json = file_get_contents("../../../data/json/white_list.json");
$wl_data = json_decode($wl_json, true);

// Add the new whitelist


$new_wl_data = [];
$new_wl_data['name'] = $name;
$new_wl_data['mac'] = $mac;


$wl_data['white-list'][$type]['wl'][] = $new_wl_data;

var_dump($wl_data);

file_put_contents("../../../data/json/white_list.json", json_encode($wl_data));
?>