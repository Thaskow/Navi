<?php
    $name = $_POST['name'];
    $ip = $_POST['ip'];
    $location = $_POST['location'];
    $model = $_POST['model'];
    $community = $_POST['community'];

    echo $name;
    echo $ip;
    echo $location;
    echo $model;
    echo $community;

    $switchs_json = file_get_contents("../../../data/json/switchs.json");
    $switchs_data = json_decode($switchs_json);

    // create object
    $newSwitch = new stdClass();
    $newSwitch->name = $name;
    $newSwitch->ip = $ip;
    $newSwitch->emplacement = $location;
    $newSwitch->model = $model;
    $newSwitch->community = $community;
    $newSwitch->ports = [];

    $switchs_data->switchs[] = $newSwitch;

    var_dump($switchs_data);

    file_put_contents("../../../data/json/switchs.json", json_encode($switchs_data));

    ?>