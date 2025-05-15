<?php
$mac = $_POST['mac'];

$w_json = file_get_contents("../../../data/json/warning.json");
$w_data = json_decode($w_json, true);

// find the mac in the whitelist
// remove from wl_data where mac is
$new_w_data = [];
foreach ($w_data['warnings'] as $w) {
    if ($w['mac'] != $mac) {
        $new_w_data[] = $w;
    }
}

echo json_encode($w_data);

$w_data['warnings'] = $new_w_data;

echo json_encode($w_data);


$result = file_put_contents("../../../data/json/warning.json", json_encode($w_data));

if ($result === false) {
    die("Error writing to file");
}
?>