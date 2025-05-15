<?php


$logRetention = $_POST['logRetention'];
$scanFrequency = $_POST['scanFrequency'];
$maxPorts = $_POST['maxPorts'];

$switches_json = file_get_contents("../../../data/json/switchs.json");
$switches_data = json_decode($switches_json, true);


$switches_data['keepLog'] = intval($logRetention);
$switches_data['timeLog'] = intval($scanFrequency);
$switches_data['maxPorts'] = intval($maxPorts);

var_dump($switches_data);

file_put_contents("../../../data/json/switchs.json", json_encode($switches_data));

?>